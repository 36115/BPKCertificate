<?php
    // ป้องกันการเข้าแบบไม่ถูกต้อง
    if (!defined('SECURE_ACCESS')) {
        header("location: ../");
    }

    $useragent = $_SERVER['HTTP_USER_AGENT'];

    if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
        $device = "mobile-screen-button";
    } else {
        $device = "desktop";
    }

    // Theme หน้าเว็บเริ่มต้น
    $mode = "auto";
    $webName = "โปรแกรมขอเกียรติบัตร";

    // ขอ UserID ผู้ใช้
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo -> prepare("SELECT * FROM users WHERE id = ?");
    $stmt -> execute([$user_id]);
    $userData = $stmt -> fetch();

    // ดึงค่ารูปแบบ Navbar
    $fetch = $pdo -> prepare('SELECT * FROM settings');
    $fetch -> execute();
    $settings = $fetch -> fetch();
?>

    <!-- Info -->
    <meta name="description" content="เว็บดาวน์โหลดเกียรติบัตรสำหรับการแข่งขัน">
    <meta name="author" content="นายสรวิชญ์ สิทธิบวรสกุล และนายวงศ์วริศ ชัยกุลประดิษฐ์">

    <!-- Favicons -->
    <link rel="icon" type="image/x-icon" href="https://www.bpk.ac.th/bpknews/assets/BPK-LOGO-1.ico">
    <link rel="shortcut icon" type="image/x-icon" href="https://www.bpk.ac.th/bpknews/assets/BPK-LOGO-1.ico"/>

    <!-- Fast Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/fastbootstrap@2.2.0/dist/css/fastbootstrap.min.css" rel="stylesheet" integrity="sha256-V6lu+OdYNKTKTsVFBuQsyIlDiRWiOmtC8VQ8Lzdm2i4=" crossorigin="anonymous">

    <!-- Font Support -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

    <!-- Font Awesome Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<header>

<style>
    * {
    font-family: "K2D", sans-serif;
    font-weight: 400;
    font-style: normal;
    }

    .form-control:valid, .form-control.is-valid, .was-validated, .form-select.is-valid:not([multiple]):not([size]), .form-select.is-valid:not([multiple])[size="1"], .was-validated .form-select:valid:not([multiple]):not([size]), .was-validated .form-select:valid:not([multiple])[size="1"] {
        --bs-form-select-bg-icon: url() !important;
        background-image: url() !important;
        border-color: var(--bs-border-color) !important;
    }

    .bpk-tbheader {
        position: sticky;
        top: -0.1;
    }

    .table-responsive {
        max-height:300px;
    }

    .hover:hover {
        cursor: pointer;
    }

    .table>:not(caption)>*>* {
        background-color: rgba(var(--bs-body-bg-rgb),var(--bs-bg-opacity)) !important;
    }

    div.dt-scroll div.dtfc-top-blocker, div.dt-scroll div.dtfc-bottom-blocker, div.dtfh-floatingparent div.dtfc-top-blocker, div.dtfh-floatingparent div.dtfc-bottom-blocker {
        background-color: transparent !important;
    }

    .required .form-label:after {
        content: " *";
        color: red;
    }

    .options {
        width: 100% !important;
        left: -100px !important;
    }

    @media (min-width: 992px) {
        .options {
            width: 300px !important;
        }
    }
