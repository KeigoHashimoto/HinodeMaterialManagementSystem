<?php
require_once "../functions/Connect.php";

class Search
{
    use Connect;

    function Search()
    {
        $keyword = $_GET['keyword'];
        $userId = $_SESSION['userId'];

        $pdo = $this->dbConnect();

        /**
         * 物品名もしくは保管場所とキーワードで検索
         * userIdで絞り込み
         */
        $sql = "SELECT * FROM  materials 
        WHERE material_name LIKE :keyword 
        AND userId = :userId 
        OR place LIKE :keyword 
        AND userId = :userId ;";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':userId', $userId,  PDO::PARAM_STR);
        $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
        $stmt->execute();
        $records = $stmt->fetchAll();

        $stmt = null;
        $pdo = null;

        return $records;
    }
}
