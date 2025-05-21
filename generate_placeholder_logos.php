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

// Define colors and labels for each logo
$logos = [
    // Banks
    'banks/bca.png' => ['#0066AE', 'BCA'],
    'banks/bni.png' => ['#F05A28', 'BNI'],
    'banks/bri.png' => ['#00529C', 'BRI'],
    'banks/mandiri.png' => ['#003D79', 'MANDIRI'],
    'banks/permata.png' => ['#009341', 'PERMATA'],
    'banks/maybank.png' => ['#FFC706', 'MAYBANK'],
    
    // E-wallets
    'ewallet/qris.png' => ['#000000', 'QRIS'],
    'ewallet/ovo.png' => ['#4C3494', 'OVO'],
    'ewallet/dana.png' => ['#0081FF', 'DANA'],
    'ewallet/shopeepay.png' => ['#F1582C', 'ShopeePay'],
    'ewallet/linkaja.png' => ['#E4251B', 'LinkAja'],
    
    // Retail
    'retail/alfamart.png' => ['#ED1D24', 'ALFAMART'],
    'retail/indomaret.png' => ['#0062AB', 'INDOMARET']
];

// Generate placeholder images
foreach ($logos as $filename => $config) {
    list($color, $text) = $config;
    $path = 'public/images/payment/' . $filename;
    
    // Check if file already exists
    if (file_exists($path)) {
        echo "File $path already exists. Skipping...\n";
        continue;
    }
    
    echo "Generating $path... ";
    
    // Create image
    $width = 200;
    $height = 80;
    $image = imagecreatetruecolor($width, $height);
    
    // Set background color to white
    $white = imagecolorallocate($image, 255, 255, 255);
    imagefilledrectangle($image, 0, 0, $width, $height, $white);
    
    // Convert hex color to RGB
    $hex = ltrim($color, '#');
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    $textColor = imagecolorallocate($image, $r, $g, $b);
    
    // Add text
    $fontSize = 5; // Maximum font size for GD
    $fontFile = 'arial.ttf'; // Use your own font file if available
    
    // Calculate position to center text
    $textWidth = imagefontwidth($fontSize) * strlen($text);
    $textHeight = imagefontheight($fontSize);
    $x = ($width - $textWidth) / 2;
    $y = ($height - $textHeight) / 2 + $textHeight;
    
    // Add text
    imagestring($image, $fontSize, $x, $y - 10, $text, $textColor);
    
    // Save image
    imagepng($image, $path);
    imagedestroy($image);
    
    echo "Done\n";
}

echo "\nLogo generation complete!\n"; 