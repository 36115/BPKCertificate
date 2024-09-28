<?php
    // ป้องกันการเข้าแบบไม่ถูกต้อง
    if (!defined('SECURE_ACCESS')) {
        header("location: ../../");
    }

    // ดึงข้อมูลผู้ใช้ที่ส่งมา
    $id = $_SESSION["id"];
    $username = $_SESSION["username"];
    if (isset($_SESSION["realname"])) {
        $realname = preg_split('/\s+/', $_SESSION["realname"]);
    }
    $email = $_SESSION["email"];
    $role = $_SESSION["role"];
    $action = $_SESSION["action"];
    $useraction = $_SESSION["useraction"];
    
    if (isset($_SESSION["edittype"])) {
        $certCount = $_SESSION["certCount"];
        $req_id = $_SESSION["reqid"];
        $detail = $_SESSION["detail"];
    }

    echo "<script type='text/javascript'>
        $(document).ready(function(){
            $('#editUser').modal('show');
        });
    </script>";

    unset($_SESSION['id']);
    unset($_SESSION['username']);
    unset($_SESSION['realname']);
    unset($_SESSION['email']);
    unset($_SESSION['action']);
    unset($_SESSION['useraction']);

    if (isset($_SESSION["edittype"])) {
        unset($_SESSION['certCount']);
        unset($_SESSION['reqid']);
        unset($_SESSION['detail']);
    }
?>

