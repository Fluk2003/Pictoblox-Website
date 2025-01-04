<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../sweet_alert.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require '../connectDB/configsdb.php';

    if (isset($_POST["submitCreateTeam"])) {
        $primaryTeacher = $_POST["primaryTeacher"];
        $primaryTeacherId = $_POST["primaryTeacherId"];
        $secondTeacherID = $_POST["secondTeacherID"];
        $thirdTeacherID = $_POST["thirdTeacherID"]; //เพิ่มมา
        $school_name = $_POST["school_name"];
        $teamName = $_POST["teamName"];
        $projectName = $_POST["projectName"];
        $detail = $_POST["detail"];
        $memberCount = $_POST["memberCount"];
        $students = $_POST["students"];
        $file_team = $_FILES["file_team"];

        // file manage here
        $targetDir = "../uploads/";
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
            $team_id = $result['max_team_id'] !== null ? $result['max_team_id'] + 1 : 0;
        } catch (PDOException $e) {
            $errorMessage = "Error fetching team_id: " . $e->getMessage();
        }

        // Insert team data
        try {
            $sqlInsertTeam = "INSERT INTO ptb_team (team_id, team_name, project_name, detail) 
                              VALUES (:team_id, :team_name, :project_name, :detail)";
            $queryInsertTeam = $conn->prepare($sqlInsertTeam);
            $queryInsertTeam->bindParam(":team_id", $team_id);
            $queryInsertTeam->bindParam(":team_name", $teamName);
            $queryInsertTeam->bindParam(":project_name", $projectName);
            $queryInsertTeam->bindParam(":detail", $detail);
            $queryInsertTeam->execute();

            if ($queryInsertTeam->rowCount() > 0) {
                $success = true;

                // Update teachers with the new team_id
                $allTeacherID = [$primaryTeacherId, $secondTeacherID, $thirdTeacherID];
                foreach ($allTeacherID as $teacherId) {
                    $sqlUpdateTeamTeacher = "UPDATE ptb_teacher SET team_id = :team_id , leader = 'true' WHERE teacher_id = :teacher_id";
                    $queryUpdateTeamTeacher = $conn->prepare($sqlUpdateTeamTeacher);
                    $queryUpdateTeamTeacher->bindParam(":team_id", $team_id);
                    $queryUpdateTeamTeacher->bindParam(":teacher_id", $teacherId);
                    $queryUpdateTeamTeacher->execute();
                }

                // Update the leader field for the primary teacher
                // $sqlUpdateLeader = "UPDATE ptb_teacher SET leader = 'true' WHERE teacher_id = :primaryTeacherId";
                // $queryUpdateLeader = $conn->prepare($sqlUpdateLeader);
                // $queryUpdateLeader->bindParam(":primaryTeacherId", $primaryTeacherId);
                // $queryUpdateLeader->execute();

                // Update students with the new team_id
                foreach ($students as $studentId) {
                    $sqlUpdateTeamStudent = "UPDATE ptb_student SET team_id = :team_id, status = 'true' 
                                             WHERE student_id = :student_id";
                    $queryUpdateTeamStudent = $conn->prepare($sqlUpdateTeamStudent);
                    $queryUpdateTeamStudent->bindParam(":team_id", $team_id);
                    $queryUpdateTeamStudent->bindParam(":student_id", $studentId);
                    $queryUpdateTeamStudent->execute();
                }
            } else {
                $success = false;
                $errorMessage = "Failed to insert team.";
            }



            if (!empty($file_name)) {
                // อัปโหลดไฟล์
                if (move_uploaded_file($_FILES["file_team"]["tmp_name"], $targetFilePath)) {
                    // อัปเดตข้อมูลในฐานข้อมูล
                    $sqlUpdate = $conn->prepare("UPDATE ptb_team SET file = :file_name WHERE team_id = :team_id");
                    $sqlUpdate->bindParam(":file_name", $file_name);
                    $sqlUpdate->bindParam(":team_id", $team_id);
                    $sqlUpdate->execute();
                } else {
                    throw new Exception("อัปโหลดไฟล์ล้มเหลว");
                }
            } else {
                throw new Exception("กรุณาเลือกไฟล์ก่อนทำการอัปโหลด");
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
                        title: 'Success!',
                        text: 'The team was created successfully.',
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
