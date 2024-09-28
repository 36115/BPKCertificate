<?php
    // ป้องกันการเข้าแบบไม่ถูกต้อง
    if (!defined('SECURE_ACCESS')) {
        header("location: ../../");
    }
    
    // ดึงข้อมูลผู้ใช้ที่ส่งมา
    $id_edit = $_GET["id"];

    require 'config.php';

    $stmt = $pdo -> prepare("SELECT * FROM users WHERE id = ?");
    $stmt -> execute([$id_edit]);
    $userInfo = $stmt -> fetch();

    // เริ่ม Session (ต้องมีทุกหน้า)
    session_start();
    
    require_once 'action/session.php';

    if ($userInfo['id']) {
        include_once 'component/nav.php';
        require_once 'assets/ajax/index.php';

        // ดึงข้อมูลจากตาราง cert_list
        $res = $pdo -> prepare("SELECT * FROM cert_list WHERE user_id = :id");
        $res -> bindParam(':id', $id_edit, PDO::PARAM_INT);
        $res -> execute();

        // รวมจำนวนการขอเกียรติบัตรทั้งหมดของผู้ใช้นั้น
        $sumAll = $pdo -> prepare("SELECT SUM(cert_req) AS certAll FROM cert_list WHERE user_id = :id");
        $sumAll -> bindParam(':id', $id_edit, PDO::PARAM_INT);
        $sumAll -> execute();
        $certCount = $sumAll -> fetch(PDO::FETCH_ASSOC);

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

            $optDate = $date->format("j {$month} {$year}");
            $optTime = $date->format("H:i:s");
            return [$optDate, $optTime];
        }

    } else {
        $_SESSION['error'] = "<b>ไม่พบผู้ใช้!</b>";
        header("location: dashboard");
    }
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="<?php echo $mode;?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แสดงข้อมูลผู้ใช้ (<?php echo $userInfo['username'];?>) | <?php echo $webName;?></title>
</head>
<?php 
    if (isset($_SESSION['id'])) {
        include_once 'component/modal/editUser.php';
    }
