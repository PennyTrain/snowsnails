<?php

require __DIR__ . '/../vendor/autoload.php';

use Cloudinary\Cloudinary;
use Dotenv\Dotenv;

// Load .env (from root folder)
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Create Cloudinary instance using env variables
$cloudinary = new Cloudinary([
    'cloud' => [
        'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
        'api_key'    => $_ENV['CLOUDINARY_API_KEY'],
        'api_secret' => $_ENV['CLOUDINARY_API_SECRET']
    ],
    'url' => ['secure' => true]
]);

return $cloudinary;