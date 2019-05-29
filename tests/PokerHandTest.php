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
        yield 'lowest possible hand' => [
            'input' => '2H 3H 4H 5H 7C',
            'expected' => 7
        ];
    }
}
