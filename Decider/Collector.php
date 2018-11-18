<?php declare(strict_types=1);

namespace Decider;

/**
 * Class Collector
 * collects items to prioritize from user
 *
 * @author Bernd Goldschmidt <github@berndgoldschmidt.de>
 *
 * @package Decider
 */
class Collector
{
    /**
     * List of items to prioritize
     *
     * @var array
     */
    private $list = [];

    /**
     * Collect items from user
     *
     * @return array
     */
    public function collect()
    {
        do {
            $result = trim(readline('Please enter item to prioritize (hit return when you are done): '));
            $this->add($result);

        } while (!empty($result));

        return $this->list;
    }

    /**
     * Add item to list
     *
     * @param string $item
     */
    private function add(string $item)
    {
        if (empty($item)) {
            return;
        }

        $this->list[] = $item;
    }
}