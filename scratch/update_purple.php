<?php

$directories = [
    __DIR__ . '/../resources/views/warga',
    __DIR__ . '/../resources/views/layouts',
];

$replacements = [
    '#5B21B6' => '#6A3297', // Primary Purple -> User's Purple
    '#4C1D95' => '#4E2472', // Dark Purple -> Darker User's Purple
    '#7C3AED' => '#8347B3', // Light Purple -> Lighter User's Purple
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
