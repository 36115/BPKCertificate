<?php
    // เช็คว่าส่งมาจาก URL ไหน
    if (isset($_GET["from"])) {
        if (str_contains($_SERVER['REQUEST_URI'], "user-info")) {
            if (isset($_GET["id"])) {
                $id = $_GET["id"];
            }
            $page = "../user-info?id=$id";
        } else if (str_contains($_SERVER['REQUEST_URI'], "dashboard")) {
            $page = "../dashboard";
        } else if (str_contains($_SERVER['REQUEST_URI'], "login")) {
            $page = "../login";
        } else {
            $page = "../";
        }
    } else {
        // ป้องกันการเข้าแบบไม่ถูกต้อง
        header("location: /");
        exit;
    }

    session_start();
    require '../config.php';

    if (isset($_POST['sendRequest'])) {
        $username = $_POST['username'];
        $realname = $_POST['fname']." ".$_POST['lname'];
        $email = strtolower($_POST['email']);
        $detail = $_POST['detail'];
    }

    if (empty($username)) {
        $_SESSION['error'] = "กรุณาใส่ชื่อผู้ใช้";
        header("location: $page");
    } else if (empty($realname)) {
        $_SESSION['error'] = "กรุณาใส่ชื่อจริง";
        header("location: $page");
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL) || preg_match('/[^\x00-\x7F]/', $email)) {
        $_SESSION['error'] = "กรุณาใส่ Email ให้ถูกต้อง";
        header("location: $page");
    } else {

        $checkUser = $pdo -> prepare("SELECT * FROM users WHERE username = ('$username') OR realname = ('$realname') OR email = ('$email')");
        $checkUser -> execute();
        $userInfo = $checkUser -> fetch();

        if (strtolower($_POST['username']) == strtolower($userInfo["username"])) {
            $username = $userInfo["username"];
        }

        if ($userInfo === false) {
            $_SESSION['error'] = "ไม่พบผู้ใช้นี้ในระบบ กรุณาลองใหม่อีกครั้ง";
            header("location: $page");
            exit;
        } else {
            try {
                $stmt = $pdo -> prepare("INSERT INTO request_users(username, realname, email, detail) VALUES(?, ?, ?, ?)");
                $stmt -> execute([$username, $realname, $email, $detail]);
    
                $_SESSION['success'] = "ส่งคำขอรีเซตรหัสผ่านแล้ว เราจะติดต่อคุณผ่านทาง Email ที่ให้มา";
                header("location: $page");
    
            } catch (PDOException $e) {
                $_SESSION['error'] = "มีบางอย่างผิดพลาดกรุณาลองใหม่อีกครั้ง";
                $_SESSION['log'] = $e;
                header("location: $page");
            }
        }
    }

