<?php
    // เริ่ม Session (ต้องมีทุกหน้า)
    session_start();
    require "../config.php";

    // ขอ UserID ผู้ใช้
    $user_id = $_SESSION['user_id'];
    $pass = 0;

    // เตรียมข้อมูลที่ส่งมา

    if (isset($_POST["submit"])) {
        $cert_sem = $_POST['certsem'];
        $cert_sub = $_POST['certsub'];
        $cert_name = $_POST['certname'];
        $cert_type = $_POST['certtype'];
        $cert_tname = $_POST['certtname'];
        $cert_num = (int) $_POST['certnum'];
        $cert_link = "";

        try {
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
            $res = $stmt -> fetchColumn();

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
            $stmt -> bindParam(1, $user_id, PDO::PARAM_INT);
            $stmt -> bindParam(2, $cert_sem, PDO::PARAM_STR);
            $stmt -> bindParam(3, $cert_sub, PDO::PARAM_INT);
            $stmt -> bindParam(4, $cert_name, PDO::PARAM_STR);
            $stmt -> bindParam(5, $cert_type, PDO::PARAM_INT);
            $stmt -> bindParam(6, $cert_tname, PDO::PARAM_STR);
            $stmt -> bindParam(7, $cert_num, PDO::PARAM_INT);
            $stmt -> bindParam(8, $val, PDO::PARAM_INT);
            $stmt -> bindParam(9, $cert_enum, PDO::PARAM_INT);
            $stmt -> bindParam(10, $cert_sym, PDO::PARAM_STR);
            $stmt -> bindParam(11, $cert_link, PDO::PARAM_STR);
            $stmt -> execute();

            // กลับหน้าหลัก
            $_SESSION['success'] = "บันทึกข้อมูลสำเร็จ!";
            header("location: ../");

        } catch (PDOException $e) {
            $_SESSION['error'] = "มีบางอย่างผิดพลาด กรุณาลองอีกครั้ง";
            $_SESSION['log'] = $e;
            header("location: ../");
        }
    } else {
        $_SESSION['error'] = "กรุณาใส่ข้อมูลให้ครบ";
        header("location: ../");
        exit;
    }