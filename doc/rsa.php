<?php

$config = array(
    "digest_alg" => "sha512",
    "private_key_bits" => 512,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
);

// Create the private and public key
$res = openssl_pkey_new($config);

// Extract the private key from $res to $privKey
openssl_pkey_export($res, $privKey);

$pubKey = openssl_pkey_get_details($res);
$pubKey = $pubKey["key"];

echo $pubKey;
echo "\n\n";
echo $privKey;

echo "\n\n";

$data = 'my plain text';
openssl_public_encrypt($data, $encrypted, $pubKey);

echo base64_encode($encrypted);