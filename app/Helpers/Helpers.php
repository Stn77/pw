<?php

// if(!function_exists('getCurrentUser')) {
//     function getCurrentUser() {
//         return auth()->user();
//     }
// }

if(!function_exists('createQrCode')) {
    function createQrCode($text, $size = 200) {
        $qrCode = new \SimpleSoftwareIO\QrCode\Generator();
        return $qrCode->size($size)->generate($text);
    }
}
