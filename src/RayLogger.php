<?php

namespace AFSDev\RayLogChannel;

use AFSDev\RayLogChannel\Ray\RayLoggingHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Logger;

class RayLogger
{
    /** @var array */
    protected $config = [];

    /**
     * Create a custom Monolog instance.
     *
     * @param array $config
     *
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        $logger = new Logger("RayLoggingHandler");

        return $logger->pushHandler(new \AFSDev\RayLogChannel\RayLoggingHandler($config));
    }

    /**
     * Get the value from the passed in config.
     *
     * @param string $field
     *
     * @return mixed
     */
    private function config(string $field)
    {
        return $this->config[$field] ?? null;
    }
}
