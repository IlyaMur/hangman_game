<?php

declare(strict_types=1);

namespace Ilyamur\HangmanGame;

/**
 * Game
 *
 * Game class, includes all game logic
 *
 * PHP version 8.1
 */


class Game
{
    private const TOTAL_ERRORS_ALLOWED = 6;

    public array $letters;
    public string $playerName;
    public GameDBProvider $gameDb;
    public array $userGuesses = [];

    /**
     * Class constructor
     *
     * @param GameDBProvider $gameDb Database provider object
     *
     * @return void
     */

    public function __construct(GameDBProvider $gameDb)
    {
        $this->gameDb = $gameDb;
        $this->letters = mb_str_split($gameDb->getWord());
    }

    /**
     * Normalize russian letters
     *
     * @param string $letter letter
     *
     * @return string
     */

    public function normalizedLetter(string $letter): string
    {
        if ($letter === 'Ë') {
            return 'Е';
        } elseif ($letter === 'Й') {
            return 'И';
        }

        return $letter;
    }

    /**
     * Normalize array of russian letters
     *
     * @param array $letters array of letters
     *
     * @return array
     */

    public function normalizedLetters(array $letters): array
    {
        return array_map(fn ($letter) => $this->normalizedLetter($letter), $letters);
    }

    /**
     * Calc user errors
     *
     * @return array
     */

    public function getErrors(): array
    {
        return array_diff($this->userGuesses, $this->normalizedLetters($this->letters));
    }

    /**
     * Count user errors
     *
     * @return int
     */

    public function errorsMade(): int
    {
        return count($this->getErrors());
    }

    /**
     * Calc remaining errors
     *
     * @return int
     */

    public function errorsAllowed(): int
    {
        return static::TOTAL_ERRORS_ALLOWED - $this->errorsMade();
    }

    /**
     * Calc remaining letters for guessing
     *
     * @return array
     */

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

    /**
     * Play the letter
     * Add to guesses array
     *
     * @param string $letter array of letters
     *
     * @return void
     */

    public function play(string $letter): void
    {
        $nomalizeLetter = $this->normalizedLetter($letter);

        if (!$this->isOver() && !in_array($nomalizeLetter, $this->userGuesses)) {
            $this->userGuesses[] = $nomalizeLetter;
        }
    }

    /**
     * Check if game is lost
     *
     * @return bool
     */

    public function isLost(): bool
    {
        return $this->errorsAllowed() === 0;
    }

    /**
     * Check if game is over
     *
     * @return bool
     */

    public function isOver(): bool
    {
        return $this->isWon() || $this->isLost();
    }

    /**
     * Check if game is win
     *
     * @return bool
     */

    public function isWon(): bool
    {
        return empty(array_diff(
            $this->normalizedLetters($this->letters),
            $this->userGuesses
        ));
    }

    /**
     * Calc guessed word
     *
     * @return string
     */

    public function getGuessedWord(): string
    {
        return implode('', $this->letters);
    }

    /**
     * Set game score to players records
     *
     * @return void
     */

    public function setScore(): void
    {
        $this->isWon() ?
            $this->gameDb->recordResult($this->playerName, true) :
            $this->gameDb->recordResult($this->playerName);
    }
}
