<?php
    // ป้องกันการเข้าแบบไม่ถูกต้อง
    if (!defined('SECURE_ACCESS')) {
        header("location: ../../");
    }
    
    // เริ่ม Session (ต้องมีทุกหน้า)
    session_start();
    require "../../config.php";

    // ขอ UserID ผู้ใช้
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo -> prepare("SELECT * FROM users WHERE id = ?");
    $stmt -> execute([$user_id]);
    $userData = $stmt -> fetch();
    
    if ($userData['role']  != "admin") {
        // $_SESSION['error'] = "<b>Access Denied</b>";
        header("location: ../../");
    }

    // เช็คว่าส่งมาจาก URL ไหน

    if (str_contains($_SERVER['REQUEST_URI'], "user-info")) {
        $pattern = '/id=(\d+)/';

        if (preg_match($pattern, $_SERVER['REQUEST_URI'], $matches)) {
            $id = $matches[1];
        }

        $page = "../../user-info?id=$id";
    } else if (str_contains($_SERVER['REQUEST_URI'], "dashboard")) {
        $page = "../../dashboard";
    } else {
        $page = "../../";
    }

    $cmd = $_POST['executeCommand'];
    
    if (isset($_POST['role_user'])) {
        $role = $_POST['role_user'];
    }

    function random_char($len){
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        $ret_char = "";
        $num = strlen($chars);
            for($i = 0; $i < $len; $i++) {
                $ret_char.= $chars[rand()%$num];
                $ret_char.= ""; 
            }
            return $ret_char;
    }

    if ($cmd == "wipe_cert_list") { // Script ล้าง cert_list และ รีเซตค่า cert_count ใน Database

        // ล้างข้อมูลลงใน Table - cert_list
        $stmt = $pdo -> prepare('DELETE FROM cert_list');
        $stmt -> execute();
        $stmt = $pdo -> prepare('ALTER TABLE cert_list AUTO_INCREMENT = 1');
        $stmt -> execute();

        // บันทึกข้อมูลลงใน Table - cert_count
        $stmt = $pdo -> prepare('UPDATE cert_count SET cert_all = 0');
        $stmt -> execute();
        $stmt = $pdo -> prepare('UPDATE cert_count SET cert_t1 = 0');
        $stmt -> execute();
        $stmt = $pdo -> prepare('UPDATE cert_count SET cert_t2 = 0');
        $stmt -> execute();
        $stmt = $pdo -> prepare('UPDATE cert_count SET cert_t3 = 0');
        $stmt -> execute();
    
        // กลับหน้าหลัก
        $_SESSION['success'] = "ล้างเกียรติบัตรทั้งหมดแล้ว";
        header("location: $page");

    } else if ($cmd == "wipe_cert_request") { // Script ล้าง cert_request

        // ล้างข้อมูลลงใน Table - cert_request
        $stmt = $pdo -> prepare('DELETE FROM request_users');
        $stmt -> execute();
        $stmt = $pdo -> prepare('ALTER TABLE request_users AUTO_INCREMENT = 1');
        $stmt -> execute();

        // กลับหน้าหลัก
        $_SESSION['success'] = "ล้างรายการขอแก้ไขข้อมูลผู้ใช้ทั้งหมดแล้ว";
        header("location: $page");

    } else if ($cmd == "fetch_cert_list") { // Script สร้างข้อมูล cert_list ใน Database

        for ($i = 1; $i <= rand(1,300); $i++) {
            $pass = 0;

            // เตรียมข้อมูลที่ส่งมา
            $rand_num = rand(1,2);
            $rand_num == 1 ? $cert_sem = "2/2567" : $cert_sem = "1/2568";
            $cert_sub = rand(1,8);
            $cert_name = random_char(20);
            $cert_type = rand(1,3);
            $cert_tname = $userData['realname'];
            $cert_num = rand(1,100);
            $rand_num == 1 ? $cert_link = "https://www.bpk.ac.th/bpknews" : $cert_link = "";

            // เช็คครูและบุคลากร = 1, นักเรียน = 2, บุคคลภายนอก = 3
            if ($cert_type == 1) {
                $cert_sym = "บ";
                $cdb = "cert_t1";
            } else if ($cert_type == 2) {
                $cert_sym = "น";
                $cdb = "cert_t2";
            } else {
                $cert_sym = "อ";
                $cdb = "cert_t3";
            }

            // ดึงข้อมูลจาก Table cert_count Column - cert_t1, cert_t2 หรือ cert_t3
            $stmt = $pdo -> prepare('SELECT '.$cdb.' FROM cert_count');
            $stmt -> execute();
            $res = $stmt->fetchColumn();

            // เช็คถ้าผู้ใช้ขอเกียรติบัตร 1 ใบ ถ้าขอมากกว่า 2 ให้ทำเหมือนเดิม
            if ($res == 0 && $cert_num == 1) { //กรณี cert_t1, cert_t2 หรือ cert_t3 = 0 และผู้ใช้ส่งค่า = 1
                $cert_enum = $cert_num;
                $val = 1;
                $pass = 1;
            } elseif ($cert_num == 1) { //กรณีผู้ใช้ส่งค่า = 1
                $cert_enum = $res + $cert_num;
                $val = $res + $cert_num;
            } else { //กรณีผู้ใช้ส่งค่ามากกว่า 1
                $cert_enum = $cert_num + $res;
                $val = $res + 1;
            }
                // บันทึกข้อมูลลงใน Table cert_count Column - cert_t1, cert_t2 หรือ cert_t3
                $ctt = "UPDATE cert_count SET ".$cdb."= ".$cdb." + ".(int)$cert_num." ";
                $stmt = $pdo -> prepare($ctt);
                $stmt -> execute();

                // บันทึกข้อมูลลงใน Table cert_count Column - cert_all (ค่ารวมทั้งหมด)
                $upt = "UPDATE cert_count SET cert_all = cert_all + ".(int)$cert_num." ";
                $stmt = $pdo -> prepare($upt);
                $stmt -> execute();

                // บันทึกข้อมูลลงใน Table - cert_list (ของ User นั้นๆ)
                $stmt = $pdo -> prepare('INSERT INTO cert_list(user_id, cert_sem, cert_sub, cert_name, cert_type, cert_tname, cert_req, cert_snum, cert_enum, cert_sym, cert_link) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
                $stmt -> bindParam(1, $user_id);
                $stmt -> bindParam(2, $cert_sem);
                $stmt -> bindParam(3, $cert_sub);
                $stmt -> bindParam(4, $cert_name);
                $stmt -> bindParam(5, $cert_type);
                $stmt -> bindParam(6, $cert_tname);
                $stmt -> bindParam(7, $cert_num);
                $stmt -> bindParam(8, $val);
                $stmt -> bindParam(9, $cert_enum);
                $stmt -> bindParam(10, $cert_sym);
                $stmt -> bindParam(11, $cert_link);
                $stmt -> execute();
        }

        // กลับหน้าหลัก
        $_SESSION['success'] = "สร้างรายการขอเกียรติบัตรทั้งหมด $i รายการ";
        header("location: $page");

    } else if ($cmd == "fetch_user_list") { // Script สร้างข้อมูล cert_list ใน Database

        for ($i = 1; $i <= rand(1,300); $i++) {
            $pass = 0;

            // เตรียมข้อมูลที่ส่งมา
            $username = random_char(5);
            $realname = random_char(10)." ".random_char(10);
            $email = strtolower(str_repeat(random_char(1), 2).'@'.str_repeat(random_char(1), 3).'.'.random_char(2));
            $password = random_char(12);
            $rand_num = rand(1,2);
            $rand_num == 1 ? $role = "user" : $role = "admin";
            $hashPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo -> prepare("INSERT INTO users(username, realname, role, email, password) VALUES(?, ?, ?, ?, ?)");
            $stmt -> execute([$username, $realname, $role, $email, $hashPassword]);
        }

        // กลับหน้าหลัก
        $_SESSION['success'] = "สร้างผู้ใช้ทั้งหมด $i ผู้ใช้";
        header("location: $page");

    } else if ($cmd == "role_user") {

        // บันทึกข้อมูลลงใน Table - users
        $stmt = $pdo -> prepare('UPDATE users SET role = "user" WHERE id = '.$user_id.'');
        $stmt -> execute();

        // กลับหน้าหลัก
        $_SESSION['success'] = "Run <b>role_user</b> Success!";
        header("location: $page");

    } else if ($cmd == "role_admin") {

        // บันทึกข้อมูลลงใน Table - users
        $stmt = $pdo -> prepare('UPDATE users SET role = "admin" WHERE id = '.$user_id.'');
        $stmt -> execute();

        // กลับหน้าหลัก
        $_SESSION['success'] = "Run <b>role_admin</b> Success!";
        header("location: $page");

    
    } else {
        $_SESSION['error'] = "กรุณาลองใหม่อีกครั้ง";
        header("location: $page");
    }