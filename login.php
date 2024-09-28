<?php
    // ค่าสำหรับเข้าหน้าเว็บได้
    define('SECURE_ACCESS', true);
    
    // เริ่ม Session (ต้องมีทุกหน้า)
    session_start();
    require 'config.php';

    if (isset($_SESSION['user_id'])) {
        header("location: /");
    }

    error_reporting(E_ERROR | E_PARSE);
    include_once 'component/nav.php';
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="<?php echo $mode;?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ | <?php echo $webName;?></title>
</head>

<style>
    html, body {
        height: 100%;
    }

    .form-signin {
        max-width: 400px;
        padding: 1rem;
    }

    .form-signin .form-floating:focus-within {
        z-index: 2;
    }
</style>

<body>
    <section style="padding: 8em 0;">
        <div class="container py-5">
            <div class="row justify-content-center">
            <?php include_once 'component/alert.php';?>
                <div class="form-signin card bg-body rounded-4 m-auto p-5 my-5">
                    <form action="action/login" method="POST">
                        <h1 class="h3 mb-5 fw-bolder">เข้าสู่ระบบ</h1>

                        <div class="form-floating mb-5">
                            <input type="text" class="form-control my-2 rounded-4" name="user" placeholder="Enter your username or email" required>
                            <label for="floatingInput">ชื่อผู้ใช้ หรือ Email</label>
                        </div>

                        <div class="form-floating mb-5">
                            <input type="password" class="form-control my-2 rounded-4" name="password" placeholder="Password" required>
                            <label for="floatingPassword">รหัสผ่าน</label>
                        </div>
                        
                        <div class="text-center mb-5">
                            <a class="hover text-decoration-underline text-body-emphasis" data-bs-toggle="modal" data-bs-target="#requestUser">ลืมรหัสผ่าน</a>
                        </div>

                        <button class="btn btn-auto w-100 py-2 border rounded-pill" name="login" type="submit">เข้าสู่ระบบ</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <?php include_once 'component/footer.php'; include_once 'component/modal/regAdmin.php'; include_once 'component/modal/requestUser.php';?>
</body>
</html>