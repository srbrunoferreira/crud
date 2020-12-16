<?php

require_once('../config.php');

class DBconnection
{
    public $connection;

    public function connect()
    {
        try {
            $connection = new PDO('mysql:host=localhost;dbname=crud', 'root', '');
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection = $connection;

            return true;
        } catch (\PDOException $exception) {
            return false;
        }
    }
}
