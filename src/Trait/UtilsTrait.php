<?php

namespace Eightyfour\Trait;

trait UtilsTrait
{
    public function getPath(string $path): false|string
    {
        return realpath(path: $path);
    }

    public function merge(array $array, mixed $extras = null): array
    {
        if (is_array(value: $extras)) {
            return array_merge($array, $extras);
        } else {
            return $extras === null ? $array : array_merge($array, [$extras]);
        }
    }
}