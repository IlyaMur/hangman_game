<?php

declare(strict_types=1);

namespace Ilyamur\HangmanGame;

use \Jfcherng\Utility\CliColor;

class ConsoleInterface
{
    public function __construct(
        private Game $game,
        private GameDBProvider $gameDb
    ) {
    }

    public function getInput()
    {
        echo 'Введите следующую букву:';
        $input = rtrim(fgets(STDIN));

        return mb_strtoupper(mb_substr($input, 0, 1)) . mb_substr($input, 1);
    }

    public function greetings(): void
    {
        echo CliColor::color("Всем привет!", 'f_light_cyan, b_yellow');
        echo PHP_EOL;
        echo CliColor::color("Начинаем игру Виселица!", 'f_light_cyan');
        echo PHP_EOL;
        echo CliColor::color("Автор: Ilya Muratov (github.com/ilyamur)", 'f_light_cyan');
        echo PHP_EOL;
    }

    public function getCurrentFigure(): string
    {
        return $this
            ->gameDb
            ->getFigure($this->game->errorsMade() + 1);
    }

    public function printOut(): void
    {
        $errors = CliColor::color("Ошибки:", 'f_red');

        echo <<<END
        
        Слово: {$this->getWordToShow()}
        
        {$this->getCurrentFigure()}
        
        $errors {$this->game->errorsMade()}: {$this->getErrorsToShow()}
        
        END;

        if ($this->game->isWon()) {
            echo CliColor::color('Поздравляем, вы выиграли!', ['f_white', 'b_green', 'b', 'blk']);
            echo PHP_EOL;
        } elseif ($this->game->isLost()) {
            echo "Вы проиграли, загаданное слово: " . $this->game->word() . "\n";
        }
    }

    private function getWordToShow(): string
    {
        $result = array_map(
            fn ($letter) => is_null($letter) ? '_' : $letter,
            $this->game->lettersToGuess()
        );

        return implode('', $result);
    }

    public function getErrorsToShow(): string
    {
        return implode(' ', $this->game->getErrors());
    }
}