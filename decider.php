#!/usr/bin/env php
<?php

/**
 * Decider
 *
 * @author Bernd Goldschmidt <github@berndgoldschmidt.de>
 *
 * @package Decider
 */


try {

    if (!registerPoorMansAutoLoader()) {
        throw new \Exception('Unable to register auto loader.');
    }

    if ($argc < 2) {
        $list = (new \Decider\Collector())->collect();
    } else {
        $list = (new \Decider\FileImporter())->import($argv[1]);
    }

    (new \Decider\Decider($list))->run();

} catch (\Exception $e) {
    die(
        'Oops, that did not went well: ' . PHP_EOL
        . '  ' . $e->getMessage() . PHP_EOL
        . $e->getTraceAsString() . PHP_EOL . PHP_EOL
    );
}


/**
 * Register auto loader
 *
 * @return bool
 */
function registerPoorMansAutoLoader(): bool
{
    /**
     * Poor man's auto loader
     *
     * @var string $className
     */
    $autoLoader = function (string $className) {
        require_once implode(DIRECTORY_SEPARATOR, explode('\\', $className)) . '.php';
    };

    return spl_autoload_register($autoLoader);
}