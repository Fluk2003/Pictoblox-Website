<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Font Awesome for Eye Icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .text-purple {
            color: #4b0082;
            /* สีที่คุณต้องการ */
        }

        .form-label {
            color: #4b0082;
        }

        .form-check-label {
            color: #4b0082;
        }

        .text-primary {
            color: #4b0082;
        }
    </style>
</head>

<body>
    <div class="d-flex justify-content-center align-items-center" style="background-color:#4b0082 ; height: 100vh; background-repeat: no-repeat; background-size: cover; ">
        <div class="bg-white p-5 shadow rounded" style="max-width: 500px; width: 100%;">
            <h3 class="text-center mb-4 text-purple">เข้าสู่ระบบ</h3>
            <form action="login.php" method="POST">
                <!-- User Type Field -->
                <div class="mb-3">
                    <label class="form-label d-block">ประเภทผู้ใช้</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="userType" id="teacher" value="teacher" checked required>
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
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="กรอกรหัสผ่านของคุณ" required>
                        <button type="button" class="btn btn-outline-secondary" id="togglePassword" style="cursor: pointer;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Registration Link -->
                <p class="text-start">
                    หากยังไม่มีบัญชีสามารถ <a href="checkUser.php" style="color: #4b0082;">ลงทะเบียนได้ที่นี่</a><br>
                    <a href="../index.php" class="my-5" style="text-decoration: none; color: #4b0082 ;">กลับหน้าหลัก</a>
                </p>

                <!-- Submit Button -->
                <button style="background-color:#4b0082; border: none;" type="submit" name="registerSubmit" class="btn btn-primary w-100">เข้าสู่ระบบ</button>
            </form>
        </div>
    </div>

    <?php
    session_start(); // Ensure session starts at the very top of the file

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userType = $_POST["userType"];
        $userName = $_POST["userName"];
        $password = $_POST["password"];

        // Process login depending on userType
        if ($userType == "teacher") {
            if ($userName == "admin" && $password == "adminadminadmin") {
                $_SESSION["username"] = $userName;
                header("Location: ../admin/admin.php?username=" . $_SESSION["username"]);
                exit; // Terminate script after redirect
            } else {
                include 'login_process_teacher.php';
            }
        } elseif ($userType == "student") {
            include 'login_process_student.php';
        }
    }
    ?>


    <script>
        // JavaScript to toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            // Toggle the type of the password field
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;

            // Toggle the icon
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
    </script>
</body>

</html>