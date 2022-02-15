<?php

declare(strict_types=1);

namespace Ilyamur\HangmanGame;

use Jfcherng\Utility\CliColor;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\StreamOutput;

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
     * Get User name from the CLI
     *
     * @return string
     */

    public function askName(): void
    {
        echo PHP_EOL;
        echo CliColor::color('Представьтесь, пожалуйста', 'f_light_cyan');
        echo CliColor::color(' (enter для пропуска).', 'f_light_cyan') . PHP_EOL;
        echo CliColor::color('Ваш ник: ', 'f_light_cyan');

        $name = rtrim(fgets(STDIN));
        echo PHP_EOL;

        if ($name === '') {
            $name = 'anonym';
        }

        $this->gameDb->recordName($name);
        $this->game->playerName = $name;
    }

    /**
     * Get User input from the CLI
     * Uppercase first letter and send it to the game
     *
     * @return string
     */

    public function getInput(): string
    {
        echo 'Введите следующую букву: ';
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
        echo CliColor::color("Добро пожаловать на игру Виселица, ", 'f_light_cyan');
        echo $this->game->playerName . PHP_EOL;
        echo CliColor::color("Автор: Ilya Muratov (github.com/ilyamur)", 'f_light_cyan') . PHP_EOL;
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

    /**
     * Print records table
     *
     * @param int $num number of records
     *
     * @return string
     */

    public function printTableOfPlayers(array $playersData): void
    {
        $output = new StreamOutput(fopen('php://stdout', 'w'));
        $table = new Table($output);

        $table
            ->setHeaders(['Имя', 'Игр всего', 'Выиграно', 'Проиграно', 'Процент побед'])
            ->setRows($playersData);
        $table->render();

        exit;
    }

    /**
     * Parse CLI args
     *
     * @param array $args CLI arguments
     *
     * @return string
     */

    public function parseArgs(array $args)
    {
        if (!isset($args[1])) {
            return;
        }

        $argsData = explode(':', $args[1]);

        if (
            $argsData[0] === 'top' &&
            filter_var($argsData[1], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 10]])
        ) {
            $playersData = $this->gameDb->getTopPlayers((int) $argsData[1]);

            $this->printTableOfPlayers($playersData);
        }

        if ($argsData[0] === 'name' && !empty($argsData[1])) {
            $playerData = $this->gameDb->getPlayerByName($argsData[1]);
            $playerData = $playerData ?: [];

            $this->printTableOfPlayers([$playerData]);
        }
    }
}
