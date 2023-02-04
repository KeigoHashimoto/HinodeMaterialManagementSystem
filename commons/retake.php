<!-- 取り消しボタン -->
<?php
if (isset($_SESSION['previous'])) {
    print_r($_SESSION);
    echo "hello";
?>
    <div class="back-btn">
        <!-- <form action="../functions/Retake.php" method="POST">
            <input type="hidden" name="csrf" value=<?php echo $csrf; ?>>
            <input type="hidden" name="token" value=<?php echo $token; ?>>


            
            <p>操作を取り消しますか？<br><input type="submit" value="取り消す"></p>

        </form> -->
    </div>
<?php } ?>