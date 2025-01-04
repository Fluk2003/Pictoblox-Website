<?php

include '../sweet_alert.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    session_start();
    require_once '../connectDB/configsdb.php';

    // echo $_GET["student_id  "];

    if (isset($_GET["student_id"])) {
        // echo "Hello Student";
        $_SESSION["student_id"] = $_GET["student_id"];
       // $_SESSION["teacher_id"] = $_GET["teacher_id"];
        $student_id = htmlspecialchars($_SESSION["student_id"]);
        // $teacher_id = htmlspecialchars($_SESSION["teacher_id"]);

        try {
            // fetchStudent student
            $sqlFetchStudent = $conn->prepare("SELECT * FROM ptb_student WHERE student_id = :student_id");
            $sqlFetchStudent->bindParam(":student_id", $student_id);
            $sqlFetchStudent->execute();
            $fetchStudent = $sqlFetchStudent->fetch(PDO::FETCH_ASSOC);


            // fetch School
            $sqlSchool = $conn->prepare("SELECT * FROM ptb_school");
            $sqlSchool->execute();
        } catch (PDOException $error) {
            echo "Have an Error " . $error->getMessage();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Logo title -->
    <link href="https://upload.wikimedia.org/wikipedia/th/3/38/LgUDRU.png" rel="icon">

    <!-- script -->
</head>

<body style="margin: 0; padding: 0; box-sizing: border-box; background-color:#f4f4f4;    ">
    <div class="" style=" height: 100%;">
        <div class="bg-white p-5 container mx-auto shadow" style="max-width: 800px; margin: 10px 0;">
            <div>
                <h3 class="text-center">แก้ไขข้อมูลส่วนตัว</h3>
            </div>
            <!-- ถ้าจะส่ง forms ที่มีการอัปโหลดรูปภาพต้องใช้ enctype="multipart/form-data" -->
            <form class="mt-5" action="student_edit_process.php" method="post" enctype="multipart/form-data">
                <div class="row">
                    <!-- คอลัมน์ซ้าย -->
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="firstName" class="form-label">ชื่อ</label>
                            <input type="text" class="form-control" id="firstName" name="firstName" required tabindex="1" placeholder="กรุณากรอกชื่อจริงของคุณ" value="<?php echo $fetchStudent["fname"]; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="lastName" class="form-label">นามสกุล</label>
                            <input type="text" class="form-control" id="lastName" name="lastName" required tabindex="2" placeholder="กรุณากรอกนามสกุลของคุณ" value="<?php echo $fetchStudent["lname"]; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="sex" class="form-label">เพศ</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="sex" id="male" value="male" required tabindex="3" <?php if ($fetchStudent["sex"] == 'male') echo 'checked'; ?>>
                                    <label class="form-check-label" for="male">ชาย</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="sex" id="female" value="female" required tabindex="3" <?php if ($fetchStudent["sex"] == 'female') echo 'checked'; ?>>
                                    <label class="form-check-label" for="female">หญิง</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="school_id" class="form-label">โรงเรียน</label>
                            <select class="form-select" aria-label="Default select example" name="school_id" required tabindex="4">
                                <?php
                                while ($dataFecthSchool = $sqlSchool->fetch(PDO::FETCH_ASSOC)) { ?>
                                    <option value="<?php echo $dataFecthSchool["school_id"]; ?>" <?php if ($dataFecthSchool["school_id"] == $fetchStudent["school_id"]) echo 'selected'; ?>>
                                        <?php echo $dataFecthSchool["school_name"]; ?>
                                    </option>
                                <?php }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="grade" class="form-label">ชั้นปีการศึกษา (มัธยมศึกษาปีที่1-6)</label>
                            <input type="text" class="form-control" id="grade" name="grade" required tabindex="5" placeholder="มัธยมศึกษาปีที่" value="<?php echo $fetchStudent["grade"]; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="major" class="form-label">สายการเรียน (เช่น วิทย์-คณิค)</label>
                            <input type="text" class="form-control" id="major" name="major" required tabindex="6" placeholder="กรุณากรอกสายการเรียนของคุณ" value="<?php echo $fetchStudent["major"]; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="facebook" class="form-label">Facebook</label>
                            <input type="text" class="form-control" id="facebook" name="facebook" required tabindex="7" placeholder="กรุณากรอก Facebook ของคุณ" value="<?php echo $fetchStudent["fb"]; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="line" class="form-label">Line</label>
                            <input type="text" class="form-control" id="line" name="line" required tabindex="8" placeholder="กรุณากรอก Line ของคุณ" value="<?php echo $fetchStudent["line"]; ?>">
                        </div>
                    </div>
                    <!-- คอลัมน์ขวา -->
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">อีเมลล์</label>
                            <input type="text" class="form-control" id="email" name="email" required tabindex="9" placeholder="กรุณากรอกอีเมลล์ของคุณ" value="<?php echo $fetchStudent["email"]; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">เบอร์โทร</label>
                            <input type="text" class="form-control" id="phone" name="phone" required tabindex="10" placeholder="กรุณากรอกเบอร์โทรศัพท์ของคุณ" value="<?php echo $fetchStudent["number"]; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="interest" class="form-label">ความสนใจ</label>
                            <textarea class="form-control" id="interest" name="interest" rows="4" required tabindex="11" placeholder="กรุณากรอกความสนใจของคุณ"><?php echo $fetchStudent["interest"]; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="userpic" class="form-label">รูปโปรไฟล์ของคุณ</label>
                            <input style="width: 100%;" class="form-control" type="file" accept="image/*" id="userpic" name="userpic" tabindex="12">
                        </div>
                        <div class="mb-3">
                            <label for="userName" class="form-label">ชื่อผู้ใช้</label>
                            <input type="text" class="form-control" id="userName" name="userName" required tabindex="13" placeholder="กรุณากรอกชื่อผู้ใช้ของคุณ(อย่างน้อย 6 ตัว)" value="<?php echo $fetchStudent["username"]; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">รหัสผ่าน</label>
                            <input type="text" class="form-control" id="password" name="password" required tabindex="14" placeholder="กรุณากรอกรหัสผ่านของคุณ" value="<?php echo $fetchStudent["password"]; ?>" >
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">รหัสผ่านอีกครั้ง</label>
                            <input type="text" class="form-control" id="confirm_password" name="confirm_password" required tabindex="15" placeholder="กรุณากรอกรหัสผ่านของคุณอีกครั้ง" value="<?php echo $fetchStudent["password"]; ?>">
                        </div>
                    </div>
                </div>
                <button style="width: 100%; margin-top: 15px;" type="submit" name="updateSubmit" class="btn btn-primary mx-auto mb-2">บันทึกการแก้ไข</button><br>
                <a href="user_data.php">ย้อนกลับ</a>
            </form>
        </div>
    </div>
</body>

</html>