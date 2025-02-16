<?php

/**
  *   LSB steganography decoding v1.0
  *   ----------------------------------------------------------------------
  *
  *   This PHP script is able to read any secret message hidden in a PNG image
  *   based on the Least Significant Bit (LSB) method. For more info, please
  *   check the encoding script.
  *
  *   Script written for learning purposes only.
  */





/**
  *  1. Open the output image and get its width and height.
  */

$img_path = 'data/output.png';
$binary = '';

$img = @imagecreatefrompng($img_path);

if (!$img) {
    echo "ERROR: could not open $img_path, please check that the file exists.";
    exit;
}

$width = imagesx($img);
$height = imagesy($img);





/**
  *  2. Retrieve the hidden message using LSB steganography.
  */

for ($i = 0; $i < $width; ++$i) {
    for ($j = 0; $j < $height; ++$j) {

        $pixel = imagecolorat($img, $i, $j);

        // Extract each color values (Red, Green and Blue)
        // and only get the LSB value.
        $R = ($pixel >> 16) & 1;
        $G = ($pixel >> 8) & 1;
        $B = $pixel & 1;

        $binary .= "$R$G$B";
    }
}





/**
  *  3. Convert the binary text to plain text and display it.
  */

$secret = '';
$len_bin = strlen($binary);

for ($byte = 0; $byte < $len_bin; $byte += 8) {
    $sub = substr($binary, $byte, 8);
    $secret .= chr(bindec($sub));
}

// Let's remind that the end of the secret message
// is marked with the special char ^
$secret = explode('^', $secret)[0];

file_put_contents('data/found-text.txt', $secret);




/**
  *  BONUS
  *
  *  We can check whether the original secret message is equal to
  *  the one found in the image, by using a quick hash function.
  */

$original = 'data/secret.txt';

if (md5(file_get_contents($original)) === md5($secret)) {
    echo 'The original secret message has been successfully retrieved!';
    echo '<br><br>';
}

echo $secret;
