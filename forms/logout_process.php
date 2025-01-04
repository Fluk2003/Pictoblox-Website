<?php
include '../sweet_alert.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();

    // ตรวจสอบหากกดปุ่ม logout
    if (isset($_POST["logout"])) {

        echo
        "<script>
            Swal.fire({
                title: 'คุณต้องการออกจากระบบใช่ไหม?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ออกจากระบบ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    // ถ้าเลือก 'ใช่, ออกจากระบบ'
                    window.location.href = '../index.php'; 
                }else {
                    window.history.back(); ;
                }
            });
        </script>";
    }
}
