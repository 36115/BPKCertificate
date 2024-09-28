<?php
    // ป้องกันการเข้าแบบไม่ถูกต้อง
    if (!defined('SECURE_ACCESS')) {
        header("location: ../../");
    }
?>

<div class="modal fade" id="addUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border border-2 bg-body py-5 rounded-4">

            <div class="modal-header">
                <h1 class="modal-title fs-5 text-body-emphasis fw-bold">เพื่มผู้ใช้</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="required modal-body">
                <form action="admin/action.php?type=none&from=<?php echo $_SERVER['REQUEST_URI'];?>&action=cfgUser&useraction=register" method="POST">

                    <div class="required mb-2">
                        <label class="form-label fs-6 mb-3 text-body-emphasis">ชื่อผู้ใช้</label>
                        <div class="input-group">
                            <span class="input-group-text rounded-start-pill" id="username-symbol">@</span>
                            <input type="text" name="username" class="form-control text-body-emphasis rounded-end-pill" placeholder="ชื่อผู้ใช้" aria-describedby="username-symbol" required>
                        </div>
                    </div>

                    <div class="required mb-2">
                        <label class="form-label text-body-emphasis">ชื่อจริง</label>
                        <div class="input-group">
                            <span class="input-group-text rounded-start-pill" id="name-symbol">ชื่อ</span>
                            <input type="text" name="fname" class="form-control text-body-emphasis" placeholder="ชื่อ" aria-describedby="name-symbol" required>
                            
                            <span class="input-group-text" id="surname-symbol">นามสกุล</span>
                            <input type="text" name="lname" class="form-control text-body-emphasis rounded-end-pill" placeholder="นามสกุล" aria-describedby="surname-symbol" required>
                        </div>
                    </div>
                    
                    <div class="required mb-2">
                        <label class="form-label fs-6 mb-3 text-body-emphasis">Email</label>
                        <input type="email" name="email" class="form-control text-body-emphasis rounded-pill" placeholder="Email" required>
                    </div>

                    <div class="required mb-2">
                        <label class="form-label fs-6 mb-3 text-body-emphasis">Password</label>
                        <input type="password" name="password" class="form-control text-body-emphasis rounded-pill" placeholder="รหัสผ่าน" required>
                    </div>

                    <div class="required mb-2">
                        <label class="form-label fs-6 mb-3 text-body-emphasis">ประเภทผู้ใช้</label>
                        <select class="form-select rounded-pill" name="role">
                            <option value="user" selected>User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <div class="m-5 text-center">
                        <button type="submitButton" type="submit" name="register" class="btn btn-light border mt-2 rounded-pill">ยืนยัน</button>
                    </div>

                </form>
                    
            </div>
        </div>
    </div>
</div>