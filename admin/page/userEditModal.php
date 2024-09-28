<?php
    // ป้องกันการเข้าแบบไม่ถูกต้อง
    if (!defined('SECURE_ACCESS')) {
        header("location: ../../");
    }

    require '../../config.php';

    if (isset($_GET["req"])) {
        $req = $_GET["req"];
        $_SESSION["edittype"] = "request";
        $col = "username";
        $val = $req;

        // ดึงข้อมูลจากตาราง request_users ของผู้ใช้นั้น
        $stmt = $pdo -> prepare("SELECT * FROM request_users WHERE id = ('$req_id')");
        $stmt -> execute();
        $userReq = $stmt -> fetch();

        $_SESSION["detail"] = nl2br(htmlspecialchars($userReq['detail']));

        // แปลงเวลา
        function convertDate($def) {
            $date = date_create($def);
            $year = (int)$date->format('Y') + 543;
            $index = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
            $monthIndex = (int)$date->format('n') - 1;
            $month = $index[$monthIndex];

            $optDate = $date->format("j {$month} {$year}");
            $optTime = $date->format("H:i:s");
            return [$optDate, $optTime];
        }
        $optCon = convertDate($userInfo["reg_date"]);
        list($optDate, $optTime) = $optCon;

        $_SESSION["optDate"] = $optDate;
        $_SESSION["optTime"] = $optTime;

        // รวมจำนวนการขอเกียรติบัตรทั้งหมดของผู้ใช้นั้น
        $sumAll = $pdo -> prepare("SELECT SUM(cert_req) AS certAll FROM cert_list WHERE user_id = :id");
        $sumAll -> bindParam(':id', $id, PDO::PARAM_INT);
        $sumAll -> execute();
        $certCount = $sumAll -> fetch(PDO::FETCH_ASSOC);

        $_SESSION["certCount"] = $certCount['certAll'];
    } else {
        $col = "id";
        $val = $id;
    }

    $stmt = $pdo -> prepare("SELECT * FROM users WHERE ($col) = ('$val')");
    $stmt -> execute();
    $userInfo = $stmt -> fetch();

    if (isset($userInfo['id'])) {

        $_SESSION["action"] = "cfgUser";
        $_SESSION["useraction"] = "editinfo";
        
        $_SESSION["id"] = $userInfo['id'];
        $_SESSION["username"] = $userInfo['username'];
        $_SESSION["realname"] = $userInfo['realname'];
        $_SESSION["email"] = $userInfo['email'];
        $_SESSION["role"] = $userInfo['role'];

        header("location: $page");
    } else {
        $_SESSION['error'] = "<b>ไม่พบผู้ใช้!</b>";
        header("location: $page");
    }