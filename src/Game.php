<?php

declare(strict_types=1);

namespace Ilyamur\HangmanGame;

class Game
{
    private const TOTAL_ERRORS_ALLOWED = 7;

    public function __construct(
        private GameDBProvider $gameDb,
        private array $userGuesses = []
    ) {
    }

    private function getLetters()
    {
        return explode('', $this->gameDb->getWord());
    }

    public function getErrors()
    {
    }

    public function normalizedLetter($letter)
    {
        if ($letter === 'Ë') {
            return 'E';
        } elseif ($letter === 'Й') {
            return 'И';
        }

        return $letter;
    }

    public function normalizedLetters($letters)
    {
        return array_map(fn ($letter) => $this->normalizedLetter($letter), $letters);
    }
}