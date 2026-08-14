<?php

$directories = [
    __DIR__ . '/../resources/views/warga',
    __DIR__ . '/../resources/views/layouts',
];

$replacements = [
    '#4B5D3A' => '#5B21B6', // Primary Green -> Primary Purple (Violet 800)
    '#364329' => '#4C1D95', // Dark Green -> Dark Purple (Violet 900)
    '#6A8253' => '#7C3AED', // Light Green -> Light Purple (Violet 600)
];

function processDirectory($dir, $replacements) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $content = file_get_contents($file->getPathname());
            
            $newContent = str_replace(array_keys($replacements), array_values($replacements), $content);
            
            if ($content !== $newContent) {
                file_put_contents($file->getPathname(), $newContent);
                echo "Updated: " . $file->getPathname() . "\n";
            }
        }
    }
}

foreach ($directories as $dir) {
    processDirectory($dir, $replacements);
}

echo "Color replacements completed.\n";
