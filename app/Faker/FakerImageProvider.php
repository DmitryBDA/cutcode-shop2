<?php

namespace App\Faker;

use Faker\Provider\Base;
use Faker\Provider\Uuid;
use Illuminate\Support\Facades\Storage;

class FakerImageProvider extends Base
{
    public function imageFile($sourceDirectory, $targetDirectory)
    {

        if (!is_dir($sourceDirectory)) {
            throw new \InvalidArgumentException(sprintf('Source directory %s does not exist or is not a directory.', $sourceDirectory));
        }

        if (!is_dir($storageTargetDirectory =  storage_path($targetDirectory))) {
            Storage::createDirectory($storageTargetDirectory);
        }

        // Drop . and .. and reset array keys
        $files = array_filter(array_values(array_diff(scandir($sourceDirectory), ['.', '..'])), static function ($file) use ($sourceDirectory) {
            return is_file($sourceDirectory . DIRECTORY_SEPARATOR . $file) && is_readable($sourceDirectory . DIRECTORY_SEPARATOR . $file);
        });

        if (empty($files)) {
            throw new \InvalidArgumentException(sprintf('Source directory %s is empty.', $sourceDirectory));
        }

        $sourceFullPath = $sourceDirectory . DIRECTORY_SEPARATOR . static::randomElement($files);

        $destinationFile = Uuid::uuid() . '.' . pathinfo($sourceFullPath, PATHINFO_EXTENSION);

        $destinationFullPath = $storageTargetDirectory . $destinationFile;

        if (false === copy($sourceFullPath, $destinationFullPath)) {
            return false;
        }
        $pathStorage = Storage::disk('local')->url($targetDirectory) . DIRECTORY_SEPARATOR . $destinationFile;
        return $pathStorage;
    }
}