?>
<body>
    <section style="padding: 8em 0;">
        <div class="container">
            <div class="justify-content-center">
            <?php include_once 'component/alert.php';?>
                <div class="container card bg-body py-5 my-3 rounded-4">
                    <p class="text-body-emphasis user-select-none fw-bolder p-3"><a href="/" class="text-body-emphasis">หน้าหลัก</a> / <a href="dashboard" class="text-body-emphasis">Dashboard</a> / <a href="user-info?id=<?php echo $userInfo["id"]?>" class="text-body-emphasis fw-bold">แสดงข้อมูลผู้ใช้ (<?php echo $userInfo['username'];?>)</a> /</p>
                    <div class="row d-flex justify-content-center align-items-center h-100">
                        <div class="col">
                            <div class="card bg-body p-5 mb-3 rounded-4">
                                <div class="row g-0">
                                    <div class="col-md-4 text-center text-body-emphasis"
                                        style="border-top-left-radius: .5rem; border-bottom-left-radius: .5rem;">
                                        <img src="https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg" class="img-fluid my-5" style="width: 80px; border-radius: 15px;" />
                                        <h5>
                                            <?php echo $userInfo['username']; ?>
                                            <?php if ($userInfo["id"] == $user_id) {?>
                                                <span class="badge text-bg-primary user-select-none rounded-pill">คุณ</span>
                                            <?php }?>
                                        </h5>
                                        <p>
                                            <?php if ($userInfo["role"] == "admin") {?>
                                                <span class="badge rounded-pill text-bg-danger user-select-none fs-6">Admin <i class="fas fa-hammer"></i></span>
                                            <?php } else if ($userInfo["role"] == "user") {?>
                                                <span class="badge rounded-pill text-bg-success user-select-none fs-6">User <i class="fas fa-user-tie"></i></span>
                                            <?php } else {?>
                                                <span class="badge rounded-pill text-bg-secondary user-select-none fs-6"><?php echo $userInfo["role"];?></span>
                                            <?php }?>
                                        </p>
                                        <a href="admin/action/edit?id=<?php echo $userInfo["id"]?>&from=<?php echo $_SERVER['REQUEST_URI']?>" type="button" id="editUser" class="ms-1 my-1 text-body-emphasis"><i class="fas fa-edit"></i></a>
                                        <?php if ($userInfo["id"] != $user_id) {?>
                                            <a type="button" href="admin/action.php?action=delUser&id=<?php echo $userInfo["id"]?>" id="delUser" class="ms-1 my-1 text-danger" onclick="return confirm('คุณต้องการลบผู้ใช้หรือไม่?')"><i class="fas fa-user-slash"></i></a>
                                        <?php }?>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body p-4">
                                            <h6>ข้อมูลผู้ใช้</h6>
                                            <hr class="mt-0 mb-4">
                                            <div class="row pt-1">
                                                <div class="col-6 mb-3">
                                                    <h6>ชื่อจริง</h6>
                                                    <p class="text-muted"><?php echo $userInfo['realname']; ?></p>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <h6>Email</h6>
                                                    <p class="text-muted"><?php echo $userInfo['email']; ?></p>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <h6>วันที่ลงทะเบียน</h6>
                                                        <?php
                                                            $optCon = convertDate($userInfo["reg_date"]);
                                                            list($optDate, $optTime) = $optCon;
                                                        ?>
                                                    <p class="text-muted"><?php echo $optDate;?> <br><b>เวลา: <?php echo $optTime?></b></p>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <h6>จำนวนเกียรติบัตรที่ขอ</h6>
                                                    <?php if ($certCount['certAll'] != 0) {?>
                                                        <p class="text-muted"><?php echo $certCount['certAll'];?> ใบ</p>
                                                    <?php } else {?>
                                                        <p class="text-danger">ไม่พบประวัติการขอเกียรติบัตร</p>
                                                    <?php }?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-7 mb-4 rounded-4">
                            <h3 class="pt-4 my-5 text-body-emphasis">ประวัติการขอเกียรติบัตร</h3>
                            <?php if ($count > 0) {?>
                                <div class="cert-list p-2">
                                <form id="certForm" method="POST">
                                    <table id="certList" class="table table-bordered table-striped text-center" style="width:100%">
                                        <thead class="bpk-tbheader">
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
                                            <tr>
                                                <th class="text-center"><b>เลือก</b></th>
                                                <th class="text-center"><b>ลำดับ</b></th>
                                                <th class="text-center"><b>รายการ</b></th>
                                                <th class="text-center"><b>ปีการศึกษา</b></th>
                                                <th class="text-center"><b>ประเภท</b></th>
                                                <th class="text-center"><b>เลขเกียรติบัตร</b></th>
                                                <th class="text-center"><b>วันที่ขอ</b></th>
                                                <th class="text-center"><b>สถานะ</b></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($data = $res -> fetch(PDO::FETCH_ASSOC)) {

                                                $optCon = convertDate($data["date"]);
                                                list($optDate, $optTime) = $optCon;
                                                
                                            ?>
                                                <tr>
                                                    <td><input class="form-check-input rounded-3" style="width: 20px; height: 20px;" type="checkbox" name="idcheckbox[]" value="<?php echo $data["id"];?>"></td>
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
                                                            <b>เวลา: <?php echo $optTime?></b>
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

                                <div class="select-options d-flex justify-content-end">
                                    <button type="button" class="btn btn-light mx-1 rounded-pill" style="display: none;" id="cancle" onclick="uncheckAll()">ยกเลิก <i class="fas fa-xmark"></i></button>
                                    <button type="button" class="btn btn-info mx-1 rounded-pill" style="display: none;" id="selectAll" onclick="checkAll()">เลือกทั้งหมด <i class="fas fa-check-double"></i></button>
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
                            <?php } else { ?>
                                <div class="container">
                                    <div class="alert alert-danger text-body-emphasis rounded-pill" role="alert"><b>ไม่พบประวัติการขอเกียรติบัตร!</b></div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
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
        // responsive: {
        //     details: {
        //         display: DataTable.Responsive.display.modal({
        //             header: function (row) {
        //                 var data = row.data();
        //                 return 'ข้อมูลของ ' + data[1];
        //             }
        //         }),
        //         renderer: DataTable.Responsive.renderer.tableAll({
        //             tableClass: 'table'
        //         })
        //     }
        // },
    });

    if (document.getElementById("timeSwitch")) {
        document.getElementById("timeSwitch").click();
    }

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
    
    toggleSelectAll();

    function submitForm(action) {
        var form = document.getElementById('certForm');
        
        if (action === 'removeLink') {
            if (confirm('คุณต้องการลบลิงก์จากรายการที่เลือกหรือไม่')) {
                form.action = 'admin/action.php?action=remLink&type=multi&id=<?php echo $userInfo["id"]?>&from=<?php echo $_SERVER['REQUEST_URI']?>';
                form.submit();
            }
        } else if (action === 'removeCert') {
            if (confirm('คุณต้องการลบจากรายการที่เลือกหรือไม่')) {
                form.action = 'admin/action.php?action=remCert&type=multi&id=<?php echo $userInfo["id"]?>&from=<?php echo $_SERVER['REQUEST_URI']?>';
                form.submit();
            }
        } else if (action === 'addLink') {
                form.action = 'admin/action.php?action=addLink&type=multi&id=<?php echo $userInfo["id"]?>&from=<?php echo $_SERVER['REQUEST_URI']?>';
            }
    }

    // Function to toggle the visibility of the delete button
    function toggleDeleteButton(tableId) {
        var isCertList = tableId === 'certList';
        var checkboxes = document.querySelectorAll('input[name="idcheckbox[]"]');
        var certLink = document.querySelector('div[id="certLink"]');
        var cancel = document.getElementById('cancle');
        var isAnyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

        if (!isCertList && (!certLink || !cancel)) {
            return;
        }

        if (checkboxes.length > 0 && certLink && cancel) {
            var isAnyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

            certLink.style.display = isAnyChecked ? 'inline-block' : 'none';
            cancel.style.display = isAnyChecked ? 'inline-block' : 'none';
        }
    }
    
    function toggleSelectAll(tableId) {
        var isCertList = tableId === 'certList';
        var selectAll = document.querySelector('button[id="selectAll"]');
        var anyChecked = Array.from(document.querySelectorAll('input.toggle-checkbox')).some(el => el.checked);

        if (!isCertList && !selectAll) {
            return;
        }

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
        }

        toggleDeleteButton(); // Initial check
    });
</script>

</html>