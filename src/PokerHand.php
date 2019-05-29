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
        'checkStraight',
        'checkThreeOfAKind',
        'checkTwoPairs',
        'checkPair',
        'checkHighestCard'
    ];

    /**
     * @var int bonus rank for a specific hand
     */
    private const BONUS_PAIR = 14;

    private const BONUS_TWO_PAIRS = 28;

    private const BONUS_THREE_OF_A_KIND = 55;

    private const BONUS_STRAIGHT = 69;

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
        usort($this->cards, static function (Card $a, Card $b) {
            return $a->getRank() > $b->getRank();
        });
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
     * Searches for straight in hand.
     *
     * @return bool true if found in hand
     */
    private function checkStraight(): bool
    {
        foreach ($this->cards as $i => $card) {
            if (isset($this->cards[$i + 1]) && !$this->areCardsNeighbours($card, $this->cards[$i + 1])) {
                return false;
            }
        }
        $this->rank = self::BONUS_STRAIGHT + end($this->cards)->getRank();
        return true;
    }

    /**
     * Searches for three of a kind in hand.
     *
     * @return bool true if found in hand
     */
    private function checkThreeOfAKind(): bool
    {
        $same = 1;
        foreach ($this->cards as $i => $card) {
            if (isset($this->cards[$i + 1]) && $this->areCardsSameValue($card, $this->cards[$i + 1])) {
                if (3 === ++$same) {
                    $this->rank = self::BONUS_THREE_OF_A_KIND + $card->getRank();
                    return true;
                }
            } else {
                $same = 1;
            }
        }
        return false;
    }

    /**
     * Searches for two pairs in hand.
     *
     * @return bool true if found in hand
     */
    private function checkTwoPairs(): bool
    {
        $pairs = 0;
        $rank = 0;
        foreach ($this->cards as $i => $card) {
            if (isset($this->cards[$i + 1]) && $this->areCardsSameValue($card, $this->cards[$i + 1])) {
                $rank += $this->cards[$i]->getRank();
                if (2 === ++$pairs) {
                    $this->rank = self::BONUS_TWO_PAIRS + $rank;
                    return true;
                }
            }
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
        foreach ($this->cards as $i => $card) {
            if (isset($this->cards[$i + 1]) && $this->areCardsSameValue($card, $this->cards[$i + 1])) {
                $this->rank = self::BONUS_PAIR + $card->getRank();
                return true;
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

    /**
     * Checks whether two given cards are next to each other (in terms of value)
     *
     * @param Card $first card we want to check
     * @param Card $second card we want to check against
     *
     * @return bool true if cards are next to each other
     */
    private function areCardsNeighbours(Card $first, Card $second): bool
    {
        return abs($first->getRank() - $second->getRank()) === 1;
    }
}
