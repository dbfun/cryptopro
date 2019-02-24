<?php

namespace App\Certificate;

/*

Многие реквизиты из GetInfo() не реализованы, при обращении возникает ошибка `Not implemented. (0x80004001)`

> CAPICOM_CERT_INFO_*_EMAIL_NAME и CAPICOM_CERT_INFO_*_UPN и CAPICOM_CERT_INFO_*_DNS_NAME не поддерживаются криптопровайдером на Unix платформах.
> Если email в RDN то можно получить всю строку с subject и достать email оттуда.

@see https://www.cryptopro.ru/forum2/default.aspx?g=posts&m=72528#post72528

*/

class Info {

  public function __construct(\CPCertificate $cert)
  {
    $this->cert = $cert;
  }

  public function get()
  {
    $ret = [
      'hasPrivateKey' => $this->cert->HasPrivateKey(),
      'serialNumber' => $this->cert->get_SerialNumber(),
      'thumbprint' => $this->cert->get_Thumbprint(),
      'subject' => $this->parseMeta($this->cert->get_SubjectName()),
      'issuer' => $this->parseMeta($this->cert->get_IssuerName()),
      'valid' => [
        'from' => $this->cert->get_ValidFromDate(),
        'to' => $this->cert->get_ValidToDate(),
      ]
    ];

    try {
      $algo = $this->cert->PublicKey()->get_Algorithm();
      $ret['algorithm'] = [
        'val' => $algo->get_Value(),
        'name' => $algo->get_FriendlyName()
      ];
    } catch (\Exception $e) { }

    try {
      $pk = $this->cert->PrivateKey();
      $ret['privateKey'] = [
        'containerName' => $pk->get_ContainerName(),
        'uniqueContainerName' => $pk->get_UniqueContainerName(),
        'providerName' => $pk->get_ProviderName()
      ];
    } catch (\Exception $e) { }


    return $ret;

  }

  private function parseMeta($str) {
    $ret = [
      'raw' => $str
    ];
    if(preg_match_all('~(emailAddress|E|C|L|O|CN|OU|T|SN|G)=([^,]*)~', $str . ',', $m)) {
      foreach($m[1] as $idx => $key) {
        $ret[$key] = stripslashes($m[2][$idx]);
      }
    }
    return $ret;
  }


}
