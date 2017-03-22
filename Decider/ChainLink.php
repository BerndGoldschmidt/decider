<?php

namespace Decider;

/**
 * Class ChainLink
 *
 * @author Bernd Goldschmidt <github@berndgoldschmidt.de>
 *
 * @package Decider
 */
class ChainLink
{
    /**
     * Link text
     *
     * @var string
     */
    private $text;

    /**
     * Previous link
     *
     * @var ChainLink
     */
    private $previous = null;

    /**
     * Next link
     *
     * @var ChainLink
     */
    private $next = null;

    /**
     * ChainLink constructor.
     *
     * @param string $text
     */
    public function __construct(string $text)
    {
        $this->setText($text);
    }

    /**
     * Get link text
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Set chain link text
     *
     * @param string $text
     */
    public function setText(string $text)
    {
        $this->text = $text;
    }

    /**
     * Get previous link
     *
     * @return ChainLink|null
     */
    public function getPrevious()
    {
        return $this->previous;
    }

    /**
     * Set previous link
     *
     * @param ChainLink $previous
     */
    public function setPrevious(ChainLink $previous)
    {
        $this->previous = $previous;
    }

    /**
     * Is this link connected to a previous one?
     *
     * @return bool
     */
    public function hasPrevious()
    {
        return ($this->previous instanceof ChainLink);
    }

    /**
     * Get next link in chain
     *
     * @return ChainLink|null
     */
    public function getNext()
    {
        return $this->next;
    }

    /**
     * Set next link in the chain
     *
     * @param ChainLink $next
     */
    public function setNext(ChainLink $next)
    {
        $this->next = $next;
    }

    /**
     * Is this link connected to a next one?
     *
     * @return bool
     */
    public function hasNext()
    {
        return ($this->next instanceof ChainLink);
    }

    /**
     * Quick string representation of link
     * including previous and next if set
     *
     * @return string
     */
    public function __toString()
    {
        $result = $this->getText();

        if ($this->hasPrevious()) {
            $result = $this->getPrevious()->getText() . ' <- ' . $result;
        }

        if ($this->hasNext()) {
            $result = $result . ' -> ' . $this->getNext()->getText();
        }

        return $result;
    }
}