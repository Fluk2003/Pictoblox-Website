<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../sweet_alert.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require '../connectDB/configsdb.php';

    if (isset($_POST["submitCreateTeam"])) {
        $primaryTeacherId = $_POST["primaryTeacherId"];
        $secondTeacherID = $_POST["secondTeacherID"];
        $thirdTeacherID = $_POST["thirdTeacherID"];
        $teamName = $_POST["teamName"];
        $projectName = $_POST["projectName"];
        $detail = $_POST["detail"];
        $students = $_POST["students"];
        $file_team = $_FILES["file_team"];
        $createdBy = $primaryTeacherId;

        // File management
        // $targetDir = "../uploads/";
        $targetDir = "../../forms/uploads/";
        $file_name = $_FILES["file_team"]["name"];
        $targetFilePath = $targetDir . basename($file_name);

        $success = false;
        $errorMessage = '';

        try {
            // Fetch the current maximum team_id
            $stmt = $conn->prepare("SELECT MAX(team_id) AS max_team_id FROM ptb_team");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Determine the next team_id
            $team_id = $result['max_team_id'] !== null ? $result['max_team_id'] + 1 : 1;
        } catch (PDOException $e) {
            $errorMessage = "Error fetching team_id: " . $e->getMessage();
        }

        // Insert team data
        try {
            $sqlInsertTeam = "INSERT INTO ptb_team (team_id, team_name, project_name, detail, file, created_by) 
                              VALUES (:team_id, :team_name, :project_name, :detail, :file, :created_by)";
            $queryInsertTeam = $conn->prepare($sqlInsertTeam);
            $queryInsertTeam->bindParam(":team_id", $team_id);
            $queryInsertTeam->bindParam(":team_name", $teamName);
            $queryInsertTeam->bindParam(":project_name", $projectName);
            $queryInsertTeam->bindParam(":detail", $detail);
            $queryInsertTeam->bindParam(":file", $file_name);
            $queryInsertTeam->bindParam(":created_by", $createdBy);
            $queryInsertTeam->execute();

            if ($queryInsertTeam->rowCount() > 0) {
                $success = true;

                // Teacher-team assignments
                $allTeacherID = [$primaryTeacherId, $secondTeacherID, $thirdTeacherID];
                foreach ($allTeacherID as $teacherId) {
                    if (!empty($teacherId)) {
                        $sqlInsertTeamTeacher = "INSERT INTO ptb_team_teacher (team_id, teacher_id) VALUES (:team_id, :teacher_id)";
                        $queryInsertTeamTeacher = $conn->prepare($sqlInsertTeamTeacher);
                        $queryInsertTeamTeacher->bindParam(":team_id", $team_id);
                        $queryInsertTeamTeacher->bindParam(":teacher_id", $teacherId);
                        $queryInsertTeamTeacher->execute();

                        // Update teacher leader status
                        if ($teacherId == $primaryTeacherId) {
                            $sqlUpdateTeacher = "UPDATE ptb_teacher SET leader = 'true' WHERE teacher_id = :teacher_id";
                            $queryUpdateTeacher = $conn->prepare($sqlUpdateTeacher);
                            $queryUpdateTeacher->bindParam(":teacher_id", $teacherId);
                            $queryUpdateTeacher->execute();
                        }
                    }
                }

                // Update students with the new team_id
                foreach ($students as $studentId) {
                    $sqlUpdateTeamStudent = "UPDATE ptb_student SET team_id = :team_id, status = 'true' 
                                             WHERE student_id = :student_id";
                    $queryUpdateTeamStudent = $conn->prepare($sqlUpdateTeamStudent);
                    $queryUpdateTeamStudent->bindParam(":team_id", $team_id);
                    $queryUpdateTeamStudent->bindParam(":student_id", $studentId);
                    $queryUpdateTeamStudent->execute();
                }

                // File upload
                if (!empty($file_name)) {
                    if (!move_uploaded_file($_FILES["file_team"]["tmp_name"], $targetFilePath)) {
                        throw new Exception("File upload failed.");
                    }
                }
            } else {
                $success = false;
                $errorMessage = "Failed to insert team.";
            }
        } catch (PDOException $error) {
            $success = false;
            $errorMessage = "Error: " . $error->getMessage();
        }

        // Output SweetAlert based on success or failure
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        if ($success) {
            echo "<script>
                    Swal.fire({
                        title: 'สำเร็จ!',
                        text: 'การสร้างทีมสำเร็จ.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '../teacherProfile.php';
                        }
                    });
                  </script>";
        } else {
            echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: '$errorMessage',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '../teacherProfile.php';
                        }
                    });
                  </script>";
        }
        exit();
    }
}
