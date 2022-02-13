<?php

declare(strict_types=1);

namespace Ilyamur\HangmanGame;

use PDO;

class GameDBProvider
{
    public function __construct(
        private PDO $db = new PDO("sqlite:" . __DIR__ . "/../database/hangman.db")
    ) {
    }

    public function getWord(): string
    {
        return $this
            ->db
            ->query('SELECT * FROM words ORDER BY RANDOM() LIMIT 1')
            ->fetchColumn(1);
    }

    public function getFigure(int $num): string
    {
        return $this
            ->db
            ->query("SELECT * FROM figures WHERE id = $num")
            ->fetchColumn(1);
    }
}