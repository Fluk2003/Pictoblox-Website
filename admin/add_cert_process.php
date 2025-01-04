<?php
include '../sweet_alert.php';
session_start();
require_once '../connectDB/configsdb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่า userID จาก session
    if (!isset($_SESSION["userID"])) {
        echo "<script>window.location.href = 'login.php';</script>";
        exit;
    }
    $userID = $_SESSION["userID"];
    $userType = ($_POST["status"] == "teacher") ? "teacher" : "student";


    // กำหนดที่เก็บไฟล์ที่อัปโหลด
    $targetDir = "../uploads/";

    // ตรวจสอบว่ามีการเลือกไฟล์หรือไม่
    if (isset($_FILES["certificate"])) {
        $certFile = $_FILES["certificate"]["name"];
        $targetFilePath = $targetDir . basename($certFile);
        // echo $certFile;
        // echo $userID;

        try {
            // ตรวจสอบว่าไฟล์ไม่ว่างเปล่า
            if (!empty($certFile)) {
                // ตรวจสอบประเภทไฟล์
                $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
                if ($fileType != "pdf") {
                    throw new Exception("กรุณาเลือกไฟล์ PDF เท่านั้น");
                }

                // อัปโหลดไฟล์
                if (move_uploaded_file($_FILES["certificate"]["tmp_name"], $targetFilePath)) {
                    // อัปเดตข้อมูลในฐานข้อมูล
                    if ($userType == "teacher") {
                        // echo $userType;
                        $sqlUpdate = $conn->prepare("UPDATE ptb_teacher SET certificate = :certificate WHERE teacher_id = :userID");
                    } else {
                        $sqlUpdate = $conn->prepare("UPDATE ptb_student SET certificate = :certificate WHERE student_id = :userID");
                        // echo $userType;
                    }

                    // ผูกค่ากับคำสั่ง SQL
                    $sqlUpdate->bindParam(":certificate", $certFile);
                    $sqlUpdate->bindParam(":userID", $userID);

                    // ตรวจสอบการอัปเดตข้อมูล
                    if ($sqlUpdate->execute()) {
                        echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ!',
                                text: 'เพิ่ม/แก้ไขใบประกาศเรียบร้อยแล้ว!',
                                confirmButtonText: 'ตกลง'
                            }).then(() => {
                                window.location.href = 'cert_data.php';
                            });
                        </script>";
                    } else {
                        throw new Exception("เกิดข้อผิดพลาดในการบันทึกข้อมูล");
                    }
                } else {
                    throw new Exception("อัปโหลดไฟล์ล้มเหลว");
                }
            } else {
                throw new Exception("กรุณาเลือกไฟล์ก่อนทำการอัปโหลด");
            }
        } catch (Exception $e) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'ข้อผิดพลาด!',
                    text: '{$e->getMessage()}',
                    confirmButtonText: 'ตกลง'
                }).then(() => {
                    window.history.back();
                });
            </script>";
        }
    } else {
        // กรณีไม่ได้เลือกไฟล์
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'ข้อผิดพลาด!',
                text: 'กรุณาเลือกไฟล์ PDF ก่อนทำการอัปโหลด',
                confirmButtonText: 'ตกลง'
            }).then(() => {
                window.history.back();
            });
        </script>";
    }
}
