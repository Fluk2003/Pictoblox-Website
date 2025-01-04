<?php
session_start();
require_once '../connectDB/configsdb.php';

if (isset($_GET['type']) && isset($_GET['id'])) {
    try {
        $userID = $_GET['id'];
        if ($_GET['type'] === 'teacher') {
            $query = "SELECT certificate FROM ptb_teacher WHERE teacher_id = :id";
            $updateQuery = "UPDATE ptb_teacher SET certificate = NULL WHERE teacher_id = :id";
        } else if ($_GET['type'] === 'student') {
            $query = "SELECT certificate FROM ptb_student WHERE student_id = :id";
            $updateQuery = "UPDATE ptb_student SET certificate = NULL WHERE student_id = :id";
        }

        // ดึงข้อมูลใบประกาศ
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $userID);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && !empty($result['certificate'])) {
            $filePath = "../uploads/certificates/" . $result['certificate'];

            // ลบไฟล์ในโฟลเดอร์หากมีอยู่
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // อัปเดต cert_status ให้เป็น NULL
        $stmtUpdate = $conn->prepare($updateQuery);
        $stmtUpdate->bindParam(':id', $userID);
        $stmtUpdate->execute();

        $_SESSION['success'] = "อัปเดตสถานะใบประกาศสำเร็จ";
    } catch (Exception $e) {
        $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
    }
} else {
    $_SESSION['error'] = "ไม่พบข้อมูลผู้ใช้งาน";
}

header("Location: cert_data.php");
exit;
?>
