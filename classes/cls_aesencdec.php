<?php
    error_reporting(E_ALL);
    define("ENCRYPT",1);
    define("DECRYPT",2);

    class AES_EncryptDecrypt {
        static $cipher = "aes-256-cbc";
        static $method = "AES_ENCRYPT";
        static $password_encrypted=NULL;
        static $password_decrypted=NULL;

        function __construct ($method = ENCRYPT) {

            if ($method == ENCRYPT) {
                self::$method = "AES_ENCRYPT";
            } else {
                self::$method= "AES_DECRYPT";
            }

        }

        function encrypt_password($password) {
            if (self::$method == "AES_ENCRYPT") {
                if ($password !== NULL and strlen($password) > 0) {
                    $encryption_key = openssl_random_pseudo_bytes(32); 
                    $iv_size = openssl_cipher_iv_length(self::$cipher);
                    $iv_cipher = openssl_random_pseudo_bytes($iv_size);
                    $key_decrypt = "{" . bin2hex($encryption_key) . "}{" . bin2hex($iv_cipher) . "}"; 
                    $encrypted_data = openssl_encrypt($password, self::$cipher, $encryption_key, 0, $iv_cipher); 
                    self::$password_encrypted = $key_decrypt . $encrypted_data;
                    return self::$password_encrypted;
                }
            }
            return NULL;
        }
        function setEncryptMethod($method) {
            if ($method == ENCRYPT) {
                self::$method = "AES_ENCRYPT";
            } else {
                self::$method= "AES_DECRYPT";
            }
        }

        function decrypt_password($password) {
            if (self::$method == "AES_DECRYPT") {
                if ($password !== NULL and strlen($password) > 0) {
                    $primeiro = substr($password,1, stripos($password,'}')-1);
                    $px = stripos($password, '}');
                    $segundo = substr($password, $px+2);
                    $segundo = substr($segundo, 0, stripos($segundo, '}'));
                    $terceiro= substr($password,strrpos($password,'}')+1);
                    $terceiro = trim($terceiro);
                    $encryption_key = hex2bin($primeiro);
                    $iv_key = hex2bin($segundo);
                    $password_decrypted = openssl_decrypt($terceiro, self::$cipher,$encryption_key,0,$iv_key);
                    return $password_decrypted;
                }
            }
            return NULL;
        }

        function getEncryptedPassword() {
            return self::$password_encrypted;
        }

        function getDecryptedPassword() {
            return self::$password_decrypted;
        }
    }

?>