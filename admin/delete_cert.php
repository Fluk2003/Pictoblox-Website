<?php
session_start();

include '../sweet_alert.php';
require_once '../connectDB/configsdb.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Action</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php
    if (isset($_GET['teacher_id']) || isset($_GET['student_id'])) {
        $userID = isset($_GET['teacher_id']) ? $_GET['teacher_id'] : $_GET['student_id'];
        $type = isset($_GET['teacher_id']) ? 'teacher' : 'student';
        echo "
        <script>
            Swal.fire({
                title: 'ยืนยันการอัปเดต?',
                text: 'คุณต้องการอัปเดตสถานะใบประกาศใช่หรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ดำเนินการ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to PHP processing script
                    window.location.href = 'delete_cert_process.php?type=$type&id=$userID';
                } else {
                    // Redirect back to admin page
                    window.location.href = 'admin.php';
                }
            });
        </script>";
    } else {
        $_SESSION['error'] = "ไม่พบข้อมูลผู้ใช้งาน";
        echo "
        <script>
            Swal.fire({
                title: 'เกิดข้อผิดพลาด!',
                text: 'ไม่พบข้อมูลผู้ใช้งาน',
                icon: 'error',
                confirmButtonText: 'ตกลง'
            }).then(() => {
                window.location.href = 'admin.php';
            });
        </script>";
    }
    ?>
</body>
</html>
