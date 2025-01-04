<?php

require_once '../connectDB/configsdb.php';

$sqlSchool = $conn->prepare("SELECT * FROM ptb_school");
$sqlSchool->execute();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
                <h3 class="text-center" style="color:#4b0082">สมัครบัญชี</h3>
            </div>
            <!-- ถ้าจะส่ง forms ที่มีการอัปโหลดรูปภาพต้องใช้ enctype="multipart/form-data" -->
            <form class="mt-5" onsubmit="onSubmit()" action="register_process_teacher.php" method="post"
                enctype="multipart/form-data">
                <div class="row">
                    <!-- คอลัมน์ซ้าย -->
                    <div class="col-12 col-md-6">

                        <div class="mb-3">
                            <label for="exampleInputEmail1"  style="color:#4b0082" class="form-label">ชื่อ</label>
                            <input style="color:#4b0082" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                                name="firstName" required tabindex="1" placeholder="กรุณากรอกชื่อจริงของคุณ">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" style="color:#4b0082" class="form-label">นามสกุล</label>
                            <input style="color:#4b0082" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                                name="lastName" required tabindex="2" placeholder="กรุณากรอกนามสกุลของคุณ">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" style="color:#4b0082" class="form-label">อีเมลล์</label>
                            <input style="color:#4b0082" type="text" class="form-control" id="exampleInputPassword1" name="email" required
                                tabindex="3" placeholder="กรุณากรอกอีเมลล์ของคุณ">
                        </div>
                        <div class="mb-3">
                            <label style="color:#4b0082"  style="color:#4b0082" for="InputSex">เพศ</label>
                            <div>
                                <!-- Male Radio Button -->
                                <div class="form-check form-check-inline">
                                    <input style="color:#4b0082" class="form-check-input" type="radio" name="sex" id="male" value="male" required tabindex="4">
                                    <label class="form-check-label" style="color:#4b0082" for="male">ชาย</label>
                                </div>
                                <!-- Female Radio Button -->
                                <div class="form-check form-check-inline">
                                    <input style="color:#4b0082" class="form-check-input" type="radio" name="sex" id="female" value="female" required tabindex="4">
                                    <label class="form-check-label" style="color:#4b0082" for="female">หญิง</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label style="color:#4b0082" for="exampleInputPassword1" class="form-label" style="color:#4b0082">โรงเรียน</label>
                            <select style="color:#4b0082" class="form-select" aria-label="Default select example" name="school_id" required
                                tabindex="5">
                                <?php
                                while ($dataFecthSchool = $sqlSchool->fetch(PDO::FETCH_ASSOC)) { ?>
                                    <option style="color:#4b0082" value="<?php echo $dataFecthSchool["school_id"]; ?>">
                                        <?php echo $dataFecthSchool["school_name"] ?>
                                    </option>
                                <?php }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label" style="color:#4b0082">วิชาที่สอน</label>
                            <input style="color:#4b0082" type="text" class="form-control" id="exampleInputPassword1" name="subject" required
                                tabindex="6" placeholder="กรุณากรอกวิชาที่คุณสอน">
                        </div>
                    </div>
                    <!-- คอลัมน์ขวา -->
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label" style="color:#4b0082">เบอร์โทร</label>
                            <input style="color:#4b0082" type="text" class="form-control" id="exampleInputPassword1" name="number" required
                                tabindex="7" placeholder="กรุณากรอกเบอร์โทรศัพท์ของคุณ">
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label" style="color:#4b0082">รูปโปรไฟล์ของคุณ</label>
                            <input style="width: 100%; color:#4b0082" class="form-control" type="file" accept="image/*" id="formFile"
                                name="userpic" required tabindex="8">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label" style="color:#4b0082">ชื่อผู้ใช้</label>
                            <input style="color:#4b0082" type="text" class="form-control" id="exampleInputPassword1" name="userName" required
                                tabindex="9" placeholder="กรุณากรอกชื่อผู้ใช้ของคุณ(อย่างน้อย 6 ตัว)">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label" style="color:#4b0082">รหัสผ่าน</label>
                            <div class="input-group">
                                <input style="color:#4b0082" type="password" class="form-control" id="password" name="password" placeholder="กรอกรหัสผ่านของคุณ" required>
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword" style="cursor: pointer;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label style="color:#4b0082" for="exampleInputPassword1" class="form-label" style="color:#4b0082">รหัสผ่านอีกครั้ง</label>
                            <input style="color:#4b0082" type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="กรอกรหัสผ่านของคุณอีกครั้ง" required>
                        </div>
                    </div>
                </div>

                <button style="width: 100%; margin-top: 15px; background-color: #4b0082; border: none;" type="submit" name="registerSubmit"
                    class="btn btn-primary  mx-auto mb-2 ">สมัครบัญชี</button><br>
                <a href="login.php"  style="color:#4b0082">ย้อนกลับ</a>
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