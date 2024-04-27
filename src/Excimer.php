<?php

namespace Luar;

use Exception;
use ExcimerProfiler;

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
}