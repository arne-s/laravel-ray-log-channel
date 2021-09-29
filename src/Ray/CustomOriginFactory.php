<?php

namespace AFSDev\RayLogChannel\Ray;

use \Spatie\Backtrace\Backtrace;
use \Spatie\Backtrace\Frame;
use \Spatie\Ray\Ray;
use \Spatie\Ray\Origin;

class CustomOriginFactory implements \Spatie\Ray\Origin\OriginFactory
{
    public function getOrigin(): \Spatie\Ray\Origin\Origin
    {
        $frame = $this->getFrame();

        return new \Spatie\Ray\Origin\Origin(
            $frame ? $frame->file : null,
            $frame ? $frame->lineNumber : null,
            \Spatie\Ray\Origin\Hostname::get()
        );
    }

    /**
     * @return \Spatie\Backtrace\Frame|null
     */
    protected function getFrame()
    {
        $frames = $this->getAllFrames();
        $indexOfRay = $this->getIndexOfRayFrame($frames);

        return $frames[$indexOfRay] ?? null;
    }

    protected function getAllFrames(): array
    {
        $frames = Backtrace::create()->frames();

        return array_reverse($frames, true);
    }

    /**
     * @param array $frames
     *
     * @return int|null
     */
    protected function getIndexOfRayFrame(array $frames)
    {
        $index = $this->search(function (Frame $frame) {

            if ($frame->class === \Illuminate\Log\LogManager::class) {
                return true;
            }

            return false;
        }, $frames);

        return $index + 2;
    }

    public function startsWith(string $hayStack, string $needle): bool
    {
        return strpos($hayStack, $needle) === 0;
    }

    /**
     * @param callable $callable
     * @param array $items
     *
     * @return int|null
     */
    protected function search(callable $callable, array $items)
    {
        foreach ($items as $key => $item) {
            if ($callable($item, $key)) {
                return $key;
            }
        }

        return null;
    }
}
