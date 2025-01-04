<?php
include '../sweet_alert.php'; // รวมไฟล์ sweet alert เพื่อใช้งาน

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    session_start();
    require_once '../connectDB/configsdb.php';

    if (isset($_POST["registerSubmit"])) {
        $userName = htmlspecialchars($_POST["userName"]);
        $password = htmlspecialchars($_POST["password"]);

        $errors = [];

        if (empty($userName)) {
            $_SESSION["userNameEmpty"] = "กรุณากรอกชื่อผู้ใช้";
            $errors[] = $_SESSION["userNameEmpty"];
        }

        if (empty($password)) {
            $_SESSION["passwordEmpty"] = "กรุณากรอกรหัสผ่าน";
            $errors[] = $_SESSION["passwordEmpty"];
        }

        if (count($errors) > 0) {
            // แสดง SweetAlert2 เมื่อมีข้อผิดพลาด
            echo "
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'มีข้อผิดพลาด',
                    text: '" . implode(' ', $errors) . "',
                }).then(() => {
                    window.history.back(); // กลับไปที่หน้าก่อนหน้า
                });
            </script>";
        } else {
            try {
                $sqlUserLogin = $conn->prepare("SELECT * FROM ptb_student WHERE username = :userName AND password = :password");
                $sqlUserLogin->bindParam(":userName", $userName);
                $sqlUserLogin->bindParam(":password", $password);
                $sqlUserLogin->execute();

                if ($sqlUserLogin->rowCount() > 0) {
                    $_SESSION["userName"] = $userName;
                    echo "
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'เข้าสู่ระบบสำเร็จ',
                            text: 'ยินดีต้อนรับ $userName',
                        }).then(() => {
                            window.location.href = '../studentProfile.php';
                        });
                    </script>";
                } else {
                    echo "
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'ไม่มีบัญชีนี้',
                            text: 'กรุณาตรวจสอบชื่อผู้ใช้และรหัสผ่าน',
                        }).then(() => {
                            window.history.back();
                        });
                    </script>";
                }

            } catch (PDOException $err) {
                echo "Error: " . $err->getMessage();
            }
        }
    }
}
?>
