<?php

// Set the directories
$directories = [
    'public/images/payment/banks/',
    'public/images/payment/ewallet/',
    'public/images/payment/retail/'
];

// Ensure directories exist
foreach ($directories as $directory) {
    if (!file_exists($directory)) {
        mkdir($directory, 0755, true);
    }
}

// Define the images to download
$images = [
    // Banks
    'public/images/payment/banks/bca.png' => 'https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg',
    'public/images/payment/banks/bni.png' => 'https://upload.wikimedia.org/wikipedia/id/5/55/BNI_logo.svg',
    'public/images/payment/banks/bri.png' => 'https://upload.wikimedia.org/wikipedia/commons/2/2e/BRI_2020.svg',
    'public/images/payment/banks/mandiri.png' => 'https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg',
    'public/images/payment/banks/permata.png' => 'https://upload.wikimedia.org/wikipedia/id/4/48/Permata_Bank.svg',
    'public/images/payment/banks/maybank.png' => 'https://upload.wikimedia.org/wikipedia/commons/9/99/Maybank_Logo.svg',
    
    // E-wallets
    'public/images/payment/ewallet/qris.png' => 'https://upload.wikimedia.org/wikipedia/commons/3/39/QRIS.svg',
    'public/images/payment/ewallet/ovo.png' => 'https://upload.wikimedia.org/wikipedia/commons/e/eb/Logo_ovo_purple.svg',
    'public/images/payment/ewallet/dana.png' => 'https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dana_blue.svg',
    'public/images/payment/ewallet/shopeepay.png' => 'https://upload.wikimedia.org/wikipedia/commons/2/26/Shopeepay_logo.svg',
    'public/images/payment/ewallet/linkaja.png' => 'https://upload.wikimedia.org/wikipedia/commons/8/85/Logo_LinkAja.svg',
    
    // Retail
    'public/images/payment/retail/alfamart.png' => 'https://upload.wikimedia.org/wikipedia/id/c/c1/Alfamart_logo.svg',
    'public/images/payment/retail/indomaret.png' => 'https://upload.wikimedia.org/wikipedia/commons/9/9d/Logo_Indomaret.svg'
];

// Download images
$success = 0;
$failure = 0;

foreach ($images as $destination => $url) {
    echo "Downloading $url to $destination... ";
    
    // For SVG files, we need to convert them to PNG
    if (strpos($url, '.svg') !== false) {
        // We'll use Imagick if available, otherwise fall back to file_get_contents
        if (extension_loaded('imagick') && class_exists('Imagick')) {
            try {
                $im = new Imagick();
                $im->readImageBlob(file_get_contents($url));
                $im->setImageFormat("png24");
                $im->resizeImage(200, 100, Imagick::FILTER_LANCZOS, 1, true);
                $im->writeImage($destination);
                $success++;
                echo "OK\n";
            } catch (Exception $e) {
                echo "Failed (using Imagick): " . $e->getMessage() . "\n";
                $failure++;
            }
        } else {
            // Fallback to direct download
            if (file_put_contents($destination, file_get_contents($url))) {
                $success++;
                echo "OK (direct download)\n";
            } else {
                $failure++;
                echo "Failed\n";
            }
        }
    } else {
        // Regular image download
        if (file_put_contents($destination, file_get_contents($url))) {
            $success++;
            echo "OK\n";
        } else {
            $failure++;
            echo "Failed\n";
        }
    }
}

echo "\nDownload complete: $success success, $failure failure\n";

// Alternatively, print instructions for manual download
echo "\nIf automatic download failed, please manually download the following logos:\n\n";
foreach ($images as $destination => $url) {
    echo "For $destination, download from: $url\n";
} 