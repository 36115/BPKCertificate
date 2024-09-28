<?php
    // ป้องกันการเข้าแบบไม่ถูกต้อง
    if (!defined('SECURE_ACCESS')) {
        header("location: ../");
    }

    // ถ้าผู้ใช้ไม่ได้ LogIn ให้กลับไปที่หน้า LogIn
    if (!isset($_SESSION['user_id'])) {
        header("location: ../login");
    }