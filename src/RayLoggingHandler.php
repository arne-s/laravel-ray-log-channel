<?php

namespace AFSDev\RayLogChannel;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Spatie\Ray\Ray;
use Spatie\Ray\Settings\SettingsFactory;

class RayLoggingHandler extends AbstractProcessingHandler
{
    public $config;

    public $defaultColors = [
        'blue' => ['DEBUG', 'INFO', 'INFO123'],
        'green' => ['NOTICE'],
        'yellow' => ['WARNING'],
        'red' => ['ERROR', 'CRITICAL', 'ALERT', 'EMERGENCY', 'API'],
    ];

    public function __construct($config)
    {
        $this->config = $config;
        $level = Logger::DEBUG;
        $bubble = true;

        parent::__construct($level, $bubble);
    }

    protected function write(array $record): void
    {
        if (class_exists('Spatie\Ray\Ray')) {
            $rayClass = Ray::class;

            $ray = new $rayClass(SettingsFactory::createFromConfigFile());
            $payload = new \AFSDev\RayLogChannel\Ray\LogPayload($record);

            if (empty($record['context'])) {
                $ray->raw($record['message']);
            } else {
                $ray->raw($record['message'], $record['context']);
            }

            if ($color = $this->getColor($record['level_name'])) {
                $ray->color($color);
            }
        }
    }

    protected function getColor($levelName)
    {
        $colors = $this->config['colors'] ?? $this->defaultColors;

        $color = null;
        foreach ($colors as $c => $levels) {
            if (in_array($levelName, $levels)) {
                $color = $c;
                break;
            }
        }
        return $color;
    }
}
