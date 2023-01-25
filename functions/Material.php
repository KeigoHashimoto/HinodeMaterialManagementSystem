<?php
require_once "Connect.php";

class Material
{
    use Connect;

    public function index()
    {
        $sql = "SELECT * FROM materials;";
        $pdo = $this->dbConnect();
        $stmt = $pdo->query($sql);
        $records = $stmt->fetchAll();
        $stmt = null;
        $pdo = null;

        return $records;
    }

    public function post()
    {
        session_start();

        /**
         * csrf対策
         */
        $csrf = isset($_POST['csrf']) ? $_POST['csrf'] : '';
        $session_csrf = isset($_SESSION['csrf']) ? $_SESSION['csrf'] : '';
        unset($_SESSION['csrf']);

        if ($csrf != '' && $csrf == $session_csrf) {

            /**
             * post
             */
            if (array_key_exists('material_name', $_POST)) {
                $materialName = $_POST['material_name'];
                $place = $_POST['place'];
                $stock = $_POST['stock'];

                /**
                 * 二重送信禁止
                 */
                $token = isset($_POST['token']) ? $_POST['token'] : "";
                $session_token = isset($_SESSION['token']) ? $_SESSION['token'] : "";
                unset($_SESSION['token']);

                /**
                 * validation
                 */
                if ($token != "" && $token == $session_token) {
                    if (empty($materialName) && !empty($place) && !empty($stock)) {
                        $_SESSION['err'] = '物品名を入力してください';
                        header("Location:../views/create.php");
                        exit;
                    } elseif (!empty($materialName) && empty($place) && !empty($stock)) {
                        $_SESSION['err'] = '保管場所を入力してください';
                        header("Location:../views/create.php");
                        exit;
                    } elseif (!empty($materialName) && !empty($place) && empty($stock)) {
                        $_SESSION['err'] = '初期在庫数を入力してください';
                        header("Location:../views/create.php");
                        exit;
                    } elseif (
                        empty($materialName) && empty($place) && !empty($stock) ||
                        empty($materialName) && !empty($place) && empty($stock) ||
                        !empty($materialName) && empty($place) && empty($stock) ||
                        empty($materialName) && empty($place) && empty($stock)
                    ) {
                        $_SESSION['err'] = '入力されていない項目があります';
                        header("Location:../views/create.php");
                        exit;
                    } else {
                        $pdo = $this->dbConnect();
                        $sql = "INSERT INTO materials(material_name,place,stock) VALUE(:material_name,:place,:stock);";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':material_name', $materialName, PDO::PARAM_STR);
                        $stmt->bindParam(':place', $place, PDO::PARAM_STR);
                        $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
                        $stmt->execute();

                        $pdo = null;
                        $stmt = null;
                        header('Location:../views/index.php');
                        exit;
                    }
                } else {
                    $_SESSION['err'] = "エラーです。やり直してください";
                }
            }
        }
    }

    public function edit($id)
    {
        // idで編集したい項目を検索
        $sql = "SELECT * FROM materials WHERE id = :id;";
        $pdo = $this->dbConnect();
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        $record = $stmt->fetch();
        $pdo = null;
        $stmt = null;

        unset($_SESSION['id']);

        return $record;
    }

    public function update()
    {
        session_start();

        /**
         * csrf対策
         */
        $csrf = isset($_POST['csrf']) ? $_POST['csrf'] : "";
        $session_csrf = isset($_SESSION['csrf']) ? $_SESSION['csrf'] : "";
        unset($_SESSION['csrf']);

        if ($csrf != "" && $csrf == $session_csrf) {

            /**
             * postの確認
             */
            if (array_key_exists("material_name", $_POST)) {
                $materialName = $_POST['material_name'];
                $place = $_POST['place'];
                $stock = $_POST['stock'];
                $id = $_POST['id'];


                /**
                 * 二重痩身対策
                 */
                $token = isset($_POST['token']) ? $_POST['token'] : "";
                $session_token = isset($_SESSION['token']) ? $_SESSION['token'] : "";
                unset($_SESSION['token']);

                if ($token != "" && $token == $session_token) {

                    /**
                     * validation
                     */
                    if (empty($materialName) && !empty($place) && !empty($stock)) {
                        $_SESSION['err'] = '物品名を入力してください';
                        $_SESSION['material_name'] = $materialName;
                        $_SESSION['place'] = $place;
                        $_SESSION['stock'] = $stock;
                        $_SESSION['id'] = $id;
                        header('Location:../views/MaterialEdit.php');
                        exit;
                    } elseif (!empty($materialName) && empty($place) && !empty($stock)) {
                        $_SESSION['err'] = '保管場所を入力してください';
                        $_SESSION['material_name'] = $materialName;
                        $_SESSION['place'] = $place;
                        $_SESSION['stock'] = $stock;
                        $_SESSION['id'] = $id;
                        header('Location:../views/MaterialEdit.php');
                        exit;
                    } elseif (!empty($materialName) && !empty($place) && empty($stock)) {
                        $_SESSION['err'] = '初期在庫数を入力してください';
                        $_SESSION['material_name'] = $materialName;
                        $_SESSION['place'] = $place;
                        $_SESSION['stock'] = $stock;
                        $_SESSION['id'] = $id;
                        header('Location:../views/MaterialEdit.php');
                        exit;
                    } elseif (
                        empty($materialName) && empty($place) && !empty($stock) ||
                        empty($materialName) && !empty($place) && empty($stock) ||
                        !empty($materialName) && empty($place) && empty($stock) ||
                        empty($materialName) && empty($place) && empty($stock)
                    ) {
                        $_SESSION['material_name'] = $materialName;
                        $_SESSION['place'] = $place;
                        $_SESSION['stock'] = $stock;
                        $_SESSION['id'] = $id;
                        $_SESSION['err'] = '入力されていない項目があります';
                        header('Location:../views/MaterialEdit.php');
                        exit;
                        header('Location:../views/MaterialEdit.php');
                        exit;
                    } else {
                        $pdo = $this->dbConnect();
                        $sql = "UPDATE materials SET material_name = :material_name,place = :place , stock = :stock WHERE id = :id;";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':material_name', $materialName, PDO::PARAM_STR);
                        $stmt->bindParam(':place', $place, PDO::PARAM_STR);
                        $stmt->bindParam(':stock', $stock, PDO::PARAM_STR);
                        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
                        $stmt->execute();

                        $pdo = null;
                        $stmt = null;

                        header('Location:../views/index.php');
                        exit;
                    }
                }
            }
        }
    }

    function used()
    {
        session_start();

        /**
         * csrf対策
         */
        $csrf = isset($_POST['csrf']) ? $_POST['csrf'] : "";
        $session_csrf = isset($_SESSION['csrf']) ? $_SESSION['csrf'] : "";
        unset($_SESSION['csrf']);

        if ($csrf != "" && $csrf == $session_csrf) {
            /**
             * postがあるか
             */
            if (array_key_exists('use', $_POST)) {
                $used = $_POST['use'];
                $id = $_POST['id'];
                $stock = $_POST['stock'];

                /**
                 * 二重送信禁止
                 */
                $token = isset($_POST['token']) ? $_POST['token'] : "";
                $session_token = isset($_SESSION['token']) ? $_SESSION['token'] : "";
                unset($_SESSION['token']);


                if ($token  != "" && $token == $session_token) {
                    /**
                     * validation
                     */
                    if (!empty($used) && !empty($id)) {
                        /**
                         * 在庫が足りているかどうか
                         */
                        if ($stock - $used >= 0) {
                            $pdo = $this->dbConnect();
                            $sql = "UPDATE materials SET stock = :stock - :used WHERE id = :id;";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':used', $used, PDO::PARAM_INT);
                            $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
                            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                            $stmt->execute();
                            $stmt = null;
                            $pdo = null;

                            $_SESSION['previous_stock'] = $stock;
                            $_SESSION['previous_id'] = $id;
                        } else {
                            $_SESSION['err'] = "在庫が足りません。";
                        }
                    } else {
                        $_SESSION['err'] = "入力されていない項目があります。";
                    }
                }
            }
        }

        header('Location:../views/use.php');
    }

    function replenish()
    {
        session_start();

        /**
         * csrf対策
         */
        $csrf = isset($_POST['csrf']) ? $_POST['csrf'] : "";
        $session_csrf = isset($_SESSION['csrf']) ? $_SESSION['csrf'] : "";
        unset($_SESSION['csrf']);

        if ($csrf != "" && $csrf == $session_csrf) {
            /**
             * postがあるか
             */
            if (array_key_exists('replenish', $_POST)) {
                $replenish = $_POST['replenish'];
                $id = $_POST['id'];
                $stock = $_POST['stock'];

                /**
                 * 二重送信禁止
                 */
                $token = isset($_POST['token']) ? $_POST['token'] : "";
                $session_token = isset($_SESSION['token']) ? $_SESSION['token'] : "";
                unset($_SESSION['token']);


                if ($token  != "" && $token == $session_token) {
                    $pdo = $this->dbConnect();
                    $sql = "UPDATE materials SET stock = :stock + :replenish WHERE id = :id;";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':replenish', $replenish, PDO::PARAM_INT);
                    $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    $stmt = null;
                    $pdo = null;

                    $_SESSION['previous_stock'] = $stock;
                    $_SESSION['previous_id'] = $id;
                }
            }
        }
        header('Location:../views/replenish.php');
        exit;
    }
}