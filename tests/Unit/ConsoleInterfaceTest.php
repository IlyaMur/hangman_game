<?php

declare(strict_types=1);

namespace Ilyamur\HangmanGame\Tests\Unit\Controllers;

use Ilyamur\HangmanGame\Game;
use Jfcherng\Utility\CliColor;
use PHPUnit\Framework\TestCase;
use Ilyamur\HangmanGame\GameDBProvider;
use Ilyamur\HangmanGame\ConsoleInterface;

class ConsoleInterfaceTest extends TestCase
{
    public function testGetErrorsCorrectly()
    {
        $gameMock = $this
            ->getMockBuilder(Game::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getErrors'])
            ->getMock();
        $gameMock->expects($this->once())->method('getErrors')->willReturn(['A', 'B', 'C']);

        $dbProviderMock = $this->createMock(GameDBProvider::class);

        $consoleMock = $this
            ->getMockBuilder(ConsoleInterface::class)
            ->setConstructorArgs([$gameMock, $dbProviderMock])
            ->onlyMethods([])
            ->getMock();

        $this->assertEquals('A B C', $consoleMock->getErrorsToShow());
    }

    public function testGetWordToShow()
    {
        $gameMock = $this
            ->getMockBuilder(Game::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['lettersToGuess'])
            ->getMock();

        $gameMock
            ->expects($this->once())
            ->method('lettersToGuess')
            ->willReturn(['A', 'B', 'C', null, 'Z']);

        $dbProviderMock = $this->createMock(GameDBProvider::class);

        $consoleMock = $this
            ->getMockBuilder(ConsoleInterface::class)
            ->setConstructorArgs([$gameMock, $dbProviderMock])
            ->onlyMethods([])
            ->getMock();

        $this->assertEquals('ABC_Z', $consoleMock->getWordToShow());
    }

    public function testCorrectFigurePrint()
    {
        $figure = file_get_contents(__DIR__ . '/__fixtures__/' . 'figure-1.txt');

        $gameMock = $this
            ->getMockBuilder(Game::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['isWon'])
            ->getMock();
        $gameMock->method('isWon')->willReturn(true);
        $gameMock->letters = [];

        $consoleMock = $this
            ->getMockBuilder(ConsoleInterface::class)
            ->setConstructorArgs([$gameMock, $this->createMock(GameDBProvider::class)])
            ->onlyMethods([
                'getCurrentFigure',
                'getErrorsToShow',
                'getWordToShow',
            ])
            ->getMock();

        $consoleMock->method('getCurrentFigure')->willReturn($figure);
        $consoleMock->method('getErrorsToShow')->willReturn('');
        $consoleMock->method('getWordToShow')->willReturn('___');

        $errors = CliColor::color("Ошибки:", 'f_red');
        $finalMessage =  CliColor::color('Поздравляем, вы выиграли!', ['f_white', 'b_green', 'b', 'blk']);

        $outputHeredoc = <<<END
        
        Слово: ___
        
        $figure
        
        $errors 0: 
        $finalMessage
        
        END;

        $consoleMock->printOut();
        $this->expectOutputString($outputHeredoc);
    }

    public function testPrintCorrectFinalMessageWhenGameIsLost()
    {
        $gameMock = $this
            ->getMockBuilder(Game::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['isLost', 'isWon', 'getGuessedWord'])
            ->getMock();
        $gameMock->method('isLost')->willReturn(true);
        $gameMock->method('getGuessedWord')->willReturn('РУБИ');
        $gameMock->letters = [];

        $consoleMock = $this
            ->getMockBuilder(ConsoleInterface::class)
            ->setConstructorArgs([$gameMock, $this->createMock(GameDBProvider::class)])
            ->onlyMethods([])
            ->getMock();

        $finalMessage = "Вы проиграли, загаданное слово: " . 'РУБИ' . "\n";

        $consoleMock->printFinalMessage();
        $this->expectOutputString($finalMessage);
    }
}