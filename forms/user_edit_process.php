<?php

include '../sweet_alert.php' ;

// เริ่มต้นการตรวจสอบคำขอ
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once '../databaseConfigs/configsdb.php'; // เชื่อมต่อฐานข้อมูล
    session_start();

    if (isset($_SESSION["userID"])) {
        $userID = $_SESSION["userID"];
        // echo $userID;
    }

    try {
        // รับค่าจากฟอร์ม
        $firstName = htmlspecialchars($_POST["firstName"]);
        $lastName = htmlspecialchars($_POST["lastName"]);
        $email = htmlspecialchars($_POST["email"]);
        $userName = htmlspecialchars($_POST["userName"]);
        $tol = htmlspecialchars($_POST["tol"]);
        $school_id = htmlspecialchars($_POST["school_id"]);
        $subject = htmlspecialchars($_POST["subject"]);
        $password = htmlspecialchars($_POST["password"]);
        $confirm_password = htmlspecialchars($_POST["confirm_password"]);

        // ตรวจสอบความยาวรหัสผ่าน
        if (strlen(trim($password)) < 8) {
            echo
            "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'รหัสผ่านสั้นเกินไป',
                    text: 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร',
                }).then(() => {
                    window.history.back();
                });
            </script>";
            exit();
        }

        // ตรวจสอบรหัสผ่านว่าตรงกันหรือไม่
        if (trim($password) !== trim($confirm_password)) {
            echo
            "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'รหัสผ่านไม่ตรงกัน',
                    text: 'กรุณากรอกให้ตรงกัน',
                }).then(() => {
                    window.history.back();
                });
            </script>";
            exit();
        }

        // ตรวจสอบการอัปโหลดรูปภาพ
        $userpic = "";
        if (isset($_FILES["userpic"]) && $_FILES["userpic"]["error"] == 0) {

            // path uploads localhost
            $targetDir = "../uploads/";

            // path uploads server
            // $targetDir = "../../forms/uploads/";

            $fileName = basename($_FILES["userpic"]["name"]);
            $targetFilePath = $targetDir . $fileName;

            // ตรวจสอบชนิดของไฟล์ (เช่น .jpg, .png)
            $allowedTypes = ["image/jpeg", "image/png", "image/gif"];
            $fileType = $_FILES["userpic"]["type"];

            if (in_array($fileType, $allowedTypes)) {
                // อัปโหลดไฟล์
                if (move_uploaded_file($_FILES["userpic"]["tmp_name"], $targetFilePath)) {
                    $userpic = $fileName; // เก็บชื่อไฟล์รูปใหม่
                } else {
                    die("เกิดข้อผิดพลาดในการอัปโหลดไฟล์");
                }
            } else {
                die("โปรดเลือกไฟล์รูปภาพที่ถูกต้อง");
            }
        } else {
            // ถ้าไม่ได้อัปโหลดรูปภาพ ให้ใช้รูปเดิม
            $sql = "SELECT userpic FROM users3 WHERE userID = :userID";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":userID", $userID);
            $stmt->execute();
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
            $userpic = $userData["userpic"];
        }

        // อัปเดตข้อมูลในฐานข้อมูล
        $sqlUpdate = $conn->prepare("UPDATE users3 
            SET firstName = :firstName, 
                lastName = :lastName, 
                email = :email, 
                userName = :userName, 
                tol = :tol, 
                school_id = :school_id, 
                subject = :subject, 
                password = :password, 
                userpic = :userpic
            WHERE userID = :userID");

        $sqlUpdate->bindParam(":firstName", $firstName);
        $sqlUpdate->bindParam(":lastName", $lastName);
        $sqlUpdate->bindParam(":email", $email);
        $sqlUpdate->bindParam(":userName", $userName);
        $sqlUpdate->bindParam(":tol", $tol);
        $sqlUpdate->bindParam(":school_id", $school_id);
        $sqlUpdate->bindParam(":subject", $subject);
        $sqlUpdate->bindParam(":password", $password);
        $sqlUpdate->bindParam(":userpic", $userpic);
        $sqlUpdate->bindParam(":userID", $userID);

        if ($sqlUpdate->execute()) {
            echo
            "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'อัปเดตข้อมูลสำเร็จ',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = '../userProfile.php';
                });
            </script>";
            exit();
        } else {
            echo
            "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถอัปเดตข้อมูลได้',
                }).then(() => {
                    window.history.back();
                });
            </script>";
        }
    } catch (PDOException $error) {
        echo "เกิดข้อผิดพลาด: " . $error->getMessage();
    }
} else {
    echo "คำขอไม่ถูกต้อง";
}
?>