<?php
session_start();
include '../sweet_alert.php'; // Include SweetAlert for usage

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
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
            // Show SweetAlert2 for errors
            echo "
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'มีข้อผิดพลาด',
                    text: '" . implode(' ', $errors) . "',
                }).then(() => {
                    window.history.back();
                });
            </script>";
            exit; // Stop further script execution
        } else {
            try {
                $sqlUserLogin = $conn->prepare("SELECT * FROM ptb_teacher WHERE username = :userName AND password = :password");
                $sqlUserLogin->bindParam(":userName", $userName);
                $sqlUserLogin->bindParam(":password", $password);
                $sqlUserLogin->execute();

                if ($sqlUserLogin->rowCount() > 0) {
                    $_SESSION["userName"] = $userName;

                    // Debug: Dump session to check if it's set correctly
                    var_dump($_SESSION);

                    // Show SweetAlert2 for successful login
                    echo "
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'เข้าสู่ระบบสำเร็จ',
                            text: 'ยินดีต้อนรับ $userName',
                        }).then(() => {
                            window.location.href = '../teacherProfile.php';
                        });
                    </script>";
                    exit; // Stop further script execution
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
                    exit; // Stop further script execution
                }
            } catch (PDOException $err) {
                echo "Error: " . $err->getMessage();
            }
        }
    }
}
?>
