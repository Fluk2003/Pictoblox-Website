<?php
// เริ่มต้น session และเชื่อมต่อฐานข้อมูล
session_start();
require_once '../connectDB/configsdb.php';
include 'sweet_alert.php' ;

// รับค่า userID จาก URL
$userID = isset($_GET['teacherID']) ? $_GET['teacherID'] : (isset($_GET['studentID']) ? $_GET['studentID'] : null);
echo $userID;

if ($userID) {
    // ตรวจสอบว่าเป็นครูหรือไม่ และอัปเดตสถานะตาม
    if (isset($_GET['teacherID'])) {
        // ถ้าเป็นครู
        $sqlUpdate = $conn->prepare("UPDATE ptb_teacher SET cert_status = 'true' WHERE teacher_id = :userID");
    } elseif (isset($_GET['studentID'])) {
        // ถ้าเป็นนักเรียน
        $sqlUpdate = $conn->prepare("UPDATE ptb_student SET cert_status = 'true' WHERE student_id = :userID");
    }

    // กำหนดค่าพารามิเตอร์
    $sqlUpdate->bindParam(':userID', $userID, PDO::PARAM_INT);

    // เรียกใช้งานคำสั่ง SQL
    if ($sqlUpdate->execute()) {
        // ถ้าอัปเดตสำเร็จ ให้เปลี่ยนไปยังหน้าอื่น หรือแสดงข้อความสำเร็จ
        echo "Success";
        exit();
    } else {
        echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูล";
    }
} else {
    echo "ไม่พบข้อมูลผู้ใช้";
}
?>
