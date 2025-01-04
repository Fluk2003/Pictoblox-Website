<?php
include '../sweet_alert.php';  // ใส่การรวมไฟล์ sweet_alert.php เพื่อให้สามารถใช้ SweetAlert ได้

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    require_once '../databaseConfigs/configsdb.php';

    if (isset($_POST["submitUploadLink"])) {
        $uploadLink1 = htmlspecialchars($_POST["uploadLink1"]);
        $uploadLink2 = htmlspecialchars($_POST["uploadLink2"]);
        $userNameSession = $_SESSION["userName"];

        try {
            $sqlUploadLink = $conn->prepare("UPDATE users3 SET uploadLink1= :uploadLink1 , uploadLink2 = :uploadLink2 WHERE userName = :userName");
            $sqlUploadLink->bindParam(":uploadLink1", $uploadLink1);
            $sqlUploadLink->bindParam(":uploadLink2", $uploadLink2);
            $sqlUploadLink->bindParam(":userName", $userNameSession);
            $sqlUploadLink->execute();

            if ($sqlUploadLink->rowCount() > 0) {
                echo 
                "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'เพิ่มงานเรียบร้อยครับ',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = '../userProfile.php'; // เปลี่ยนหน้าเมื่ออัปเดตสำเร็จ
                    });
                </script>";
            } else {
                echo 
                "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'เพิ่มงานล้มเหลว',
                        text: 'ไม่สามารถเพิ่มงานได้ กรุณาลองใหม่อีกครั้ง',
                    }).then(() => {
                        window.history.back();
                    });
                </script>";
            }
        
        } catch (PDOException $err) {
            echo 
            "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'มีข้อผิดพลาดในการเชื่อมต่อกับฐานข้อมูล',
                }).then(() => {
                    window.history.back();
                });
            </script>";
        }
    }
}
?>
