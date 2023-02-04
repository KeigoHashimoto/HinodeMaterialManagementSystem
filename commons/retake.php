<!-- 取り消しボタン -->
<?php
if (isset($_SESSION['previous'])) {
    $previous = $_SESSION['previous'];
    unset($_SESSION['previous']);
?>
    <div class="back-btn">
        <?php

        if (isset($previous)) { ?>
            <form action="../functions/Retake.php" method="POST">
                <input type="hidden" name="csrf" value=<?php echo $csrf; ?>>
                <input type="hidden" name="token" value=<?php echo $token; ?>>


                <?php foreach ($previous as $id => $stock) { ?>
                    <input type="hidden" name="previous_id[]" value=<?php echo $id; ?>>
                    <input type="hidden" name="type[]" value=<?php echo $type ?>>
                    <input type="hidden" name="previous_stock[]" value=<?php echo $stock; ?>>

                <?php } ?>

                <p>操作を取り消しますか？</p>
                <input type="submit" name="submit" value="取り消す">
                <input type="submit" value="取り消さない">

            </form>
        <?php } ?>
    </div>
<?php } ?>