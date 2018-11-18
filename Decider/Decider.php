<?php declare(strict_types=1);

namespace Decider;

/**
 * Class Decider
 *
 * @author Bernd Goldschmidt <github@berndgoldschmidt.de>
 *
 * @package Decider
 */
class Decider
{
    /**
     * Default vector to rate items against: Importance
     *
     * @var string
     */
    public const VECTOR_DEFAULT = 'Importance';

    /**
     * Possible answers for a 1:1 decision
     *
     * @var array
     */
    private const POSSIBLE_ANSWERS = [
        '1', // pick option (1) as more important
        '2', // pick option (2) as more important
        'q', // quit

        // @todo more options
        // 'u', // undecided or undecidable => try remaining entries
        // 'l', // decide later
    ];

    /**
     * List to sort
     *
     * @var array
     */
    private $inputList;

    /**
     * The resulting chain
     *
     * @var Chain
     */
    private $chain;

    /**
     * Vector to rate items against
     *
     * @var string
     */
    private $vector = self::VECTOR_DEFAULT;

    /**
     * Decider constructor.
     *
     * @param array $list
     * @param string $vector
     * @param bool $shuffleList
     */
    public function __construct(array $list, string $vector = self::VECTOR_DEFAULT, $shuffleList = true)
    {
        $list = array_map('trim', $list);

        if ($shuffleList) {
            shuffle($list);
        }

        $this->setInputList($list);
        $this->setChain(new Chain());
        $this->setVector($vector);
    }

    /**
     * Run interactive decision process
     */
    public function run()
    {
        foreach ($this->getInputList() as $listItem) {

            $link = new ChainLink($listItem);

            $insertionResult = $this->insert($link);

            if ($insertionResult === false) {
                return;
            }
        }

        $this->showResult();
    }

    /**
     * Insert ChainLink
     *
     * @param ChainLink $insertLink
     * @return bool
     */
    private function insert(ChainLink $insertLink): bool
    {
        if ($this->getChain()->count() === 0) {
            $this->getChain()->insert($insertLink);
            return true;
        }

        $compareLink = $this->getChain()->getTopLink();

        while ($compareLink instanceof ChainLink) {
            @system('clear');

            echo PHP_EOL
                . 'Which rates higher regarding "' . $this->getVector() . '" to you? ' . PHP_EOL
                . '  (1) '. $insertLink->getText() . PHP_EOL
                . '    or ' . PHP_EOL
                . '  (2) ' . $compareLink->getText()
                . PHP_EOL . PHP_EOL;

            $result = '';

            while (!in_array($result, self::POSSIBLE_ANSWERS)) {
                $result = readline('Please enter "1", "2", or "q" to quit: ');
            }

            if ($result === 'q') {
                $this->showResult();
                return false;
            }

            if ($result === '1') {
                $this->getChain()->insertBefore($insertLink, $compareLink);
                return true;
            }

            $compareLink = $compareLink->getNext();
        }

        // finally add new link at the end
        $this->getChain()->insert($insertLink);

        return true;
    }

    /**
     * Show result
     */
    private function showResult()
    {
        echo PHP_EOL. PHP_EOL;
        echo 'Here are your priorities regarding "' . $this->getVector() . '":' . PHP_EOL;
        echo '--------------------------' . PHP_EOL;

        // render chain
        echo $this->getChain()->render();

        // @todo render undecided
    }

    /**
     * Return original list
     *
     * @return array
     */
    public function getInputList(): array
    {
        return $this->inputList;
    }

    /**
     * Set List of strings to be sorted
     *
     * @param array $inputList
     */
    public function setInputList(array $inputList)
    {
        $this->inputList = $inputList;
    }

    /**
     * Get chain
     *
     * @return Chain
     */
    public function getChain(): Chain
    {
        return $this->chain;
    }

    /**
     * Set chain
     *
     * @param Chain $chain
     */
    public function setChain(Chain $chain)
    {
        $this->chain = $chain;
    }

    /**
     * @return string
     */
    public function getVector(): string
    {
        return $this->vector;
    }

    /**
     * @param string $vector
     */
    public function setVector(string $vector): void
    {
        $this->vector = $vector;
    }

}