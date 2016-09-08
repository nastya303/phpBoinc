<?php 

$res = openssl_pkey_new();

/* Extract the private key from $res to $privKey */
openssl_pkey_export($res, $privKey);
file_put_contents('private.pem', $privKey);


/* Extract the public key from $res to $pubKey */
$pubKey = openssl_pkey_get_details($res);
$pubKey = $pubKey["key"];

file_put_contents('public.pem', $pubKey);

?>