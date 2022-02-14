<?php

declare(strict_types=1);

namespace Ilyamur\HangmanGame\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Ilyamur\HangmanGame\GameDBProvider;

class GameDBProviderTest extends TestCase
{
    /**
     * @dataProvider figuresProvider
     */
    public function testCorrectlyReturnFigure(int $figureNum)
    {
        $dbProvider = new GameDBProvider(DB_DSN);

        $path = $this->getFigurePath($figureNum);

        $expectedFigure = file_get_contents($path);
        $figure = $dbProvider->getFigure($figureNum);

        $this->assertEquals($expectedFigure, $figure);
    }

    public function figuresProvider()
    {
        return [[3], [7], [5]];
    }

    public function getFigurePath(int $figureNum)
    {
        return __DIR__ . '/__fixtures__/' . "figure-$figureNum.txt";
    }

    public function testCorrectlyGetWordStringsFromDB()
    {
        $dbProvider = new GameDBProvider(DB_DSN);
        $word = $dbProvider->getWord();

        $this->assertIsString($word);
    }
}
