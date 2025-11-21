<?php
// src/Service/MessageEncryptor.php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MessageEncryptor
{
    private string $key;

    public function __construct(ParameterBagInterface $params)
    {
        $secret = $params->get('kernel.secret');
        $this->key = substr(hash('sha256', $secret, true), 0, 32); // AES-256 key
    }

    public function encrypt(string $plainText): string
    {
        $iv = random_bytes(16);
        $cipherText = openssl_encrypt($plainText, 'AES-256-CBC', $this->key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $cipherText);
    }

    public function decrypt(string $encoded): string
    {
        $data = base64_decode($encoded);
        $iv = substr($data, 0, 16);
        $cipherText = substr($data, 16);
        return openssl_decrypt($cipherText, 'AES-256-CBC', $this->key, OPENSSL_RAW_DATA, $iv);
    }
}

?>