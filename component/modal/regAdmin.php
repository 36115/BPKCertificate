<?php
    // ป้องกันการเข้าแบบไม่ถูกต้อง
    if (!defined('SECURE_ACCESS')) {
        header("location: ../../");
    }
?>

<div class="modal fade" id="regAdmin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border border-2 bg-body py-5 rounded-4">
            <div class="modal-header">
                <h1 class="modal-title fs-5 text-body-emphasis fw-bold">ลงทะเบียน</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <div class="py-5 mb-2">
                    โปรดติดต่อผู้ดูแลระบบเพื่อขอลงทะเบียน
                </div>

                <div class="m-5 text-center">
                    <button type="button" class="btn btn-light border mt-2 rounded-pill" data-bs-dismiss="modal">ปิด</button>
                </div>

            </div>
        </div>
    </div>
</div>