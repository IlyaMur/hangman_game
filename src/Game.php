<?php

declare(strict_types=1);

namespace Ilyamur\HangmanGame;

class Game
{
    private const TOTAL_ERRORS_ALLOWED = 6;

    private array $letters;
    private array $userGuesses = [];

    public function __construct(GameDBProvider $gameDb)
    {
        $this->letters = mb_str_split($gameDb->getWord());
    }

    public function getErrors(): array
    {
        return array_diff($this->userGuesses, $this->letters);
    }

    public function errorsMade(): int
    {
        return count($this->getErrors());
    }

    public function errorsAllowed(): int
    {
        return static::TOTAL_ERRORS_ALLOWED - $this->errorsMade();
    }


    private function normalizedLetter(string $letter): string
    {
        if ($letter === 'Ë') {
            return 'E';
        } elseif ($letter === 'Й') {
            return 'И';
        }

        return $letter;
    }

    private function normalizedLetters(array $letters): array
    {
        return array_map(fn ($letter) => $this->normalizedLetter($letter), $letters);
    }

    public function lettersToGuess(): array
    {
        return array_map(
            fn ($letter) => in_array(
                $this->normalizedLetter($letter),
                $this->userGuesses
            ) ? $letter : null,
            $this->letters
        );
    }

    public function play(string $letter): void
    {
        $nomalizeLetter = $this->normalizedLetter($letter);

        if (!$this->isOver() && !in_array($nomalizeLetter, $this->userGuesses)) {
            $this->userGuesses[] = $nomalizeLetter;
        }
    }

    public function isLost(): bool
    {
        return $this->errorsAllowed() === 0;
    }

    public function isOver(): bool
    {
        return $this->isWon() || $this->isLost();
    }

    public function isWon(): bool
    {
        return empty(array_diff(
            $this->normalizedLetters($this->letters),
            $this->userGuesses
        ));
    }

    public function word(): string
    {
        return implode('', $this->letters);
    }
}