<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../sweet_alert.php';
require '../connectDB/configsdb.php';

// Check if either teacher_id or student_id is provided
if (!isset($_GET['teacher_id']) && !isset($_GET['student_id'])) {
    die("Teacher ID or Student ID is required.");
}

$teacher_id = $_GET['teacher_id'] ?? null;
$student_id = $_GET['student_id'] ?? null;


try {
    // Fetch team data based on teacher_id or student_id
    if ($teacher_id) {
        $stmt = $conn->prepare("
           SELECT t.team_id, t.team_name, t.project_name, t.detail, t.file, sc.school_name,
                GROUP_CONCAT(DISTINCT CONCAT(pt.fname, ' ', pt.lname) ORDER BY ttt.id SEPARATOR ',  ') AS teachers,
                GROUP_CONCAT(DISTINCT CONCAT(ps.fname, ' ', ps.lname) ORDER BY ps.student_id SEPARATOR ', ') AS students
            FROM ptb_team t
            LEFT JOIN ptb_team_teacher ttt ON ttt.team_id = t.team_id
            LEFT JOIN ptb_teacher pt ON pt.teacher_id = ttt.teacher_id
            LEFT JOIN ptb_student ps ON ps.team_id = t.team_id
            LEFT JOIN ptb_school sc ON sc.school_id = pt.school_id
            WHERE ttt.team_id IN (
                SELECT team_id
                FROM ptb_team_teacher
                WHERE teacher_id = :teacher_id
            ) 
            GROUP BY t.team_id, t.team_name, t.project_name, t.detail, t.file, sc.school_name
            ORDER BY t.team_name ASC


        ");
        $stmt->bindParam(':teacher_id', $teacher_id, PDO::PARAM_INT);
        $stmt->execute();
        $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif ($student_id) {
        $stmt = $conn->prepare("
           SELECT t.team_id, t.team_name, t.project_name, t.detail, t.file, sc.school_name,
                GROUP_CONCAT(DISTINCT CONCAT(pt.fname, ' ', pt.lname) ORDER BY ttt.id SEPARATOR ',  ') AS teachers,
                GROUP_CONCAT(DISTINCT CONCAT(ps.fname, ' ', ps.lname) ORDER BY ps.student_id SEPARATOR ', ') AS students
            FROM ptb_team t
            LEFT JOIN ptb_team_teacher ttt ON ttt.team_id = t.team_id
            LEFT JOIN ptb_teacher pt ON pt.teacher_id = ttt.teacher_id
            LEFT JOIN ptb_student ps ON ps.team_id = t.team_id
            LEFT JOIN ptb_school sc ON sc.school_id = pt.school_id
            WHERE t.team_id IN (
                SELECT team_id FROM ptb_student WHERE student_id = :student_id
            )
            GROUP BY t.team_id, t.team_name, t.project_name, t.detail, t.file, sc.school_name
            ORDER BY t.team_name ASC
        ");
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->execute();
        $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    $errorMessage = "Error fetching team data: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">จัดการทีม</h1>

        <?php if (isset($errorMessage)): ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '<?php echo htmlspecialchars($errorMessage); ?>',
                });
            </script>
        <?php endif; ?>

        <?php if (!empty($teams)): ?>
            <table class="table table-bordered table-striped mt-4">
                <thead class="thead-dark text-center">
                    <tr>
                        <th>ชื่อทีม</th>
                        <th>ชื่อโครงงาน</th>
                        <th>รายละเอียด</th>
                        <th>รายชื่อคุณครู</th>
                        <th>รายชื่อนักเรียน</th>
                        <th>โรงเรียน</th>
                        <th>ไฟล์งาน</th>
                        <th>ปุ่มต่างๆ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($teams as $team): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($team['team_name']); ?></td>
                            <td><?php echo htmlspecialchars($team['project_name']); ?></td>
                            <td><?php echo htmlspecialchars($team['detail']); ?></td>
                            <td>
                                <?php
                                // แยกชื่อคุณครูและแสดงทีละบรรทัด
                                $teachers = explode(', ', $team['teachers']); // แยกชื่อคุณครูด้วยเครื่องหมายคอมมา
                                $j = 0;
                                foreach ($teachers as $teacher) {
                                    $j++;
                                    echo "<div>$j) " . htmlspecialchars($teacher) . "</div>";
                                }
                                ?>
                            </td>

                            <td>
                                <?php
                                // Fetch the students for the current team
                                $stmt = $conn->prepare("SELECT fname, lname, sex , grade , major FROM ptb_student ps WHERE ps.team_id = :team_id");
                                $stmt->bindParam(':team_id', $team['team_id'], PDO::PARAM_INT);
                                $stmt->execute();
                                $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                $i = 0;
                                // Loop through the students and display their names with the correct title based on gender
                                foreach ($students as $student) {
                                    // Check the student's sex and assign the correct title
                                    if ($student['sex'] == 'male') {
                                        $title = 'นาย'; // Male prefix
                                    } elseif ($student['sex'] == 'female') {
                                        $title = 'นางสาว'; // Female prefix
                                    } else {
                                        $title = ''; // No title if sex is unknown
                                    }
                                    $i++;

                                    // Display the student name with the title
                                    echo "<div>$i) $title {$student['fname']} {$student['lname']} {$student['grade']} สาย{$student['major']}</div>";
                                }
                                ?>
                            </td>


                            <td><?php echo htmlspecialchars($team['school_name']); ?></td>
                            <td>
                                <?php $dir = '../../forms/uploads/' ?>
                                <center><a href="<?php echo $dir . $team["file"]; ?>" class="btn btn-dark" target="_blank">ดูไฟล์</a></center>
                            </td>
                            <td>
                                <?php if ($teacher_id): ?>
                                    <center>
                                        <a href="edit_team.php?team_id=<?php echo $team['team_id']; ?>&teacher_id=<?php echo $teacher_id; ?>" class="btn btn-warning btn-sm m-2">แก้ไข</a>

                                        <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $team['team_id']; ?>, <?php echo $teacher_id; ?>)">
                                            ลบ
                                        </button>
                                    </center>
                                <?php elseif ($student_id): ?>
                                    <span class="text-muted">Cannot edit or delete</span>
                                <?php else: ?>
                                    <span class="text-muted">Cannot edit or delete</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <center><a class="btn btn-success" href="../teacherProfile.php">กลับสู่หน้าโปรไฟล์</a></center>
        <?php else: ?>
            <p>คุณยังไม่มีทีม</p>
        <?php endif; ?>
    </div>

    <script>
        function confirmDelete(team_id, teacher_id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This team will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'delete_team_process.php?team_id=' + team_id + '&teacher_id=' + teacher_id;
                }
            });
        }

        const backPage = () => {
            // Assuming you have the teacher_id stored in a JavaScript variable or accessible from the server
            const teacherId = <?php echo $teacher_id; ?>; // Get the teacher_id from PHP to JavaScript

            // Redirect to teacherProfile.php with the teacher_id
            window.location.href = 'teacherProfile.php?teacher_id=' + teacherId;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>