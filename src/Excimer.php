<?php

namespace Luar;

use Exception;
use ExcimerProfiler;
use ExcimerTimer;

class Excimer
{
    public static function trace(string $root_dir, string $name = 'speedscope', string $request_uri = '/'): void
    {
        if (!extension_loaded('excimer') ) {
            throw new Exception("Excimer extension is not installed on your php.ini");
        }

        if (!file_exists($root_dir)) {
            if(!mkdir($root_dir, 0777, true)){
                throw new Exception("Error when trying to create directory '" . $root_dir . "'");
            }
        }

        $excimer = new ExcimerProfiler();
        $excimer->setPeriod(0.001); // 1ms
        $excimer->setEventType(EXCIMER_REAL);
        $excimer->start();
        register_shutdown_function( function () use ($excimer, $request_uri, $root_dir, $name) {
            $excimer->stop();
            $data = $excimer->getLog()->getSpeedscopeData();
            $data['profiles'][0]['name'] = $request_uri;
            file_put_contents($root_dir . '/'.$name.'-' . date('Y-m-d_His') . '.json',
                    json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        } );
    }

    public static function startTimer(string $root_dir, string $name = 'php-memory-usage')
    {
        if (!extension_loaded('excimer') ) {
            throw new Exception("Excimer extension is not installed on your php.ini");
        }

        if (!file_exists($root_dir)) {
            if(!mkdir($root_dir, 0777, true)){
                throw new Exception("Error when trying to create directory '" . $root_dir . "'");
            }
        }

        $timer = new ExcimerTimer;
        $timer->setPeriod(0.001); // 1ms
        $startTime = microtime( true );
        $timer->start();
        register_shutdown_function( function () use ( $timer, $startTime, $root_dir, $name ) {
            $timer->stop();
            $usage = sprintf( "%.2f", memory_get_usage() / 1048576 ); // MB
            $interval = (microtime( true ) - $startTime) * 1000; // ms
            $ms = sprintf( "%.2f", $interval );

            file_put_contents(
                $root_dir . $name . "-".date('Y-m-d') . ".csv",
                date("Y-m-d H:i:s")."; $ms; $usage\n",
                FILE_APPEND );
        } );
    }
}