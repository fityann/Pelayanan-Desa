<?php
$sourceFile = __DIR__ . '/public/images/logo-desa-puspamukti.jpg';
$destFile = __DIR__ . '/public/images/logo-desa-puspamukti-rounded.png';

// Load source image
$srcImg = imagecreatefromjpeg($sourceFile);
if (!$srcImg) {
    die("Could not load JPEG image\n");
}

$width = imagesx($srcImg);
$height = imagesy($srcImg);

// Create transparent destination image
$destImg = imagecreatetruecolor($width, $height);
imagesavealpha($destImg, true);
$transparent = imagecolorallocatealpha($destImg, 0, 0, 0, 127);
imagefill($destImg, 0, 0, $transparent);

// We want a rounded rectangle mask
$radius = min($width, $height) * 0.4;
$borderWidth = max(2, min($width, $height) * 0.05);

// Create the mask
$maskImg = imagecreatetruecolor($width, $height);
$black = imagecolorallocate($maskImg, 0, 0, 0);
$white = imagecolorallocate($maskImg, 255, 255, 255);
imagefill($maskImg, 0, 0, $black);

// draw corners
imagefilledarc($maskImg, $radius, $radius, $radius * 2, $radius * 2, 180, 270, $white, IMG_ARC_PIE);
imagefilledarc($maskImg, $width - $radius - 1, $radius, $radius * 2, $radius * 2, 270, 360, $white, IMG_ARC_PIE);
imagefilledarc($maskImg, $width - $radius - 1, $height - $radius - 1, $radius * 2, $radius * 2, 0, 90, $white, IMG_ARC_PIE);
imagefilledarc($maskImg, $radius, $height - $radius - 1, $radius * 2, $radius * 2, 90, 180, $white, IMG_ARC_PIE);
// draw rectangles
imagefilledrectangle($maskImg, $radius, 0, $width - $radius - 1, $height - 1, $white);
imagefilledrectangle($maskImg, 0, $radius, $width - 1, $height - $radius - 1, $white);

// Apply mask
for ($x = 0; $x < $width; $x++) {
    for ($y = 0; $y < $height; $y++) {
        $color = imagecolorsforindex($maskImg, imagecolorat($maskImg, $x, $y));
        if ($color['red'] > 127) { // inside the mask
            $srcColor = imagecolorsforindex($srcImg, imagecolorat($srcImg, $x, $y));
            // To make it fit within a border, maybe we don't draw border here.
            $newColor = imagecolorallocatealpha($destImg, $srcColor['red'], $srcColor['green'], $srcColor['blue'], 0);
            imagesetpixel($destImg, $x, $y, $newColor);
        }
    }
}

// Draw a simple circle/rounded rect outline
$borderColor = imagecolorallocatealpha($destImg, 216, 184, 76, 0); // #D8B84C
imagesetthickness($destImg, $borderWidth);
// draw arcs
imagearc($destImg, $radius, $radius, $radius * 2, $radius * 2, 180, 270, $borderColor);
imagearc($destImg, $width - $radius - 1, $radius, $radius * 2, $radius * 2, 270, 360, $borderColor);
imagearc($destImg, $width - $radius - 1, $height - $radius - 1, $radius * 2, $radius * 2, 0, 90, $borderColor);
imagearc($destImg, $radius, $height - $radius - 1, $radius * 2, $radius * 2, 90, 180, $borderColor);
// lines
imageline($destImg, $radius, 0, $width - $radius - 1, 0, $borderColor);
imageline($destImg, $width - 1, $radius, $width - 1, $height - $radius - 1, $borderColor);
imageline($destImg, $width - $radius - 1, $height - 1, $radius, $height - 1, $borderColor);
imageline($destImg, 0, $height - $radius - 1, 0, $radius, $borderColor);

// Output
$result = imagepng($destImg, $destFile);
if ($result) {
    echo "Success\n";
} else {
    echo "Failed to save PNG\n";
}
?>
