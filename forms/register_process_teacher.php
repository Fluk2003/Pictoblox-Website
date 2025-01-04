<?php

include '../sweet_alert.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    session_start();
    require_once '../connectDB/configsdb.php';

    if (isset($_POST["registerSubmit"])) {
        $firstName = htmlspecialchars($_POST["firstName"]);
        $lastName = htmlspecialchars($_POST["lastName"]);
        $sex = htmlspecialchars($_POST["sex"]);
        $email = htmlspecialchars($_POST["email"]);
        $userName = htmlspecialchars($_POST["userName"]);
        $password = htmlspecialchars($_POST["password"]);
        $confirm_password = htmlspecialchars($_POST["confirm_password"]);
        $userpic = $_FILES["userpic"];
        $number = htmlspecialchars($_POST["number"]);
        $school_id = htmlspecialchars($_POST["school_id"]);
        $subject = htmlspecialchars($_POST["subject"]);

        // picture data
        $userpic_name = $userpic["name"];

        // error check
        $errors = [];

        // Validation checks
        if (empty($firstName)) {
            $errors[] = "กรุณากรอกชื่อ";
        }

        if (empty($lastName)) {
            $errors[] = "กรุณากรอกนามสกุล";
        }

        if (!isset($_POST['sex']) || empty($_POST['sex'])) {
            $errors[] = "กรุณาเลือกเพศ";
        }



        if (empty($email)) {
            $errors[] = "กรุณากรอกอีเมล";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "รูปแบบอีเมลไม่ถูกต้อง";
        }

        if (empty($userName)) {
            $errors[] = "กรุณากรอกชื่อผู้ใช้";
        } elseif (strlen($userName) < 6) {
            $errors[] = "userNameต้องมีอย่างน้อย 6 ตัว";
        }

        if (empty($password)) {
            $errors[] = "กรุณากรอกรหัสผ่าน";
        } elseif (strlen($password) < 8) {
            $errors[] = "รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร";
        }

        if ($password !== $confirm_password) {
            $errors[] = "รหัสผ่านและการยืนยันรหัสผ่านไม่ตรงกัน";
        }

        // uploadfile
        if ($userpic['error'] != UPLOAD_ERR_OK) {
            $errors[] = "กรุณาอัปโหลดรูปภาพโปรไฟล์";
        } else {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($userpic['type'], $allowedTypes)) {
                $errors[] = "กรุณาอัปโหลดไฟล์รูปภาพในรูปแบบ JPEG, PNG หรือ GIF";
            } else {
                // $uploadDir = '../uploads/';
                $uploadDir = '../../forms/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $uploadPath = $uploadDir . basename($userpic['name']);

                if (!move_uploaded_file($userpic['tmp_name'], $uploadPath)) {
                    $errors[] = "ไม่สามารถอัปโหลดไฟล์ได้ กรุณาลองใหม่อีกครั้ง";
                } else {
                    $userpic_name = basename($userpic['name']);
                }
            }
        }

        if (empty($subject)) {
            $errors[] = "กรุณากรอกวิชา";
        }

        if (count($errors) > 0) {
            echo "<script> Swal.fire({
                    icon: 'error',
                    title: 'มีข้อผิดพลาด',
                    text: '" . implode("<br>", $errors) . "',
                    confirmButtonText: 'ตกลง'
                })</script>";
        } else {
            try {
                // Check username availability in both teacher and student tables
                $checkUsername = $conn->prepare("SELECT username FROM ( SELECT username FROM ptb_teacher UNION SELECT username FROM ptb_student ) AS combined_users WHERE username = :userName");
                $checkUsername->bindParam(":userName", $userName);
                $checkUsername->execute();
                if ($checkUsername->rowCount() > 0) {
                    echo "<script> Swal.fire({
                          icon: 'error',
                          title: 'ชื่อผู้ใช้ถูกใช้ไปแล้ว',
                          text: 'กรุณาใช้ชื่อผู้ใช้ใหม่',
                          confirmButtonText: 'ตกลง'
                           }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'register_teacher.php';
                                }
                            })</script>";
                    exit; // Stop further execution if the username already exists
                } else {
                    // Insert user into database
                    $sqlRegister = $conn->prepare("INSERT INTO ptb_teacher(username, password, sex, email, fname, lname, subject, number, picture, school_id)
                    VALUES(:username, :password, :sex, :email, :fname, :lname, :subject, :number, :picture, :school_id)");

                    // Bind parameters
                    $sqlRegister->bindParam(":username", $userName);
                    $sqlRegister->bindParam(":password", $password);
                    $sqlRegister->bindParam(":sex", $sex);
                    $sqlRegister->bindParam(":email", $email);
                    $sqlRegister->bindParam(":fname", $firstName);
                    $sqlRegister->bindParam(":lname", $lastName);
                    $sqlRegister->bindParam(":subject", $subject);
                    $sqlRegister->bindParam(":number", $number);
                    $sqlRegister->bindParam(":picture", $userpic_name);
                    $sqlRegister->bindParam(":school_id", $school_id);
                    $sqlRegister->execute();

                    if ($sqlRegister->rowCount() > 0) {
                        echo "<script> Swal.fire({
                                icon: 'success',
                                title: 'ลงทะเบียนสำเร็จ',
                                text: 'คุณสามารถเข้าสู่ระบบได้ทันที',
                                confirmButtonText: 'ตกลง'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'login.php';
                                }
                            })</script>";
                    } else {
                        echo "<script> Swal.fire({
                                icon: 'error',
                                title: 'ไม่สามารถเพิ่มข้อมูลได้',
                                text: 'กรุณาลองใหม่อีกครั้ง',
                                confirmButtonText: 'ตกลง'
                            })</script>";
                    }
                }
            } catch (PDOException $error) {
                echo "<script> Swal.fire({
                        icon: 'error',
                        title: 'มีข้อผิดพลาด',
                        text: '" . $error->getMessage() . "',
                        confirmButtonText: 'ตกลง'
                    })</script>";
            }
        }
    }
} else {
    echo "<script> Swal.fire({
            icon: 'warning',
            title: 'ไม่พบข้อมูล',
            text: 'ไม่มีข้อมูลที่ต้องการ',
            confirmButtonText: 'ตกลง'
        })</script>";
}