</style>

    <?php if ($settings['navbar_mode'] == 1) { ?>

        <nav class="navbar navbar-expand-lg fixed-top py-5">
            <div class="container">
                <a class="navbar-brand user-select-none" href="/">
                    <img src="https://www.bpk.ac.th/bpknews/assets/images/img/logobaner.png" width="300" height="70" class="d-inline-block align-top">
                </a>

                <button class="navbar-toggler rounded-3 my-2 collapsed" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                    <i class="fas fa-bars"></i></span>
                </button>

                <div class="offcanvas offcanvas-end bg-body" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header py-3">
                        <a class="navbar-brand user-select-none" href="/">
                            <img src="https://www.bpk.ac.th/bpknews/assets/images/img/logobaner.png" width="250" class="d-inline-block align-top">
                        </a>

                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    
                    <div class="offcanvas-body py-0">
                        <ul class="nav navbar-nav">
                            <li class="nav-item my-2">
                                <a class="text-body-emphasis user-select-none text-decoration-underline" href="https://www.bpk.ac.th/bpknews">ไปหน้าเว็บหลัก</a>
                            </li>
                        </ul>

                        <ul class="nav navbar-nav ms-auto">
                            <li class="nav-item dropdown my-2">
                                <div class="dropdown-center">
                                    <button class="btn border rounded-pill d-flex dropdown-toggle align-items-center" type="button" id="bd-theme" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-circle-half-stroke me-2"></i>
                                        <span id="bd-theme-text">โหมด</span>
                                    </button>
                                    <div class="dropdown-menu theme border my-2 dropdown-menu-end rounded-4">
                                        <div>
                                            <button type="button" class="dropdown-item d-flex align-items-center rounded-pill" data-bs-theme-value="light">
                                            <i class="fa-solid fa-sun me-2 theme-icon"></i>
                                            สว่าง
                                            </button>
                                        </div>
                                        <div>
                                            <button type="button" class="dropdown-item d-flex align-items-center rounded-pill" data-bs-theme-value="dark">
                                            <i class="fa-solid fa-moon me-2 theme-icon"></i>
                                            มืด
                                            </button>
                                        </div>
                                        <div>
                                            <button type="button" class="dropdown-item d-flex align-items-center active rounded-pill" data-bs-theme-value="auto">
                                            <i class="fa-solid fa-<?php echo $device;?> me-2 theme-icon"></i>
                                            อุปกรณ์
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <?php if (!isset($_SESSION['user_id'])) { ?>
                                    <a href="login" class="btn btn-light bg-transparent text-body-emphasis border me-2 rounded-pill">เข้าสู่ระบบ</a>
                                    <a href="register" class="btn btn-light rounded-pill" data-bs-toggle="modal" data-bs-target="#regAdmin">ลงทะเบียน</a>
                                    <?php } else { ?>
                                        <div class="dropdown-center">
                                            <button class="btn btn-primary text-white dropdown-toggle rounded-pill" type="button" id="userDropdown" data-bs-toggle="dropdown">
                                                <i class="fa-regular fa-face-smile-wink"></i> สวัสดี, <?php echo $userData['username']; ?>!
                                            </button>
                                            <div class="dropdown-menu options p-3 my-2 border rounded-4">
                                                <h6 class="dropdown-header">เมนู</h6>
                                                <a class="btn btn-info dropdown-item rounded-pill" href="/">
                                                    หน้าหลัก <i class="fas fa-home float-end"></i>
                                                </a>
                                                <a class="btn btn-info dropdown-item rounded-pill" data-bs-toggle="modal" data-bs-target="#requestUser">
                                                    ขอแก้ไขข้อมูล <i class="fas fa-comment-dots float-end"></i>
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="btn btn-danger dropdown-item rounded-pill" href="action/logout" onclick="return confirm('คุณต้องการออกจากระบบหรือไม่?')">
                                                    ออกจากระบบ <i class="fas fa-sign-out-alt float-end"></i>
                                                </a>
                                            <?php if ($userData['role'] == "admin") { ?>
                                                <div class="dropdown-divider"></div>
                                                    <h6 class="dropdown-header">สำหรับ Admin</h6>
                                                    <a class="btn btn-primary dropdown-item rounded-pill" data-bs-toggle="modal" data-bs-target="#adminMenu">
                                                        เมนู Admin <i class="fas fa-wrench float-end"></i>
                                                    </a>
                                                </div>
                                            <?php }?>
                                        </div>
                                <?php } ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <?php } else { ?>
            <nav class="navbar navbar-expand-lg fixed-top py-5">
                <div class="container">
                    <a class="navbar-brand user-select-none" href="/"><img src="https://www.bpk.ac.th/bpknews/assets/images/img/logobaner.png" width="300" height="70" class="d-inline-block align-top"></a>
                    <button class="navbar-toggler rounded-3 my-2 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId">
                        <i class="fas fa-bars"></i></span>
                    </button>
                    <div class="collapse navbar-collapse" id="collapsibleNavId">
                        <ul class="nav navbar-nav">
                            <li class="nav-item my-2">
                                <a class="text-body-emphasis user-select-none text-decoration-underline" href="https://www.bpk.ac.th/bpknews">ไปหน้าเว็บหลัก</a>
                            </li>
                        </ul>

                        <ul class="nav navbar-nav ms-auto">
                            <li class="nav-item dropdown my-2">
                                <div class="dropdown-center">
                                    <button class="btn border rounded-pill d-flex dropdown-toggle align-items-center" type="button" id="bd-theme" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-circle-half-stroke me-2"></i>
                                        <span id="bd-theme-text">โหมด</span>
                                    </button>
                                    <div class="dropdown-menu theme border my-2 dropdown-menu-end rounded-4">
                                        <div>
                                            <button type="button" class="dropdown-item d-flex align-items-center rounded-pill" data-bs-theme-value="light">
                                            <i class="fa-solid fa-sun me-2 theme-icon"></i>
                                            สว่าง
                                            </button>
                                        </div>
                                        <div>
                                            <button type="button" class="dropdown-item d-flex align-items-center rounded-pill" data-bs-theme-value="dark">
                                            <i class="fa-solid fa-moon me-2 theme-icon"></i>
                                            มืด
                                            </button>
                                        </div>
                                        <div>
                                            <button type="button" class="dropdown-item d-flex align-items-center active rounded-pill" data-bs-theme-value="auto">
                                            <i class="fa-solid fa-<?php echo $device;?> me-2 theme-icon"></i>
                                            อุปกรณ์
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <?php if (!isset($_SESSION['user_id'])) { ?>
                                    <a href="login" class="btn btn-light bg-transparent text-body-emphasis border me-2 rounded-pill">เข้าสู่ระบบ</a>
                                    <a href="register" class="btn btn-light rounded-pill" data-bs-toggle="modal" data-bs-target="#regAdmin">ลงทะเบียน</a>
                                    <?php } else { ?>
                                        <div class="dropdown-center">
                                            <button class="btn btn-primary text-white dropdown-toggle rounded-pill" type="button" id="userDropdown" data-bs-toggle="dropdown">
                                                <i class="fa-regular fa-face-smile-wink"></i> สวัสดี, <?php echo $userData['username']; ?>!
                                            </button>
                                            <div class="dropdown-menu options p-3 my-2 border rounded-4">
                                                <h6 class="dropdown-header">เมนู</h6>
                                                <a class="btn btn-info dropdown-item rounded-pill" href="/">
                                                    หน้าหลัก <i class="fas fa-home float-end"></i>
                                                </a>
                                                <a class="btn btn-info dropdown-item rounded-pill" data-bs-toggle="modal" data-bs-target="#requestUser">
                                                    ขอแก้ไขข้อมูล <i class="fas fa-comment-dots float-end"></i>
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="btn btn-danger dropdown-item rounded-pill" href="action/logout" onclick="return confirm('คุณต้องการออกจากระบบหรือไม่?')">
                                                    ออกจากระบบ <i class="fas fa-sign-out-alt float-end"></i>
                                                </a>
                                            <?php if ($userData['role'] == "admin") { ?>
                                                <div class="dropdown-divider"></div>
                                                    <h6 class="dropdown-header">สำหรับ Admin</h6>
                                                    <a class="btn btn-primary dropdown-item rounded-pill" data-bs-toggle="modal" data-bs-target="#adminMenu">
                                                        เมนู Admin <i class="fas fa-wrench float-end"></i>
                                                    </a>
                                                </div>
                                            <?php }?>
                                        </div>
                                <?php } ?>
                            </li>
                        </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        <?php } ?>
