<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once '../connectDB/configsdb.php';
    echo "have post method <br>";

    if (isset($_POST["submit_update"])) {
        echo "have submit button <br>";

        try {

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
            $firstTeacherName = $_POST["firstTeacherName"];
            $teacher_id = $_POST['teacher_id'];

            // DEBUG HERE
            echo "id" . $id . "<br>";
            echo "team_id" . $team_id . "<br>";
            echo "team_name" . $team_name . "<br>";
            echo "project_name" . $project_name . "<br>";
            echo "detail" . $detail . "<br>";
            echo "numStudents" . $numStudents . "<br>";
            echo "secondTeacherID" . $secondTeacherID . "<br>";
            echo "thirdTeacherID" . $thirdTeacherID . "<br>";
            print_r($students);
            echo "<br>";
            echo "firstTeacherID" . $firstTeacherID . "<br>";
            echo "firstTeacherName" . $firstTeacherName . "<br>";

            // file start
            $fileName = null;
            if (isset($_FILES['file_team']) && $_FILES['file_team']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['file_team']['tmp_name'];
                $fileName = $_FILES['file_team']['name'];
                $fileDest = "../uploads/" . $fileName;
                if (!move_uploaded_file($fileTmpPath, $fileDest)) {
                    throw new Exception("Failed to upload the file.");
                }
            }
            //end file


            // start update team
            $sqlUpdateTeam = $conn->prepare("UPDATE ptb_team
                                             SET team_name = :team_name,
                                                 project_name = :project_name,
                                                 detail = :detail,
                                                 file = COALESCE(:file, file)
                                             WHERE team_id = :team_id");
            $sqlUpdateTeam->bindParam(':team_name', $team_name);
            $sqlUpdateTeam->bindParam(':project_name', $project_name);
            $sqlUpdateTeam->bindParam(':detail', $detail);
            $sqlUpdateTeam->bindParam(':file', $fileName);
            $sqlUpdateTeam->bindParam(':team_id', $team_id);
            $sqlUpdateTeam->execute();

            if ($sqlUpdateTeam->rowCount() > 0) {
                echo "update Team Successfully";
            } else {
                echo "update team failed";
            }
            // end update team


            // clear teacher first
            $sqlClearTeacher = $conn->prepare("UPDATE ptb_team_teacher SET teacher_id = NULL WHERE team_id = :team_id");
            $sqlClearTeacher->bindParam(":team_id", $team_id);
            $sqlClearTeacher->execute();

            if ($sqlClearTeacher->rowCount() > 0) {
                echo "clear teacher successfully";
            } else {
                echo "failed to clear teacher";
            }


            // Clear student
            for ($i = 0; $i <= $numStudents; $i++) {
                // clear student 
                $sqlClearStudent = $conn->prepare("UPDATE ptb_student SET team_id = NULL , status = 'false' WHERE student_id = :student_id");
                $sqlClearStudent->bindParam("student_id", $students[$i]);
                $sqlClearStudent->execute();

                if ($sqlClearStudent->rowCount() > 0) {
                    echo "clear student successfully";
                } else {
                    echo "failed to clear student";
                }
            }


            // Insert student
            for($i = 0 ; $i <= $numStudents ; $i++) {
                $sqlInsertStudent = $conn->prepare("UPDATE ptb_student SET team_id = :team_id , status = 'true'  WHERE student_id = :student_id");
                $sqlInsertStudent->bindParam("team_id", $team_id);
                $sqlInsertStudent->bindParam("student_id", $students[$i]);
                $sqlInsertStudent->execute();

                if ($sqlInsertStudent->rowCount() > 0) {
                    echo "Insert student successfully";
                } else {
                    echo "failed to Insert student";
                }

            } 




            // all teacher
            $allTeacherID = [$firstTeacherID, $secondTeacherID, $thirdTeacherID];
            // count
            $countTeacher = count($allTeacherID);
            print_r($allTeacherID) . "<br>";
            echo "all teacher is : " . $countTeacher . "<br>";

            // Step 1: ลบ ID ที่ต้องการออกจาก table ptb_team_teacher
            $idsToDelete = [];
            for ($i = 0; $i < $countTeacher; $i++) {
                $idsToDelete[] = $id + $i; // สร้าง array ของ ID ที่ต้องการลบ
            }

            // Step 2: ลบหลายๆ id ที่ตรงกันใน table ptb_team_teacher โดยใช้คำสั่ง SQL 'IN'
            $placeholders = implode(',', array_fill(0, count($idsToDelete), '?'));
            $sqlDeleteRecords = $conn->prepare("DELETE FROM ptb_team_teacher WHERE id IN ($placeholders)");
            $sqlDeleteRecords->execute($idsToDelete);

            if ($sqlDeleteRecords->rowCount() > 0) {
                echo "deleted records successfully<br>";
            } else {
                echo "delete failed<br>";
            }

            // Loop for insert teacher
            foreach ($allTeacherID as $teacherID) {
                $sqlInsertTeachers = $conn->prepare("INSERT INTO ptb_team_teacher(team_id, teacher_id) VALUES(:team_id, :teacher_id)");
                $sqlInsertTeachers->bindParam(":team_id", $team_id);
                $sqlInsertTeachers->bindParam(":teacher_id", $teacherID);
                $sqlInsertTeachers->execute();

                if ($sqlInsertTeachers->rowCount() > 0) {
                    echo "insert teacher successfully";
                    header('location:manage_team copy.php?teacher_id=' . $teacher_id);
                } else {
                    echo "insert failed";
                }
            }
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }
}
