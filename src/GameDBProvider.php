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
        try {
            $this->db = new PDO($dsn);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
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

    /**
     * Record players name
     *
     * @param string $name players name
     *
     * @return void
     */

    public function recordName(string $name): void
    {
        $sql = "INSERT OR IGNORE 
                INTO players (name) 
                VALUES (:name)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);

        try {
            $stmt->execute();
        } catch (\PDOException $th) {
            echo $th->getMessage();
        }
    }

    /**
     * Get player results from DB
     *
     * @param string $name players name
     *
     * @return array
     */

    public function getPlayerByName(string $name): array | false
    {
        $sql = "SELECT name, 
                       games_total, 
                       games_won, 
                       games_lost,
                       effectivity
                FROM players
                WHERE name = :name";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);

        try {
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Record player result to DB
     *
     * @param string $name players name
     * @param bool $win game result (default - lost)
     *
     * @return void
     */

    public function recordResult(string $name, bool $win = false): void
    {
        $player = $this->getPlayerByName($name);
        $player['games_total'] += 1;

        $win ? $player['games_won']++ : $player['games_lost']++;

        $effectivity = round(($player['games_won'] / $player['games_total']) * 100, 2);

        $sql = "UPDATE players 
                SET games_total = :games_total, 
                    games_won = :games_won, 
                    games_lost = :games_lost,
                    effectivity = :effectivity
                WHERE name = :name";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':games_total', $player['games_total'], PDO::PARAM_STR);
        $stmt->bindValue(':games_won', $player['games_won'], PDO::PARAM_INT);
        $stmt->bindValue(':games_lost', $player['games_lost'], PDO::PARAM_INT);
        $stmt->bindValue(':effectivity', $effectivity, PDO::PARAM_STR);

        try {
            $stmt->execute();
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Get top players from DB
     *
     * @return array
     */

    public function getTopPlayers(int $num): array
    {
        $sql = "SELECT name, 
                       games_total, 
                       games_won, 
                       games_lost, 
                       effectivity 
                FROM players 
                ORDER BY effectivity DESC 
                LIMIT :num";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':num', $num, PDO::PARAM_STR);

        try {
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }
}
