<?php

namespace GisoStallenberg\PDOClauseHelper\Tests;

use GisoStallenberg\PDOClauseHelper\ClauseHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Test the clause helper
 */
class ClauseHelperTest extends TestCase
{
    /**
     * Test the to placeholder functionality
     */
    #[DataProvider('toInClausePlaceHolderStringData')]
    public function testToInClausePlaceHolderString($array, $prefix, $expectedString, $expectedArray)
    {
        $binder = ClauseHelper::create();

        $this->assertNotEquals($array, $expectedArray);

        $string = $binder->toInClausePlaceHolderString($array, $prefix);

        $this->assertEquals($string, $expectedString);
        $this->assertEquals($array, $expectedArray);
    }

    /**
     * Data for the test
     *
     * @return array
     */
    public static function toInClausePlaceHolderStringData()
    {
        return [
            [
                [
                    'a',
                    'b',
                    'c',
                ],
                null,
                '(:parameter0, :parameter1, :parameter2)',
                [
                    ':parameter0' => 'a',
                    ':parameter1' => 'b',
                    ':parameter2' => 'c',
                ],

            ],
            [
                [
                    153,
                    158,
                    186,
                ],
                'id',
                '(:id0, :id1, :id2)',
                [
                    ':id0' => 153,
                    ':id1' => 158,
                    ':id2' => 186,
                ],

            ],
            [
                [
                    'a' => 153,
                    'b' => 158,
                    'c' => 186,
                ],
                'id',
                '(:id0, :id1, :id2)',
                [
                    ':id0' => 153,
                    ':id1' => 158,
                    ':id2' => 186,
                ],

            ]

        ];
    }
}
