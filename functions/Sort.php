<?php

require_once "../functions/Connect.php";

class Sort
{
    use Connect;

    function sort()
    {
        $word = $_GET['word'];
        $userId = $_SESSION['user_id'];

        $pdo = $this->dbConnect();
        $sql = "SELECT * FROM materials WHERE userId = :userId ORDER BY $word;";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
        $stmt->execute();

        $records = $stmt->fetchAll();

        $stmt = null;
        $pdo = null;

        return $records;
    }
}
