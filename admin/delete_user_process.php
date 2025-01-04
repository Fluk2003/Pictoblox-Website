<?php
session_start();
require_once '../connectDB/configsdb.php';

// ตรวจสอบการยืนยันการลบ
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['userID']) && isset($_POST['status'])) {
        $userID = $_POST['userID'];
        $status = $_POST['status'];

        try {
            if ($status == 'teacher') {
                $sql = $conn->prepare("DELETE FROM ptb_teacher WHERE teacher_id = :userID");
            } else {
                $sql = $conn->prepare("DELETE FROM ptb_student WHERE student_id = :userID");
            }

            $sql->bindParam(':userID', $userID);
            $sql->execute();

            // หลังจากลบเสร็จให้กลับไปที่หน้าผู้ใช้งานทั้งหมด
            header('Location:user_data.php');
            exit();

        } catch (PDOException $e) {
            echo "เกิดข้อผิดพลาดในการลบข้อมูล: " . $e->getMessage();
        }
    }
} else {
    header('Location: admin_dashboard.php');
    exit();
}
