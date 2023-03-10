<?php
require_once "Connect.php";

/**
 * 物品管理
 */

class Material
{
    use Connect;

    /**
     * 物品の一覧を取得
     */
    public function index()
    {
        $sql = "SELECT * FROM materials WHERE userId = :userId ORDER BY stock;";
        $pdo = $this->dbConnect();
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userId', $_SESSION['user_id']);
        $stmt->execute();
        $records = $stmt->fetchAll();
        $stmt = null;
        $pdo = null;

        return $records;
    }

    /**
     * 物品データの登録
     */
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
                $userId = $_POST['userId'];

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
                    if (mb_strlen($materialName) > 50) {
                        $_SESSION['err'] = '50文字以内で入力してください';
                        header('Location:../views/create.php');
                        exit;
                    }
                    if (mb_strlen($place) > 50) {
                        $_SESSION['err'] = '50文字以内で入力してください';
                        header('Location:../views/create.php');
                        exit;
                    }

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
                        $sql = "INSERT INTO materials(userId,material_name,place,need,stock) VALUE(:userId,:material_name,:place,:need,:stock);";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);
                        $stmt->bindParam(':material_name', $materialName, PDO::PARAM_STR);
                        $stmt->bindParam(':place', $place, PDO::PARAM_STR);
                        $stmt->bindParam(':need', $stock, PDO::PARAM_STR);
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

    /**
     * 物品データの編集
     * 編集したいデータを取得
     */
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

    /**
     * 物品データのアップデート
     */
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

    /**
     * 物品を使用する
     */
    function used()
    {
        session_start();

        $previous = array();

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
                /**
                 * 二重送信禁止
                 */
                $token = isset($_POST['token']) ? $_POST['token'] : "";
                $session_token = isset($_SESSION['token']) ? $_SESSION['token'] : "";
                unset($_SESSION['token']);

                /**
                 * 二重送信対策ようトークンの判定
                 */
                if ($token  != "" && $token == $session_token) {

                    //postで送られてきたidとuseを取得して配列を作る
                    for ($i = 0; $i < count($_POST['id']); $i++) {

                        $key = isset($_POST['id'][$i]) ? $_POST['id'][$i] : "";
                        $value = isset($_POST['use'][$i]) ? $_POST['use'][$i] : "";

                        $data[] = [$key => $value];


                        if ($value == "") {
                            $_SESSION['err'] = "使用数が入力されていない項目があります。";
                        } elseif (is_int($value)) {
                            $_SESSION['err'] = "数値で入力してください。";
                        } elseif ($value < 0) {
                            $_SESSION['err'] = "0以下の数値は入力できません";
                        } else {
                            //作った配列をもとにさらに物品ごとのidとusedを絞り込む
                            //idをもとにstockを取得
                            foreach ($data[$i] as $id => $used) {


                                //stockをidから検索
                                $pdo = $this->dbConnect();
                                $sql = "SELECT stock FROM materials WHERE id = :id;";
                                $stmt = $pdo->prepare($sql);
                                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                                $stmt->execute();
                                //stock 取得
                                $stock = $stmt->fetch();
                                $stmt = null;

                                //取消用のセッション
                                $previous += [$id => $stock['stock']];


                                //在庫の確認
                                if ($stock['stock'] - $used >= 0) {

                                    //取得した情報でstockをアップデート
                                    $sql = "UPDATE materials SET stock = :stock - :used WHERE id = :id;";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->bindParam(':used', $used, PDO::PARAM_INT);
                                    $stmt->bindParam(':stock', $stock['stock'], PDO::PARAM_INT);
                                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                                    $stmt->execute();
                                    $stmt = null;
                                    $pdo = null;

                                    $_SESSION['previous'] = $previous;
                                } else {
                                    $_SESSION['err'] =  "の在庫が足りません。";
                                }
                            }
                        }
                    }
                }
            }
        }

        header('Location:../views/use.php');
    }

    /**
     * 物品を補充する
     */
    function replenish()
    {
        session_start();

        $previous = array();

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
                /**
                 * 二重送信禁止
                 */
                $token = isset($_POST['token']) ? $_POST['token'] : "";
                $session_token = isset($_SESSION['token']) ? $_SESSION['token'] : "";
                unset($_SESSION['token']);

                if ($token  != "" && $token == $session_token) {
                    //POSTを繰り返し分で取り出して配列でdata[]に格納する。
                    for ($i = 0; $i < count($_POST['id']); $i++) {
                        $key = isset($_POST['id'][$i]) ? $_POST['id'][$i] : "";
                        $value = isset($_POST['replenish'][$i]) ? $_POST['replenish'][$i] : "";

                        $data = [$key => $value];


                        if ($value == "") {
                            $_SESSION['err'] = "入力されていない項目があります。";
                        } elseif ($value < 0) {
                            $_SESSION['err'] = "0以下の数値は入力できません。";
                        } else {
                            foreach ($data as $id => $replenish) {
                                $pdo = $this->dbConnect();

                                //stock取得
                                $sql = "SELECT stock FROM materials WHERE id = :id;";
                                $stmt = $pdo->prepare($sql);
                                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                                $stmt->execute();
                                $stock =  $stmt->fetch();
                                $stmt = null;

                                $sql = "UPDATE materials SET stock = :stock + :replenish WHERE id = :id;";
                                $stmt = $pdo->prepare($sql);
                                $stmt->bindParam(':replenish', $replenish, PDO::PARAM_INT);
                                $stmt->bindParam(':stock', $stock['stock'], PDO::PARAM_INT);
                                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                                $stmt->execute();
                                $stmt = null;
                                $pdo = null;


                                $previous += [$id => $stock['stock']];

                                $_SESSION['previous'] = $previous;
                            }
                        }
                    }
                }
            }
        }
        header('Location:../views/replenish.php');
        exit;
    }

    function destroy()
    {
        $id = $_GET['delete_id'];

        $pdo = $this->dbConnect();
        $sql = "DELETE FROM materials WHERE id = :id;";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();

        $stmt = null;
        $pso = null;
    }
}
