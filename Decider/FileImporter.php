<?php declare(strict_types=1);

namespace Decider;

/**
 * Class FileImporter
 * used to import list of items to prioritize from text file
 * (use one item per line)
 *
 * @author Bernd Goldschmidt <github@berndgoldschmidt.de>
 *
 * @package Decider
 */
class FileImporter
{
    /**
     * Import list from file
     *
     * @param string $listPath
     * @return array
     * @throws \Exception
     */
    public function import($listPath): array
    {
        if (!is_readable($listPath) || !is_file($listPath)) {
            throw new \Exception('Unable to read file "' . $listPath . '"');
        }

        return file($listPath);
    }
}