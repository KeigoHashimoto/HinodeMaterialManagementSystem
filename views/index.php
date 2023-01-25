<?php
require_once "../functions/Material.php";

$select = new Material();
$index = $select->index();
?>

<?php include "../commons/doctype.php" ?>

<body>
    <?php include "../commons/header.php" ?>

    <main>
        <h2>物品在庫一覧</h2>

        <?php
        session_start();
        if (!empty($_SESSION['err'])) { ?>
            <p><?php echo $_SESSION['err'] ?></p>
        <?php
            unset($_SESSION['err']);
        } ?>

        <table>
            <thead>
                <tr>
                    <th>物品名</th>
                    <th>保管場所</th>
                    <th>在庫数</th>
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
                        <td class="table-edit"><a href="MaterialEdit.php?id=<?php echo $record['id'] ?>">編集</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>
</body>

</html>