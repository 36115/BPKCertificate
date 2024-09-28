<?php
    // ป้องกันการเข้าแบบไม่ถูกต้อง
    if (!defined('SECURE_ACCESS')) {
        header("location: ../../");
    }

    // ดึงข้อมูลจากตาราง cert_count
    $stmt = $pdo -> prepare("SELECT * FROM cert_count");
    $stmt -> execute();
    $result = $stmt -> fetch(PDO::FETCH_ASSOC);

    // ดึงข้อมูลจากตาราง users
    $userCnt = $pdo -> prepare("SELECT * FROM users");
    $userCnt -> execute();
    $cntAll = $userCnt -> rowCount();

    // ดึงข้อมูลจากตาราง cert_request
    $userReq = $pdo -> prepare("SELECT * FROM request_users");
    $userReq -> execute();
    $reqAll = $userReq -> rowCount();
?>

<style>

    fieldset {
        display: none;
    }

    fieldset.show {
        display: block;
    }

    .tabs {
        cursor: pointer;
        border-bottom: 3px solid transparent;
        transition: .2s;
    }

    .tabs:hover, .tabs.active {
        border-bottom: 3px solid var(--bs-link-color);
        border-radius: 3px;
        transition: .2s;
    }

</style>

    <div class="modal fade" id="adminMenu" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 700px;">
            <div class="modal-content border border-2 bg-body p-4 rounded-4">
                <div class="d-flex justify-content-between">
                    <h3 class="text-center fw-bolder p-2"><span class="fas fa-wrench"></span> เมนู Admin</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="nav justify-content-center">
                    <div class="tabs active mx-3 my-2" id="certInfo">
                        <h6 class="fw-bolder"><span class="fas fa-certificate"></span> เกียรติบัตร</h6>
                    </div>
                    
                    <div class="tabs mx-3 my-2 transition ease-in-out scale-105-hover duration-200" id="userInfo">
                        <h6 class="text-muted"><span class="fas fa-user"></span> ผู้ใช้ในระบบ</h6>
                    </div>

                    <div class="tabs mx-3 my-2 transition ease-in-out scale-105-hover duration-200" id="executeCmd">
                        <h6 class="text-muted"><span class="fas fa-cog"></span> การตั้งค่า</h6>
                    </div>

                    <div class="tabs mx-3 my-2 transition ease-in-out scale-105-hover duration-200" id="manualGuide">
                        <h6 class="text-muted"><span class="fas fa-circle-question"></span> คู่มือการใช้</h6>
                    </div>

                    <div class="tabs mx-3 my-2 transition ease-in-out scale-105-hover duration-200" id="aboutUs">
                        <h6 class="text-muted"><span class="fas fa-address-card"></span> เกี่ยวกับ</h6>
                    </div>
                    
                </div>

                <div class="modal-body overflow-y-scroll" style="height: 350px;">
                    <fieldset class="show" id="certInfoPage">
                        <h5 class="pt-3 pb-3 mb-6 border-bottom"><span class="fa fa-circle-info"></span> สถิติเกียรติบัตรทั้งหมด</h5>
                        <div class="mb-2 align-items-center rounded-3">
                            <b>ทั้งหมด</b> <span class="badge rounded-pill text-bg-primary"><?php echo $result["cert_all"]; ?></span> รายการ
                        </div>
                        <ul>
                            <li class="mb-2 align-items-center rounded-3">
                                <span class="fas fa-user-tie"></span> ครูและบุคลากร <span class="badge rounded-pill text-bg-secondary"><?php echo $result["cert_t1"]; ?></span>
                            </li>
                            <li class="mb-2 align-items-center rounded-3">
                                <span class="fas fa-user-graduate"></span> นักเรียน <span class="badge rounded-pill text-bg-secondary"><?php echo $result["cert_t2"]; ?></span>
                            </li>
                            <li class="mb-2 align-items-center rounded-3">
                                <span class="fas fa-building-user"></span> บุคคลภายนอก <span class="badge rounded-pill text-bg-secondary"><?php echo $result["cert_t3"]; ?></span>
                            </li>
                        </ul>
                    </fieldset>

                    <fieldset id="userInfoPage">
                        <h5 class="pt-3 pb-3 mb-6 border-bottom"><span class="fa fa-users"></span> จำนวนผู้ใช้ในระบบ</h5>
                        <li class="mb-2 align-items-center rounded-3">
                            <b>ผู้ใช้ทั้งหมด</b> <span class="badge rounded-pill text-bg-primary"><?php echo $cntAll;?></span> คน
                        </li>
                        <h5 class="pt-3 pb-3 mb-6 border-bottom"><span class="fa fa-key"></span> จำนวนผู้ใช้ขอแก้ไขข้อมูล</h5>
                        <li class="mb-2 align-items-center rounded-3">
                            <b>จำนวน</b> <span class="badge rounded-pill text-bg-primary"><?php echo $reqAll;?></span> รายการ
                        </li>
                    </fieldset>

                    <fieldset id="executeCmdPage">
                        <h5 class="pt-3 pb-3 mb-6 border-bottom"><span class="fas fa-cog"></span> การตั้งค่า</h5>

                        <li>รูปแบบ NavBar</li>
                            <form action="admin/action.php?from=<?php echo $_SERVER['REQUEST_URI'];?>&type=settings" method="POST" class="px-3">
                                <div class="form-check py-1">
                                    <input class="form-check-input" type="radio" name="nav_mode" id="default" value="default" <?php if ($settings['navbar_mode'] == 0) echo "checked";?>>
                                    <label class="form-check-label user-select-none" for="default">
                                        ค่าเริ่มต้น
                                    </label>
                                </div>

                                <div class="form-check py-1">
                                    <input class="form-check-input" type="radio" name="nav_mode" id="offcanvas" value="offcanvas" <?php if ($settings['navbar_mode'] == 1) echo "checked";?>>
                                    <label class="form-check-label user-select-none" for="offcanvas">
                                        Offcanvas
                                    </label>
                                </div>

                                <div class="text-center m-2">
                                    <button type="submit" class="btn btn-primary rounded-pill">บันทึก</button>
                                </div>
                            </form>

                        <h5 class="pt-3 pb-3 mb-6 border-bottom"><span class="fas fa-terminal"></span> คำสั่ง <b class="text-danger">(โปรดใช้อย่างระวัง)</b></h5>
                        <form action="admin/action/execute?from=<?php echo $_SERVER['REQUEST_URI'];?>" method="POST" class="px-3">
                            <div class="mb-3">
                                <select class="form-select rounded-pill" name="executeCommand">
                                    <option value="wipe_cert_list" selected>ล้างรายการเกียรติบัตรทั้งหมด</option>
                                    <option value="wipe_cert_request">ล้างรายการขอแก้ไขข้อมูลผู้ใช้ทั้งหมด</option>
                                    <option value="fetch_cert_list">สร้างรายการขอเกียรติบัตร (สำหรับทดสอบระบบ)</option>
                                    <option value="fetch_user_list">สร้างผู้ใช้จำลอง (สำหรับทดสอบระบบ)</option>
                                </select>
                            </div>
                            <div class="text-center m-2">
                                <button type="submit" class="btn btn-danger rounded-pill" onclick="return confirm('ต้องการใช้คำสั่งหรือไม่?')">Execute</button>
                            </div>
                        </form>
                    </fieldset>

                    <fieldset id="manualGuidePage">
                        <h5 class="pt-3 pb-3 mb-6 border-bottom"><span class="fa fa-certificate"></span> เกียรติบัตร</h5>
                        <ul class="mt-3">
                            <li><a href="/manual/#addCert">เพิ่มรายการขอเกียรติบัตร</a></li>
                            <li><a href="/manual/#certUrl">เพิ่มและลบลิงก์ดาวน์โหลดเกียรติบัตรในรายการ</a></li>
                        </ul>
                        <h5 class="pt-3 pb-3 mb-6 border-bottom"><span class="fa fa-users"></span> ผู้ใช้งาน</h5>
                        <ul class="mt-3">
                            <li><a href="/manual/#userCfg">เพิ่ม ลบ และแก้ไขผู้ใช้งานในระบบ</a></li>
                            <li><a href="/manual/#passReset">รีเซตรหัสผ่านให้ผู้ใช้ที่ลืมรหัสผ่าน</a></li>
                        </ul>
                    </fieldset>

                    <fieldset id="aboutUsPage">
                        <h5 class="pt-3 pb-3 mb-6 border-bottom"><span class="fa fa-circle-question"></span> จัดทำโดย</h5>
                        <ul class="mt-3">
                            <li>นายสรวิชญ์ สิทธิบวรสกุล ม.6/10 เลขที่ 11</li>
                            <li>นายวงศ์วริศ ชัยกุลประดิษฐ์ ม.6/10 เลขที่ 13</li>
                        </ul>
                        <h5 class="pt-5 pb-3 mb-6 border-bottom"><span class="fa fa-comments"></span> ทดสอบระบบ / ให้คำแนะนำโดย</h5>
                        <ul class="mt-3">
                            <li>ครูพลากร ศิลาโคตร</li>
                            <li>ครูพิราวรรณ คำสุ</li>
                        </ul>
                    </fieldset>

                </div>

                <div class="border-bottom"></div>
                <div class="modal-footer justify-content-center">
                    <a class="btn btn-primary rounded-pill" href="dashboard"><span class="fas fa-gauge"></span> เข้าสู่หน้า Admin Dashboard</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $(".tabs").click(function(){
                
                $(".tabs").removeClass("active");
                $(".tabs").addClass("transition ease-in-out scale-105-hover duration-200");
                $(".tabs h6").removeClass("fw-bolder");
                $(".tabs h6").addClass("text-muted");
                $(this).children("h6").removeClass("text-muted");
                $(this).children("h6").addClass("fw-bolder");
                $(this).addClass("active");
                $(this).removeClass("transition ease-in-out scale-105-hover duration-200");

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