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
</head>

<body>
    <div style="background-color:#4b0082; overflow: hidden; height: 100vh;">
        <div class="bg-white p-5 container mx-auto shadow"
            style="max-width: 500px; margin-top: 50px; border-radius: 4px;">
            <div>
                <h3 class="text-center">สถานะการสมัคร</h3>
            </div>
            <div class="d-flex flex-column flex-sm-row justify-content-around mt-4">
                <a href="register_teacher.php" class="btn btn-primary mb-2 mb-sm-0" style="width: 100%; max-width: 200px; border-radius: 4px; background-color:rgb(174, 17, 199); border: none;">ครู</a>
                <a href="register_student.php" class="btn btn-success" style="width: 100%; max-width: 200px; border-radius: 2px; background-color:rgb(120, 7, 200); border: none;">นักเรียน</a>
            </div>
            <div class="mt-5 text-center">
                <a href="login.php">ย้อนกลับ</a>
            </div>
        </div>
    </div>
</body>

</html>
