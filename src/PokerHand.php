<?php

/**
 * Handles ranking and comparing pair of poker hands.
 */

namespace App;

/**
 * Class PokerHand
 *
 * @package App
 */
class PokerHand
{
    /**
     * @var Card[] $cards list of cards in hand
     */
    private $cards;

    /**
     * PokerHand constructor.
     *
     * @param string $hand
     */
    public function __construct(string $hand)
    {
        $cards = explode(' ', $hand);
        foreach ($cards as $card) {
            $this->cards[] = new Card($card);
        }
    }

    /**
     * Gets rank of this hands based on cards.
     *
     * @return int rank of this hand
     */
    public function getRank(): int
    {
        return $this->getHighestCardRank();
    }

    /**
     * Gets rank of the highest card in hand.
     *
     * @return int rank of the highest card in hand
     */
    private function getHighestCardRank(): int
    {
        $highest = 0;
        foreach ($this->cards as $card) {
            $highest = $highest >= $card->getRank() ? $highest : $card->getRank();
        }
        return $highest;
    }
}
