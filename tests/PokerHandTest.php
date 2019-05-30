<?php

/**
 * Test suite for comparing poker hands.
 */

namespace Test;

use App\PokerHand;
use Generator;
use PHPUnit\Framework\TestCase;

/**
 * Class PokerHandTest
 *
 * @package Test
 */
class PokerHandTest extends TestCase
{
    /**
     * Tests rank of hand based on cards.
     *
     * @dataProvider pokerHandsProvider
     *
     * @param string $input symbols of five cards in hand
     * @param int $expected rank of this hand
     */
    public function testRankPokerHand(string $input, int $expected): void
    {
        $hand = new PokerHand($input);
        $this->assertEquals($expected, $hand->getRank());
    }

    /**
     * Provides hands of cards.
     *
     * @return Generator
     */
    public function pokerHandsProvider(): Generator
    {
        yield 'highest card: lowest possible hand' => [
            'input' => '2H 3H 4H 5H 7C',
            'expected' => 7
        ];
        yield 'highest card: highest card ace' => [
            'input' => '2H 3H 4H 5H AS',
            'expected' => 14
        ];
        yield 'pair: two fours' => [
            'input' => '2H 3H 4H 4D AS',
            'expected' => 18
        ];
        yield 'two pairs: two fives and two jacks' => [
            'input' => '5H 2H JS JD 5S',
            'expected' => 44
        ];
        yield 'three of a kind: three queens' => [
            'input' => 'QD KH QH AS QS',
            'expected' => 67
        ];
        yield 'straight: straight from seven to jack' => [
            'input' => 'JS 7H TH 9H 8D',
            'expected' => 80
        ];
        yield 'flush: all spades' => [
            'input' => '2S KS TS QS 6S',
            'expected' => 96
        ];
        yield 'full house: two queens and three nines' => [
            'input' => '9S QH 9D QC 9C',
            'expected' => 106
        ];
        yield 'four of a kind: four aces and a seven' => [
            'input' => 'AS AH 7D AC AD',
            'expected' => 125
        ];
        yield 'straight flush: from five to nine' => [
            'input' => '8H 6H 7H 9H 5H',
            'expected' => 134
        ];
    }
}
