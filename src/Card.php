<?php

/**
 * Keeps information about value and suit.
 */

namespace App;

/**
 * Class Card
 *
 * @package App
 */
class Card
{
    /**
     * @var string all possible values of a card
     */
    public const TWO = '2';
    public const THREE = '3';
    public const FOUR = '4';
    public const FIVE = '5';
    public const SIX = '6';
    public const SEVEN = '7';
    public const EIGHT = '8';
    public const NINE = '9';
    public const TEN = 'T';
    public const JACK = 'J';
    public const QUEEN = 'Q';
    public const KING = 'K';
    public const ACE = 'A';

    /**
     * @var string all possible suits of a card
     */
    public const CLUBS = 'C';
    public const DIAMONDS = 'D';
    public const HEARTS = 'H';
    public const SPADES = 'S';

    /**
     * @var array text description of a single card's value
     */
    public const NAME = [
        self::TWO => 'two',
        self::THREE => 'three',
        self::FOUR => 'four',
        self::FIVE => 'five',
        self::SIX => 'six',
        self::SEVEN => 'seven',
        self::EIGHT => 'eight',
        self::NINE => 'nine',
        self::TEN => 'ten',
        self::JACK => 'jack',
        self::QUEEN => 'queen',
        self::KING => 'king',
        self::ACE => 'ace'
    ];

    /**
     * @var array text description of a single card's suit
     */
    public const SUIT_NAME = [
        self::CLUBS => 'clubs',
        self::DIAMONDS => 'diamonds',
        self::HEARTS => 'hearts',
        self::SPADES => 'spades'
    ];

    /**
     * @var array rank of a single card (based on value only)
     */
    private const RANK = [
        self::TWO => 2,
        self::THREE => 3,
        self::FOUR => 4,
        self::FIVE => 5,
        self::SIX => 6,
        self::SEVEN => 7,
        self::EIGHT => 8,
        self::NINE => 9,
        self::TEN => 10,
        self::JACK => 11,
        self::QUEEN => 12,
        self::KING => 13,
        self::ACE => 14
    ];

    /**
     * @var string $value value of this card
     */
    private $value;

    /**
     * @var string $suit suit of this card
     */
    private $suit;

    /**
     * Card constructor.
     *
     * @param string $card
     */
    public function __construct(string $card)
    {
        $symbols = str_split($card);
        $this->value = $symbols[0];
        $this->suit = $symbols[1];
    }

    /**
     * Gets value of this card.
     *
     * @return string value of this card
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Gets suit of this card.
     *
     * @return string suit of this card
     */
    public function getSuit(): string
    {
        return $this->suit;
    }

    /**
     * Gets rank of this card (based on value only).
     *
     * @return int value from RANK table
     */
    public function getRank(): int
    {
        return self::RANK[$this->value];
    }

    /**
     * Gets text representation of this card's value.
     *
     * @return string text representation
     */
    public function getName(): string
    {
        return self::NAME[$this->value];
    }

    /**
     * Gets text representation of this card's suit.
     *
     * @return string text representation
     */
    public function getSuitName(): string
    {
        return self::SUIT_NAME[$this->suit];
    }
}
