<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once '../connectDB/configsdb.php';

    if (isset($_POST["submit_update"])) {
        try {
            $conn->beginTransaction(); // Start transaction

            $id = $_POST["id"];
            $team_id = $_POST['team_id'];
            $team_name = $_POST['teamName'];
            $project_name = $_POST['projectName'];
            $detail = $_POST['detail'];
            $numStudents = $_POST['numStudents'];
            $secondTeacherID = $_POST['secondTeacherID'];
            $thirdTeacherID = $_POST['thirdTeacherID'];
            $students = $_POST['students'];
            $firstTeacherID = $_POST['firstTeacherID'];
            $teacher_id = $_POST['teacher_id'];

            $fileName = null;
            if (isset($_FILES['file_team']) && $_FILES['file_team']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['file_team']['tmp_name'];
                $fileName = $_FILES['file_team']['name'];
                $fileDest = "../uploads/" . $fileName;
                if (!move_uploaded_file($fileTmpPath, $fileDest)) {
                    throw new Exception("Failed to upload the file.");
                }
            }

            // Update team details
            $sqlUpdateTeam = $conn->prepare("
                UPDATE ptb_team
                SET team_name = :team_name,
                    project_name = :project_name,
                    detail = :detail,
                    file = COALESCE(:file, file)
                WHERE team_id = :team_id
            ");
            $sqlUpdateTeam->bindParam(':team_name', $team_name);
            $sqlUpdateTeam->bindParam(':project_name', $project_name);
            $sqlUpdateTeam->bindParam(':detail', $detail);
            $sqlUpdateTeam->bindParam(':file', $fileName);
            $sqlUpdateTeam->bindParam(':team_id', $team_id);
            $sqlUpdateTeam->execute();

            // Clear all students in this team
            $sqlClearStudents = $conn->prepare("
                UPDATE ptb_student
                SET team_id = NULL, status = 'false'
                WHERE team_id = :team_id
            ");
            $sqlClearStudents->bindParam(':team_id', $team_id);
            $sqlClearStudents->execute();

            // Insert updated students into the team
            foreach ($students as $student_id) {
                $sqlInsertStudent = $conn->prepare("
                    UPDATE ptb_student
                    SET team_id = :team_id, status = 'true'
                    WHERE student_id = :student_id
                ");
                $sqlInsertStudent->bindParam(':team_id', $team_id);
                $sqlInsertStudent->bindParam(':student_id', $student_id);
                $sqlInsertStudent->execute();
            }

            // Clear all teachers for this team
            $sqlClearTeachers = $conn->prepare("DELETE FROM ptb_team_teacher WHERE team_id = :team_id");
            $sqlClearTeachers->bindParam(':team_id', $team_id);
            $sqlClearTeachers->execute();

            // Insert updated teachers into the team
            $allTeacherIDs = [$firstTeacherID, $secondTeacherID, $thirdTeacherID];
            foreach ($allTeacherIDs as $teacherID) {
                if (!empty($teacherID)) {
                    $sqlInsertTeacher = $conn->prepare("
                        INSERT INTO ptb_team_teacher (team_id, teacher_id)
                        VALUES (:team_id, :teacher_id)
                    ");
                    $sqlInsertTeacher->bindParam(':team_id', $team_id);
                    $sqlInsertTeacher->bindParam(':teacher_id', $teacherID);
                    $sqlInsertTeacher->execute();
                }
            }

            $conn->commit(); // Commit transaction

            echo "Team updated successfully!";
            header('Location: manage_team copy.php?teacher_id=' . $teacher_id);
            exit;
        } catch (Exception $error) {
            $conn->rollBack(); // Rollback on failure
            echo "Error: " . $error->getMessage();
        }
    }
}
