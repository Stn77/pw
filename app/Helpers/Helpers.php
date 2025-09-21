<?php

if(!function_exists('generateRandomString')) {
    function generateRandomString($length = 10) {
        return substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / 62))), 1, $length);
    }
}

if(!function_exists('createQrCode')) {
    function createQrCode($text, $size = 200) {
        $qrCode = new \SimpleSoftwareIO\QrCode\Generator();
        return $qrCode->size($size)->generate($text);
    }
}
