<?php

declare(strict_types=1);

namespace Ilyamur\HangmanGame;

use Jfcherng\Utility\CliColor;

/**
 * ConsoleInterface
 *
 * Print out game information to the console
 *
 * PHP version 8.1
 */

class ConsoleInterface
{
    /**
     * Class constructor
     *
     * @param GameDBProvider $gameDb Database provider object
     * @param Game $game Game instance
     *
     * @return void
     */

    public function __construct(
        public Game $game,
        public GameDBProvider $gameDb
    ) {
    }

    /**
     * Get User input from the CLI
     * Uppercase first letter and send it to the game
     *
     * @return string
     */

    public function getInput(): string
    {
        echo 'Введите следующую букву:';
        $input = rtrim(fgets(STDIN));

        return mb_strtoupper(mb_substr($input, 0, 1));
    }

    /**
     * Print colorized greetings message to the CLI
     *
     * @return string
     */

    public function greetings(): void
    {
        echo CliColor::color("Всем привет!", 'f_light_cyan, b_yellow');
        echo PHP_EOL;
        echo CliColor::color("Начинаем игру Виселица!", 'f_light_cyan');
        echo PHP_EOL;
        echo CliColor::color("Автор: Ilya Muratov (github.com/ilyamur)", 'f_light_cyan');
        echo PHP_EOL;
    }

    /**
     * Get current figure from the DB
     *
     * @return string
     */

    public function getCurrentFigure(): string
    {
        return $this
            ->gameDb
            ->getFigure($this->game->errorsMade() + 1);
    }

    /**
     * Get current figure from the DB
     *
     * @return string
     */

    public function printOut(): void
    {
        $errors = CliColor::color("Ошибки:", 'f_red');

        echo <<<END
        
        Слово: {$this->getWordToShow()}
        
        {$this->getCurrentFigure()}
        
        $errors {$this->game->errorsMade()}: {$this->getErrorsToShow()}
        
        END;

        $this->printFinalMessage();
    }

    /**
     * Print final message
     *
     * @return void
     */

    public function printFinalMessage(): void
    {
        if ($this->game->isWon()) {
            echo CliColor::color('Поздравляем, вы выиграли!', ['f_white', 'b_green', 'b', 'blk']);
            echo PHP_EOL;
        } elseif ($this->game->isLost()) {
            echo "Вы проиграли, загаданное слово: " . $this->game->getGuessedWord() . PHP_EOL;
        }
    }

    /**
     * Prepare word to show
     *
     * @return string
     */

    public function getWordToShow(): string
    {
        // Change unguessed letters to '_'
        $result = array_map(
            fn ($letter) => is_null($letter) ? '_' : $letter,
            $this->game->lettersToGuess()
        );

        // Print result as a string
        return implode('', $result);
    }

    /**
     * Prepare wrong letters for print
     *
     * @return string
     */

    public function getErrorsToShow(): string
    {
        return implode(' ', $this->game->getErrors());
    }
}
