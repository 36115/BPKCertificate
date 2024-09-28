<?php
    // ป้องกันการเข้าแบบไม่ถูกต้อง
    if (!defined('SECURE_ACCESS')) {
        header("location: ../../");
    }
?>

<div class="mb-2">
    <label class="form-label text-body-emphasis">Id</label>
    <input type="text" name="id" class="form-control text-body-emphasis rounded-pill" value="<?php echo $id;?>" disabled>
</div>

<div class="mb-2">
    <label class="form-label text-body-emphasis">ชื่อผู้ใช้</label>
    <div class="input-group">
        <span class="input-group-text rounded-start-pill" id="username-symbol">@</span>
        <input type="text" name="username" class="form-control text-body-emphasis rounded-end-pill" value="<?php echo $username;?>" placeholder="ชื่อผู้ใช้" aria-describedby="username-symbol" required>
    </div>
</div>

<div class="mb-2">
    <label class="form-label text-body-emphasis">ชื่อจริง</label>
    <div class="input-group">
        <span class="input-group-text rounded-start-pill" id="name-symbol">ชื่อ</span>
        <input type="text" name="fname" class="form-control text-body-emphasis" value="<?php echo $realname[0];?>" placeholder="ชื่อ" aria-describedby="name-symbol" required>
        
        <span class="input-group-text" id="surname-symbol">นามสกุล</span>
        <input type="text" name="lname" class="form-control text-body-emphasis rounded-end-pill" value="<?php echo $realname[1];?>" placeholder="นามสกุล" aria-describedby="surname-symbol" required>
    </div>
</div>

<div class="mb-2">
    <label class="form-label text-body-emphasis">Email</label>
    <input type="email" name="email" class="form-control text-body-emphasis rounded-pill" value="<?php echo $email;?>" placeholder="Email" required>
</div>

<div class="mb-2">
    <label class="form-label text-body-emphasis">รหัสผ่านใหม่</label>
    <input type="password" name="password" class="form-control text-body-emphasis rounded-pill" placeholder="รหัสผ่าน">
</div>

<div class="mb-2">
    <label class="form-label fs-6 mb-3 text-body-emphasis">ประเภทผู้ใช้</label>
    <select class="form-select rounded-pill" name="role">
        <option value="user" <?php if ($role == "user") echo "selected";?>>User</option>
        <option value="admin" <?php if ($role == "admin") echo "selected";?>>Admin</option>
        <?php unset($_SESSION['edittype']); ?>
    </select>
</div>

<div class="m-5 text-center">
    <button type="submit" name="editInfo" class="btn btn-light border mt-2 rounded-pill">บันทึก</button>
</div>