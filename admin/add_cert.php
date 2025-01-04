<?php
session_start();
require_once '../connectDB/configsdb.php';

// Check if userID is passed as teacher_id or student_id
if (isset($_GET["teacher_id"])) {
    $_SESSION["userID"] = $_GET["teacher_id"];
    $userID = $_SESSION["userID"];
    $userType = "teacher";
} elseif (isset($_GET["student_id"])) {
    $_SESSION["userID"] = $_GET["student_id"];
    $userID = $_SESSION["userID"];
    $userType = "student";
} else {
    // If no userID is passed, redirect or handle the error
    echo "Invalid user ID.";
    exit;
}

try {
    // Modify the SQL query to fetch either teacher or student data
    if ($userType == "teacher") {
        $sqlFetchData = $conn->prepare("SELECT * FROM ptb_teacher JOIN ptb_school ON ptb_teacher.school_id = ptb_school.school_id WHERE ptb_teacher.teacher_id = :userID");
    } else {
        $sqlFetchData = $conn->prepare("SELECT * FROM ptb_student JOIN ptb_school ON ptb_student.school_id = ptb_school.school_id WHERE ptb_student.student_id = :userID");
    }

    $sqlFetchData->bindParam(":userID", $userID);
    $sqlFetchData->execute();

    $fetchData = $sqlFetchData->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $error) {
    echo "Error: " . $error->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่ม/แก้ไขข้อมูลใบประกาศ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://upload.wikimedia.org/wikipedia/th/3/38/LgUDRU.png" rel="icon">
</head>

<body>
    <nav class="navbar navbar-expand-lg" style="background-color: #6a0dad;">
        <div class="container">
            <a class="navbar-brand text-white" style="font-size: 1.5em;" href="#">มหาวิทยาลัยราชภัฏอุดรธานี</a>
            <button class="navbar-toggler" style="color: white; border: 1px solid white; opacity: 0.7;" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarScroll">
                <a href="cert_data.php" class="btn btn-outline-light">กลับหน้าเพิ่มใบประกาศ</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-5">
        <h4>เพิ่ม/แก้ไขข้อมูลใบประกาศของคุณ <?php echo $fetchData["fname"] . " " . $fetchData["lname"]; ?></h4>
        <hr>
        <form action="add_cert_process.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="status" value="<?php echo $userType ?>">
            <div class="mb-3">
                <label for="certFile" class="form-label">ชื่อ</label>
                <input class="form-control" type="text" name="certFile" value="<?php echo $fetchData["fname"] . " " . $fetchData["lname"]; ?>" disabled>
            </div>

            <div class="mb-3">
                <label for="schoolName" class="form-label">โรงเรียน</label>
                <input class="form-control" type="text" name="schoolName" value="<?php echo $fetchData["school_name"]; ?>" disabled>
            </div>

            <div class="mb-3">
                <label for="certificate" class="form-label">ใบประกาศ (ไฟล์ PDF)</label>
                <input class="form-control" accept=".pdf" type="file" name="certificate" id="certificate">
            </div>

            <button type="submit" class="btn btn-primary" style="background-color: #4b0082 !important;">บันทึกข้อมูล</button>
        </form>
    </div>
</body>

</html>