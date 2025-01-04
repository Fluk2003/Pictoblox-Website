<?php
session_start();
require_once '../connectDB/configsdb.php';

// ตรวจสอบว่าใช้งานโดยผู้ใช้ที่ล็อกอินหรือไม่
if (!isset($_SESSION["username"])) {
    header('location:../index.php');
    exit();
}

// ตรวจสอบว่าได้ส่งค่า ID ของผู้ใช้ที่ต้องการลบหรือไม่
if (isset($_GET['teacher_id'])) {
    $userID = $_GET['teacher_id'];
    $status = 'teacher';  // ผู้ใช้ประเภทครู
} elseif (isset($_GET['student_id'])) {
    $userID = $_GET['student_id'];
    $status = 'student';  // ผู้ใช้ประเภทนักเรียน
} else {
    header('location: admin_dashboard.php');  // ถ้าไม่มี ID ส่งมาให้กลับหน้าแดชบอร์ด
    exit();
}

// รับข้อมูลผู้ใช้จากฐานข้อมูล
if ($status == 'teacher') {
    $sql = $conn->prepare("SELECT fname, lname FROM ptb_teacher WHERE teacher_id = :userID");
} else {
    $sql = $conn->prepare("SELECT fname, lname FROM ptb_student WHERE student_id = :userID");
}
$sql->bindParam(':userID', $userID);
$sql->execute();
$user = $sql->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // ถ้าหากไม่พบผู้ใช้ที่มี ID นี้ในฐานข้อมูล
    echo "ไม่พบผู้ใช้ที่ต้องการลบ";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยืนยันการลบข้อมูล</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-lg" style="background-color: #6a0dad;">
        <div class="container">
            <a class="navbar-brand text-white" style="font-size: 1.5em;" href="#">หน้าหลักแอดมิน</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h4>ยืนยันการลบข้อมูลของ <?php echo $user['fname'] . ' ' . $user['lname']; ?>?</h4>
        <p>โปรดยืนยันว่าคุณต้องการลบข้อมูลผู้ใช้คนนี้</p>
        
        <form method="POST" action="delete_user_process.php">
            <input type="hidden" name="userID" value="<?php echo $userID; ?>">
            <input type="hidden" name="status" value="<?php echo $status; ?>">

            <div class="d-flex justify-content-between">
                <a href="user_data.php" class="btn btn-secondary">ยกเลิก</a>
                <button type="submit" class="btn btn-danger">ลบข้อมูล</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
