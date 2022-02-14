<?php

declare(strict_types=1);

namespace Ilyamur\HangmanGame\Tests\Unit\Controllers;

use Ilyamur\HangmanGame\Game;
use PHPUnit\Framework\TestCase;
use Ilyamur\HangmanGame\GameDBProvider;

class GameTest extends TestCase
{
    public function testCorrectAssignAndSplitInConstructor()
    {
        $word = 'testWord';

        $dbProviderMock = $this
            ->getMockBuilder(GameDBProvider::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getWord'])
            ->getMock();

        $dbProviderMock
            ->expects($this->once())
            ->method('getWord')
            ->willReturn($word);

        $game = new Game($dbProviderMock);

        $this->assertEquals($word, $game->getGuessedWord());
    }

    public function testCorrectNormalizeRussianLetters()
    {
        $gameMock = $this
            ->getMockBuilder(Game::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock();

        $this->assertEquals('Е', $gameMock->normalizedLetter('Ë'));
        $this->assertEquals('И', $gameMock->normalizedLetter('Й'));
        $this->assertEquals('Ж', $gameMock->normalizedLetter('Ж'));
    }

    /**
     * @dataProvider dataForNormalizedLettersMethod
     */

    public function testCorrectNormalizeArraysWithRussianLetters(array $arrayAfter, array $arrayBefore)
    {
        $gameMock = $this
            ->getMockBuilder(Game::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock();

        $this->assertEquals($arrayAfter, $gameMock->normalizedLetters($arrayBefore));
    }

    public function dataForNormalizedLettersMethod()
    {
        return [
            'Ë' => [['Е', 'Л', 'К', 'А'], ['Ë', 'Л', 'К', 'А']],
            'Й' =>  [['Ф', 'А', 'И', 'Л'], ['Ф', 'А', 'Й', 'Л']],
            '-' => [['Т', 'Е', 'Т', 'Р', 'А', 'Д', 'Ь'], ['Т', 'Е', 'Т', 'Р', 'А', 'Д', 'Ь']],
            'Empty arrays' => [[], []],
        ];
    }

    public function testCorrectGetErrors()
    {
        $gameMock = $this
            ->getMockBuilder(Game::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock();

        $gameMock->userGuesses = ['Т', 'Е', 'С', 'В'];
        $gameMock->letters = ['Т', 'Е', 'С', 'Т'];

        $this->assertEquals([3 => 'В'], $gameMock->getErrors());
    }

    public function testCorrectCountErrors()
    {
        $gameMock = $this
            ->getMockBuilder(Game::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock();

        $gameMock->userGuesses = ['Т', 'Е', 'С', 'В'];
        $gameMock->letters = ['Т', 'Е', 'С', 'Т'];
        $this->assertEquals(1, $gameMock->errorsMade());

        $gameMock->userGuesses = [];
        $this->assertEquals(0, $gameMock->errorsMade());
    }

    public function testCorrectCountAllowedErrors()
    {
        $word = 'test';

        $dbProviderMock = $this
            ->getMockBuilder(GameDBProvider::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getWord'])
            ->getMock();

        $dbProviderMock
            ->expects($this->once())
            ->method('getWord')
            ->willReturn($word);

        $game = new Game($dbProviderMock);

        $this->assertEquals(6, $game->errorsAllowed());

        $game->userGuesses = ['A', 'B', 'C', 'D', 'Z', 'B'];
        $this->assertEquals(0, $game->errorsAllowed());
        // in this condition - game is lost
        $this->assertTrue($game->isLost());
    }

    /**
     * @dataProvider dataForOverTheGame
     */

    public function testCorrectOverTheGame(string $method)
    {
        $gameMock = $this
            ->getMockBuilder(Game::class)
            ->disableOriginalConstructor()
            ->onlyMethods([$method])
            ->getMock();

        $gameMock->letters = [];

        $gameMock->method($method)->willReturn(true);

        $this->assertTrue($gameMock->isOver());
    }

    public function dataForOverTheGame(): array
    {
        return [
            ['isLost'],
            ['isWon']
        ];
    }

    public function testCorrectCalcLettersToGuess()
    {
        $gameMock = $this
            ->getMockBuilder(Game::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock();

        $gameMock->userGuesses = ['Т', 'Е'];
        $gameMock->letters = ['Т', 'Е', 'С', 'Т'];
        $this->assertEquals(['Т', 'Е', null, 'Т'], $gameMock->lettersToGuess());

        $gameMock->userGuesses = [];
        $gameMock->letters = ['Т', 'Е', 'С', 'Т'];
        $this->assertEquals([null, null, null, null], $gameMock->lettersToGuess());
    }

    public function testCorrectPlayTheLetter()
    {
        $gameMock = $this
            ->getMockBuilder(Game::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['isOver'])
            ->getMock();

        $gameMock->method('isOver')->willReturn(false);

        $gameMock->userGuesses = [];
        $gameMock->play('E');
        $this->assertEquals(['E'], $gameMock->userGuesses);

        // does not play same letter twice
        $gameMock->userGuesses = ['B'];
        $gameMock->play('B');
        $this->assertEquals(['B'], $gameMock->userGuesses);
    }
    // does not play if game is over

    public function testDoesNotPlayIfGameIsOver()
    {
        $gameMock = $this
            ->getMockBuilder(Game::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['isOver'])
            ->getMock();

        $gameMock->method('isOver')->willReturn(true);

        $gameMock->userGuesses = ['B'];
        $gameMock->play('Z');

        $this->assertEquals(['B'], $gameMock->userGuesses);
    }

    public function testCorrectGetTheWord()
    {
        $gameMock = $this
            ->getMockBuilder(Game::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock();

        $gameMock->letters = ['T', 'E', 'S', 'T'];

        $this->assertEquals('TEST', $gameMock->getGuessedWord());
    }

    public function testCorrectWonTheGame()
    {
        $gameMock = $this
            ->getMockBuilder(Game::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock();

        $gameMock->userGuesses = ['T', 'E', 'S', 'F', 'B'];
        $gameMock->letters = ['T', 'E', 'S', 'T'];

        $this->assertTrue($gameMock->isWon());
    }
}