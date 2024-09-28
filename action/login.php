<?php
    // ป้องกันการเข้าแบบไม่ถูกต้อง
    if (!defined('SECURE_ACCESS')) {
        header("location: ../");
    }
    
    session_start();
    require "../config.php";

    if (isset($_POST['login'])) {
        $user = $_POST['user'];
        $password = $_POST['password'];
    }

    if (empty($user)) {
        $_SESSION['error'] = "กรุณากรอกข้อมูลให้ครบ";
        header("location: ../login");
    } else if (empty($password)) {
        $_SESSION['error'] = "กรุณาใส่รหัสผ่านของท่าน";
        header("location: ../login");
    } else {
        try {
            if (str_contains($user, '@')) {
                if (!filter_var($user, FILTER_VALIDATE_EMAIL) || preg_match('/[^\x00-\x7F]/', $email)) {
                    $_SESSION['error'] = "กรุณาใส่ Email ให้ถูกต้อง";
                    header("location: ../login");
                    exit;
                }
                $stmt = $pdo -> prepare("SELECT * FROM users WHERE email = ?");
            } else {
                $stmt = $pdo -> prepare("SELECT * FROM users WHERE username = ?");
            }

            $stmt -> execute([$user]);
            $userData = $stmt -> fetch();

            if ($userData && password_verify($password, $userData['password'])) {
                $_SESSION['user_id'] = $userData['id'];
                header("location: ../");
            } else {
                $_SESSION['error'] = "กรุณากรอก Email หรือรหัสผ่านให้ถูกต้อง";
                header("location: ../login");
            }

        } catch (PDOException $e) {
            $_SESSION['error'] = "มีบางอย่างผิดพลาดกรุณาลองใหม่อีกครั้ง";
            $_SESSION['log'] = $e;
            header("location: ../login");
        }
    }
