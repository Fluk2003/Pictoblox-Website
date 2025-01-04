<?php
session_start();
require_once '../connectDB/configsdb.php';

if (!isset($_GET['team_id'])) {
    die("Team ID not provided.");
}

$team_id = $_GET['team_id'];
$teacher_id = $_GET['teacher_id'];

try {

    $sqlFetchTeam = "SELECT * FROM ptb_team JOIN ptb_team_teacher ON ptb_team.team_id = ptb_team_teacher.team_id WHERE ptb_team_teacher.team_id = :team_id";
    $fetchTea = $conn->prepare($sqlFetchTeam);
    $fetchTea->bindParam(':team_id', $team_id);
    $fetchTea->execute();
    $teamDetails = $fetchTea->fetch(PDO::FETCH_ASSOC);

    if (!$teamDetails) {
        die("Team not found.");
    }


    $sqlFetchTeachersInTeam = $conn->prepare(
        "
        SELECT t.teacher_id, CONCAT(t.fname, ' ', t.lname) AS teacher_name, s.school_id , s.school_name
        FROM ptb_teacher t
        INNER JOIN ptb_team_teacher tt ON t.teacher_id = tt.teacher_id
        INNER JOIN ptb_school s ON t.school_id = s.school_id
        WHERE tt.team_id = :team_id"
    );
    $sqlFetchTeachersInTeam->bindParam(':team_id', $team_id, PDO::PARAM_INT);
    $sqlFetchTeachersInTeam->execute();
    $teachersInTeam = $sqlFetchTeachersInTeam->fetchAll(PDO::FETCH_ASSOC);

    $school_id = $teachersInTeam[0]['school_id'];

    $sqlFetchTeachersFromSameSchool = $conn->prepare("
    SELECT teacher_id, CONCAT(fname, ' ', lname) AS teacher_name
    FROM ptb_teacher
    WHERE school_id = :school_id
    ");
    $sqlFetchTeachersFromSameSchool->bindParam(':school_id', $school_id);
    $sqlFetchTeachersFromSameSchool->execute();
    $teachersFromSameSchool = $sqlFetchTeachersFromSameSchool->fetchAll(PDO::FETCH_ASSOC);

    $sqlFetchAllStudents = $conn->prepare("
    SELECT student_id, CONCAT(fname, ' ', lname) AS student_name, grade, sex, status
    FROM ptb_student 
    WHERE school_id = :school_id
");
    $sqlFetchAllStudents->bindParam(':school_id', $school_id, PDO::PARAM_INT);
    $sqlFetchAllStudents->execute();

    // Correctly encode the status field for JavaScript
    $allStudents = array_map(function ($student) {
        $student['status'] = $student['status'] === 'true' || $student['status'] === true; // Ensure boolean format
        return $student;
    }, $sqlFetchAllStudents->fetchAll(PDO::FETCH_ASSOC));


    // Fetch students already in the team
    $sqlFetchStudentsInTeam = $conn->prepare("
        SELECT student_id, CONCAT(fname, ' ', lname) AS student_name, grade, sex, status 
        FROM ptb_student 
        WHERE team_id = :team_id
    ");
    $sqlFetchStudentsInTeam->bindParam(':team_id', $team_id, PDO::PARAM_INT);
    $sqlFetchStudentsInTeam->execute();
    $studentsInTeam = $sqlFetchStudentsInTeam->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $error) {
    echo $error->getMessage();
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #6f42c1; /* Purple background */
            font-family: 'Arial', sans-serif;
            color: #fff;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin-top: 50px;
        }

        h2 {
            font-size: 30px;
            margin-bottom: 30px;
            color: #6f42c1;
            text-align: center;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            border: 1px solid #ddd;
            padding: 12px;
            font-size: 16px;
        }

        .btn {
            font-size: 16px;
            padding: 12px 24px;
            border-radius: 10px;
        }

        .btn-primary {
            background-color: #6f42c1;
            border-color: #6f42c1;
        }

        .btn-primary:hover {
            background-color: #5a2e9a;
            border-color: #5a2e9a;
        }

        .btn-secondary {
            background-color: #ddd;
            border-color: #ddd;
        }

        .btn-secondary:hover {
            background-color: #ccc;
            border-color: #ccc;
        }

        .form-label {
            font-weight: 600;
            color: #6f42c1;
        }

        .form-control::placeholder,
        .form-select::placeholder {
            color: #888;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #6f42c1;
            box-shadow: 0 0 5px rgba(111, 66, 193, 0.5);
        }

        #studentsContainer select {
            margin-bottom: 10px;
        }

        #studentsContainer select:disabled {
            background-color: #f0f0f0;
        }

        button[type="submit"],
        button[type="button"] {
            font-weight: bold;
            margin-top: 20px;
        }
    </style>

    <script>
        const allStudents = <?php echo json_encode($allStudents); ?>; // Ensure status is properly formatted in backend
        const studentsInTeam = <?php echo json_encode($studentsInTeam); ?>;

        function updateStudentFields() {
            const numStudents = parseInt(document.getElementById('numStudents').value, 10); // Get number of students
            const container = document.getElementById('studentsContainer');
            let selectedStudentIds = [];

            // Clear all select elements initially
            container.innerHTML = '';

            // Function to create a single student select dropdown
            function createStudentSelect(index, preselectedId = null) {
                const select = document.createElement('select');
                select.classList.add('form-select', 'mb-2');
                select.name = `students[${index}]`;
                select.required = true;

                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = `-- เลือกสมาชิกคนที่ ${index + 1} --`;
                select.appendChild(defaultOption);

                // Add student options to select
                allStudents.forEach(student => {
                    // Only add student options if they are not already in the team
                    if (student.status !== true || studentsInTeam.some(s => s.student_id === student.student_id)) {
                        const studentOption = document.createElement('option');
                        studentOption.value = student.student_id;
                        studentOption.textContent =
                            (student.sex === 'male' ? 'นาย' : 'นางสาว') + ' ' + student.student_name + ' - ' + student.grade;

                        // Mark the option as selected if the student is already in the team
                        if (student.student_id === preselectedId) {
                            studentOption.selected = true;
                        }

                        select.appendChild(studentOption);
                    }
                });

                select.addEventListener('change', function() {
                    selectedStudentIds = Array.from(container.querySelectorAll('select')).map(s => s.value);
                    updateDisabledOptions();
                });

                return select;
            }

            // Function to disable already selected students in other selects
            function updateDisabledOptions() {
                const allSelects = container.querySelectorAll('select');

                allSelects.forEach(select => {
                    const selectedValue = select.value;

                    Array.from(select.options).forEach(option => {
                        // Enable the option if it's the current selected value or empty
                        if (option.value === '' || option.value === selectedValue) {
                            option.disabled = false; // Keep current selection enabled
                        } else {
                            const student = allStudents.find(s => s.student_id === option.value);
                            if (student?.status === true || selectedStudentIds.includes(option.value)) {
                                option.disabled = true; // Disable if the student is already selected
                            } else {
                                option.disabled = false;
                            }
                        }
                    });
                });
            }

            // Add existing team members first (up to current team size)
            studentsInTeam.forEach((student, index) => {
                const select = createStudentSelect(index, student.student_id);
                container.appendChild(select);
                selectedStudentIds.push(student.student_id);
            });

            console.log("studentsInTeam.length: " + studentsInTeam.length);

            // Add new selects if numStudents > current team size
            for (let i = studentsInTeam.length; i < numStudents; i++) {
                const select = createStudentSelect(i);
                container.appendChild(select);
            }

            // Get current number of selects in the container
            const currentSelects = container.querySelectorAll('select');

            // Remove excess selects if numStudents < current team size
            for (let i = numStudents; i < studentsInTeam.length; i++) {
                const selectToRemove = currentSelects[i];
                selectToRemove.remove(); // This will remove the extra selects
            }

            updateDisabledOptions(); // Ensure options are correctly disabled after rendering the selects
        }

        window.onload = function() {
            updateStudentFields();
        };
    </script>

</head>

<body>
    <div class="container">
        <h2>แก้ไขทีม
        </h2>
        <form id="editTeamForm" action="edit_team_process.php" method="post" enctype="multipart/form-data" onsubmit="confirmFormSubmit(event)">
            <input type="hidden" name="id" value="<?php echo $teamDetails['id']; ?>">
            <input type="hidden" name="team_id" value="<?php echo  $teamDetails['team_id']; ?>">
            <input type="hidden" name="teacher_id" value="<?php echo $teacher_id ?>">
            <input type="hidden" name="firstTeacherID" value="<?php echo htmlspecialchars($teachersInTeam[0]['teacher_id']); ?>">

            <!-- Teacher selections (team leader and co-teachers) -->
            <div class="mb-3">
                <label class="form-label">คุณครูที่ปรึกษาคนที่ 1 </label>
                <input type="text" class="form-control" name="firstTeacherName" value="<?php echo htmlspecialchars($teachersInTeam[0]['teacher_name']); ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="secondTeacher" class="form-label">คุณครูที่ปรึกษาคนที่ 2</label>
                <select class="form-select" id="secondTeacher" name="secondTeacherID" required>
                    <option value="">-- เลือกคุณครูที่ปรึกษาคนที่ 2 --</option>
                    <?php if (!empty($teachersFromSameSchool)): ?>
                        <?php foreach ($teachersFromSameSchool as $teacher): ?>
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
            <button type="submit" name="submit_update" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>

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