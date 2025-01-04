<?php
// helpers/EncryptionHelper.php

class EncryptionHelper
{
    private $key;
    private $iv;

    public function __construct()
    {
        $this->key = $_ENV['ENCRYPTION_KEY'];
        $this->iv = $_ENV['ENCRYPTION_IV'];
    }

    // Hàm mã hóa
    public function encrypt($data)
    {
        return openssl_encrypt($data, 'AES-256-CBC', $this->key, 0, $this->iv);
    }

    // Hàm giải mã
    public function decrypt($encryptedData)
    {
        return openssl_decrypt($encryptedData, 'AES-256-CBC', $this->key, 0, $this->iv);
    }
}
