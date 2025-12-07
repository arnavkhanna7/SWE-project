<?php

//Lösche Alle Dateien in dir und lösche dann dir selber
function deleteDirectory($dir_path) {
    $dir = new DirectoryIterator($dir_path);

    foreach ($dir as $fileInfo) {
        if (!$fileInfo->isDot() && $fileInfo->isFile()) {
            unlink($fileInfo->getPathname()); // Delete the file
        }
    }
    rmdir($dir_path);
}