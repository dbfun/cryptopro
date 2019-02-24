<?php

namespace App;

class Exception extends \Exception {

  /**
   * Приводит число к шестнадцатеричному виду и обрезает до 8ми символов с конца
   *
   * @param int $errorCode Код ошибки
   * @return string
   */
  public static function cryptoproErrorCode($errorCode)
  {
      return strtoupper(substr(dechex($errorCode), -8));
  }

  /**
   * Возвращает сообщение по коду ошибки
   *
   * @param int|string $errorCode Код ошибки в виде числа int или строки 800B010A
   * @param bool|string $defaultMessage Сообщение по умолчанию если не найдено по коду
   * @return string
   */
  public static function cryptoproError($errorCode, $defaultMessage = false)
  {
      if (is_int($errorCode)) {
          $errorCode = self::cryptoproErrorCode($errorCode);
      }

      switch ($errorCode) {
          // Стандартные виндовые коды ошибок
          case '800B010E'://The revocation process could not continue - the certificate(s) could not be checked.
              $res = 'Процесс получения цепочки сертификатов не завершён. Подпись не может быть проверена. '
              . '(нет доступа к интернету для скачивания сертификатов цепочки)'
              . ' или один из сертификатов цепочки аннулирован';
              break;
          case '800B010A':
              $res = 'Не удается построить цепочку сертификатов для доверенного корневого центра';
              break;
          case '80070057':// The parameter is incorrect
              $res = 'Неверный параметр';
              break;
          case '800B010C':// Internal error.
              $res = 'Ваш сертификат был отозван создателем';
              break;
          case '80090001':
              $res = 'Bad UID';
              break;
          case '80090002':
              $res = 'Bad Hash';
              break;
          case '80090003':
              $res = 'Bad Key';
              break;
          case '80090004':
              $res = 'Bad Length';
              break;
          case '80090005':
              $res = 'Bad Data';
              break;
          case '80090006':
              $res = 'Неправильная подпись';
              break;
          case '80090007':
              $res = 'Bad Version of provider';
              break;
          case '80090008':
              $res = 'Invalid algorithm specified';
              break;
          case '80090009':
              $res = 'Invalid flags specified';
              break;
          case '8009000A':
              $res = 'Invalid type specified';
              break;
          case '8009000B':
              $res = 'Key not valid for use in specified state';
              break;
          case '8009000C':
              $res = 'Hash not valid for use in specified state';
              break;
          case '8009000D':
              $res = 'Key does not exist';
              break;
          case '8009000E':
              $res = 'Insufficient memory available for the operation';
              break;
          case '8009000F':
              $res = 'Object already exists';
              break;
          case '80090010':
              $res = 'Access denied';
              break;
          case '80090011':
              $res = 'Object was not found';
              break;
          case '80090012':
              $res = 'Data already encrypted';
              break;
          case '80090013':
              $res = 'Invalid provider specified';
              break;
          case '80090014':
              $res = 'Invalid provider type specified';
              break;
          case '80090015':
              $res = 'Provider\'s public key is invalid';
              break;
          case '80090016':
              $res = 'Keyset does not exist';
              break;
          case '80090017':
              $res = 'Provider type not defined';
              break;
          case '80090018':
              $res = 'Provider type as registered is invalid';
              break;
          case '80090019':
              $res = 'The keyset is not defined';
              break;
          case '8009001A':
              $res = 'Keyset as registered is invalid';
              break;
          case '8009001B':
              $res = 'Provider type does not match registered value';
              break;
          case '8009001C':
              $res = 'The digital signature file is corrupt';
              break;
          case '8009001D':
              $res = 'Provider DLL failed to initialize correctly';
              break;
          case '8009001E':
              $res = 'Provider DLL could not be found';
              break;
          case '8009001F':
              $res = 'The Keyset parameter is invalid';
              break;
          case '80090020':
              $res = 'An internal error occurred';
              break;
          case '80090021':
              $res = 'A base error occurred';
              break;
          case '80091001':
              $res = 'An error was encountered doing a cryptographic message operation';
              break;
          case '80091002':
              $res = 'The cryptographic algorithm is unknown';
              break;
          case '80091003':
              $res = 'The object identifier is badly formatted';
              break;
          case '80091004':
              $res = 'Недопустимый тип криптографического сообщения';
              break;
          case '80091005':
              $res = 'The message is not encoded as expected';
              break;
          case '80091006':
              $res = 'The message does not contain an expected authenticated attribute';
              break;
          case '80091007': // The hash value is not correct
              $res = 'Хеш файла не соответствует подписи';
              break;
          case '80091008':
              $res = 'The index value is not valid';
              break;
          case '80091009':
              $res = 'The message content has already been decrypted';
              break;
          case '8009100A':
              $res = 'The message content has not been decrypted yet';
              break;
          case '8009100B':
              $res = 'The enveloped-data message does not contain the specified recipient';
              break;
          case '8009100C':
              $res = 'The control type is not valid';
              break;
          case '8009100D':
              $res = 'The issuer and/or serial number are/is not valid';
              break;
          case '8009100E':
              $res = 'The original signer is not found';
              break;
          case '8009100F':
              $res = 'The message does not contain the requested attributes';
              break;
          case '80092001':
              $res = 'The length specified for the output data was insufficient';
              break;
          case '80092002':
              $res = 'An error was encountered while encoding or decoding';
              break;
          case '80092003':
              $res = 'An error occurred while reading or writing to the file';
              break;
          case '80092004':
              $res = 'The object or property wasn\'t found';
              break;
          case '80092005':
              $res = 'The object or property already exists';
              break;
          case '80092006':
              $res = 'No provider was specified for the store or object';
              break;
          case '80092007':
              $res = 'The specified certificate is self signed';
              break;
          case '80092008':
              $res = 'The previous certificate or CRL context was deleted';
              break;
          case '80092009':
              $res = 'No match when trying to find the object';
              break;
          case '8009200A':
              $res = 'The type of the cryptographic message being decoded is different than what was expected';
              break;
          case '8009200B':
              $res = 'The certificate doesn\'t have a private key property';
              break;
          case '8009200C':
              $res = 'No certificate was found having a private key property to use for decrypting';
              break;
          case '8009200D':
              $res = 'Either, not a cryptographic message or incorrectly formatted';
              break;
          case '8009200E':
              $res = 'The signed message doesn\'t have a signer for the specified signer index';
              break;
          case '8009200F':
              $res = 'Final closure is pending until additional frees or closes';
              break;
          case '80092010':
              $res = 'The certificate or signature has been revoked';
              break;
          case '80092011':
              $res = 'No .dll or exported function was found to verify revocation';
              break;
          case '80092012':
              $res = 'The called function wasn\'t able to do a revocation check on the certificate or signature';
              break;
          case '80092013':
              $res = 'Since the revocation server was offline, the called function wasn\'t able to complete the revocation check';
              break;
          case '80092020':
              $res = 'The string contains a non-numeric character';
              break;
          case '80092021':
              $res = 'The string contains a non-printable character';
              break;
          case '80092022':
              $res = 'The string contains a character not in the 7 bit ASCII character set';
              break;
          case '80093000':
              $res = 'OSS Certificate encode/decode error code base., '
                  . 'See asn1code.h for a definition of the OSS runtime errors.'
                  . 'The OSS error values are offset by CRYPT_E_OSS_ERROR';
              break;

          // Коды ошибок библиотеки tspcli
          case 'C2100100':
              $res = 'При попытке отправки запроса возникла ошибка HTTP';
              break;
          case 'C2100101':
              $res = 'Указанный тип аутентификации запрещен групповой политикой';
              break;
          case 'C2100102':
              $res = 'Указанный тип аутентификации прокси-сервера запрещен групповой политикой';
              break;
          case 'C2100103':
              $res = 'Указанная служба штампов запрещена групповой политикой';
              break;
          case 'C2100104':
              $res = 'Использование поля Nonce запрещено групповой политикой';
              break;
          case 'C2100110':
              $res = 'Указанный алгоритм хеширования запрещен групповой политикой';
              break;
          case 'C2100111':
              $res = 'Указанный "PolicyID" запрещен групповой политикой';
              break;
          case 'C2100120':
              $res = 'Значение полей "Nonce" запроса и штампа не совпадают';
              break;
          case 'C2100121':
              $res = 'Не задан адрес службы штампов времени';
              break;
          case 'C2100122':
              $res = 'Штамп времени просрочен (выдан слишком давно)';
              break;
          case 'C2100123':
              $res = 'В запросе отсутствует хэш-значение';
              break;
          case 'C2100124':
              $res = 'Получен ответ службы штампов времени с ошибкой';
              break;

          // Коды ошибок библиотеки ocspcli
          case 'C2110100':
              $res = 'При попытке отправки запроса возникла ошибка HTTP';
              break;
          case 'C2110101':
              $res = 'Указанный тип аутентификации запрещен групповой политикой';
              break;
          case 'C2110102':
              $res = 'Указанный тип аутентификации прокси-сервера запрещен групповой политикой';
              break;
          case 'C2110103':
              $res = 'Указанная служба OCSP запрещена групповой политикой';
              break;
          case 'C2110104':
              $res = 'Встречено расширение (AcceptableTypes или Nonce), запрещенное групповой политикой';
              break;
          case 'C2110110':
              $res = 'Подписанные OCSP-запросы запрещены политикой';
              break;
          case 'C2110111':
              $res = 'Неподписанные OCSP-запросы запрещены политикой';
              break;
          case 'C2110120':
              $res = 'Поля "Nonce" OCSP-запроса и ответа не совпадают';
              break;
          case 'C2110121':
              $res = 'Не задан адрес службы OCSP';
              break;
          case 'C2110122':
              $res = 'OCSP-ответ просрочен по значению поля "ProducedAt" или "NextUpdate"';
              break;
          case 'C2110123':
              $res = 'Значение поля "ThisUpdate" OCSP-ответа просрочено';
              break;
          case 'C2110124':
              $res = 'Значение поля "NextUpdate" OCSP-ответа меньше значения "ThisUpdate"';
              break;
          case 'C2110125':
              $res = 'В OCSP-ответе не найден запрашиваемый статус сертификата';
              break;
          case 'C2110126':
              $res = 'Сертификат отозван';
              break;
          case 'C2110127':
              $res = 'Статус сертификата не известен';
              break;
          case 'C2110128':
              $res = 'Получен OCSP-ответ с ошибкой';
              break;
          case 'C2110129':
              $res = 'Полученный OCSP-ответ содержит неизвестное критическое расширение';
              break;
          case 'C2110130':
              $res = 'Время Службы OCSP рассинхронизировано со Службой штампов времени';
              break;

          default:
              if ($defaultMessage) {
                  return $defaultMessage;
              }
              $res = 'Неизвестная ошибка, нет описания';
      }

      return $res . ' (0x' . $errorCode . ')';
  }

}
