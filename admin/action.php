<?php
    // เริ่ม Session (ต้องมีทุกหน้า)
    session_start();
    require '../config.php';

    // เช็คว่าส่งมาจาก URL ไหน

    if (str_contains($_SERVER['REQUEST_URI'], "user-info")) {
        $pattern = '/id=(\d+)/';

        if (preg_match($pattern, $_SERVER['REQUEST_URI'], $matches)) {
            $id = $matches[1];
        }

        $page = "../user-info?id=$id";
    } else if (str_contains($_SERVER['REQUEST_URI'], "dashboard")) {
        $page = "../dashboard";
    } else {
        $page = "../";
    }

    // เช็ค Action จากลิงก์ที่ส่งมา
    if (isset($_GET["action"])) {
        $action = $_GET["action"];

        // เช็ค Action ว่าเป็นรูปแบบไหน
        if ($action == "remCert") {
            $table = "cert_list";
        } else if ($action == "remLink" || $action == "addLink") {
            $table = "cert_list";
        } else if ($action == "remReq") {
            $table = "request_users";
        } else {
            $table = "users";
        }
    }

    if (isset($_GET["type"])) {
        $type = $_GET["type"];
    }

    if ($type == "multi") { // หลายรายการ

        if (isset($_POST["idcheckbox"])) {
            $list_arr = $_POST["idcheckbox"];
            $list = implode(",", $list_arr);
        } else {
            $_SESSION['error'] = "ไม่สามารถทำรายการได้ในขณะนี้";
            header("location: $page");
            exit;
        }

        try {
            if ($action == "remLink") { // ลบลิงก์เกียรติบัตรหลายรายการ
                $cmd = "UPDATE $table SET cert_link = '' WHERE id in ($list)";
                $stmt = $pdo -> query($cmd);
                $stmt -> execute();
            } else if ($action == "addLink") { // เพิ่มลิงก์เกียรติบัตรหลายรายการ
                $link = $_POST['link'];

                if (!filter_var($link, FILTER_VALIDATE_URL)) {
                    $_SESSION['error'] = "กรุณาใส่ลิงก์ให้ถูกต้อง";
                    header("location: $page");
                    exit;
                }

                $cmd = "UPDATE $table SET cert_link = ('$link') WHERE id in ($list)";
                $stmt = $pdo -> query($cmd);
                $stmt -> execute();
            } else { // ลบหลายรายการ (Action อื่นๆ)
                $cmd = "DELETE FROM $table WHERE id in ($list)";
                $stmt = $pdo -> query($cmd);
                $stmt -> execute();
            }
    
            if ($action == "remCert") { // ลบรายการขอเกียรติบัตรหลายรายการ
                $_SESSION['success'] = "ลบรายการเกียรติบัตรที่เลือกทั้งหมด ".count($list_arr)." รายการแล้ว";
            } else if ($action == "remReq") { // ลบคำขอแก้ไขข้อมูลผู้ใช้หลายรายการ
                $_SESSION['success'] = "ลบคำขอแก้ไขข้อมูลผู้ใช้ที่เลือกทั้งหมด ".count($list_arr)." รายการแล้ว";
            } else if ($action == "remLink") { // ลบลิงก์เกียรติบัตรหลายรายการ
                $_SESSION['success'] = "ลบลิงก์เกียรติบัตรที่เลือกทั้งหมด ".count($list_arr)." รายการแล้ว";
            } else if ($action == "addLink") { // เพิ่มลิงก์เกียรติบัตรหลายรายการ
                $_SESSION['success'] = "เพิ่มลิงก์เกียรติบัตรที่เลือกทั้งหมด ".count($list_arr)." รายการแล้ว";
            } else { // ลบผู้ใช้หลายรายการ
                $_SESSION['success'] = "ลบผู้ใช้ที่เลือกทั้งหมด ".count($list_arr)." รายการแล้ว";
            }

            header("location: $page");
            exit;
    
        } catch (PDOException $e) {
            if ($action == "remCert") { // ลบรายการขอเกียรติบัตรหลายรายการ
                $_SESSION['error'] = "ไม่สามารถลบรายการเกียรติบัตรที่เลือกทั้งหมด ".count($list_arr)." รายการได้ในขณะนี้";
            } else if ($action == "remReq") { // ลบคำขอแก้ไขข้อมูลผู้ใช้หลายรายการ
                $_SESSION['error'] = "ไม่สามารถลบคำขอแก้ไขข้อมูลผู้ใช้ที่เลือกทั้งหมด ".count($list_arr)." รายการได้ในขณะนี้";
            } else if ($action == "remLink") { // ลบลิงก์เกียรติบัตรหลายรายการ
                $_SESSION['error'] = "ไม่สามารถลบลิงก์เกียรติบัตรที่เลือกทั้งหมด ".count($list_arr)." รายการได้ในขณะนี้";
            } else if ($action == "addLink") { // เพิ่มลิงก์เกียรติบัตรหลายรายการ
                $_SESSION['error'] = "ไม่สามารถเพิ่มลิงก์เกียรติบัตรที่เลือกทั้งหมด ".count($list_arr)." รายการได้ในขณะนี้";
            } else { // ลบผู้ใช้หลายรายการ
                $_SESSION['error'] = "ไม่สามารถลบผู้ใช้ที่เลือกทั้งหมด ".count($list_arr)." รายการได้ในขณะนี้";
            }

            $_SESSION['log'] = $e;
            header("location: $page");
            exit;
        }
    } else if ($type == "none") { // รายการเดียว

        if (isset($_GET["id"])) { // เช็คว่าได้ส่งค่า id มาหรือไม่
            $id = $_GET["id"];
        }

        try {

            if ($action == "delUser") { // ลบผู้ใช้รายการเดียว
    
                $stmt = $pdo -> prepare("SELECT * FROM $table WHERE id = ($id)");
                $stmt -> execute();
                $userDel = $stmt -> fetch();
    
                $stmt = $pdo -> prepare("DELETE FROM $table WHERE id in ($id)");
                $stmt -> execute();
                
                // กลับหน้าหลัก
                $_SESSION['success'] = "ลบผู้ใช้: <b>".$userDel["username"]."</b> เรียบร้อยแล้ว";

            } else if ($action == "remReq") { // ลบคำขอแก้ไขข้อมูลผู้ใช้รายการเดียว
    
                $stmt = $pdo -> prepare("DELETE FROM $table WHERE id in ($id)");
                $stmt -> execute();
                
                // กลับหน้าหลัก
                $_SESSION['success'] = "ลบคำขอแก้ไขข้อมูลผู้ใช้รายการที่: <b>".$id."</b> เรียบร้อยแล้ว";

            } else if ($action == "cfgUser") { // แก้ไขข้อมูลผู้ใช้
                $userAction = $_GET["useraction"];
                $edited = date("Y-m-d H:i:s");

                $minLength = 6;
                $UserminLength = 3;
                $maxLength = 20;

                if ($userAction == "editinfo") { // แก้ไขข้อมูลผู้ใช้
                    $sameUser = 0;
                    $passwordCheck = false;
                }

                if (isset($_SESSION["edittype"])) { // ประเภทการแก้ไขข้อมูลผู้ใช้ (แบบปกติ หรือจากคำขอผู้ใช้)
                    $edittype = $_SESSION["edittype"];
                }

                if (isset($id) || isset($_POST['register'])) { // เพิ่มผู้ใช้, แก้ไขผู้ใช้
                    $username = $_POST['username'];
                    $realname = $_POST['fname']." ".$_POST['lname'];
                    $email = strtolower($_POST['email']);
                    $password = $_POST['password'];
                    if ($userAction == "editinfo") {
                        if (!empty($password)) {
                            $passwordCheck = true;
                        }
                    }
                    $role = $_POST['role'];
                }

                if (empty($username)) {
                    $_SESSION['error'] = "กรุณาใส่ชื่อผู้ใช้";
                    header("location: $page");
                } else if (strlen($username) < $UserminLength || strlen($username) > $maxLength || preg_match('/[^\x00-\x7F]/', $username)) {
                    $_SESSION['error'] = "กรุณาใส่ชื่อผู้ใช้ให้ถูกต้อง";
                    $_SESSION['log'] = "ชื่อผู้ใช้ควรมีความยาวไม่ต่ำกว่า 3 และไม่เกิน 20 ตัวอักษร และต้องเป็นอักขระภาษาอังกฤษเท่านั้น";
                    header("location: $page");
                } else if (!filter_var($email, FILTER_VALIDATE_EMAIL) || preg_match('/[^\x00-\x7F]/', $email)) {
                    $_SESSION['error'] = "กรุณาใส่ Email ให้ถูกต้อง";
                    header("location: $page");
                } else if ($userAction == "register" && strlen($password) < $minLength) {
                    $_SESSION['error'] = "กรุณาใส่รหัสผ่านให้ถูกต้อง";
                    $_SESSION['log'] = "รหัสผ่านควรมีความยาวไม่ต่ำกว่า 6 ตัวอักษร";
                    header("location: $page");
                } else {
                    if ($passwordCheck == true) {

                        if (strlen($password) < $minLength) {
                            $_SESSION['error'] = "กรุณาใส่รหัสผ่านให้ถูกต้อง";
                            header("location: $page");
                            exit;
                        }

                        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
                    }

                    if ($userAction == "editinfo") { // แก้ไขข้อมูลผู้ใช้
                        $hashPassword = password_hash($password, PASSWORD_DEFAULT);

                        $checkId = $pdo -> prepare("SELECT COUNT(*) FROM users WHERE id = ?");
                        $checkId -> execute([$id]);
                        $IdExists = $checkId -> fetchColumn();

                        if ($id == $IdExists) {
                            $sameUser = 1;
                        }
                    } else {
                        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
                    }

                    if ($sameUser = 0 || $userAction == "register") { // เพิ่มผู้ใช้
                        $checkUsername = $pdo -> prepare("SELECT COUNT(*) FROM users WHERE username = ?");
                        $checkUsername -> execute([$username]);
                        $usernameExists = $checkUsername -> fetchColumn();

                        $checkEmail = $pdo -> prepare("SELECT COUNT(*) FROM users WHERE email = ?");
                        $checkEmail -> execute([$email]);
                        $userEmailExists = $checkEmail -> fetchColumn();

                        if ($usernameExists) {
                            $_SESSION['error'] = "ชื่อผู้ใช้: <b>$username</b> ถูกใช้ไปแล้ว";
                            header("location: $page");
                            exit;
                        } else if ($userEmailExists) {
                            $_SESSION['error'] = "Email: <b>$email</b> ถูกใช้ไปแล้ว";
                            header("location: $page");
                            exit;
                        }

                        if ($userAction == "register") { // เพิ่มผู้ใช้
                            $stmt = $pdo -> prepare("INSERT INTO users(username, realname, role, email, password, edited_date) VALUES(?, ?, ?, ?, ?, ?)");
                            $stmt -> execute([$username, $realname, $role, $email, $hashPassword, $edited]);

                            $_SESSION['success'] = "เพิ่มผู้ใช้: <b>$username</b> แล้ว";
                            header("location: $page");
                            exit;
                        }
                    } else { // แก้ไขข้อมูลผู้ใช้

                        if ($passwordCheck == true) {
                            $stmt = $pdo -> prepare("UPDATE users SET username = ?, realname = ?, role = ?, email = ?, password = ?, edited_date = ? WHERE id = ".$id."");
                            $stmt->execute([$username, $realname, $role, $email, $hashPassword, $edited]);
                        } else {
                            $stmt = $pdo -> prepare("UPDATE users SET username = ?, realname = ?, role = ?, email = ?, edited_date = ? WHERE id = ".$id."");
                            $stmt->execute([$username, $realname, $role, $email, $edited]);

                            if ($edittype == "request") { // แก้ไขข้อมูลผู้ใช้จากคำขอ
                                $stmt = $pdo -> prepare("SELECT * FROM request_users WHERE username = ('$username')");
                                $stmt -> execute();
                                $userDel = $stmt -> fetch();
                            
                                $cmd = "DELETE FROM request_users WHERE username in ('$username')";
                                $stmt = $pdo -> prepare($cmd);
                                $stmt -> execute();
    
                                $_SESSION['success'] = "แก้ไขข้อมูลตามรายการคำขอผู้ใช้: <b>$username</b> สำเร็จ";
                                header("location: $page");
                                exit;
                            }
                        }

                        $_SESSION['success'] = "แก้ไขข้อมูลผู้ใช้: <b>$username</b> สำเร็จ";
                    }
                }
            } else {
                $_SESSION['error'] = "กรุณาลองอีกครั้ง";
            }
            
            header("location: $page");
            exit;

        } catch (PDOException $e) {
            
            if ($action == "delUser") { // ลบผู้ใช้รายการเดียว
                $_SESSION['error'] = "ไม่สามารถลบผู้ใช้: <b>".$userDel["username"]."</b> ได้ในขณะนี้";
            } else if ($action == "remReq") { // ลบคำขอแก้ไขข้อมูลผู้ใช้รายการเดียว
                $_SESSION['error'] = "ไม่สามารถลบคำขอแก้ไขข้อมูลผู้ใช้รายการที่: <b>".$id."</b> ได้ในขณะนี้";
            } else if ($useraction == "editinfo") { // แก้ไขข้อมูลผู้ใช้รายการเดียว
                $_SESSION['error'] = "ไม่สามารถแก้ไขข้อมูลผู้ใช้: <b>$username</b> ได้ในขณะนี้";
            } else if ($edittype == "request") { // แก้ไขข้อมูลผู้ใช้จากคำขอรายการเดียว
                $_SESSION['error'] = "ไม่สามารถแก้ไขรหัสผ่านผู้ใช้: <b>$username</b> ได้ในขณะนี้";   
            } else if ($useraction == "register") { // เพิ่มผู้ใช้
                $_SESSION['error'] = "ไม่สามารถเพิ่มผู้ใช้: <b>$username</b> ได้ในขณะนี้";   
            }

            $_SESSION['log'] = $e;
            header("location: $page");
            exit;
        }
    } else if ($type == "settings") {
        
        $nav_mode = $_POST['nav_mode'];

        if ($nav_mode == "offcanvas") {
            $nav_mode = 1;
        } else {
            $nav_mode = 0;
        }

        try {
            $stmt = $pdo -> prepare("UPDATE settings SET navbar_mode = ('$nav_mode')");
            $stmt -> execute();

            $_SESSION['success'] = "บันทึกการตั้งค่าสำเร็จ";   
            header("location: $page");
            exit;
        } catch (PDOException $e) {
            $_SESSION['error'] = "ไม่สามารถบันทึกการตั้งค่าได้ในขณะนี้";   
            header("location: $page");
            exit;
        }

    } else {
        $_SESSION['error'] = "กรุณาลองอีกครั้ง";
        $_SESSION['log'] = $e;
        header("location: $page");
    }