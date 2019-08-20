<?php
require('../../Middleware/checkAdmin.php');
use Database\Database;
require('../../Database/database.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Đổi mật khẩu</title>

    <link rel="stylesheet" href="../../Public/CSS/style.css">
</head>
<body>
    <div class="flex-justify-center">
        <div class="width-50">
            <?php
                require_once('../Layout/menu.php');
            ?>
            <br>
            <h1 class="text-center">Đổi mật khẩu</h1>
            <br>
            <div class="flex-justify-center">
                <div class="update-form-container">
                    <form action="../../index.php?controller=Controller&function=doChangePassword" method="POST">
                        <lable>Mật khẩu hiện tại</lable>
                        <input type="password"  name="current_password" maxlength="50" placeholder="Nhập mật khẩu hiện tại" required>
                        <lable>Mật khẩu mới</lable>
                        <input type="password" name="new_password" maxlength="50" placeholder="Nhập mật khẩu mới" required>
                        <lable>Nhập lại mật khẩu</lable>
                        <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu" required>
                        <input type="submit" name="submit" value="Lưu">
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>