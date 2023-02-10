<?php
require_once "../functions/Material.php";
require_once "../functions/Sort.php";
session_start();

if (isset($_GET['word'])) {
    $select = new Sort();
    $index = $select->sort();
} else {
    $select = new Material();
    $index = $select->index();
}

?>

<?php include "../commons/doctype.php" ?>

<body>
    <?php include "../commons/header.php" ?>

    <main>
        <?php
        if (array_key_exists('userId', $_SESSION)) { ?>
            <h2>物品在庫一覧</h2>


            <?php
            if (!empty($_SESSION['err'])) { ?>
                <p class="err"><?php echo $_SESSION['err'] ?></p>
            <?php
                unset($_SESSION['err']);
            } ?>

            <table>
                <thead>
                    <tr>
                        <th>
                            <form action="" method="get">
                                <input type="hidden" name="word" value="material_name">
                                <input type="submit" value="物品名" class="sort">
                            </form>
                        </th>
                        <th>
                            <form action="" method="get">
                                <input type="hidden" name="word" value="place">
                                <input type="submit" value="保管場所" class="sort">
                            </form>
                        </th>
                        <th>
                            <form action="" method="get">
                                <input type="hidden" name="word" value="stock">
                                <input type="submit" value="在庫" class="sort">
                            </form>
                        </th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($index as $record) { ?>
                        <tr>
                            <td class="table-name"><?php echo $record['material_name'] ?></td>
                            <td class="table-place"><?php echo $record['place'] ?></td>
                            <?php if ($record['stock'] == 0) { ?>
                                <td class="table-stock-danger"><?php echo $record['stock'] ?></td>
                            <?php } else { ?>
                                <td class="table-stock"><?php echo $record['stock'] ?></td>
                            <?php } ?>
                            <td class="table-edit"><a href="MaterialEdit.php?id=<?php echo $record['id'] ?>"><i class="fas fa-pen"></i></a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else {
            header('Location:./login.php');
        } ?>

    </main>

    <?php include "../commons/js.php" ?>


</body>

</html>