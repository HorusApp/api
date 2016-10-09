<?php

namespace App\Utils;

class _Encryption {

    private $skey = "horusapp!@#$%^&*?";
    
    public function encode(String $query) {
        return base64_encode(mcrypt_encrypt(
            MCRYPT_RIJNDAEL_256,
            md5($this->skey),
            $query,
            MCRYPT_MODE_CBC,
            md5(md5($this->skey))
        ));
    }

    public function decode(String $query) {
        return rtrim(mcrypt_decrypt(
            MCRYPT_RIJNDAEL_256,
            md5($this->skey),
            base64_decode($query),
            MCRYPT_MODE_CBC,
            md5(md5($this->skey))
        ), "\0");
    }
}