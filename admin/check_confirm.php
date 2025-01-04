<?php
session_start();

require_once '../connectDB/configsdb.php';


$sqlFetchData = $conn->prepare("SELECT 
        ptb_teacher.teacher_id AS userID, 
        ptb_teacher.fname, 
        ptb_teacher.lname, 
        ptb_teacher.username, 
        ptb_teacher.email, 
        ptb_teacher.number, 
        ptb_school.school_name ,
        ptb_teacher.certificate,
        ptb_teacher.cert_status,
        'ครู' AS status
    FROM 
        ptb_teacher 
    JOIN 
        ptb_school 
    ON 
        ptb_teacher.school_id = ptb_school.school_id 
    WHERE 
        ptb_teacher.username != 'admin' AND ptb_teacher.cert_status = 'true'   
    UNION 
    SELECT 
        ptb_student.student_id AS userID, 
        ptb_student.fname, 
        ptb_student.lname, 
        ptb_student.username, 
        ptb_student.email, 
        ptb_student.number, 
        ptb_school.school_name ,
        ptb_student.certificate,
        ptb_student.cert_status,    
        'นักเรียน' AS status
    FROM 
        ptb_student
    JOIN 
        ptb_school 
    ON 
        ptb_student.school_id = ptb_school.school_id AND ptb_student.cert_status = 'true'   
    ");

$sqlFetchData->execute();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เช็คข้อมูลการอนุมัติผู้เข้าอบรม</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Logo title -->
    <link href="https://upload.wikimedia.org/wikipedia/th/3/38/LgUDRU.png" rel="icon">

    <style>
        .navbar-toggler-icon {
            background-image: url('data:image/svg+xml;charset=utf8,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30"%3E%3Cpath stroke="white" stroke-width="2" d="M4 7h22M4 15h22M4 23h22"/%3E%3C/svg%3E');
        }

        .card-img-top {
            width: 100%;
            /* ทำให้รูปภาพขยายเต็มความกว้างของการ์ด */
            height: 200px;
            /* กำหนดความสูงแบบ fixed */
            object-fit: contain;
            /* ตัดส่วนเกินของรูปภาพให้พอดีกับขนาด */
        }

        td {
            border-right: 1px solid #cfe2ff;
            border-left: 1px solid #cfe2ff;
            font-weight: normal;
        }

        td>a {
            text-decoration: none;
            color: black;
        }
    </style>

</head>

<body>
    <nav class="navbar navbar-expand-lg" style="background-color: #6a0dad;">
        <div class="container">
            <a class="navbar-brand text-white" style="font-size: 1.5em;" href="#">หน้าหลักแอดมิน</a>
            <button class="navbar-toggler" style="color: white; border: 1px solid white; opacity: 0.7;" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end mt-1 " id="navbarScroll">
                <div class="d-flex mt-2 mt-lg-0">
                    <a href="cert_data.php" class="btn btn-outline-light" name="logout">กลับหน้าเพิ่มใบประกาศ</a>
                </div>
            </div>
        </div>
    </nav>

    <div>
        <div class="container mx-auto " style="margin-top: 50px;">
            <div class="container ">
                <div class="row align-items-center">
                    <div class="col-12 col-md-6">
                        <h4 class="mt-4">เช็คข้อมูลการอนุมัติผู้เข้าอบรม</h4>
                    </div>
                    <div class="col-12 col-md-6">
                        <form class="d-flex mb-2 mb-lg-0 mt-4 w-100" method="get" action="forms/search_process.php">
                            <input class="form-control me-2 w-100" style="min-width: 200px; max-width: 500px;" type="search" name="search" placeholder="ค้นหา" aria-label="Search">
                            <button class="btn btn-primary" style="background-color: #6a0dad;" type="submit">ค้นหา</button>
                        </form>
                    </div>
                </div>
                <hr>
            </div>
            <div class="table-responsive">
                <table class="table text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ชื่อ</th>
                            <th>นามสกุล</th>
                            <th>โรงเรียน</th>
                            <th>เช็คใบประกาศ</th>
                            <th>แก้ไขประกาศ</th>
                        </tr>
                    </thead>
                    <tbody class="">
                        <?php
                        while ($fetchData = $sqlFetchData->fetch(PDO::FETCH_ASSOC)) { ?>
                            <tr>
                                <td><?php echo $fetchData["fname"]; ?></td>
                                <td><?php echo $fetchData["lname"]; ?></td>
                                <td><?php echo $fetchData["school_name"]; ?></td>
                                <td>
                                    <?php

                                    // path of certificate localhost
                                    $dir = "../uploads/";


                                    // path of certificate server
                                    // $dir = "../../forms/uploads/";

                                    // echo $fetchData["uploadFile"]; 
                                    if (!empty($fetchData["certificate"])) {
                                        if ($fetchData["cert_status"] == "true") { ?>
                                            <p class="text-success">อนุมัติเรียบร้อย</ย>
                                            <?php } else { ?>

                                        <?php }
                                    } ?>
                                </td>
                                <td>
                                    <?php
                                    if ($fetchData["status"] == 'ครู') { ?>
                                        <a href="add_cert.php?teacher_id=<?php echo $fetchData["userID"]; ?>" class="btn btn-warning">แก้ไขใบประกาศ</a>
                                    <?php } elseif($fetchData["status"] == 'นักเรียน') { ?>
                                        <a href="add_cert.php?student_id=<?php echo $fetchData["userID"]; ?>" class="btn btn-warning">แก้ไขใบประกาศ</a>
                                    <?php }
                                    ?>
                                </td>
                            </tr>
                        <?php }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>