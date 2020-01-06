<?php

namespace Config;

class Functions
{
    function deleteFile($file, $oldFile, $filesystem)
    {
        if (!empty($oldFile) && 'img/uploads/avatars/' . $file !== $oldFile) {
            $filesystem->remove(['symlink', "../public/" . $oldFile, 'activity.log']);
        }
    }
}