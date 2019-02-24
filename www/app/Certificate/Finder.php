<?php

namespace App\Certificate;

class Finder {

  private static $findTypeMap =
  [
    'sha1' => CERTIFICATE_FIND_SHA1_HASH,
    'subject' => CERTIFICATE_FIND_SUBJECT_NAME
  ];

  public function findType($find_type)
  {
    $this->findType =& self::$findTypeMap[$find_type];
    if(!isset($this->findType))
    {
      throw new \App\Exception("No such find_type", 404);
    }
    return $this;
  }

  public function query($query)
  {
    $this->query = $query;
    return $this;
  }

  public function fetch()
  {
    $store = new \CPStore();
    $store->Open(CURRENT_USER_STORE, 'My', STORE_OPEN_READ_ONLY);
    $this->certs = $store->get_Certificates();
    if($this->certs->Count() === 0)
    {
      throw new \App\Exception("No certificates in store 'My'", 404);
    }

    if(isset($this->findType))
    {
      // 3 парамет - $valid_only
      $this->certs = $this->certs->Find($this->findType, $this->query, false);
    }

    return $this;
  }

  public function count() {
    return $this->certs->Count();
  }

  public function get()
  {
    return $this->certs;
  }

  public function first()
  {
    return $this->certs->Item(1);
  }

}
