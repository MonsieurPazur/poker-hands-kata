<?php

/**
 * Test suite for Cards.
 */

namespace Test;

use App\Card;
use Generator;
use PHPUnit\Framework\TestCase;

/**
 * Class CardTest
 *
 * @package Test
 */
class CardTest extends TestCase
{
    /**
     * Tests whether card is of specific suit.
     *
     * @dataProvider suitsProvider
     *
     * @param string $input value + suit
     * @param string $expected extracted suit
     */
    public function testCardSuit(string $input, string $expected): void
    {
        $card = new Card($input);
        $this->assertEquals($expected, $card->getSuit());
    }

    /**
     * Tests whether card is of specific value.
     *
     * @dataProvider valuesProvider
     *
     * @param string $input value + suit
     * @param string $expected extracted value
     */
    public function testCardValue(string $input, string $expected): void
    {
        $card = new Card($input);
        $this->assertEquals($expected, $card->getValue());
    }

    /**
     * Tests rank of a single card.
     *
     * @dataProvider ranksProvider
     *
     * @param string $input value + suit
     * @param int $expected rank of card
     */
    public function testCardRank(string $input, int $expected): void
    {
        $card = new Card($input);
        $this->assertEquals($expected, $card->getRank());
    }

    /**
     * Tests name of a single card.
     *
     * @dataProvider namesProvider
     *
     * @param string $input value + suit
     * @param string $expected name of card
     */
    public function testCardName(string $input, string $expected): void
    {
        $card = new Card($input);
        $this->assertEquals($expected, $card->getName());
    }

    /**
     * Tests suit name of a single card.
     *
     * @dataProvider suitNamesProvider
     *
     * @param string $input value + suit
     * @param string $expected suit name of card
     */
    public function testCardSuitName(string $input, string $expected): void
    {
        $card = new Card($input);
        $this->assertEquals($expected, $card->getSuitName());
    }

    /**
     * Provides data for suits tests.
     *
     * @return Generator
     */
    public function suitsProvider(): Generator
    {
        yield 'King of clubs' => [
            'input' => 'KC',
            'expected' => Card::CLUBS
        ];
        yield 'Ace of spades' => [
            'input' => 'AS',
            'expected' => Card::SPADES
        ];
    }

    /**
     * Provides data for values tests.
     *
     * @return Generator
     */
    public function valuesProvider(): Generator
    {
        yield 'Seven of hearts' => [
            'input' => '7H',
            'expected' => Card::SEVEN
        ];
        yield 'Queen of diamonds' => [
            'input' => 'QD',
            'expected' => Card::QUEEN
        ];
    }

    /**
     * Provides data for rank tests.
     *
     * @return Generator
     */
    public function ranksProvider(): Generator
    {
        yield 'Eight of spades' => [
            'input' => '8S',
            'expected' => 8
        ];
        yield 'Eight of diamonds' => [
            'input' => '8D',
            'expected' => 8
        ];
        yield 'QUEEN of hearts' => [
            'input' => 'QH',
            'expected' => 12
        ];
    }

    /**
     * Provides data for name tests.
     *
     * @return Generator
     */
    public function namesProvider(): Generator
    {
        yield 'Eight of spades' => [
            'input' => '8S',
            'expected' => Card::NAME[Card::EIGHT]
        ];
        yield 'Queen of hearts' => [
            'input' => 'QH',
            'expected' => Card::NAME[Card::QUEEN]
        ];
    }

    /**
     * Provides data for suit name tests.
     *
     * @return Generator
     */
    public function suitNamesProvider(): Generator
    {
        yield 'Eight of spades' => [
            'input' => '8S',
            'expected' => Card::SUIT_NAME[Card::SPADES]
        ];
        yield 'Queen of hearts' => [
            'input' => 'QH',
            'expected' => Card::SUIT_NAME[Card::HEARTS]
        ];
    }
}
