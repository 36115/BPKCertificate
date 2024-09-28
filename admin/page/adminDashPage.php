<?php
    // ป้องกันการเข้าแบบไม่ถูกต้อง
    if (!defined('SECURE_ACCESS')) {
        header("location: /");
    }

    // เริ่ม Session (ต้องมีทุกหน้า)
    session_start();
    require 'config.php';
    require_once 'action/session.php';

    include_once 'component/nav.php';

    // ถ้าผู้ใช้ไม่ได้เป็น Admin ให้กลับไปที่หน้า Index
    if ($userData['role']  != "admin") {
        // $_SESSION['error'] = "<b>Access Denied!</b>";
        echo "<script>window.location.href='/';</script>";
    }

    require_once 'assets/ajax/index.php';
    
    // ดึงข้อมูลจากตาราง users มาแสดง
    $res = $pdo -> prepare("SELECT * FROM users");
    $res -> execute();

    // ดูว่ามีข้อมูลใน users หรือไม่
    $count = $res -> rowCount();
    $i = 1;

    // ดึงข้อมูลจากตาราง request_users มาแสดง
    $req = $pdo -> prepare("SELECT * FROM request_users");
    $req -> execute();

    // ดูว่ามีข้อมูลใน request_users หรือไม่
    $countReq = $req -> rowCount();
    $j = 1;

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
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="<?php echo $mode;?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | <?php echo $webName;?></title>
</head>
<body>
    <section style="padding: 8em 0;">
        <div class="container">
            <div class="row justify-content-center">
            <?php include_once 'component/alert.php';?>
                <p class="text-body-emphasis user-select-none fw-bolder p-3"><a href="/" class="text-body-emphasis">หน้าหลัก</a> / <a href="dashboard" class="text-body-emphasis">Dashboard</a> /</p>

                <h1 class="text-center text-body-emphasis fw-bolder">Dashboard</h1>
                <p class="text-center text-body-emphasis py-3 fw-light">สวัสดี, <?php echo $userData['username']; ?>. ยินดีต้อนรับสู่ Admin Dashboard!</p>

                <div class="card bg-body p-7 rounded-4">
                    <h1 class="h3 text-body-emphasis">ผู้ใช้ทั้งหมด</h1>
                    <?php if ($count > 0) {?>
                        <div class="user-table p-2">
                        <form action="admin/action.php?type=multi&from=<?php echo $_SERVER['REQUEST_URI'];?>" method="POST" id="userForm">
                            <table id="userList" class="table display table-bordered table-striped text-center" style="width:100%">
                                <div class="btn-group add-user d-flex justify-content-end">
                                    <button type="button" class="btn btn-primary text-white mb-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#addUser"><i class="fas fa-user-plus"></i> เพื่มผู้ใช้</button>
                                </div>
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
                                        <th class="text-center"><b>Username</b></th>
                                        <th class="text-center"><b>ชื่อจริง</b></th>
                                        <th class="text-center"><b>Rank</b></th>
                                        <th class="text-center"><b>Email</b></th>
                                        <th class="text-center"><b>วันที่ลงทะเบียน</b></th>
                                        <th class="text-center"><b>วันที่แก้ไขข้อมูล</b></th>
                                        <th class="text-center"><b>คำสั่ง</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($data = $res -> fetch(PDO::FETCH_ASSOC)) {

                                        $optCon = convertDate($data["reg_date"]);
                                        list($optDate, $optTime) = $optCon;

                                        $optCon = convertDate($data["edited_date"]);
                                        list($editedDate, $editedTime) = $optCon;

                                    ?>
                                        <tr>
                                            <?php if ($data["id"] != $user_id) {?>
                                                <td><input class="form-check-input rounded-3" style="width: 20px; height: 20px;" type="checkbox" name="idcheckbox[]" value="<?php echo $data["id"];?>"></td>
                                            <?php } else {?>
                                                <td><input class="form-check-input rounded-3" style="width: 20px; height: 20px;" type="checkbox" disabled></td>
                                            <?php }?>
                                            <td><?php echo $i++;?></td>
                                            <td>
                                                <?php echo $data["username"];?>

                                                <?php if ($data["id"] == $user_id) {?>
                                                    <span class="badge text-bg-primary user-select-none rounded-pill">คุณ</span>
                                                <?php }?>
                                                
                                            </td>
                                            <td><?php echo $data["realname"];?></td>
                                            <td>
                                                <?php if ($data["role"] == "admin") {?>
                                                    <span class="badge rounded-pill text-bg-danger user-select-none fs-6">Admin <i class="fas fa-hammer"></i></span>
                                                <?php } else if ($data["role"] == "user") {?>
                                                    <span class="badge rounded-pill text-bg-success user-select-none fs-6">User <i class="fas fa-user-tie"></i></span>
                                                <?php } else {?>
                                                    <span class="badge rounded-pill text-bg-secondary user-select-none fs-6"><?php echo $data["role"];?></span>
                                                <?php }?>
                                            </td>
                                            <td><?php echo $data["email"];?></td>
                                            <td>
                                            <?php echo $optDate;?>
                                                <div class="collapse show" id="time">
                                                    <b>เวลา: <?php echo $optTime?></b>
                                                </div>
                                            </td>
                                            <td>
                                            <?php echo $editedDate;?>
                                                <div class="collapse show" id="time">
                                                    <b>เวลา: <?php echo $editedTime?></b>
                                                </div>
                                            </td>
                                            <td>
                                                <a type="button" href="user-info?id=<?php echo $data["id"]?>" class="btn btn-outline-secondary ms-1 my-1 text-body-emphasis rounded-pill"><i class="fas fa-circle-info"></i></a>
                                                <a type="button" href="admin/action/edit?id=<?php echo $data["id"]?>" class="btn btn-outline-info ms-1 my-1 text-body-emphasis rounded-pill"><i class="fas fa-edit"></i></a>
                                                <?php if ($data["id"] != $user_id) {?>
                                                    <a type="button" href="admin/action.php?type=none&from=<?php echo $_SERVER['REQUEST_URI'];?>&action=delUser&id=<?php echo $data["id"]?>" class="btn btn-outline-danger ms-1 my-1 text-body-emphasis rounded-pill" onclick="return confirm('คุณต้องการลบผู้ใช้หรือไม่?')"><i class="fas fa-user-slash"></i></a>
                                                <?php }?>
                                            </td>
                                        </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>

                        <div class="select-options d-flex justify-content-end">
                            <button type="button" class="btn btn-light mx-1 rounded-pill" style="display: none;" id="cancle" onclick="uncheckAll('userList')">ยกเลิก <i class="fas fa-xmark"></i></button>
                            <button type="button" class="btn btn-info mx-1 rounded-pill" style="display: none;" id="selectAll" onclick="checkAll('userList')">เลือกทั้งหมด <i class="fas fa-check-double"></i></button>
                            <button type="submit" class="btn btn-danger mx-1 rounded-pill" id="delUserAll" style="display: none;" onclick="return confirm('คุณต้องการลบผู้ใช้ที่เลือกหรือไม่')">
                                ลบผู้ใช้ <i class="fas fa-trash-can"></i>
                            </button>
                        </div>
                                        </form>
                    <?php } else { ?>
                        <div class="btn-group add-user d-flex justify-content-end">
                            <button type="button" class="btn btn-primary text-white mb-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#addUser"><i class="fas fa-user-plus"></i> เพื่มผู้ใช้</button>
                        </div>
                        <div class="container">
                            <div class="alert alert-danger text-body-emphasis rounded-pill" role="alert"><b>ไม่พบผู้ใช้ในระบบ!</b></div>
                        </div>
                    <?php } ?>
                </div>

                <div class="card bg-body mt-7 p-7 rounded-4">
                    <h1 class="h3 text-body-emphasis">คำขอแก้ไขข้อมูลผู้ใช้</h1>
                    <?php if ($countReq > 0) {?>
                        <div class="user-table p-2">
                        <form action="admin/action.php?type=multi&from=<?php echo $_SERVER['REQUEST_URI'];?>&action=remReq" method="POST" id="reqUser">
                            <table id="userRequest" class="table display table-bordered table-striped text-center" style="width:100%">
                                <thead class="bpk-tbheader">
                                <div class="table-options user-select-none btn-group">
                                        <a class="hover text-body-emphasis mt-2 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            ตัวเลือก 
                                        </a>
                                        <ul class="dropdown-menu p-1 rounded-4">
                                            <div class="form-check form-switch m-1">
                                                <input class="form-check-input hover" type="checkbox" role="switch" data-bs-toggle="collapse" style="width: 40px; height: 20px;" href="#timeB" id="timeSwitchB" checked>
                                                <label class="form-check-label hover p-1 mx-1 user-select-none" for="timeSwitchB">แสดงเวลา</label>
                                            </div>
                                            <div class="form-check form-switch m-1">
                                                <input class="form-check-input hover toggle-checkbox" type="checkbox" role="switch" id="selectB" style="width: 40px; height: 20px;" data-column="0">
                                                <label class="form-check-label hover p-1 mx-1 user-select-none" for="selectB">เลือกรายการ</label>
                                            </div>
                                        </ul>
                                    </div>
                                    <tr>
                                        <th class="text-center"><b>เลือก</b></th>
                                        <th class="text-center"><b>ลำดับ</b></th>
                                        <th class="text-center"><b>Username</b></th>
                                        <th class="text-center"><b>ชื่อจริง</b></th>
                                        <th class="text-center"><b>Email</b></th>
                                        <th class="text-center"><b>วันที่ส่ง</b></th>
                                        <th class="text-center"><b>คำสั่ง</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($reqData = $req -> fetch(PDO::FETCH_ASSOC)) {

                                        $optCon = convertDate($reqData["date"]);
                                        list($optDate, $optTime) = $optCon;

                                    ?>
                                        <tr>
                                            <td><input class="form-check-input rounded-3" style="width: 20px; height: 20px;" type="checkbox" name="idcheckbox[]" value="<?php echo $reqData["id"];?>"></td>
                                            <td><?php echo $j++;?></td>
                                            <td>
                                                <?php echo $reqData["username"];?>

                                                <?php if ($reqData["username"] == $userData["username"]) {?>
                                                    <span class="badge text-bg-primary user-select-none rounded-pill">คุณ</span>
                                                <?php }?>
                                                
                                            </td>
                                            <td><?php echo $reqData["realname"];?></td>
                                            <td><?php echo $reqData["email"];?></td>
                                            <td>
                                                <?php echo $optDate;?>
                                                    <div class="collapse show" id="timeB">
                                                        <b>เวลา: <?php echo $optTime?></b>
                                                    </div>
                                                </td>
                                            <td>
                                                <a type="button" href="admin/action/edit?reqid=<?php echo $reqData["id"]?>&req=<?php echo $reqData["username"]?>" class="btn btn-outline-secondary ms-1 my-1 text-body-emphasis rounded-pill"><i class="fas fa-file-pen"></i> แก้ไข</a>
                                                <a type="button" href="admin/action.php?type=none&id=<?php echo $reqData["id"]?>&from=<?php echo $_SERVER['REQUEST_URI'];?>&action=remReq" class="btn btn-outline-danger ms-1 my-1 text-body-emphasis rounded-pill" onclick="return confirm('คุณต้องการลบคำขอรายการนี้หรือไม่?')"><i class="fas fa-trash"></i> ลบ</a>
                                            </td>
                                        </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>

                        <div class="select-options d-flex justify-content-end">
                            <button type="button" class="btn btn-light mx-1 rounded-pill" style="display: none;" id="cancleB" onclick="uncheckAll('userRequest')">ยกเลิก <i class="fas fa-xmark"></i></button>
                            <button type="button" class="btn btn-info mx-1 rounded-pill" style="display: none;" id="selectAllB" onclick="checkAll('userRequest')">เลือกทั้งหมด <i class="fas fa-check-double"></i></button>
                            <button type="submit" class="btn btn-danger mx-1 rounded-pill" id="delReqAll" style="display: none;" onclick="return confirm('คุณต้องการลบคำขอที่เลือกหรือไม่')">
                                ลบรายการ <i class="fas fa-circle-xmark"></i>
                            </button>
                        </div>
                                        </form>

                    <?php } else { ?>
                        <div class="container pt-3">
                            <div class="alert alert-danger text-body-emphasis rounded-pill" role="alert">
                                <span class="fas fa-xl fa-circle-xmark"></span>
                                <b>ไม่พบรายการคำขอในระบบ</b>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
    <?php 
        include_once 'component/footer.php';
        include_once 'component/modal/addUser.php';

        if (isset($_SESSION['id'])) {
            include_once 'component/modal/editUser.php';
        }
    ?>
</body>

<script>
    let table = new DataTable('#userList', {
        // paging: true,
        // pageLength: 10,
        // lengthChange: false,
        // scrollY: '400px',
        language: {
            info: '<li class="m-3">รายการทั้งหมด <b>_MAX_</b> รายการ</li>',
            // info: '<li class="m-3">รายการทั้งหมด <b>_TOTAL_</b> Users</li> หน้า <b>_PAGE_</b> จาก <b>_PAGES_</b>',
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
                target: [0, 8],
            },
        ],
    });

    let tableB = new DataTable('#userRequest', {
        language: {
            info: '<li class="m-3">รายการทั้งหมด <b>_MAX_</b> รายการ</li>',
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
                targets: [0, 6],
            },
        ],
    });

    document.getElementById("timeSwitch").click();

    if (document.getElementById("timeSwitchB")) {
        document.getElementById("timeSwitchB").click();
    }

    // Function to toggle the visibility of the delete button
    document.querySelectorAll('input.toggle-checkbox').forEach((el) => {
        el.addEventListener('change', function (e) {
            let columnIdx = this.getAttribute('data-column');
            let tableId = this.id === 'selectB' ? 'userRequest' : 'userList';
            let tableInstance = tableId === 'userRequest' ? tableB : table;
            
            let column = tableInstance.column(columnIdx);
            column.visible(this.checked);
            toggleSelectAll(tableId);
        });

        // Set initial state
        let columnIdx = el.getAttribute('data-column');
        let tableId = el.id === 'selectB' ? 'userRequest' : 'userList';
        let tableInstance = tableId === 'userRequest' ? tableB : table;
        let column = tableInstance.column(columnIdx);
        el.checked = column.visible();
        toggleSelectAll(tableId);
    });
    
    toggleSelectAll();

    // Function to toggle the visibility of the delete button
    function toggleDeleteButton(tableId) {
        var isUserList = tableId === 'userList';
        var deleteButtonId = isUserList ? 'delUserAll' : 'delReqAll';
        var cancelButtonId = isUserList ? 'cancle' : 'cancleB';

        var checkboxes = document.querySelectorAll(`#${tableId} input[name="idcheckbox[]"]`);
        var deleteButton = document.getElementById(deleteButtonId);
        var cancelButton = document.getElementById(cancelButtonId);

        // If either deleteButton or cancelButton doesn't exist and we're not dealing with userList, skip the operation
        if (!isUserList && (!deleteButton || !cancelButton)) {
            return;
        }

        if (checkboxes.length > 0 && deleteButton && cancelButton) {
            var isAnyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

            deleteButton.style.display = isAnyChecked ? 'inline-block' : 'none';
            cancelButton.style.display = isAnyChecked ? 'inline-block' : 'none';
        }
    }
    
    function toggleSelectAll(tableId) {
        var isUserList = tableId === 'userList';
        var selectAllId = isUserList ? 'selectAll' : 'selectAllB';
        var toggleCheckboxId = isUserList ? 'select' : 'selectB';

        var selectAll = document.getElementById(selectAllId);
        var toggleCheckbox = document.getElementById(toggleCheckboxId);

        if (!isUserList && !selectAll) {
            return;
        }

        if (selectAll && toggleCheckbox) {
            selectAll.style.display = toggleCheckbox.checked ? 'inline-block' : 'none';
        }
    }

    // Modify your existing functions
    function checkAll(tableId) {
        var checkboxes = document.querySelectorAll(`#${tableId} input[name="idcheckbox[]"]`);
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = true;
        });

        toggleDeleteButton(tableId);
    }

    function uncheckAll(tableId) {
        var checkboxes = document.querySelectorAll(`#${tableId} input[name="idcheckbox[]"]`);
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = false;
        });

        toggleDeleteButton(tableId);
    }

    // Add event listeners to all checkboxes
    function addCheckboxListeners() {
        ['userList', 'userRequest'].forEach(function(tableId) {
            var checkboxes = document.querySelectorAll(`#${tableId} input[name="idcheckbox[]"]`);
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    toggleDeleteButton(tableId);
                });
            });
        });
    }

    // Call this when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        addCheckboxListeners();
        document.getElementById("select").click();
        
        // Check if selectB exists before clicking
        const selectB = document.getElementById("selectB");
        if (selectB) {
            selectB.click();
        }
        
        toggleDeleteButton('userList');
        toggleDeleteButton('userRequest');
    });

    // Search Bar Theme
    function userSearch() {
        var searchInput = document.getElementById('dt-search-0');

        if (searchInput) {
            searchInput.placeholder = 'ค้นหา (ค้นโดยใช้ ID, Username, Rank, Email หรือวันที่สมัครได้)';
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        var searchInput = document.getElementById('dt-search-0');

        if (searchInput) {
            searchInput.placeholder = 'ค้นหา (ค้นโดยใช้ ID, Username, Rank, Email หรือวันที่สมัครได้)';
        }
    });
</script>
</html>