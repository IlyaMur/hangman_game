<?php

declare(strict_types=1);

namespace Ilyamur\HangmanGame;

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

    public function greetings()
    {
        echo "Всем привет!\n";
        echo "Начинаем игру Виселица!\n";
    }

    public function getCurrentFigure()
    {
        return $this
            ->gameDb
            ->getFigure($this->game->errorsMade() + 1);
    }

    public function printOut()
    {
        echo <<<END
        Слово: {$this->getWordToShow()}
        {$this->getCurrentFigure()}
        Ошибки {$this->game->errorsMade()}: {$this->getErrorsToShow()} 
        
        END;

        if ($this->game->isWon()) {
            echo 'Поздравляем, вы выиграли!';
        } elseif ($this->game->isLost()) {
            echo "Вы проиграли, загаданное слово: " . $this->game->word() . "\n";
        }
    }

    private function getWordToShow()
    {
        $result = array_map(
            fn ($letter) => is_null($letter) ? '_' : $letter,
            $this->game->lettersToGuess()
        );

        return implode('', $result);
    }

    public function getErrorsToShow()
    {
        return implode(' ', $this->game->getErrors());
    }
}