<?php
    // ค่าสำหรับเข้าหน้าเว็บได้
    define('SECURE_ACCESS', true);

    // เริ่ม Session (ต้องมีทุกหน้า)
    session_start();
    require 'config.php';
    require_once 'action/session.php';
    
    include_once 'component/nav.php';

    require_once 'assets/ajax/index.php';

    // ดึงข้อมูลจากตาราง cert_list แต่ละ user มาแสดง
    if ($userData['role'] == "admin") {
        $userAdmin = $pdo -> prepare("SELECT * FROM users");
        $userAdmin -> execute();
        $res = $pdo -> prepare("SELECT * FROM cert_list");
    } else {
        $res = $pdo -> prepare("SELECT * FROM cert_list WHERE user_id = :id");
        $res -> bindParam(':id', $user_id, PDO::PARAM_INT);
    }
    $res -> execute();

    // ดูว่า cert_list มีข้อมูลหรือไม่ $userAdmin["user_id"]
    $count = $res -> rowCount();
    $i = 1;
    
    // แปลงเวลา
    function convertDate($def) {
        $date = date_create($def);
        $year = (int)$date->format('Y') + 543;
        $index = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
        $monthIndex = (int)$date->format('n') - 1;
        $month = $index[$monthIndex];

        // Separate date and time
        $optDate = $date->format("j {$month} {$year}");
        $optTime = $date->format("H:i:s");
        return [$optDate, $optTime];
    }
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="<?php echo $mode;?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าหลัก | <?php echo $webName;?></title>
</head>
<body>
    <section style="padding: 8em 0;">
        <div class="container">
            <div class="row justify-content-center">
            <?php include_once 'component/alert.php';?>
            <p class="text-body-emphasis user-select-none fw-bolder p-3"><a href="/" class="text-body-emphasis">หน้าหลัก</a> /</p>
                <div class="card bg-body p-7 mb-4 rounded-4">
                    <h3 class="pt-4 my-5 text-body-emphasis text-center">
                        <?php if ($userData['role'] == "admin") {?>
                            รายการขอเกียรติบัตรผู้ใช้ทั้งหมด
                            <?php } else {?>
                            ประวัติการขอเกียรติบัตร
                        <?php }?>
                    </h3>
                    <?php if ($count > 0) {?>
                        <div class="cert-list p-2">
                        <?php if ($userData['role'] == "admin") {?>
                            <form id="certForm" method="POST">
                        <?php }?>
                            <table id="certList" class="table table-bordered table-striped text-center" style="width:100%">
                                <thead class="bpk-tbheader">
                                    <?php if ($userData['role'] == "admin") {?>
                                        <div class="table-options user-select-none btn-group">
                                            <a class="hover text-body-emphasis mt-2 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                ตัวเลือก 
                                            </a>
                                            <ul class="dropdown-menu p-1 rounded-4">
                                                <div class="form-check form-switch m-1">
                                                    <input class="form-check-input hover" type="checkbox" role="switch" data-bs-toggle="collapse" style="width: 40px; height: 20px;" href="#time" id="timeSwitch" checked>
                                                    <label class="form-check-label hover p-1 mx-1 user-select-none" for="timeSwitch">แสดงเวลา</label>
                                                </div>
                                                <div class="form-check form-switch m-1">
                                                    <input class="form-check-input hover toggle-checkbox" type="checkbox" role="switch" id="select" style="width: 40px; height: 20px;" data-column="0">
                                                    <label class="form-check-label hover p-1 mx-1 user-select-none" for="select">เลือกรายการ</label>
                                                </div>
                                            </ul>
                                        </div>
                                    <?php } else {?>
                                        <div class="form-check form-switch m-1">
                                            <input class="form-check-input hover" type="checkbox" role="switch" data-bs-toggle="collapse" style="width: 40px; height: 20px;" href="#time" id="timeSwitch" checked>
                                            <label class="form-check-label hover p-1 mx-1 user-select-none" for="timeSwitch">แสดงเวลา</label>
                                        </div>
                                    <?php }?>
                                    <tr>
                                        <?php if ($userData['role'] == "admin") {?>
                                            <th class="text-center"><b>เลือก</b></th>
                                        <?php }?>
                                        <th class="text-center"><b>ลำดับ</b></th>
                                        <th class="text-center"><b>รายการ</b></th>
                                        <?php if ($userData['role'] == "admin") {?>
                                            <th class="text-center"><b>User ที่ขอ</b></th>
                                        <?php }?>
                                        <th class="text-center"><b>ปีการศึกษา</b></th>
                                        <th class="text-center"><b>ประเภท</b></th>
                                        <th class="text-center"><b>เลขเกียรติบัตร</b></th>
                                        <th class="text-center"><b>วันที่ขอ</b></th>
                                        <th class="text-center"><b>สถานะ</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($data = $res -> fetch(PDO::FETCH_ASSOC)) {
                                        
                                        $optCon = convertDate($data["cert_date"]);
                                        list($optDate, $optTime) = $optCon;

                                        // ขอ UserID ผู้ใช้
                                        $user_inp = $data["user_id"];
                                        $stmt = $pdo -> prepare("SELECT * FROM users WHERE id = ?");
                                        $stmt -> execute([$user_inp]);
                                        $userCert = $stmt -> fetch();
                                    ?>
                                        <tr>
                                            <?php if ($userData['role'] == "admin") {?>
                                                <td><input class="form-check-input rounded-3" style="width: 20px; height: 20px;" type="checkbox" name="idcheckbox[]" value="<?php echo $data["id"];?>"></td>
                                            <?php }?>
                                            <td><?php echo $i++;?></td>
                                            <td><?php echo $data["cert_name"];?> 
                                                <b>กลุ่มสาระการเรียนรู้<?php if ($data["cert_sub"] == 1) {?>ภาษาไทย
                                                    <?php } if ($data["cert_sub"] == 2) {?>คณิตศาสตร์
                                                    <?php } if ($data["cert_sub"] == 3) {?>ภาษาต่างประเทศ
                                                    <?php } if ($data["cert_sub"] == 4) {?>วิทยาศาสตร์เเละเทคโนโลยี
                                                    <?php } if ($data["cert_sub"] == 5) {?>สังคมศึกษาศาสนาเเละวัฒนธรรม
                                                    <?php } if ($data["cert_sub"] == 6) {?>สุขศึกษาเเละพลศึกษา
                                                    <?php } if ($data["cert_sub"] == 7) {?>การงานอาชีพ
                                                    <?php } if ($data["cert_sub"] == 8) {?>ศิลปะ<?php }?>
                                                </b>
                                            </td>
                                            <?php if ($userData['role'] == "admin") {?>
                                                <td>
                                                    <?php if (isset($userCert["username"])) {?>
                                                        <a href="user-info?id=<?php echo $userCert["id"];?>" class="text-body-emphasis">
                                                            <i><b><?php echo $userCert["username"];?></b></i>
                                                            <?php if ($userCert["id"] == $user_id) {?>
                                                                <span class="badge text-bg-primary user-select-none rounded-pill">คุณ</span>
                                                            <?php }?>
                                                        </a>
                                                    <?php } else { ?>
                                                        <span class="text-danger">[ผู้ใช้ที่ถูกลบ]</span>
                                                    <?php }?>
                                                </td>
                                            <?php }?>
                                            <td><?php echo $data["cert_sem"];?></td>
                                            <td><?php if ($data["cert_type"] == 1) {?>ครูและบุคลากร
                                                <?php } if ($data["cert_type"] == 2) {?>นักเรียน
                                                <?php } if ($data["cert_type"] == 3) {?>บุคคลภายนอก<?php }?></td>
                                            <td><?php echo $data["cert_sym"];?>. <?php echo $data["cert_snum"];?>
                                                <?php if ($data["cert_snum"] == $data["cert_enum"]) {?>
                                                    <?php echo "";?>
                                                <?php } else {?> - <?php echo $data["cert_enum"]; }?>
                                            </td>
                                            <td>
                                                <?php echo $optDate;?>
                                                <div class="collapse show" id="time">
                                                    <b>เวลา: <?php echo $optTime;?></b>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if ($data["cert_link"] == "") {?>
                                                    <span class="text-warning user-select-none">อยู่ระหว่างดำเนินการ</span>
                                                <?php } else {?>
                                                    <a href="<?php echo $data["cert_link"]?>" target="_blank">ดาวน์โหลด</a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                        <?php if ($userData['role'] == "admin") {?>
                            <div class="select-options d-flex justify-content-end">
                                <button type="button" class="btn btn-light mx-1 rounded-pill" style="display: none;" id="cancle" onclick="uncheckAll()"><i class="fas fa-xmark"></i> ยกเลิก</button>
                                <button type="button" class="btn btn-info mx-1 rounded-pill" style="display: none;" id="selectAll" onclick="checkAll()"><i class="fas fa-check-double"></i> เลือกทั้งหมด </button>
                                <div class="btn-group" id="certLink" style="display: none;">
                                    <button type="button" class="btn btn-primary mx-1 text-white dropdown-toggle rounded-pill" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-list-ul"></i> ตัวเลือก
                                    </button>
                                    <ul class="dropdown-menu border my-2 dropdown-menu-end rounded-4">
                                        <li><button type="button" class="dropdown-item" onclick="submitForm('addLink')" data-bs-toggle="modal" data-bs-target="#addLink"><i class="fas fa-link"></i> เพิ่มลิงก์</button></li>
                                        <li><button type="button" class="dropdown-item" onclick="submitForm('removeLink')"><i class="fas fa-link-slash"></i> ลบลิงก์</button></li>
                                        <li><button type="button" class="dropdown-item text-danger" onclick="submitForm('removeCert')"><i class="fas fa-trash-can"></i> ลบรายการ</button></li>
                                    </ul>
                                </div>
                            </div>
                        <?php include_once 'component/modal/addLink.php';?>
                                    </form>
                        <?php }?>
                    <?php } else { ?>
                        <div class="container">
                            <div class="alert alert-danger text-body-emphasis rounded-pill" role="alert">
                                <span class="fas fa-xl fa-circle-xmark"></span>
                                <b>ไม่พบรายการขอเกียรติบัตรในระบบ</b>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="row card bg-body p-5 rounded-4">
                    <h3 class="text-center text-body-emphasis py-3">แบบฟอร์มขอเกียรติบัตร</h3>

                    <form action="action/certificate" method="POST" class="required needs-validation row g-3 mt-3" novalidate>
                        <div class="col-md-6">
                            <label class="form-label text-body-emphasis">ปีการศึกษา</label>
                            <select name="certsem" class="form-select text-body-emphasis rounded-pill" required>
                                <option value="" disabled selected>- ตัวเลือก -</option>
                                <option value="2/2567">2/2567</option>
                                <option value="1/2568">1/2568</option>
                            </select>
                            <div class="invalid-feedback">กรุณาเลือกปีการศึกษา</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-body-emphasis">กลุ่มสาระการเรียนรู้</label>
                            <select name="certsub" class="form-select text-body-emphasis rounded-pill" required>
                                <option value="" disabled selected>- ตัวเลือก -</option>
                                <option value="1">ภาษาไทย</option>
                                <option value="2">คณิตศาสตร์</option>
                                <option value="3">ภาษาต่างประเทศ</option>
                                <option value="4">วิทยาศาสตร์เเละเทคโนโลยี</option>
                                <option value="5">สังคมศึกษาศาสนาเเละวัฒนธรรม</option>
                                <option value="6">สุขศึกษาเเละพลศึกษา</option>
                                <option value="7">การงานอาชีพ</option>
                                <option value="8">ศิลปะ</option>
                            </select>
                            <div class="invalid-feedback">กรุณาเลือกกลุ่มสาระ</div> 
                        </div>
                        <div class="col-12">
                            <label for="validName" oninput="checkInput()" class="form-label text-body-emphasis">ชื่อกิจกรรม</label>
                            <input type="text" id="validName" name="certname" class="form-control text-body-emphasis rounded-pill" placeholder="กรอกชื่อกิจกรรม" required>
                            <div class="invalid-feedback">กรุณากรอกชื่อกิจกรรม</div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-body-emphasis">ประเภทกิจกรรม</label>
                            <select name="certtype" class="form-select text-body-emphasis rounded-pill" required>
                                <option value="" disabled selected>- ตัวเลือก -</option>
                                <option value="1">ครูและบุคลากร</option>
                                <option value="2">นักเรียน</option>
                                <option value="3">บุคคลภายนอก</option>
                            </select>
                            <div class="invalid-feedback">กรุณาเลือกประเภท</div>
                        </div>
                        <div class="col-6">
                            <label for="validTeacher" oninput="checkInput()" class="form-label text-body-emphasis">ชื่อครู</label>
                            <input type="text" id="validTeacher" name="certtname" class="form-control text-body-emphasis rounded-pill" placeholder="กรอกชื่อครู" value="<?php echo $userData['realname'];?>" required>
                            <div class="invalid-feedback">กรุณากรอกชื่อครู</div>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-body-emphasis">จำนวนเกียรติบัตร</label>
                            <input min="1" type="number" name="certnum" class="form-control text-body-emphasis rounded-pill" placeholder="จำนวนเกียรติบัตรที่ต้องการขอ" required/>
                            <div class="invalid-feedback">กรุณากรอกจำนวนเกียรติบัตร</div>
                        </div>
                        <div class="col-12 mt-4 text-center">
                            <button name="submit" type="submit" class="btn btn-light border mt-2 rounded-pill">ยืนยันข้อมูล</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <?php include_once 'component/footer.php';?>
</body>

<script>
    let table = new DataTable('#certList', {
        // paging: true,
        // pageLength: 10,
        // lengthChange: false,
        // scrollY: '400px',
        language: {
            info: '<li class="m-3">รายการทั้งหมด <b>_MAX_</b> รายการ</li>',
            // info: '<li class="m-3">รายการทั้งหมด <b>_MAX_</b> รายการ</li> หน้า <b>_PAGE_</b> จาก <b>_PAGES_</b>',
            search: "",
            zeroRecords: '<div class="m-2">ไม่พบข้อมูล!</div>',
            infoEmpty: '<b class="text-danger">ไม่พบผลการค้นหา!</b>',
            infoFiltered: '- ผลการค้นหา <b>_TOTAL_</b> รายการ',
        },
        fixedColumns: {
            start: 1,
            end: 1
        },
        paging: false,
        scrollCollapse: true,
        scrollX: true,
        scrollY: 400,
        order: [[1]],
        columnDefs: [
            {
                orderable: false,
                target: 0,
            },
        ],
    });

    if (document.getElementById("timeSwitch")) {
        document.getElementById("timeSwitch").click();
    }

    <?php if ($userData['role'] == "admin") {?>

        document.querySelectorAll('input.toggle-checkbox').forEach((el) => {
            el.addEventListener('change', function (e) {
                let columnIdx = this.getAttribute('data-column');
                let column = table.column(columnIdx);
                
                // Toggle the visibility based on checkbox state
                column.visible(this.checked);
                toggleSelectAll();
            });

            // Set initial state
            let columnIdx = el.getAttribute('data-column');
            let column = table.column(columnIdx);
            el.checked = column.visible();
            toggleSelectAll();
        });

        if (document.querySelector('button[id="selectAll"]')) {
            toggleSelectAll();
        }

        function submitForm(action) {
            var form = document.getElementById('certForm');
            
            if (action === 'removeLink') {
                if (confirm('คุณต้องการลบลิงก์จากรายการที่เลือกหรือไม่')) {
                    form.action = 'admin/action.php?action=remLink&type=multi';
                    form.submit();
                }
            } else if (action === 'removeCert') {
                if (confirm('คุณต้องการลบจากรายการที่เลือกหรือไม่')) {
                    form.action = 'admin/action.php?action=remCert&type=multi';
                    form.submit();
                }
            } else if (action === 'addLink') {
                    form.action = 'admin/action.php?action=addLink&type=multi';
                }
        }

        // Function to toggle the visibility of the delete button
        function toggleDeleteButton() {
            var checkboxes = document.querySelectorAll('input[name="idcheckbox[]"]');
            var certLink = document.querySelector('div[id="certLink"]');
            var cancle = document.querySelector('button[id="cancle"]');
            var isAnyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

            if (isAnyChecked) {
                certLink.style.display = 'inline-block';
                cancle.style.display = 'inline-block';
            } else {
                certLink.style.display = 'none';
                cancle.style.display = 'none';
            }
        }
        
        function toggleSelectAll() {
            var selectAll = document.querySelector('button[id="selectAll"]');
            var anyChecked = Array.from(document.querySelectorAll('input.toggle-checkbox')).some(el => el.checked);

            if (anyChecked) {
                selectAll.style.display = 'inline-block';
            } else {
                selectAll.style.display = 'none';
            }
        }

        function checkAll() {
            var checkboxes = document.querySelectorAll('input[name="idcheckbox[]"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = true;
            });
            toggleDeleteButton(); // Add this line
        }

        function uncheckAll() {
            var checkboxes = document.querySelectorAll('input[name="idcheckbox[]"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = false;
            });
            toggleDeleteButton(); // Add this line
        }

        // Add event listeners to all checkboxes
        function addCheckboxListeners() {
            var checkboxes = document.querySelectorAll('input[name="idcheckbox[]"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', toggleDeleteButton);
            });
        }

        // Call this when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            addCheckboxListeners();
            if (document.getElementById("select")) {
                document.getElementById("select").click();
                toggleDeleteButton();
            }
        });

    <?php }?>

    (() => {
        'use strict';

        const forms = document.querySelectorAll('.needs-validation');

        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    const submitButton = document.getElementByName('submit');
                    submitButton.disabled = true;
                    form.submit();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();

</script>

</html>