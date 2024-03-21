<?php

namespace Eightyfour\Core\Response;

class Result
{
    public function __construct(
        public array $data,
        public ?string $format = null
    ) {
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }
}