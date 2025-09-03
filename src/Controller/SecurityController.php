<?php 

namespace webpo\Controller;

use webpo\Controller;

class SecurityController extends Controller {



    public function generatePublicKey(){
        $privateKeyResource = openssl_pkey_new([
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ]);
        openssl_pkey_export($privateKeyResource, $privateKey);
        $publicKey = openssl_pkey_get_details($privateKeyResource)['key'];
        file_put_contents(WPA_PATH . "/private.pem", $privateKey);
        file_put_contents(WPA_PATH . "/public.pem", $publicKey);
    }

    public function getKey(){
        echo file_get_contents(WPA_PATH . "/public.pem");
    }

    public function isBase64($string){
        return base64_encode(base64_decode($string, true)) === $string;
    }

    public function tryDecrypt($encryptedPassword, $privateKey) {
    global $decrypted;

    // Step 1: Check if it's valid Base64
    if (!$this->isBase64($encryptedPassword)) {
        return false; // Not encrypted
    }

    // Step 2: Check length for 2048-bit key (256 bytes when decoded)
    $decoded = base64_decode($encryptedPassword, true);
    if (strlen($decoded) !== 256) {
        return false; // Doesn't match expected length
    }

    // Step 3: Try decryption
    if (openssl_private_decrypt($decoded, $decrypted, $privateKey)) {
        return $decrypted; // Successfully decrypted
    }

    return false; // Failed to decrypt
}



}