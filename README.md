<?php

require __DIR__ . '/vendor/autoload.php';

use Cloudinary\Cloudinary;
use Dotenv\Dotenv;

// Load .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    // Create Cloudinary instance
    $cloudinary = new Cloudinary([
        'cloud' => [
            'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
            'api_key'    => $_ENV['CLOUDINARY_API_KEY'],
            'api_secret' => $_ENV['CLOUDINARY_API_SECRET']
        ],
        'url' => ['secure' => true]
    ]);

    echo "✅ Cloudinary config loaded successfully<br>";

    // Try uploading a test image (from URL)
    $result = $cloudinary->uploadApi()->upload(
        'https://res.cloudinary.com/demo/image/upload/sample.jpg'
    );

    echo "✅ Upload successful!<br>";
    echo "Image URL: " . $result['secure_url'];

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}