<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

// Instantiate the app
$settings = require __DIR__ . '/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/dependencies.php';

// Register routes
require __DIR__ . '/routes.php';

$c = $app->getContainer();
$c['errorHandler'] = function ($c) {
  return function ($request, $response, $exception) use ($c) {
    $errCode = $exception->getCode();
    $errMsg = $exception->getMessage();

    $httpCode = 500;

    if(is_a($exception, '\\App\\Exception'))
    {
      $httpCode = $errCode >= 400 && $errCode < 600 ? $errCode : 500;
    }
    else
    {
      // Ошибка КриптоПро?
      $errMsg = \App\Exception::cryptoproError($errCode, $errMsg);
    }


    return $response->
      withStatus($httpCode)->
      withJson(['status' => 'fail', 'errMsg' => $errMsg, 'errCode' => $errCode]);
  };
};

$c['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
      return $response->
        withStatus(404)->
        withJson(['status' => 'fail', 'errMsg' => 'Page not found', 'errCode' => 404]);
    };
};

$c['notAllowedHandler'] = function ($c) {
    return function ($request, $response, $methods) use ($c) {
      return $response->
        withStatus(405)->
        withHeader('Allow', implode(', ', $methods))->
        withHeader('Content-type', 'text/html')->
        withJson(['status' => 'fail', 'errMsg' => 'Method must be one of: ' . implode(', ', $methods), 'errCode' => 405]);
    };
};

// Run app
$app->run();
