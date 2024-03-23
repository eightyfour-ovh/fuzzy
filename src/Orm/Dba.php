<?php

namespace Eightyfour\Orm;

use Eightyfour\Collections\Collection;
use Eightyfour\Exception\Exception;
use PDO;
use PDOException;
use PDOStatement;

class Dba
{
    protected PDO $pdo;
    protected PDOStatement $stmt;

    public function __construct(
        private readonly string $driver,
        private string $hostname,
        private string $username,
        private string $password,
        private array $options = [],
        private ?string $encode = null
    ) {
        try {

            $this->pdo = new PDO(
                dsn: '',
                username: '',
                password: '',
                options: $options
            );
        } catch (PDOException $e) {
            throw new Exception(message: $e->getMessage());
        }
    }

    public function getDriver(): string
    {
        return $this->driver;
    }

    public function getHostname(): string
    {
        return $this->hostname;
    }

    public function setHostname(string $hostname): Dba
    {
        $this->hostname = $hostname;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): Dba
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): Dba
    {
        $this->password = $password;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): Dba
    {
        $this->options = $options;

        return $this;
    }

    public function getEncode(): ?string
    {
        return $this->encode;
    }

    public function setEncode(?string $encode): Dba
    {
        $this->encode = $encode;

        return $this;
    }

    public function query(string $sql, array $options = [], ?array $params = []): self
    {
        $this->stmt = $this->pdo->prepare(query: $sql, options: $options);
        if (!$this->stmt) {
            throw new Exception(message: 'unable to prepare the query');
        }

        $this->stmt->execute(params: $params);

        return $this;
    }

    public function fetchAll(string $className, int $mode = PDO::FETCH_ASSOC): Collection
    {
        $results = $this->stmt->fetchAll(mode: $mode);

        // TODO: handle $className

        return new Collection(array: $results);
    }

    public function fetchOne(
        string $className,
        int $mode,
        int $cursorOrientation,
        int $cursorOffset
    ): mixed {
        $result = $this->stmt->fetch(
            mode: $mode,
            cursorOrientation: $cursorOrientation,
            cursorOffset: $cursorOffset
        );

        // TODO: handle $className

        return $result;
    }
}