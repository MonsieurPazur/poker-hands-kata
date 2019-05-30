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
        'checkStraightFlush',
        'checkFourOfAKind',
        'checkFullHouse',
        'checkFlush',
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
    private const BONUS_FLUSH = 83;
    private const BONUS_FULL_HOUSE = 97;
    private const BONUS_FOUR_OF_A_KIND = 111;
    private const BONUS_STRAIGHT_FLUSH = 125;

    /**
     * @var Card[] $cards list of cards in hand
     */
    private $cards;

    /**
     * @var int $rank rank value of this hand based on cards
     */
    private $rank;

    /**
     * @var string $reason text description of this hand
     */
    private $reason;

    /**
     * PokerHand constructor.
     *
     * @param string $hand
     */
    public function __construct(string $hand)
    {
        $this->parseCards($hand);
        $this->rank = 0;
        $this->reason = '';
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
     * Represents hand with text description, reason for a specific cards rank.
     *
     * @return string reason for a specific rank
     */
    public function getReason(): string
    {
        $this->handleRank();
        return $this->reason;
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
     * Searches for straight flush in hand.
     *
     * @return bool true if found in hand
     */
    private function checkStraightFlush(): bool
    {
        for ($i = 0; $i < count($this->cards) - 1; $i++) {
            if (!$this->areCardsNeighbours($this->cards[$i], $this->cards[$i + 1])
                || !$this->areCardsSameSuit($this->cards[$i], $this->cards[$i + 1])
            ) {
                return false;
            }
        }
        $this->rank = self::BONUS_STRAIGHT_FLUSH + end($this->cards)->getRank();
        $this->reason = 'straight flush: ' . $this->cards[0]->getSuitName()
            . ', from '. $this->cards[0]->getName()
            . ' to ' . end($this->cards)->getName();
        return true;
    }

    /**
     * Searches for four of a kind in hand.
     *
     * @return bool true if found in hand
     */
    private function checkFourOfAKind(): bool
    {
        $same = 1;
        for ($i = 0; $i < count($this->cards) - 1; $i++) {
            if ($this->areCardsSameValue($this->cards[$i], $this->cards[$i + 1])) {
                if (4 === ++$same) {
                    $this->rank = self::BONUS_FOUR_OF_A_KIND + $this->cards[$i]->getRank();
                    $this->reason = 'four of a kind: ' . $this->cards[$i]->getName();
                    return true;
                }
            } else {
                $same = 1;
            }
        }
        return false;
    }

    /**
     * Searches for flush in hand.
     *
     * @return bool true if found in hand
     */
    private function checkFullHouse(): bool
    {
        $three = null;
        $pair = null;
        $same = 1;
        for ($i = 0; $i < count($this->cards) - 1; $i++) {
            if ($this->areCardsSameValue($this->cards[$i], $this->cards[$i + 1])) {
                $same++;

                // There are three scenarios to take in consideration here.
                // Given cards array is sorted:
                //
                // 1. There's no three and/or pair in hand (pretty obvious).
                // 2. Pair first then three (if first pair is found, we leave it, when we find next one).
                if (2 === $same && null === $pair) {
                    $pair = $this->cards[$i];
                }

                // 3. Three first then pair (we cancel first pair and look for it in the last two cards).
                if (3 === $same) {
                    $three = $this->cards[$i];
                    if (null !== $pair && $this->areCardsSameValue($three, $pair)) {
                        $pair = null;
                    }
                }
            } else {
                $same = 1;
            }
        }

        // This is essentialy checking first scenario.
        if (null !== $pair && null !== $three) {
            $this->rank = self::BONUS_FULL_HOUSE + $three->getRank();
            $this->reason = 'full house: ' . $three->getName() . ' over ' . $pair->getName();
            return true;
        }
        return false;
    }

    /**
     * Searches for flush in hand.
     *
     * @return bool true if found in hand
     */
    private function checkFlush(): bool
    {
        for ($i = 0; $i < count($this->cards) - 1; $i++) {
            if (!$this->areCardsSameSuit($this->cards[$i], $this->cards[$i + 1])) {
                return false;
            }
        }
        $this->rank = self::BONUS_FLUSH + end($this->cards)->getRank();
        $this->reason = 'flush: ' . $this->cards[0]->getSuitName();
        return true;
    }

    /**
     * Searches for straight in hand.
     *
     * @return bool true if found in hand
     */
    private function checkStraight(): bool
    {
        for ($i = 0; $i < count($this->cards) - 1; $i++) {
            if (!$this->areCardsNeighbours($this->cards[$i], $this->cards[$i + 1])) {
                return false;
            }
        }
        $this->rank = self::BONUS_STRAIGHT + end($this->cards)->getRank();
        $this->reason = 'straight: from ' . $this->cards[0]->getName() . ' to ' . end($this->cards)->getName();
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
        for ($i = 0; $i < count($this->cards) - 1; $i++) {
            if ($this->areCardsSameValue($this->cards[$i], $this->cards[$i + 1])) {
                if (3 === ++$same) {
                    $this->rank = self::BONUS_THREE_OF_A_KIND + $this->cards[$i]->getRank();
                    $this->reason = 'three of a kind: ' . $this->cards[$i]->getName();
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
        $firstPair = null;
        for ($i = 0; $i < count($this->cards) - 1; $i++) {
            if ($this->areCardsSameValue($this->cards[$i], $this->cards[$i + 1])) {
                $rank += $this->cards[$i]->getRank();
                if (null === $firstPair) {
                    $firstPair = $this->cards[$i];
                }
                if (2 === ++$pairs) {
                    $this->rank = self::BONUS_TWO_PAIRS + $rank;
                    $this->reason = 'two pairs: ' . $this->cards[$i]->getName() . ' and ' . $firstPair->getName();
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
        for ($i = 0; $i < count($this->cards) - 1; $i++) {
            if ($this->areCardsSameValue($this->cards[$i], $this->cards[$i + 1])) {
                $this->rank = self::BONUS_PAIR + $this->cards[$i]->getRank();
                $this->reason = 'pair: ' . $this->cards[$i]->getName();
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
        $highest = end($this->cards);
        $this->rank = $highest->getRank();
        $this->reason = 'high card: ' . $highest->getName();
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
     * Checks whether two given cards are next to each other (in terms of value).
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

    /**
     * Checks whether two given cards are of the same suit.
     *
     * @param Card $first card we want to check
     * @param Card $second card we want to check against
     *
     * @return bool true if both cards are of the same suit
     */
    private function areCardsSameSuit(Card $first, Card $second): bool
    {
        return $first->getSuit() === $second->getSuit();
    }
}
