<?php

class Explorer {

  public function run()
  {
    echo '# Classes' . PHP_EOL . PHP_EOL;
    $this->showClasses();
    echo '# Constants' . PHP_EOL . PHP_EOL;
    $this->showConstants();
  }

  private function showClasses()
  {
    $classes = get_declared_classes();

    $classes = array_filter($classes, function($name){
      return preg_match('~^CP~i', $name);
    });

    foreach($classes as $class)
    {
      echo $class . PHP_EOL;

      $reflectionClass = new \ReflectionClass($class);
      $methodsList = $reflectionClass->getMethods();
      array_walk($methodsList, function($m){
        if($m->name == '__construct') return;
        echo "  " . $m->name . '()' . PHP_EOL;
      });

      echo PHP_EOL;
    }
  }

  private function showConstants() {
    $constants = get_defined_constants(true);

    foreach ($constants['php_CPCSP'] as $k => $v) {
      echo $k . ' = ' . $v . PHP_EOL;
    }
  }



}

$e = new Explorer;
$e->run();
