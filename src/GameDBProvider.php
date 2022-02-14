<?php

declare(strict_types=1);

namespace Ilyamur\HangmanGame;

use PDO;

/**
 * GameDBProvider
 *
 * Game DB Provider class, responds for DB connection
 *
 * PHP version 8.1
 */

class GameDBProvider
{
    private PDO $db;

    /**
     * Class constructor
     *
     * @param string $dsn dsn string for db connection
     *
     * @return void
     */

    public function __construct(string $dsn)
    {
        $this->db = new PDO($dsn);
    }

    /**
     * Get random word from DB
     *
     * @return string
     */

    public function getWord(): string
    {
        return $this
            ->db
            ->query('SELECT * FROM words ORDER BY RANDOM() LIMIT 1')
            ->fetchColumn(1);
    }

    /**
     * Get figure from DB
     *
     * @param int $num figure num
     *
     * @return string
     */

    public function getFigure(int $num): string
    {
        return $this
            ->db
            ->query("SELECT * FROM figures WHERE id = $num")
            ->fetchColumn(1);
    }
}
