<?php
session_start();
require_once '../connectDB/configsdb.php';

// Check if team_id is provided
if (!isset($_GET['team_id'])) {
    die("Team ID not provided.");
}

$team_id = $_GET['team_id'];

try {
    // Fetch team details
    $sqlFetchTeam = $conn->prepare("
        SELECT t.team_id, t.team_name, t.project_name, t.detail, t.file
        FROM ptb_team t
        WHERE t.team_id = :team_id
    ");
    $sqlFetchTeam->bindParam(':team_id', $team_id, PDO::PARAM_INT);
    $sqlFetchTeam->execute();
    $teamDetails = $sqlFetchTeam->fetch(PDO::FETCH_ASSOC);

    if (!$teamDetails) {
        die("Team not found.");
    }

    // Fetch teachers in the same team
    $sqlFetchTeachersInTeam = $conn->prepare("
        SELECT t.teacher_id, CONCAT(t.fname, ' ', t.lname) AS teacher_name, s.school_id , s.school_name
        FROM ptb_teacher t
        INNER JOIN ptb_team_teacher tt ON t.teacher_id = tt.teacher_id
        INNER JOIN ptb_school s ON t.school_id = s.school_id
        WHERE tt.team_id = :team_id
    ");
    $sqlFetchTeachersInTeam->bindParam(':team_id', $team_id, PDO::PARAM_INT);
    $sqlFetchTeachersInTeam->execute();
    $teachersInTeam = $sqlFetchTeachersInTeam->fetchAll(PDO::FETCH_ASSOC);

    // Assuming the first teacher is the one who created the team
    $school_id = $teachersInTeam[0]['school_id'];

    // Fetch teachers from the same school
    $sqlFetchTeachersFromSameSchool = $conn->prepare("
    SELECT teacher_id, CONCAT(fname, ' ', lname) AS teacher_name
    FROM ptb_teacher
    WHERE school_id = :school_id
");
    $sqlFetchTeachersFromSameSchool->bindParam(':school_id', $school_id, PDO::PARAM_INT);
    $sqlFetchTeachersFromSameSchool->execute();
    $teachersFromSameSchool = $sqlFetchTeachersFromSameSchool->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all students in the same school as the team
    $school_id = $teachersInTeam[0]['school_id']; // Using the first teacher's school_id
    $sqlFetchAllStudents = $conn->prepare("
        SELECT student_id, CONCAT(fname, ' ', lname) AS student_name, grade, sex 
        FROM ptb_student 
        WHERE school_id = :school_id
    ");
    $sqlFetchAllStudents->bindParam(':school_id', $school_id, PDO::PARAM_INT);
    $sqlFetchAllStudents->execute();
    $allStudents = $sqlFetchAllStudents->fetchAll(PDO::FETCH_ASSOC);

    // Fetch students already in the team
    $sqlFetchStudentsInTeam = $conn->prepare("
        SELECT student_id, CONCAT(fname, ' ', lname) AS student_name, grade, sex 
        FROM ptb_student 
        WHERE team_id = :team_id
    ");
    $sqlFetchStudentsInTeam->bindParam(':team_id', $team_id, PDO::PARAM_INT);
    $sqlFetchStudentsInTeam->execute();
    $studentsInTeam = $sqlFetchStudentsInTeam->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $error) {
    die("Error: " . $error->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Team</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const allStudents = <?php echo json_encode($allStudents); ?>;
        const studentsInTeam = <?php echo json_encode($studentsInTeam); ?>;

        function updateStudentFields() {
            const numStudents = document.getElementById('numStudents').value;
            const container = document.getElementById('studentsContainer');
            container.innerHTML = ''; // Clear existing fields

            // Keep track of selected student IDs
            let selectedStudentIds = [];

            // Function to create a student select dropdown
            function createStudentSelect(index, preselectedId = null) {
                const select = document.createElement('select');
                select.classList.add('form-select', 'mb-2');
                select.name = `students[${index}]`;
                select.required = true;

                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = `-- เลือกสมาชิกคนที่ ${index + 1} --`;
                select.appendChild(defaultOption);

                allStudents.forEach(student => {
                    const studentOption = document.createElement('option');
                    studentOption.value = student.student_id;
                    studentOption.textContent = (student.sex === 'male' ? 'นาย' : 'นางสาว') + ' ' + student.student_name + ' - ' + student.grade;

                    if (student.student_id === preselectedId) {
                        studentOption.selected = true;
                    } else if (selectedStudentIds.includes(student.student_id)) {
                        studentOption.disabled = true;
                    }

                    select.appendChild(studentOption);
                });

                // Handle dropdown changes
                select.addEventListener('change', function() {
                    selectedStudentIds = Array.from(container.querySelectorAll('select')).map(s => s.value);
                    updateDisabledOptions();
                });

                return select;
            }

            // Function to update the disabled state of options
            function updateDisabledOptions() {
                const allSelects = container.querySelectorAll('select');
                allSelects.forEach(select => {
                    const selectedValue = select.value;
                    Array.from(select.options).forEach(option => {
                        if (option.value === '' || option.value === selectedValue) {
                            option.disabled = false;
                        } else {
                            option.disabled = selectedStudentIds.includes(option.value);
                        }
                    });
                });
            }

            // Add already selected students
            studentsInTeam.forEach((student, index) => {
                const select = createStudentSelect(index, student.student_id);
                container.appendChild(select);
                selectedStudentIds.push(student.student_id);
            });

            // Add additional empty selections
            for (let i = studentsInTeam.length; i < numStudents; i++) {
                const select = createStudentSelect(i);
                container.appendChild(select);
            }

            updateDisabledOptions(); // Initialize disabled options
        }

        window.onload = function() {
            updateStudentFields();
        };
    </script>

</head>

<body>
    <div class="container m-5">
        <h2 class="text-center">แก้ไขทีม</h2>
        <form id="editTeamForm" action="edit_team_process copy.php" method="post" enctype="multipart/form-data" onsubmit="confirmFormSubmit(event)">
            <input type="hidden" name="team_id" value="<?php echo $teamDetails['team_id']; ?>">

            <!-- Teacher selections (team leader and co-teachers) -->
            <div class="mb-3">
                <label class="form-label">คุณครูที่ปรึกษาคนที่ 1 </label>
                <input type="text" class="form-control" name="firstTeacherID" value="<?php echo htmlspecialchars($teachersInTeam[0]['teacher_name']); ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="secondTeacher" class="form-label">คุณครูที่ปรึกษาคนที่ 2</label>
                <select class="form-select" id="secondTeacher" name="secondTeacherID">
                    <option value="">-- เลือกคุณครูที่ปรึกษาคนที่ 2 --</option>
                    <?php if (!empty($teachersFromSameSchool)): ?>
                        <?php foreach ($teachersFromSameSchool as $teacher): ?>
                            <!-- Exclude the first teacher (team leader) from the list -->
                            <?php if ($teacher['teacher_id'] != $teachersInTeam[0]['teacher_id']): ?>
                                <option value="<?php echo htmlspecialchars($teacher['teacher_id']); ?>"
                                    <?php echo isset($teachersInTeam[1]) && $teacher['teacher_id'] == $teachersInTeam[1]['teacher_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($teacher['teacher_name']); ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="secondTeacher2" class="form-label">คุณครูที่ปรึกษาคนที่ 3</label>
                <select class="form-select" id="secondTeacher2" name="thirdTeacherID">
                    <option value="">-- เลือกคุณครูที่ปรึกษาคนที่ 3 --</option>
                    <?php if (!empty($teachersFromSameSchool)): ?>
                        <?php foreach ($teachersFromSameSchool as $teacher): ?>
                            <!-- Exclude the first teacher (team leader) from the list -->
                            <?php if ($teacher['teacher_id'] != $teachersInTeam[0]['teacher_id']): ?>
                                <option value="<?php echo htmlspecialchars($teacher['teacher_id']); ?>"
                                    <?php echo isset($teachersInTeam[2]) && $teacher['teacher_id'] == $teachersInTeam[2]['teacher_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($teacher['teacher_name']); ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- School Name -->
            <div class="mb-3">
                <label for="schoolName" class="form-label">โรงเรียน</label>
                <input type="text" class="form-control" id="schoolName"
                    value="<?php echo htmlspecialchars($teachersInTeam[0]['school_name']); ?>" name="school_name" readonly>
            </div>


            <!-- File -->
            <div class="mb-3">
                <label for="file_team" class="form-label">อัปโหลดไฟล์ข้อเสนอโครงการ</label>
                <input type="file" class="form-control" id="file_team" name="file_team"
                    accept="application/pdf" placeholder="อัปโหลดไฟล์ข้อเสนอโครงการ" value="<?php echo htmlspecialchars($teamDetails['file']) ?>">
            </div>

            <!-- Team Name -->
            <div class="mb-3">
                <label for="teamName" class="form-label">ชื่อทีม</label>
                <input type="text" class="form-control" id="teamName" name="teamName" placeholder="กรุณาตั้งชื่อทีม" value="<?php echo htmlspecialchars($teamDetails['team_name']) ?>"
                    required>
            </div>

            <!-- Number of Students -->
            <div class="mb-3">
                <label for="numStudents" class="form-label">จำนวนสมาชิกนักเรียน</label>
                <input type="number" class="form-control" id="numStudents" name="numStudents" value="<?php echo count($studentsInTeam); ?>" min="1" max="5" onchange="updateStudentFields()" required>
            </div>

            <!-- Students Section -->
            <div id="studentsContainer"></div>

            <!-- Project Name -->
            <div class="mb-3">
                <label for="projectName" class="form-label">ชื่อโครงการ</label>
                <input type="text" class="form-control" name="projectName" value="<?php echo htmlspecialchars($teamDetails['project_name']); ?>" required>
            </div>

            <!-- Detail of the Project -->
            <div class="mb-3">
                <label for="detail" class="form-label">รายละเอียดโครงการคร่าวๆ</label>
                <textarea class="form-control" id="detail" name="detail" placeholder="รายละเอียดของโครงการพอสังเขป" required><?php echo htmlspecialchars($teamDetails['detail']); ?></textarea>
            </div>


            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>

            <!-- Cancel Button -->
            <button type="button" class="btn btn-secondary" onclick="goBack()">ยกเลิก</button>

            <script>
                function goBack() {
                    window.history.back(); // Go back to the previous page
                }
            </script>

        </form>
    </div>
</body>

</html>