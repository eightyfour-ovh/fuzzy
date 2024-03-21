<?php

namespace Eightyfour\Router;

use Eightyfour\Configuration\Configurator;

class Loader
{
    private const array CONTENT_TO_IGNORE = ['.', '..'];

    public function scan(?Configurator $config = null): array
    {
        return $this->scanDirectories(directories: $this->getDirectories(config: $config));
    }

    private function scanDirectories(array $directories, array $filesToScan = []): array
    {
        foreach ($directories as $directory) {
            $path = realpath(path: $directory);
            $contents = scandir(directory: $directory);
            if (!$contents || $contents === self::CONTENT_TO_IGNORE) {
                break;
            }
            foreach ($contents as $content) {
                if (!in_array(needle: $content, haystack: self::CONTENT_TO_IGNORE)) {
                    $location = $path . DIRECTORY_SEPARATOR . $content;
                    if (is_dir(filename: $location) === false) {
                        $filesToScan[] = $location;
                    } else {
                        $filesToScan = $this->scanDirectories(directories: [$location], filesToScan: $filesToScan);
                    }
                }
            }
        }

        return $filesToScan;
    }

    private function getDirectories(?Configurator $config = null): array
    {
        return array_merge([
            // TODO: add framework's routes
        ], $config?->getDirectories(type: Configurator::DIR_ROUTER) ?: []);
    }
}