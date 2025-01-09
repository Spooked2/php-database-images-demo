<?php

namespace classes;

use PDO;

class Database
{

    public function connect(): PDO
    {
        return new PDO(DSN, USERNAME, PASSWORD);
    }

    public function disconnect(): void
    {
        global $db;

        unset($db);
    }

}