<?php
require_once "../functions/Material.php";
require_once "../functions/Remember.php";
require_once "../commons/rememberAuth.php";

session_start();

$csrf = bin2hex(random_bytes(32));
$_SESSION['csrf'] = $csrf;

$token = uniqid("", true);
$_SESSION['token'] = $token;


//idに基づく物品を検索
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    unset($_SESSION['id']);
} else {
    echo "err";
}


$edit = new Material();
$material = $edit->edit($id);

?>

<?php include "../commons/doctype.php" ?>

<body>
    <?php include "../commons/header.php" ?>

    <di class="edit">
        <?php if (!empty($_SESSION['err'])) { ?>
            <p class="err">
                <?php echo $_SESSION['err'];
                unset($_SESSION['err']) ?>
            </p>
        <?PHP } ?>

        <main>
            <h2>物品情報の編集</h2>

            <form action="../functions/Update.php" method="post">
                <input type="hidden" name="csrf" value=<?php echo $csrf ?>>
                <input type="hidden" name="token" value=<?php echo $token ?>>
                <input type="hidden" name="id" value=<?php echo $id ?>>
                <div class="form-group">
                    <label for="material_name">物品名</label>
                    <?php if (!empty($material)) { ?>
                        <input type="text" name="material_name" value=<?php echo $material['material_name'] ?>>
                    <?php } ?>
                </div>
                <div class="form-group">
                    <label for="place">保管場所</label>
                    <?php if (!empty($material)) { ?>
                        <input type="text" name="place" value=<?php echo $material['place'] ?>>
                    <?php } ?>
                </div>
                <div class="form-group">
                    <label for="stock">在庫数</label>
                    <?php if (!empty($material)) { ?>
                        <input type="text" name="stock" value=<?php echo $material['stock'] ?>>
                    <?php } ?>
                </div>
                <input type="submit" value="submit" class="submit-btn">
            </form>
        </main>
    </di>

    <?php include "../commons/js.php" ?>

</body>

</html>