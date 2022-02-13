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

        return ucfirst(fgets(STDIN));
    }

    public function greetings()
    {
        echo 'Всем привет!';
        echo 'Начинаем игру Виселица!';
    }

    public function getCurrentFigure()
    {
        return $this
            ->gameDb
            ->getFigure($this->game->errorsMade);
    }

    public function printOut()
    {
        echo <<<END
            Слово: {$this->getWordToShow()}
            {$this->getCurrentFigure()}
            Ошибки {$this->game->errorsMade}: {$this->getErrorsToShow()}
        END;

        if ($this->game->isWon()) {
            echo 'Поздравляем, вы выиграли!';
        } elseif ($this->game->isLost()) {
            echo "Вы проиграли, загаданное слово: " . $this->game->word;
        }
    }

    private function getWordToShow()
    {
        //     result =
        //       @game.letters_to_guess.map do |letter|
        //         if letter == nil
        //           "__"
        //         else
        //           letter
        //         end
        //       end

        //     result.join(" ")
        //   end

        $result = array_map(
            fn ($letter) => is_null($letter) ? '__' : $letter,
            $this->game->lettersToGuess
        );

        return implode('', $result);
    }

    public function getErrorsToShow()
    {
        return implode('', $this->game->errors);
    }
}