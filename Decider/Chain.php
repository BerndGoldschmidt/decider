<?php declare(strict_types=1);

namespace Decider;

/**
 * Class Chain
 *
 * @author Bernd Goldschmidt <github@berndgoldschmidt.de>
 *
 * @package Decider
 */
class Chain
    implements \Countable
{
    /**
     * Top / first chain link
     *
     * @var ChainLink
     */
    private $topLink;

    /**
     * Insert new chain link right before existing
     *
     * @param ChainLink $newLink
     * @param ChainLink $nextLink
     */
    public function insertBefore(ChainLink $newLink, ChainLink $nextLink = null)
    {
        // if chain currently empty => insert as top
        if ($this->topLink === null) {
            $this->topLink = $newLink;
            return;
        }

        // if next link is current top
        // => replace top with current and set formers top previous to new
        if ($nextLink === $this->getTopLink()) {
            $this->setTopLink($newLink);
            $nextLink->setPrevious($newLink);
            $newLink->setNext($nextLink);
            return;
        }

        // insert in between
        $previous = $nextLink->getPrevious();
        $previous->setNext($newLink);

        $newLink->setPrevious($previous);
        $newLink->setNext($nextLink);

        $nextLink->setPrevious($newLink);
    }

    /**
     * Insert chain link at the end
     *
     * @param ChainLink $insertLink
     */
    public function insert(ChainLink $insertLink)
    {
        $link = $this->getTopLink();

        if ($link === null) {
            $this->setTopLink($insertLink);
            return;
        }

        while (
            $link instanceof ChainLink
            && $link->hasNext()
        ) {
            $link = $link->getNext();
        }

        $insertLink->setPrevious($link);
        $link->setNext($insertLink);
    }

    /**
     * Count elements of an object
     *
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     *
     * The return value is cast to an integer.
     */
    public function count()
    {
        $count = 0;
        if ($this->topLink === null) {
            return $count;
        }

        $count++;
        $link = $this->topLink;

        while ($link->getPrevious() !== null) {
            $count++;
            $link = $link->getPrevious();
        }

        return $count;
    }

    /**
     * Get top (first) chain link
     *
     * @return ChainLink|null
     */
    public function getTopLink()
    {
        return $this->topLink;
    }

    /**
     * Set top link
     *
     * @param ChainLink $topLink
     */
    public function setTopLink(ChainLink $topLink)
    {
        $this->topLink = $topLink;
    }

    /**
     * Render chain
     *
     * @return string
     */
    public function render()
    {
        $result = '';
        $pos = 1;
        $link = $this->getTopLink();

        while ($link instanceof ChainLink) {

            $result .=
                str_pad('(' . $pos . ') ', 8, ' ', STR_PAD_LEFT)
                . $link->getText()
                . PHP_EOL;

            if (!$link->hasNext()) {
                break;
            }

            $link = $link->getNext();
            $pos++;
        }

        return $result;
    }
}