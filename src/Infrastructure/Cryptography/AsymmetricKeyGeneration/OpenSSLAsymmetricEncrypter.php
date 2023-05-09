<?php

namespace App\Infrastructure\Cryptography\AsymmetricKeyGeneration;

use App\Data\Protocols\Cryptography\AsymmetricEncrypter;
use App\Domain\Dto\Signature;

class OpenSSLAsymmetricEncrypter implements AsymmetricEncrypter
{
    public function encrypt(string $json_data): Signature
    {
        $config = [
            'digest_alg' => 'sha512',
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ];
        $new_key_pair = openssl_pkey_new($config);

        openssl_pkey_export($new_key_pair, $privateKey);

        $details = openssl_pkey_get_details($new_key_pair);
        $publicKey = $details['key'];

        //create signature
        openssl_sign($json_data, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        $signature = base64_encode($signature);

        return new Signature($privateKey, $publicKey, $signature);
    }
}