<?php

namespace AFSDev\RayLogChannel\Ray;

class LogPayload extends \Spatie\Ray\Payloads\Payload
{
    public function __construct()
    {
        // Use custom origin factory to correct link to file/line number
        self::$originFactoryClass = \AFSDev\RayLogChannel\Ray\CustomOriginFactory::class;
    }

    public function getType(): string {}

    public function getContent(): array {}
}
