<?php
require_once "../functions/Material.php";
session_start();

$select = new Material();
$index = $select->index();

$csrf = bin2hex(random_bytes(32));
$_SESSION['csrf'] = $csrf;

$token = uniqid("", true);
$_SESSION['token'] = $token;

$type = "use";


?>

<?php include "../commons/doctype.php" ?>

<body>
    <?php include "../commons/header.php" ?>

    <main>
        <div class="container use">
            <h2>物品を使う</h2>

            <!-- 取り消しボタン -->
            <?php //include "../commons/retake.php" 
            ?>

            <!-- error -->
            <?php
            if (!empty($_SESSION['err'])) { ?>
                <p class="err">
                    <?php echo $_SESSION['err'];
                    unset($_SESSION['err']); ?>
                </p>
            <?php
                unset($_SESSION['err']);
            } ?>

            <form action="../functions/Use.php" method="post">
                <input type="hidden" name="csrf" value=<?php echo $csrf ?>>
                <input type="hidden" name="token" value=<?php echo $token ?>>

                <?php foreach ($index as $record) { ?>
                    <div class="wrap">
                        <?php
                        if ($record['stock'] > 0) { ?>
                            <input type="hidden" name="id[]" value=<?php echo $record['id'] ?>>
                            <input type="hidden" name="stock[]" value=<?php echo $record['stock'] ?>>
                            <input type="hidden" name="material_name[]" value=<?php echo $record['material_name'] ?>>
                            <div class="form-group">
                                <p><?php echo $record['material_name'] ?> |</p>
                                <p>在庫：<?php echo $record['stock'] ?></p>
                                <p>持ち出し数： <input type="number" name="use[]" value="0"></p>
                            </div>

                            <small>最終持ち出し日時 :
                                <?php echo date('Y/m/d', strtotime($record['updated_at'])) ?>
                            </small>
                        <?php } ?>
                    </div>
                <?php } ?>
                <button type="submit" value="submit" class="submit-btn">USE</button>
            </form>


        </div>
    </main>
</body>

</html>