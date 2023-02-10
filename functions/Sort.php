<?php

require_once "../functions/Connect.php";

class Sort
{
    use Connect;

    function sort()
    {
        $word = $_GET['word'];
        $pdo = $this->dbConnect();
        $sql = "SELECT * FROM materials ORDER BY $word;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $records = $stmt->fetchAll();

        $stmt = null;
        $pdo = null;

        return $records;
    }
}
