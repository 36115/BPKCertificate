<?php
    // ป้องกันการเข้าแบบไม่ถูกต้อง
    if (!defined('SECURE_ACCESS')) {
        header("location: ../../");
    }
?>

<div class="modal fade" id="requestUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border border-2 bg-body py-5 rounded-4">

            <div class="modal-header">
                <h1 class="modal-title fs-5 text-body-emphasis fw-bold">แบบฟอร์มขอแก้ไขข้อมูลผู้ใช้</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form action="action/request?from=<?php echo $_SERVER['REQUEST_URI'];?>" method="POST">

                    <p class="text-danger fw-light pb-4">กรุณาใส่ชื่อผู้ใช้ให้ถูกต้อง เพื่อให้สามารถแก้ไขข้อมูลผู้ใช้นั้นได้</p>

                    <div class="required mb-4">
                        <label class="form-label text-body-emphasis">ชื่อผู้ใช้ที่มีปัญหา</label>
                        <div class="input-group">
                            <span class="input-group-text rounded-start-pill" id="username-symbol">@</span>
                            <input type="text" name="username" class="form-control text-body-emphasis rounded-end-pill" placeholder="ชื่อผู้ใช้" aria-describedby="username-symbol" required>
                        </div>
                    </div>
                    
                    <div class="required mb-4">
                        <label class="form-label text-body-emphasis">ชื่อจริง</label>
                        <div class="input-group">
                            <span class="input-group-text rounded-start-pill" id="name-symbol">ชื่อ</span>
                            <input type="text" name="fname" class="form-control text-body-emphasis" placeholder="ชื่อ" aria-describedby="name-symbol" required>
                            
                            <span class="input-group-text" id="surname-symbol">นามสกุล</span>
                            <input type="text" name="lname" class="form-control text-body-emphasis rounded-end-pill" placeholder="นามสกุล" aria-describedby="surname-symbol" required>
                        </div>
                    </div>
                    
                    <div class="required mb-4">
                        <label class="form-label text-body-emphasis">Email ที่สามารถติดต่อได้</label>
                        <input type="email" name="email" class="form-control text-body-emphasis rounded-pill" placeholder="Email" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-body-emphasis">ข้อมูลเพิ่มเติม <span class="text-muted">(ไม่บังคับ)</span></label>
                        <textarea rows="4" name="detail" class="form-control text-body-emphasis rounded-4" placeholder="ข้อมูลนอกเหนือจากนี้ หรือช่องทางการติดต่อเพิ่มเติม" style="white-space: pre-line;"></textarea>
                    </div>

                    <div class="m-5 text-center">
                        <button type="submit" name="sendRequest" class="btn btn-light border mt-2 rounded-pill">ส่งคำขอ</button>
                    </div>

                </form>
                    
            </div>
        </div>
    </div>
</div>