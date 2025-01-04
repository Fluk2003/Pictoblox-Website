<?php


if ($_SERVER["REQUEST_METHOD"] == "GET") {

    session_start();
    require_once '../connectDB/configsdb.php';

    // echo $_GET["teacher_id"];

    if (isset($_GET["teacher_id"])) {
        // echo "Hello Student";
       // $_SESSION["student_id"] = $_GET["student_id"];
        $_SESSION["teacher_id"] = $_GET["teacher_id"];
        //$student_id = htmlspecialchars($_SESSION["student_id"]);
        $teacher_id = htmlspecialchars($_SESSION["teacher_id"]);

        try {
            // fetchTeacher student
            $sqlFetchTeacher = $conn->prepare("SELECT * FROM ptb_teacher WHERE teacher_id = :teacher_id");
            $sqlFetchTeacher->bindParam(":teacher_id", $teacher_id);
            $sqlFetchTeacher->execute();
            $fetchTeacher = $sqlFetchTeacher->fetch(PDO::FETCH_ASSOC);


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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        #togglePassword {
            border: 1px solid #ccc;
            /* สีเทา */
        }
    </style>

    <!-- script -->
</head>

<body style="margin: 0; padding: 0; box-sizing: border-box; background-color:rgb(214, 171, 221);    ">
    <div class="" style=" height: 100%;">
        <div class="bg-white p-5 container mx-auto shadow" style="max-width: 800px; margin: 10px 0;">
            <div>
                <h3 class="text-center" style="color:#4b0082;">แก้ไขข้อมูลส่วนตัว</h3>
            </div>
            <!-- ถ้าจะส่ง forms ที่มีการอัปโหลดรูปภาพต้องใช้ enctype="multipart/form-data" -->
            <form class="mt-5" action="teacher_edit_process.php" method="post" enctype="multipart/form-data">
                <div class="row">
                    <!-- คอลัมน์ซ้าย -->
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label style="color:#4b0082" for="firstName" class="form-label">ชื่อ</label>
                            <input style="color:#4b0082" type="text" class="form-control" id="firstName" name="firstName" required tabindex="1" placeholder="กรุณากรอกชื่อจริงของคุณ" value="<?php echo $fetchTeacher["fname"]; ?>">
                        </div>
                        <div class="mb-3">
                            <label style="color:#4b0082" for="lastName" class="form-label">นามสกุล</label>
                            <input style="color:#4b0082" type="text" class="form-control" id="lastName" name="lastName" required tabindex="2" placeholder="กรุณากรอกนามสกุลของคุณ" value="<?php echo $fetchTeacher["lname"]; ?>">
                        </div>
                        <div class="mb-3">
                            <label style="color:#4b0082" for="sex" class="form-label">เพศ</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input style="color:#4b0082" class="form-check-input" type="radio" name="sex" id="male" value="male" required tabindex="3" <?php if ($fetchTeacher["sex"] == 'male') echo 'checked'; ?>>
                                    <label style="color:#4b0082" class="form-check-label" for="male">ชาย</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input style="color:#4b0082" class="form-check-input" type="radio" name="sex" id="female" value="female" required tabindex="3" <?php if ($fetchTeacher["sex"] == 'female') echo 'checked'; ?>>
                                    <label style="color:#4b0082" class="form-check-label" for="female">หญิง</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label style="color:#4b0082" for="school_id" class="form-label">โรงเรียน</label>
                            <select style="color:#4b0082" class="form-select" aria-label="Default select example" name="school_id" required tabindex="4">
                                <?php
                                while ($dataFecthSchool = $sqlSchool->fetch(PDO::FETCH_ASSOC)) { ?>
                                    <option style="color:#4b0082" value="<?php echo $dataFecthSchool["school_id"]; ?>" <?php if ($dataFecthSchool["school_id"] == $fetchTeacher["school_id"]) echo 'selected'; ?>>
                                        <?php echo $dataFecthSchool["school_name"]; ?>
                                    </option>
                                <?php }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="email" style="color:#4b0082" class="form-label">อีเมลล์</label>
                            <input style="color:#4b0082" type="text" class="form-control" id="email" name="email" required tabindex="9" placeholder="กรุณากรอกอีเมลล์ของคุณ" value="<?php echo $fetchTeacher["email"]; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="subject" style="color:#4b0082" class="form-label">วิชาที่สอน</label>
                            <input type="text" style="color:#4b0082" class="form-control" id="subject" name="subject" required tabindex="10" placeholder="กรุณากรอกอีเมลล์ของคุณ" value="<?php echo $fetchTeacher["subject"]; ?>">
                        </div>
                    </div>
                    <!-- คอลัมน์ขวา -->
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label style="color:#4b0082" for="phone" class="form-label">เบอร์โทร</label>
                            <input style="color:#4b0082" type="text" class="form-control" id="phone" name="phone" required tabindex="11" placeholder="กรุณากรอกเบอร์โทรศัพท์ของคุณ" value="<?php echo $fetchTeacher["number"]; ?>">
                        </div>
                        <div class="mb-3">
                            <label style="color:#4b0082" for="userpic" class="form-label">รูปโปรไฟล์ของคุณ</label>
                            <input style="color:#4b0082" style="width: 100%;" class="form-control" type="file" accept="image/*" id="userpic" name="userpic" tabindex="12">
                        </div>
                        <div class="mb-3">
                            <label style="color:#4b0082" for="userName" class="form-label">ชื่อผู้ใช้</label>
                            <input style="color:#4b0082" type="text" class="form-control" id="userName" name="userName" required tabindex="13" placeholder="กรุณากรอกชื่อผู้ใช้ของคุณ(อย่างน้อย 6 ตัว)" value="<?php echo $fetchTeacher["username"]; ?>">
                        </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label" style="color:#4b0082">รหัสผ่าน</label>
                                <div class="input-group">
                                    <input style="color:#4b0082" type="password" class="form-control" id="password" name="password" placeholder="กรอกรหัสผ่านของคุณ" value="<?php echo $fetchTeacher["password"]; ?>"  required>
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword" style="cursor: pointer;">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label style="color:#4b0082" for="confirm_password" class="form-label">รหัสผ่านอีกครั้ง</label>
                                <input  style="color:#4b0082" type="password" class="form-control" id="confirmPassword" name="confirm_password" required tabindex="15" placeholder="กรุณากรอกรหัสผ่านของคุณอีกครั้ง" value="<?php echo $fetchTeacher["password"]; ?>">
                            </div>
                    </div>
                </div>
                <button style="width: 100%; margin-top: 15px; background-color: #4b0082; border: none;" type="submit" name="updateSubmit" class="btn btn-primary mx-auto mb-2">บันทึกการแก้ไข</button><br>
                <a href="../teacherProfile.php"  style="color:#4b0082">ย้อนกลับ</a>
            </form>
        </div>
    </div>
    <script>
        // JavaScript to toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');
        const confirmPasswordField = document.getElementById('confirmPassword'); // เพิ่มการอ้างอิงไปที่ฟิลด์ "ยืนยันรหัสผ่าน"

        togglePassword.addEventListener('click', function() {
            // Toggle the type of the password field
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            confirmPasswordField.type = type; // เพิ่มการเปลี่ยนประเภทให้กับฟิลด์ "ยืนยันรหัสผ่าน"

            // Toggle the icon
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
    </script>
</body>

</html>