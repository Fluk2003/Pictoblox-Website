<?php
// Start session and include database connection
session_start();
require_once '../connectDB/configsdb.php';
include '../sweet_alert.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['selectedIDs']) && is_array($_POST['selectedIDs'])) {
        $selectedIDs = $_POST['selectedIDs'];

        try {
            // Begin a transaction
            $conn->beginTransaction();

            // Prepare SQL statement for updating teacher certificates
            $stmtTeacher = $conn->prepare("UPDATE ptb_teacher SET cert_status = 'true' WHERE teacher_id = :id");

            // Prepare SQL statement for updating student certificates
            $stmtStudent = $conn->prepare("UPDATE ptb_student SET cert_status = 'true' WHERE student_id = :id");

            foreach ($selectedIDs as $id) {
                // Determine whether the ID belongs to a teacher or student based on prefix (e.g., 'T' for teacher, 'S' for student)
                if (strpos($id, 'T') === 0) {
                    $stmtTeacher->execute(['id' => substr($id, 1)]);
                } elseif (strpos($id, 'S') === 0) {
                    $stmtStudent->execute(['id' => substr($id, 1)]);
                }
            }

            // Commit the transaction
            $conn->commit();

            // Display success message using SweetAlert
            echo "<script>
                Swal.fire({
                    title: 'สำเร็จ!',
                    text: 'อัปเดตสถานะใบประกาศสำเร็จ',
                    icon: 'success',
                    confirmButtonText: 'ตกลง'
                }).then(() => {
                    window.location.href = 'cert_data.php';
                });
            </script>";
            exit();

        } catch (Exception $e) {
            // Rollback the transaction in case of error
            $conn->rollBack();

            // Display error message using SweetAlert
            echo "<script>
                Swal.fire({
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'ไม่สามารถอัปเดตข้อมูลได้: " . $e->getMessage() . "',
                    icon: 'error',
                    confirmButtonText: 'ตกลง'
                });
            </script>";
        }
    } else {
        // Display error message if no IDs were selected
        echo "<script>
            Swal.fire({
                title: 'เกิดข้อผิดพลาด!',
                text: 'กรุณาเลือกข้อมูลที่ต้องการอัปเดต',
                icon: 'warning',
                confirmButtonText: 'ตกลง'
            }).then(() => {
                window.location.href = '../cert_data.php';
            });
        </script>";
    }
} else {
    // Redirect if accessed directly
    header('Location: ../cert_data.php');
    exit();
}
?>
