<?php require_once '../connectDB/configsdb.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แอดมิน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <center>
        <h2 class="m-5"><span><a href="admin.php">รายชื่อครู</a></span> &nbsp;&nbsp;รายชื่อนักเรียน</h2>
    </center>
    <table class="table text-center">
        <thead class="">
            <tr>
                <th scope="col">ชื่อ</th>
                <th scope="col">นามสกุล</th>
                <th scope="col">ชื่อผู้ใช้</th>
                <th scope="col">รหัสผ่าน</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sqlFetchAllTeacher = "SELECT * FROM ptb_student";
            $queryFetchAllTeacher = $conn->prepare($sqlFetchAllTeacher);
            $queryFetchAllTeacher->execute();

            while ($teacherData = $queryFetchAllTeacher->fetch(PDO::FETCH_ASSOC)) { ?>

                <?php
                if ($teacherData["fname"] != "admin" && $teacherData["lname"] != "adminadminadmin") { ?>
                    <tr>
                        <th scope="row"><?php echo $teacherData["fname"]; ?></th>
                        <td><?php echo $teacherData["lname"]; ?></td>
                        <td><?php echo $teacherData["username"]; ?></td>
                        <td><?php echo $teacherData["password"]; ?></td>
                    </tr>
                <?php }
                ?>
            <?php }
            ?>
        </tbody>
    </table>
</body>

</html>