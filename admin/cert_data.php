<?php
session_start();
require_once '../connectDB/configsdb.php';

// Fetch data from the database (teachers and students)
$sqlFetchData = $conn->prepare("
    SELECT 
        ptb_teacher.teacher_id AS userID, 
        ptb_teacher.fname, 
        ptb_teacher.lname, 
        ptb_teacher.username, 
        ptb_teacher.email, 
        ptb_teacher.number, 
        ptb_school.school_name,
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
        ptb_teacher.username != 'admin' AND ptb_teacher.cert_status = 'false'
    
    UNION ALL
    
    SELECT 
        ptb_student.student_id AS userID, 
        ptb_student.fname, 
        ptb_student.lname, 
        ptb_student.username, 
        ptb_student.email, 
        ptb_student.number, 
        ptb_school.school_name,
        ptb_student.certificate,
        ptb_student.cert_status,
        'นักเรียน' AS status
    FROM 
        ptb_student 
    JOIN 
        ptb_school 
    ON 
        ptb_student.school_id = ptb_school.school_id 
    WHERE 
        ptb_student.cert_status = 'false'
");

$sqlFetchData->execute();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลใบประกาศผู้เข้าอบรม</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://upload.wikimedia.org/wikipedia/th/3/38/LgUDRU.png" rel="icon">
    <style>
        .navbar-toggler-icon {
            background-image: url('data:image/svg+xml;charset=utf8,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30"%3E%3Cpath stroke="white" stroke-width="2" d="M4 7h22M4 15h22M4 23h22"/%3E%3C/svg%3E');
        }

        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: contain;
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
            <a class="navbar-brand text-white" href="#">หน้าหลักแอดมิน</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end">
                <a href="admin.php" class="btn btn-outline-light">กลับหน้าโปรไฟล์</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-6">
                <h4>เพิ่มใบประกาศผู้เข้าอบรม</h4>
            </div>
            <div class="col-6 text-end">
                <form class="d-flex" method="get" action="forms/search_process.php">
                    <input class="form-control me-2" type="search" name="search" placeholder="ค้นหา">
                    <button class="btn btn-primary" type="submit">ค้นหา</button>
                </form>
            </div>
        </div>
        <a href="check_confirm.php" class="btn btn-primary mb-3">เช็คประวัติข้อมูลการอนุมัติ</a>

        <form method="post" action="confirm_cert.php">
            <table class="table text-center">
                <thead class="table-dark">
                    <tr>

                        <th>ชื่อ</th>
                        <th>นามสกุล</th>
                        <th>โรงเรียน</th>
                        <th>สถานะ</th>
                        <th>เช็คใบประกาศ</th>
                        <th>เพิ่มใบประกาศ</th>
                        <th>ลบใบประกาศ</th>
                        <th>อนุมัติ</th>
                        <th>เลือกอนุมัติทั้งหมด <input type="checkbox" id="selectAll"></th>

                    </tr>
                </thead>
                <tbody>
                    <?php while ($fetchData = $sqlFetchData->fetch(PDO::FETCH_ASSOC)) { ?>
                        <tr>

                            <td><?php echo $fetchData['fname']; ?></td>
                            <td><?php echo $fetchData['lname']; ?></td>
                            <td><?php echo $fetchData['school_name']; ?></td>
                            <td><?php echo $fetchData['status']; ?></td>
                            <td>
                                <?php if (!empty($fetchData['certificate'])) {
                                    echo $fetchData['cert_status'] == 'false'
                                        ? '<p class="text-warning">รอการอนุมัติ</p>'
                                        : '<p class="text-success">อนุมัติเรียบร้อย</p>';
                                } else {
                                    echo '<p class="text-danger">ยังไม่ได้ใบประกาศ</p>';
                                } ?>
                            </td>
                            <td>
                                <a class="btn btn-primary" href="<?php echo $fetchData['status'] == 'ครู' ? 'add_cert.php?teacher_id=' . $fetchData['userID'] : 'add_cert.php?student_id=' . $fetchData['userID']; ?>">เพิ่มใบประกาศ</a>
                            </td>
                            <td>
                                <a class="btn btn-danger" href="<?php echo $fetchData['status'] == 'ครู' ? 'delete_cert.php?teacher_id=' . $fetchData['userID'] : 'delete_cert.php?student_id=' . $fetchData['userID']; ?>">ลบใบประกาศ</a>
                            </td>
                            <td>
                                <button type="submit"
                                    name="selectedIDs[]"
                                    value="<?php echo $fetchData['status'] == 'ครู' ? 'T' . $fetchData['userID'] : 'S' . $fetchData['userID']; ?>"
                                    class="btn btn-success">
                                    อนุมัติ
                                </button>

                            </td>
                            <td>
                                <?php if (!empty($fetchData['certificate']) == 'flse') { ?>
                                    <input type="checkbox" name="selectedIDs[]" value="<?php echo $fetchData['status'] == 'ครู' ? 'T' . $fetchData['userID'] : 'S' . $fetchData['userID']; ?>">
                                <?php } else { ?>
                                    <p>คุณยังไม่ได้เพิ่มใบประกาศ</p>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-success mt-3">อนุมัติทั้งหมด</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('selectAll').addEventListener('click', function(e) {
            const checkboxes = document.querySelectorAll('input[name="selectedIDs[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = e.target.checked);
        });
    </script>
</body>

</html>