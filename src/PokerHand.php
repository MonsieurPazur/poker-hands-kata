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
     * @var array list of methods to check (in order) for different hands
     */
    private const CHECKERS = [
        'checkTwoPairs',
        'checkPair',
        'checkHighestCard'
    ];

    /**
     * @var int bonus rank for a specific hand
     */
    private const BONUS_PAIR = 14;
    private const BONUS_TWO_PAIRS = 28;

    /**
     * @var Card[] $cards list of cards in hand
     */
    private $cards;

    /**
     * @var int $rank rank value of this hand based on cards
     */
    private $rank;

    /**
     * PokerHand constructor.
     *
     * @param string $hand
     */
    public function __construct(string $hand)
    {
        $this->parseCards($hand);
        $this->rank = 0;
    }

    /**
     * Gets rank of this hands based on cards.
     *
     * @return int rank of this hand
     */
    public function getRank(): int
    {
        $this->handleRank();
        return $this->rank;
    }

    /**
     * Generates list of cards based on given hand string
     *
     * @param string $hand symbols of cards
     */
    private function parseCards(string $hand): void
    {
        $cards = explode(' ', $hand);
        foreach ($cards as $card) {
            $this->cards[] = new Card($card);
        }
    }

    /**
     * Handles cards and calculates rank.
     */
    private function handleRank(): void
    {
        // Loop over all of different checkers.
        foreach (self::CHECKERS as $checker) {
            // If a specific hand is found, there's no need to look further.
            if ($this->$checker()) {
                break;
            }
        }
    }

    /**
     * Searches for two pairs in hand.
     *
     * @return bool true if found in hand
     */
    private function checkTwoPairs(): bool
    {
        $rank = self::BONUS_TWO_PAIRS;
        $pairs = 0;
        for ($i = 0, $iMax = count($this->cards); $i < $iMax; $i++) {
            for ($j = $i + 1, $jMax = count($this->cards); $j < $jMax; $j++) {
                if ($this->areCardsSameValue($this->cards[$i], $this->cards[$j])) {
                    $rank += $this->cards[$i]->getRank();
                    $pairs++;
                }
            }
        }
        if (2 === $pairs) {
            $this->rank = $rank;
            return true;
        }
        return false;
    }

    /**
     * Searches for single pair in hand.
     *
     * @return bool true if found in hand
     */
    private function checkPair(): bool
    {
        for ($i = 0, $iMax = count($this->cards); $i < $iMax; $i++) {
            for ($j = $i + 1, $jMax = count($this->cards); $j < $jMax; $j++) {
                if ($this->areCardsSameValue($this->cards[$i], $this->cards[$j])) {
                    $this->rank = self::BONUS_PAIR + $this->cards[$i]->getRank();
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Searches for a highest card in hand.
     *
     * @return bool true if found in hand
     */
    private function checkHighestCard(): bool
    {
        $highest = 0;
        foreach ($this->cards as $card) {
            $highest = $highest >= $card->getRank() ? $highest : $card->getRank();
        }
        $this->rank = $highest;
        return true;
    }

    /**
     * Checks whether two given cards are of the same value.
     *
     * @param Card $first card we want to check
     * @param Card $second card we want to check against
     *
     * @return bool true if both cards are of the same value
     */
    private function areCardsSameValue(Card $first, Card $second): bool
    {
        return $first->getValue() === $second->getValue();
    }
}