</header>

<script>
    (() => {
        'use strict'
        const storedTheme = localStorage.getItem('theme')
        const getPreferredTheme = () => {
            if (storedTheme) {
            return storedTheme
            }
            return 'auto'  // Changed this line to return 'auto' by default
        }
        const setTheme = function (theme) {
            if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.setAttribute('data-bs-theme', 'dark')
            } else if (theme === 'auto') {
            document.documentElement.setAttribute('data-bs-theme', 'light')
            } else {
            document.documentElement.setAttribute('data-bs-theme', theme)
            }
        }
        setTheme(getPreferredTheme())
        const showActiveTheme = theme => {
            const activeThemeIcon = document.querySelector('.theme-icon-active')
            const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`)
            const svgOfActiveBtn = btnToActive.querySelector('i').getAttribute('class')
            document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
            element.classList.remove('active')
            })
            btnToActive.classList.add('active')
            const themeSwitcher = document.querySelector('#bd-theme')
            if (themeSwitcher) {
            const themeSwitcherText = document.querySelector('#bd-theme-text')
            const activeThemeIcon = themeSwitcher.querySelector('i')
            const btnIcon = btnToActive.querySelector('i').getAttribute('class')
            const themeSwitcherLabel = `${btnToActive.textContent} (${theme})`
            activeThemeIcon.setAttribute('class', btnIcon)
            themeSwitcherText.textContent = btnToActive.textContent
            }
        }
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (storedTheme !== 'light' && storedTheme !== 'dark') {  // Changed condition to use AND instead of OR
            setTheme(getPreferredTheme())
            }
        })
        window.addEventListener('DOMContentLoaded', () => {
            showActiveTheme(getPreferredTheme())
            document.querySelectorAll('[data-bs-theme-value]')
            .forEach(toggle => {
                toggle.addEventListener('click', () => {
                const theme = toggle.getAttribute('data-bs-theme-value')
                localStorage.setItem('theme', theme)
                setTheme(theme)
                showActiveTheme(theme)
                })
            })
        })
    })()
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
