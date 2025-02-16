<?php

/**
  *   LSB steganography encoding v1.0
  *   ----------------------------------------------------------------------
  *
  *   This PHP script hides a secret message inside a PNG image by using the 
  *   Least Significant Bit (LSB) method. The secret message is loaded from
  *   a text file given in parameter. The script first converts the message
  *   in binary, then it hides the bits inside each color pixel's value.
  *
  *   Script written for learning purposes only.
  */





/**
  *  1. Read the file that contains the secret
  *     message and convert its text in binary.
  */

if (empty($_GET['file']) || !is_string($_GET['file'])) {
    echo 'ERROR: please provide the name of the text file.';
    exit(1);
}


$txt_file = $_GET['file'];

// Let's check that the text file's name doesn't contain suspicious chars
if (!preg_match('/^[a-z0-9-_]{1,}$/i', $txt_file)) {
    echo 'ERROR: The text file\'s name cannot contain special characters.';
    exit(2);
}


$binary = '';
$secret = file_get_contents("data/$txt_file.txt", 'r');

if ($secret === false) {
    echo "ERROR: could not read $txt_file, please check file permissions.";
    exit(3);
}

// Add a special chars to indicate the end of the secret message.
$secret .= '^';



// Convert the secret message in its binary representation.
foreach (str_split($secret) as $char) {
    $byte = ord($char);
    $bin = decbin($byte);

    // Pad with '0' on the left side to form 8-bits blocks
    $binary .= str_pad($bin, 8, '0', STR_PAD_LEFT);
}

$bin_len = strlen($binary);





/**
  *  2. Open the example image and get its width and height.
  */

$img_path = 'data/example.png';
$out_path = 'data/output.png';
$img = @imagecreatefrompng($img_path);

if ($img === false) {
    echo "ERROR: could not open $img_path, please check that the file exists.";
    exit(4);
}

$width = imagesx($img);
$height = imagesy($img);


if ($bin_len > 3 * $width * $height) {
    echo 'ERROR: the image is too small to contain all the secret message.';
    exit(5);
}




/**
  *  3. Hide the binary inside the image using LSB steganography.
  */

// Count the number of bits hidden in the image.
$c_bits = 0;

for ($i = 0; $i < $width; ++$i) {
    for ($j = 0; $j < $height; ++$j) {

        if ($c_bits >= $bin_len) {
            break;
        }

        // Extract each color values (Red, Green and Blue)
        // with the LSB set to zero.
        $pixel = imagecolorat($img, $i, $j);
        $R = ($pixel >> 16) & 0xFE;
        $G = ($pixel >> 8) & 0xFE;
        $B = $pixel & 0xFE;


        if ($c_bits < $bin_len && $binary[$c_bits++] === '1')
            ++$R;

        if ($c_bits < $bin_len && $binary[$c_bits++] === '1')
            ++$G;

        if ($c_bits < $bin_len && $binary[$c_bits++] === '1')
            ++$B;


        $new_pixel = imagecolorallocate($img, $R, $G, $B);
        imagesetpixel($img, $i, $j, $new_pixel);
        imagecolordeallocate($img, $pixel);
    }
}



imagepng($img, $out_path);
echo "The secret message ($c_bits bits) is now hidden in $out_path!";
