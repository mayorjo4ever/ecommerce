<?php

if (!function_exists('upload_image')) {
    function upload_image($file, $folder = 'products', $width = 800, $height = 800)
    {
        return App\Helpers\ImageHelper::uploadImage($file, $folder, $width, $height);
    }
}

if (!function_exists('delete_image')) {
    function delete_image($path)
    {
        return App\Helpers\ImageHelper::deleteImage($path);
    }
}

if (!function_exists('generate_product_qr')) {
    function generate_product_qr($product)
    {
        return App\Helpers\QRCodeHelper::generateProductQR($product);
    }
}

if (!function_exists('generate_order_qr')) {
    function generate_order_qr($order)
    {
        return App\Helpers\QRCodeHelper::generateOrderQR($order);
    }
}