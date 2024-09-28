<?php
    // ป้องกันการเข้าแบบไม่ถูกต้อง
    if (!defined('SECURE_ACCESS')) {
        header("location: ../../");
    }
?>

<div class="modal fade" id="addLink" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 text-body-emphasis fw-bold">เพิ่มลิงก์</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="required modal-body">
                <div class="col-12 mb-2">
                    <label class="form-label fs-6 mb-3 text-secondary-emphasis">เช่น https://drive.google.com/...</label>
                    <input type="text" name="link" class="form-control text-body-emphasis rounded-pill" placeholder="ใส่ลิงก์ที่ต้องการส่งให้ผู้ขอเกียรติบัตร" required>
                </div>
            </div>
                <div class="m-5 text-center">
                    <button type="submit" name="addlink" class="btn btn-light border mt-2 rounded-pill">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>
</div>