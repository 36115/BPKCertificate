<?php
    // ค่าสำหรับเข้าหน้าเว็บได้
    define('SECURE_ACCESS', true);
    
    // เริ่ม Session (ต้องมีทุกหน้า)
    ob_start();

    // ดึงเว็บจากโฟเดอร์ Admin 
    require_once 'admin/page/adminDashPage.php';

    // ถ้าผู้ใช้ไม่ได้เป็น Admin ให้กลับไปที่หน้า Index
    if ($userData['role']  != "admin") {
        // $_SESSION['error'] = "<b>Access Denied!</b>";
        header("location: /");
        // echo "<script>window.location.href='/';</script>";
    }