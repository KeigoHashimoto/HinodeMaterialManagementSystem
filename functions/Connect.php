<?php

trait Connect
{
    public $user = "root";
    public $pass = "root";
    public $db = "mysql:dbname=MMS;host=localhost;char=utf8";

    function dbConnect()
    {
        try {
            $pdo = new PDO($this->db, $this->user, $this->pass);
            return $pdo;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
