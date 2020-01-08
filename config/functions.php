<?php

function deleteFile($file, $oldFile)
{
    if (!empty($oldFile) && 'img/uploads/avatars/' . $file !== $oldFile && file_exists("/../public/".$oldFile)) {
        unlink(__DIR__."/../public/".$oldFile);
    }
}