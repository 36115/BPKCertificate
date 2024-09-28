<?php
    // ป้องกันการเข้าแบบไม่ถูกต้อง
    if (!defined('SECURE_ACCESS')) {
        header("location: ../");
    }
?>

<?php if (isset($_SESSION['success'])) { ?>
    <div class="alert alert-success alert-dismissible text-body-emphasis fade show rounded-pill" role="alert">
        <span class="fas fa-xl fa-circle-check"></span>
            <?php
                echo $_SESSION['success'];
                unset($_SESSION['success']);
            ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php } ?>

<?php if (isset($_SESSION['error'])) { ?>
    <div class="alert alert-danger alert-dismissible text-body-emphasis fade show rounded-5" role="alert">
        <span class="fas fa-xl fa-circle-xmark"></span>
            <?php
                echo $_SESSION['error'];
                unset($_SESSION['error']);
            ?>
            <?php if ($userData['role'] == "admin") {?>
                <a class="text-body-emphasis text-decoration-underline hover user-select-none" data-bs-toggle="collapse" href="#info" aria-expanded="false">ข้อมูลเพิ่มเติม</a>
                <div class="collapse p-3" id="info">
                    <h5 class="pt-3 pb-3 mb-6 border-bottom"><span class="fa fa-server"></span> Server Log</h5>
                    <?php
                        echo $_SESSION['log'];
                        unset($_SESSION['log']);
                    ?>
                </div>
            <?php }?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php } ?>