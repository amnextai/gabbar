<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

function encrypt_file($original_file_location, $new_file_location, $key) {
    $key = substr(sha1($key, true), 0, 16);

    $cipher = 'AES-128-CBC';
    $iv_length = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($iv_length);

    $original_file = fopen($original_file_location, 'rb');
    $new_file = fopen($new_file_location, 'w');

    fwrite($new_file, $iv);

    while($plaintext = fread($original_file, $iv_length * 5000)) {
        $ciphertext = openssl_encrypt($plaintext, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        $iv = substr($ciphertext, 0, $iv_length);
        fwrite($new_file, $ciphertext);
    }

    fclose($original_file);
    fclose($new_file);
}

function decrypt_and_output($file_stream, $key, $temp_file = null) {
    /* Generate key based on the password */
    $key = substr(sha1($key, true), 0, 16);

    /* Cipher used for encryption */
    $cipher = 'AES-128-CBC';

    /* Get IV length */
    $iv_length = openssl_cipher_iv_length($cipher);

    /* Read IV from first part of the file */
    $iv = fread($file_stream, $iv_length);

    while($buffer = fread($file_stream, (5000 + 1) * $iv_length)) {
        $plaintext = openssl_decrypt($buffer, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        $iv = substr($buffer, 0, $iv_length);

        if($temp_file) {
            fwrite($temp_file, $plaintext);
        } else {
            echo $plaintext;
        }
    }
}

function decrypt_file($original_file_location, $new_file_location, $key) {
    $key = substr(sha1($key, true), 0, 16);

    $cipher = 'AES-128-CBC';
    $iv_length = openssl_cipher_iv_length($cipher);

    $original_file = fopen($original_file_location, 'rb');
    $new_file = fopen($new_file_location, 'w');

    $iv = fread($original_file, $iv_length);

    while($ciphertext = fread($original_file, $iv_length * (5000 + 1))) {
        $plaintext = openssl_decrypt($ciphertext, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        $iv = substr($ciphertext, 0, $iv_length);
        fwrite($new_file, $plaintext);
    }

    fclose($original_file);
    fclose($new_file);
}

