<?php
require_once "../functions/Material.php";
session_start();

/**
 * csrfトークン
 */
$csrf = bin2hex(random_bytes(32));
$_SESSION['csrf'] = $csrf;

/**
 * 二重送信禁止トークン
 */
$token = uniqid("", true);
$_SESSION['token'] = $token;

/**
 * 物品一覧の取得
 */
$select = new Material();
$index = $select->index();

?>

<?php include "../commons/doctype.php" ?>

<body>
    <?php include "../commons/header.php" ?>

    <main>
        <div class="use">

            <!-- 取り消しボタン -->
            <div class="back-btn">
                <?php
                if (isset($_SESSION['previous_id']) && isset($_SESSION['previous_stock'])) {
                    // 前回の値を変数に入れてセッションを破棄
                    $previousId = $_SESSION['previous_id'];
                    unset($_SESSION['previous_id']);
                    $previousStock = $_SESSION['previous_stock'];
                    unset($_SESSION['previous_stock']);
                ?>
                    <!-- 前回の値をPOSTで送る -->
                    <form action="../functions/Retake.php" method="POST">
                        <input type="hidden" name="previous_id" value=<?php echo $previousId; ?>>
                        <input type="hidden" name="type" value="replenish">
                        <input type="hidden" name="previous_stock" value=<?php echo $previousStock; ?>>
                        <input type="hidden" name="csrf" value=<?php echo $csrf; ?>>
                        <input type="hidden" name="token" value=<?php echo $token; ?>>
                        <p>操作を取り消しますか？<br><input type="submit" value="取り消す"></p>
                    </form>
                <?php } ?>


            </div>

            <h2>物品を補充する</h2>

            <!-- error -->
            <?php
            if (!empty($_SESSION['err'])) { ?>
                <p><?php echo $_SESSION['err'] ?></p>
            <?php
                unset($_SESSION['err']);
            } ?>


            <?php foreach ($index as $record) {
            ?>
                <form action="../functions/Replenish.php" method="post">
                    <input type="hidden" name="csrf" value=<?php echo $csrf ?>>
                    <input type="hidden" name="token" value=<?php echo $token ?>>
                    <input type="hidden" name="id" value=<?php echo $record['id'] ?>>
                    <input type="hidden" name="stock" value=<?php echo $record['stock'] ?>>
                    <div class="form-group">
                        <label for=" replenish"><?php echo $record['material_name'] ?></label>
                        <p>補充数：<input type="number" name="replenish"></p>
                        <p>在庫数：<?php echo $record['stock']; ?></p>
                    </div>
                    <input type="submit" value="submit">
                    <small>最終補充日時　<?php echo date('Y/m/d', strtotime($record['updated_at'])) ?></small>
                </form>
            <?php } ?>
        </div>
    </main>
</body>

</html>