<?php

namespace App\Infrastructure\Cryptography\DataEncryption;

use App\Data\Protocols\Cryptography\DataDecrypter;
use App\Data\Protocols\Cryptography\DataEncrypter;
use Exception;

class Encrypter implements DataEncrypter, DataDecrypter
{
    public function __construct(private string $key = '', private string $cipher = 'AES-128-CBC')
    {
        $this->key = base64_encode($this->key);
    }

    public function encrypt(string $originalString): string
    {
        $plaintext = $originalString;
        $ivlen = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($plaintext, $this->cipher, $this->key, $options = OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $this->key, $as_binary = true);

        return base64_encode($iv.$hmac.$ciphertext_raw);
    }

    public function decrypt(string $ciphertext): string
    {
        $c = base64_decode($ciphertext);
        $ivlen = openssl_cipher_iv_length($this->cipher);
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len = 32);
        $ciphertext_raw = substr($c, $ivlen + $sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $this->cipher, $this->key, $options = OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $this->key, $as_binary = true);
        if (hash_equals($hmac, $calcmac)) {
            return $original_plaintext;
        }

        throw new Exception('unable to decrypt');
    }
}
