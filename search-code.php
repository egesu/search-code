#!/usr/bin/php
<?php

if(empty($argv[1])) {
    error('Missing file type');
}

if(empty($argv[2])) {
    error('Missing string to search');
}

$path = $argv[1];
$string = $argv[2];

$files = globRecursive($path, '.');

foreach($files as $file) {
    $fileContents = file_get_contents($file);
    $fileContentsArray = explode("\n", $fileContents);
    foreach($fileContentsArray as $lineNumber => $row) {
        if(strpos($row, $string) !== false) {
            $lineNumber++;
            $row = trim($row);
            echo "$file:$lineNumber\n\t\t\t$row\n\n";
        }
    }
}

function error($msg = '') {
    echo "\n" . ($msg ?: 'Error') . "\n\n";
    die();
}

function globRecursive($find, $path = '.') {
    $dh = opendir($path);
    $found = glob("$path/$find");

    while(($file = readdir($dh)) !== false) {
        if($file === '..') continue;
        if($file === '.') {
            $found = array_merge($found, glob("$path/$find"));
        } elseif(is_dir("$path/$file")) {
            $found = array_merge($found, globRecursive($find, "$path/$file"));
        }
    }
    closedir($dh);
    return $found;
}