<div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border border-2 bg-body p<?php echo $p = !isset($_SESSION["edittype"]) ? "y-5" : "-5 pb-lg-0"?> rounded-4">

            <?php if (isset($_SESSION["edittype"])) {?>

                <style>
                    fieldset {
                        display: none;
                    }

                    fieldset.show {
                        display: block;
                    }

                    .tabs {
                        cursor: pointer;
                    }

                    .tabs:hover, .tabs.active {
                        border-bottom: 3px solid var(--bs-link-color);
                        border-radius: 3px;
                    }
                </style>

                <div class="d-flex justify-content-between">
                    <h3 class="text-center fw-bolder p-2"><span class="fas fa-file-circle-plus"></span> แก้ไขข้อมูลคำขอรายการที่ <?php echo $req_id;?></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="nav justify-content-center">
                    <div class="tabs mx-4 active" id="requestInfo">
                        <h6 class="fw-bolder"><span class="fas fa-address-card"></span> ข้อมูลผู้ใช้</h6>
                    </div>
                    
                    <div class="tabs mx-4" id="editInfo">
                        <h6 class="text-muted"><span class="fas fa-user"></span> แก้ไขข้อมูล</h6>
                    </div>
                </div>
                
                <div class="modal-body overflow-y-scroll required" style="height: 520px;">

                    <fieldset class="show" id="requestInfoPage">
                        <h5 class="pt-3 pb-3 mb-6 border-bottom"><span class="fa fa-circle-info"></span> ผู้ใช้: <?php echo $username;?></h5>
                        <div class="mb-2 align-items-center rounded-3">
                            <div class="text-center text-body-emphasis"
                                style="border-top-left-radius: .5rem; border-bottom-left-radius: .5rem;">
                                <img src="https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg" class="img-fluid my-5" style="width: 80px; border-radius: 15px;" />
                                <h5>
                                    <?php echo $username;?>
                                    <?php if ($id == $user_id) {?>
                                        <span class="badge text-bg-primary user-select-none rounded-pill">คุณ</span>
                                    <?php }?>
                                </h5>
                                <p>
                                    <?php if ($role == "admin") {?>
                                        <span class="badge rounded-pill text-bg-danger user-select-none fs-6">Admin <i class="fas fa-hammer"></i></span>
                                    <?php } else if ($role == "user") {?>
                                        <span class="badge rounded-pill text-bg-success user-select-none fs-6">User <i class="fas fa-user-tie"></i></span>
                                    <?php } else {?>
                                        <span class="badge rounded-pill text-bg-secondary user-select-none fs-6"><?php echo $role;?></span>
                                    <?php }?>
                                </p>
                                <a class="btn btn-primary rounded-pill" href="user-info?id=<?php echo $id;?>"><span class="fas fa-id-card"></span> ดูข้อมูลทั้งหมด</a>
                                <a class="btn btn-danger rounded-pill ms-1 my-1" href="admin/action.php?type=none&id=<?php echo $req_id?>&from=<?php echo $_SERVER['REQUEST_URI'];?>&action=remReq" onclick="return confirm('คุณต้องการลบคำขอรายการนี้หรือไม่?')"><i class="fas fa-file-circle-xmark"></i> ลบรายการนี้</a>
                            </div>

                            <div class="card-body p-4">

                                <h6 class="dropdown-toggle user-select-none hover" href="#detail" data-bs-toggle="collapse">รายละเอียดคำขอ</h6>
                                <hr class="mt-0 mb-4">
                                <div class="collapse row pt-1" id="detail">
                                <div class="col-6 mb-3 overflow-x-scroll" style="width: 500px;">
                                        <?php if (trim($detail) === '') { ?>
                                            <p class="text-muted">ไม่ระบุเนื้อหา</p>
                                        <?php } else { ?>
                                            <p class="text-muted"><?php echo $detail;?></p>
                                        <?php }?>
                                    </div>
                                </div>

                                <h6 class="dropdown-toggle user-select-none hover" href="#info" data-bs-toggle="collapse">ข้อมูลเกี่ยวกับผู้ใช้</h6>
                                <hr class="mt-0 mb-4">
                                <div class="collapse row pt-1" id="info">
                                    <div class="col-6 mb-3">
                                        <h6>ชื่อจริง</h6>
                                        <p class="text-muted"><?php echo $realname[0]." ".$realname[1];?></p>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <h6>Email</h6>
                                        <p class="text-muted"><?php echo $email;?></p>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <h6>วันที่ลงทะเบียน</h6>
                                        <p class="text-muted"><?php echo $optDate;?> <br><b>เวลา: <?php echo $optTime?></b></p>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <h6>จำนวนเกียรติบัตรที่ขอ</h6>
                                        <?php if ($certCount != 0) {?>
                                            <p class="text-muted"><?php echo $certCount;?> ใบ</p>
                                        <?php } else {?>
                                            <p class="text-danger">ไม่พบประวัติการขอเกียรติบัตร</p>
                                        <?php }?>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </fieldset>

                    <fieldset id="editInfoPage">
                        <h5 class="pt-3 pb-3 mb-6 border-bottom"><span class="fa fa-pencil"></span> แก้ไขข้อมูล</h5>
                        <div class="mb-2 align-items-center rounded-3">
                            <form action="admin/action.php?id=<?php echo $id;?>&type=none&from=<?php echo $_SERVER['REQUEST_URI'];?>&action=<?php echo $action?>&usertype=editinfo&useraction=<?php echo $useraction?>&edittype=<?php echo $_SESSION["edittype"];?>" method="POST">
                                <?php 
                                include_once 'editUserForm.php';
                                unset($_SESSION['role']);
                                unset($_SESSION['edittype']);
                                ?>
                            </form>
                        </div>
                    </fieldset>
                </div>

                <script>
                    $(document).ready(function(){
                        $(".tabs").click(function(){
                            
                            $(".tabs").removeClass("active");
                            $(".tabs h6").removeClass("fw-bolder");    
                            $(".tabs h6").addClass("text-muted");    
                            $(this).children("h6").removeClass("text-muted");
                            $(this).children("h6").addClass("fw-bolder");
                            $(this).addClass("active");

                            current_fs = $(".active");

                            next_fs = $(this).attr('id');
                            next_fs = "#" + next_fs + "Page";

                            $("fieldset").removeClass("show");
                            $(next_fs).addClass("show");

                            current_fs.animate({}, {
                                step: function() {
                                    current_fs.css({
                                        'display': 'none',
                                        'position': 'relative'
                                    });
                                    next_fs.css({
                                        'display': 'block'
                                    });
                                }
                            });
                        });
                    });

                    var myLink = document.querySelectorAll('a[href="#"]');
                        myLink.forEach(function(link){
                            link.addEventListener('click', function(e) {
                            e.preventDefault();
                        });
                    });
                </script>
                    
            <?php } else {?>

                <div class="modal-header">
                    <h1 class="modal-title fs-5 text-body-emphasis fw-bold">แก้ไขข้อมูล</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body required">
                    <form action="admin/action.php?id=<?php echo $id;?>&type=none&from=<?php echo $_SERVER['REQUEST_URI'];?>&action=<?php echo $action?>&usertype=editinfo&useraction=<?php echo $useraction?>" method="POST">
                        <?php include_once 'editUserForm.php';?>
                    </form>
                </div>

            <?php }?>
            </div>
        </div>
    </div>
</div>