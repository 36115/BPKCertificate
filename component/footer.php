<?php
    // ป้องกันการเข้าแบบไม่ถูกต้อง
    if (!defined('SECURE_ACCESS')) {
        header("location: ../");
    }
    
    include_once  __DIR__ . '/modal/requestUser.php';
    
    if ($userData['role']  == "admin") {
        include_once  __DIR__ . '/modal/adminMenu.php';
    }
?>

<body class="d-flex flex-column min-vh-100">
        <div class="wrapper flex-grow-1"></div>
            <footer class="text-center border px-5 py-5">
                <p class="mt-3 text-body-emphasis"><b>จัดทำโดย</b></br>
                    นายสรวิชญ์ สิทธิบวรสกุล ม.6/10 เลขที่ 11</br>
                    นายวงศ์วริศ ชัยกุลประดิษฐ์ ม.6/10 เลขที่ 13
                </p>
            </footer>
    </body>
</body>