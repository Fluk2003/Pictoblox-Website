<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="d-flex justify-content-center align-items-center" style="background-color: #f4f4f4; height: 100vh;">
        <div class="bg-white p-5 shadow rounded" style="max-width: 500px; width: 100%;">
            <h3 class="text-center mb-4">เข้าสู่ระบบ</h3>
            <form action="login.php" method="POST">
                <!-- User Type Field -->
                <div class="mb-3">
                    <label class="form-label d-block">ประเภทผู้ใช้</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="userType" id="teacher" value="teacher" required>
                        <label class="form-check-label" for="teacher">ครู</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="userType" id="student" value="student" required>
                        <label class="form-check-label" for="student">นักเรียน</label>
                    </div>
                </div>

                <!-- Username Field -->
                <div class="mb-3">
                    <label for="username" class="form-label">ชื่อผู้ใช้</label>
                    <input type="text" class="form-control" id="username" name="userName" placeholder="กรอกชื่อผู้ใช้ของคุณ" required>
                </div>

                <!-- Password Field -->
                <div class="mb-3">
                    <label for="password" class="form-label">รหัสผ่าน</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="กรอกรหัสผ่านของคุณ" required>
                </div>

                <!-- Registration Link -->
                <p class="text-start">
                    หากยังไม่มีบัญชีสามารถ <a href="checkUser.php" class="text-primary">ลงทะเบียนได้ที่นี่</a><br>
                    <a href="../index.php" class="text-primary my-5" style="text-decoration: none;" >กลับหน้าหลัก</a>
                </p>
                

                <!-- Submit Button -->
                <button type="submit" name="registerSubmit" class="btn btn-primary w-100">เข้าสู่ระบบ</button>
            </form>
        </div>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userType = $_POST["userType"];
        $userName = $_POST["userName"];
        $password = $_POST["password"];

        // Process login depending on userType
        if ($userType == "teacher") {

            if($userName == "admin" && $password == "adminadminadmin" ) {
                header("location: ../admin/admin.php");
            }else {
                include 'login_process_teacher.php';
            }

        } else if ($userType == "student") {
            include 'login_process_student.php';
        }
    }
    ?>
</body>

</html>
