<?php
session_start();
require_once '../connectDB/configsdb.php';

// Fetch the logged-in teacher's details
if (!isset($_GET['teacher_id'])) {
    die("Teacher ID not provided.");
}

$teach_id = $_GET['teacher_id'];
try {
    // Fetch logged-in teacher details and associated school details
    $sqlFetchTeacherDetails = $conn->prepare("
        SELECT ptb_teacher.*, ptb_school.school_name 
        FROM ptb_teacher 
        JOIN ptb_school ON ptb_teacher.school_id = ptb_school.school_id 
        WHERE ptb_teacher.teacher_id = :teach_id
    ");
    $sqlFetchTeacherDetails->bindParam(':teach_id', $teach_id);
    $sqlFetchTeacherDetails->execute();
    $teacherDetails = $sqlFetchTeacherDetails->fetch(PDO::FETCH_ASSOC);

    $_SESSION["school_id"] = $teacherDetails["school_id"];

    if (!$teacherDetails) {
        die("No teacher details found for the given ID.");
    }

    // Fetch all teachers in the same school, excluding the logged-in teacher
    $sqlFetchTeachers = $conn->prepare("
        SELECT * FROM ptb_teacher 
        WHERE school_id = :school_id AND teacher_id != :teach_id 
    ");
    $sqlFetchTeachers->bindParam(':school_id', $teacherDetails['school_id']);
    $sqlFetchTeachers->bindParam(':teach_id', $teach_id);
    $sqlFetchTeachers->execute();
    $teachers = $sqlFetchTeachers->fetchAll(PDO::FETCH_ASSOC);

    // Fetch team_id
    $sqlTeamID = "SELECT team_id FROM ptb_team ORDER BY team_id DESC LIMIT 1";
    $queryTeamID = $conn->prepare($sqlTeamID);
    $queryTeamID->execute();
    $fetchTeamID = $queryTeamID->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $error) {
    die("Error: " . $error->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Team</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #6a1b9a; /* Purple background */
            color: #ffffff; /* White text for contrast */
            font-family: 'Arial', sans-serif;
        }

        .container {
            background: #ffffff; /* White background for the form */
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Subtle shadow */
            padding: 30px;
            color: #333333; /* Neutral text inside the form */
        }

        h2 {
            color: rgb(0, 0, 0); /* Black title text */
            margin-bottom: 20px;
            font-size: 28px;
            text-align: center;
        }

        .form-label {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #8e44ad;
            border-color: #8e44ad;
        }

        .btn-primary:hover {
            background-color: #732d91;
            border-color: #732d91;
        }

        .form-control:focus, .form-select:focus {
            border-color: #8e44ad;
            box-shadow: 0 0 0 0.2rem rgba(142, 68, 173, 0.25);
        }

        .form-select option[disabled] {
            color: #999;
        }

        .mb-3 {
            margin-bottom: 1.5rem !important;
        }

        .text-center a {
            color: #8e44ad;
            font-weight: bold;
            text-decoration: none;
        }

        .text-center a:hover {
            text-decoration: underline;
        }

        .btn {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <h2>สร้างทีม</h2>
        <form action="process_create_team copy.php" method="post" enctype="multipart/form-data">
            <!-- Primary Teacher -->
            <div class="mb-3">
                <label for="primaryTeacher" class="form-label">หัวหน้าทีม</label>
                <input type="text" class="form-control" id="primaryTeacher"
                    value="<?php echo htmlspecialchars($teacherDetails['fname'] . ' ' . $teacherDetails['lname']); ?>"
                    name="primaryTeacher" readonly>
                <input type="hidden" name="primaryTeacherId" value="<?php echo $teach_id; ?>">
            </div>

            <!-- Co-Teachers -->
            <div class="mb-3">
                <label for="secondTeacher" class="form-label">คุณครูที่ปรึกษาคนที่ 1</label>
                <select class="form-select" id="secondTeacher" name="secondTeacherID">
                    <option value="">-- กรุณาเลือกคุณครูที่ปรึกษาคนที่ 1 --</option>
                    <option value="">-- ไม่มีครูที่ปรึกษาคนที่ 1 --</option>
                    <?php foreach ($teachers as $teacher): ?>
                        <option value="<?php echo htmlspecialchars($teacher['teacher_id']); ?>">
                            <?php echo htmlspecialchars($teacher['fname'] . ' ' . $teacher['lname']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="secondTeacher2" class="form-label">คุณครูที่ปรึกษาคนที่ 2</label>
                <select class="form-select" id="secondTeacher2" name="thirdTeacherID">
                    <option value="">-- กรุณาเลือกคุณครูที่ปรึกษาคนที่ 2 --</option>
                    <option value="">-- ไม่มีครูที่ปรึกษาคนที่ 2 --</option>
                    <?php foreach ($teachers as $teacher): ?>
                        <option value="<?php echo htmlspecialchars($teacher['teacher_id']); ?>">
                            <?php echo htmlspecialchars($teacher['fname'] . ' ' . $teacher['lname']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- School Name -->
            <div class="mb-3">
                <label for="schoolName" class="form-label">โรงเรียน</label>
                <input type="text" class="form-control" id="schoolName"
                    value="<?php echo htmlspecialchars($teacherDetails['school_name']); ?>" name="school_name" readonly>
            </div>

            <!-- File Upload -->
            <div class="mb-3">
                <label for="file_team" class="form-label">อัปโหลดไฟล์ข้อเสนอโครงการ</label>
                <input type="file" class="form-control" id="file_team" name="file_team"
                    accept="application/pdf" placeholder="อัปโหลดไฟล์ข้อเสนอโครงการ">
            </div>

            <!-- Team Name -->
            <div class="mb-3">
                <label for="teamName" class="form-label">ชื่อทีม</label>
                <input type="text" class="form-control" id="teamName" name="teamName" placeholder="กรุณาตั้งชื่อทีม"
                    required>
            </div>

            <!-- Member Count -->
            <div class="mb-3">
                <label for="memberCount" class="form-label">จำนวนสมาชิก</label>
                <input type="number" class="form-control" id="memberCount" name="memberCount"
                    placeholder="กรุณาระบุจำนวนสมาชิก" min="1" max="5" required>
            </div>

            <!-- Dynamic Student Selection -->
            <div id="studentSelection" class="mb-3" style="display: none;">
                <div id="studentCheckboxes"></div>
            </div>

            <!-- Project Name -->
            <div class="mb-3">
                <label for="projectName" class="form-label">ชื่อโครงการ</label>
                <input type="text" class="form-control" id="projectName" name="projectName"
                    placeholder="กรุณาใส่ชื่อโปรเจค" required>
            </div>

            <!-- Project Details -->
            <div class="mb-3">
                <label for="detail" class="form-label">รายละเอียดโครงการคร่าวๆ</label>
                <textarea class="form-control" id="detail" name="detail" placeholder="รายละเอียดของโครงการพอสังเขป"
                    required></textarea>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" name="submitCreateTeam" class="btn btn-primary mb-3">ยืนยันการสร้างทีม</button><br>
                <a href="../teacherProfile.php">กลับหน้าโปรไฟล์</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('memberCount').addEventListener('change', async function() {
            let numberOfMembers = parseInt(this.value);
            let studentSelectionDiv = document.getElementById('studentSelection');
            let studentCheckboxesDiv = document.getElementById('studentCheckboxes');

            if (numberOfMembers > 0) {
                studentSelectionDiv.style.display = 'block';
                studentCheckboxesDiv.innerHTML = ''; // Clear previous inputs

                // Fetch students from the server
                const response = await fetch('fetch_students.php');
                const students = await response.json();

                if (students.error) {
                    alert(students.error);
                    return;
                }

                for (let i = 1; i <= numberOfMembers; i++) {
                    let dropdownGroup = document.createElement('div');
                    dropdownGroup.classList.add('mb-2');
                    dropdownGroup.innerHTML = `
                        <label for="student${i}" class="form-label">สมาชิกนักเรียนคนที่ ${i}</label>
                        <select class="form-select" id="student${i}" name="students[]" required>
                            <option value="">-- กรุณาเลือกนักเรียนคนที่ ${i} --</option>
                            ${students.map(student => `<option value="${student.student_id}">${student.sex === 'male' ? "นาย" : "นางสาว"} ${student.student_name} ${student.grade} ${student.major}</option>`).join('')}
                        </select>
                    `;
                    studentCheckboxesDiv.appendChild(dropdownGroup);
                }
            } else {
                studentSelectionDiv.style.display = 'none';
            }
        });
    </script>
</body>

</html>
