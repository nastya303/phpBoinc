<?php  
include('phpseclib/Crypt/RSA.php');
include('phpseclib/Crypt/AES.php');

include('phpseclib/Math/BigInteger.php');
//include('phpseclib/Crypt/Hash.php');

ini_set('display_errors', '1');
//libxml_use_internal_errors(true);


$xml_post = file_get_contents('php://input');
if (!$xml_post) {
    echo 'Error: no input file';
    die();
}


$xml = simplexml_load_string($xml_post);

if (!$xml) {
    echo "Failed loading XML\n";
    foreach(libxml_get_errors() as $error) {
        echo "\t", $error->message;
    }
    die();
}
echo 'XML Recieved. <br>';


/******************************* OPEN_SSL decryption *****************/

/*
// Encrypt the data to $encrypted using the public key
$pempublickey = file_get_contents('my_public.pem');
$pubKey = openssl_pkey_get_public($pempublickey);
openssl_public_encrypt($plain_text, $encryptedData, $pubKey);
*/

// load the private key and decrypt the encrypted data
$pemprivatekey = file_get_contents('private.pem');
$privateKey = openssl_pkey_get_private($pemprivatekey);

$encryptedAesKey = $xml->kparam1;
echo '   Encrypted key: ' . $encryptedAesKey;

$encryptedAesVi = $xml->kparam2;
echo '   Encrypted vi: ' . $encryptedAesVi;

openssl_private_decrypt(base64_decode($encryptedAesKey), $decryptedAESKey, $privateKey);
openssl_private_decrypt(base64_decode($encryptedAesVi), $decryptedAESVi, $privateKey);

echo '   Decrypted key: ' . $decryptedAESKey;
echo '   Decrypted vi: ' . $decryptedAESVi;


$encrypted_text = $xml->text;
echo '   Encrypted key text: ' . $encrypted_text;

/***********************************************/




/************************* PHP mcrypt decryption ********************/
$iv = $decryptedAESVi; //'fedcba9876543210'; #Same as in JAVA
$key = $decryptedAESKey; //'0123456789abcdef'; #Same as in JAVA

$code = hex2binnary($encrypted_text);
$td = mcrypt_module_open('rijndael-128', '', 'cbc', $iv);

mcrypt_generic_init($td, $key, $iv);
$decryptedAes = mdecrypt_generic($td, $code);

mcrypt_generic_deinit($td);
mcrypt_module_close($td);

$decrypted_final = utf8_encode(trim($decryptedAes));

echo '   Decrypted text: ' . $decrypted_final;


function hex2binnary($hexdata) {
  $bindata = '';

  for ($i = 0; $i < strlen($hexdata); $i += 2) {
        $bindata .= chr(hexdec(substr($hexdata, $i, 2)));
  }
    return $bindata;
  }

/*****************************************************************/

?>
