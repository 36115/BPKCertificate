<?php
    // ค่าสำหรับเข้าหน้าเว็บได้
    define('SECURE_ACCESS', true);

    // เริ่ม Session (ต้องมีทุกหน้า)
    session_start();
    require_once '../../action/session.php';

    if (isset($_GET["reqid"])) {
        $req_id = $_GET["reqid"];
        $_SESSION["reqid"] = $req_id;
    }

    // เช็คว่าส่งมาจาก URL ไหน
    if (isset($_GET["id"])) {
        $id = $_GET["id"];
    }

    if (isset($_GET["from"])) {
        if (str_contains($_SERVER['REQUEST_URI'], "user-info")) {
            $page = "../../user-info?id=$id";
        } else if (str_contains($_SERVER['REQUEST_URI'], "userRequest")) {
            $page = "../../dashboard";
        }
    } else {
        $page = "../../dashboard";
    }

    require_once '../page/userEditModal.php';