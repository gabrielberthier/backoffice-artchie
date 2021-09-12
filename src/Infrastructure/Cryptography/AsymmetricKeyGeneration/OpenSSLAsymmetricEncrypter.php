<?php

namespace App\Infrastructure\Cryptography\AsymmetricKeyGeneration;

use App\Data\Protocols\Cryptography\AsymmetricEncrypter;
use App\Domain\DTO\Signature;

class OpenSSLAsymmetricEncrypter implements AsymmetricEncrypter
{
    public function encrypt(string $json_data): Signature
    {
        $config = [
            'digest_alg' => 'sha512',
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ];
        $resource = openssl_pkey_new($config);

        // Extract private key from the pair
        openssl_pkey_export($resource, $privateKey);

        // Extract public key from the pair
        $keyDetails = openssl_pkey_get_details($resource);
        $publicKey = $keyDetails['key'];

        openssl_sign($json_data, $signature, $privateKey);

        $signature = base64_encode($signature);

        return new Signature($privateKey, $publicKey, $signature);
    }
}
