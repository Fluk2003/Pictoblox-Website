<?php

// Debug Code

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    session_start();
    require_once '../connectDB/configsdb.php';

    if (isset($_POST["submitCreateTeam"])) {
        // Debuggin Code
        // echo "คุณกด create team  มาแล้ว" ;


        //  Input from form

        // substring from TEAM100(real id)
        $team_id = $_POST["team_id"];
        $team_id = substr($team_id, 4);

        $primaryTeacher = $_POST["primaryTeacher"];
        $primaryTeacherID = $_POST["primaryTeacherId"];
        $secondTeacherID = $_POST["secondTeacherID"];
        $schoolName = $_POST["school_name"];
        $teamName = $_POST["teamName"];
        $projectName = $_POST["projectName"];
        $detail = $_POST["detail"];
        $students = $_POST["students"];


        // SQL Primary Teacher Comand Here
        $sqlPrimayTeacher = "SELECT * FROM ptb_teacher JOIN ptb_school ON ptb_teacher.school_id = ptb_school.school_id WHERE teacher_id = :teacher_id";
        $sqlFetchPrimaryTeacher = $conn->prepare($sqlPrimayTeacher);
        $sqlFetchPrimaryTeacher->bindParam(":teacher_id", $primaryTeacherID);
        $sqlFetchPrimaryTeacher->execute();
        $fetchPrimaryTeacher = $sqlFetchPrimaryTeacher->fetch(PDO::FETCH_ASSOC);


        // SQL Second Teacher Command Here
        $sqlSecondTeacher = "SELECT * FROM ptb_teacher JOIN ptb_school ON ptb_teacher.school_id = ptb_school.school_id WHERE teacher_id = :teacher_id";
        $sqlFetchSecondTeacher = $conn->prepare($sqlSecondTeacher);
        $sqlFetchSecondTeacher->bindParam(":teacher_id", $secondTeacherID);
        $sqlFetchSecondTeacher->execute();
        $fetchSecondTeacher = $sqlFetchSecondTeacher->fetch(PDO::FETCH_ASSOC);


        // PrimaryTeacherID
        // $primaryTeacherID = $fetchPrimaryTeacher["teacher_id"];
        // // echo $primaryTeacherID;

        // SecondeTeacherID
        // $secondTeacherID = $fetchSecondTeacher["teacher_id"];
        // // echo $secondTeacher;


        // TeacherIDArr
        // $teahcerIDArr = [$primaryTeacherID, $secondTeacherID];


        // memberArrID 
        $memberArrID = [];
        $memberArrID[] = $primaryTeacherID; // 
        $memberArrID[] = $secondTeacherID; // 

        // memberArrName



        // All of memebers
        $memberArrID = array_merge($memberArrID, $students); // รวมค่าจาก $students เข้ากับ $memberArr

        // Debug code memberArr
        // print_r($teahcerIDArr);


        // Test Debuggin Code      
        echo "รหัสทีม : " . $team_id;
        echo "<br>ครูคนที่ครูคนที่ 1 :  " . $primaryTeacher;
        echo "<br>ครูคนที่ 2 :  " . $fetchSecondTeacher['fname'] . " " . $fetchSecondTeacher["lname"];
        echo "<br>ชื่อโรงเรียน :  " . $schoolName;
        echo "<br>ชื่อทีม : " . $teamName;
        echo "<br>ชื่อโครงงาน " . $projectName;
        echo "<br>รายละเอียด : " . $detail . "<br>";
        echo "<br>จำนวนสมาชิก : " . count($memberArrID) . "<br>";
        $i = 0;
        foreach ($memberArrID as $member) {
            $i = $i + 1;
            echo "สมาชิกที่ " . ($i)  . " : "  . htmlspecialchars($member) . "<br>";
        }

        try {

            $membersName = [];
            $index_start = 0;

            while ($index_start < count($memberArrID)) {

                // เริ่มต้นการวนลูปสมาชิก
                $index_start = 0;
                while ($index_start < count($memberArrID)) {
                    
                    // เช็คชื่อของสมาชิก
                    if ($index_start == 0 || $index_start == 1) {
                        // ครู
                        $sqlTeacherName = "SELECT * FROM ptb_teacher WHERE teacher_id = :teacher_id ";
                        $sqlTeacherName = $conn->prepare($sqlTeacherName);
                        $sqlTeacherName->bindParam(":teacher_id", $memberArrID[$index_start]);
                        $sqlTeacherName->execute();
                        $fetchNameTeacher = $sqlTeacherName->fetch(PDO::FETCH_ASSOC);

                        if ($sqlTeacherName->rowCount() > 0) {
                            $membersName[$index_start] =  $fetchNameTeacher["fname"] . " " . $fetchNameTeacher["lname"];
                        } else {
                            echo "fail";
                        }

                        // Insert member as teacher into ptb_member
                        $sqlMember = "INSERT INTO ptb_member(member_id, member_name) VALUES(:member_id, :member_name)";
                        $queryMember = $conn->prepare($sqlMember);
                        $queryMember->bindParam(":member_id", $memberArrID[$index_start]);
                        $queryMember->bindParam(":member_name", $membersName[$index_start]);
                        $queryMember->execute();

                        // Update team_id for teacher
                        $sqlUpdateTeamID = "UPDATE ptb_member SET team_id = :team_id WHERE member_id = :member_id";
                        $queryUpdateTeamID = $conn->prepare($sqlUpdateTeamID);
                        $queryUpdateTeamID->bindParam(":team_id", $team_id);
                        $queryUpdateTeamID->bindParam(":member_id", $memberArrID[$index_start]);
                        $queryUpdateTeamID->execute();

                        // Update member_type to 'teacher'
                        $sqlUpdateTypeTeacher = "UPDATE ptb_member SET member_type = 'teacher' WHERE member_id = :member_id";
                        $queryUpdateTypeTeacher = $conn->prepare($sqlUpdateTypeTeacher);
                        $queryUpdateTypeTeacher->bindParam(":member_id", $memberArrID[$index_start]);
                        $queryUpdateTypeTeacher->execute();

                        if ($queryUpdateTypeTeacher->rowCount() > 0) {
                            echo "อัพเดตตำแหน่งของครูเรียบร้อย";
                        } else {
                            echo "อัพเดตตำแหน่งของครูไม่สำเร็จ";
                        }
                    } elseif ($index_start > 1) {
                        // นักเรียน
                        $sqlStudentName = "SELECT * FROM ptb_student WHERE student_id = :student_id ";
                        $sqlStudentName = $conn->prepare($sqlStudentName);
                        $sqlStudentName->bindParam(":student_id", $memberArrID[$index_start]);
                        $sqlStudentName->execute();
                        $fetchNameStudent = $sqlStudentName->fetch(PDO::FETCH_ASSOC);

                        if ($sqlStudentName->rowCount() > 0) {
                            $membersName[$index_start] =  $fetchNameStudent["fname"] . " " . $fetchNameStudent["lname"];
                        } else {
                            echo "fail";
                        }

                        // Insert member as student into ptb_member
                        $sqlMember = "INSERT INTO ptb_member(member_id, member_name) VALUES(:member_id, :member_name)";
                        $queryMember = $conn->prepare($sqlMember);
                        $queryMember->bindParam(":member_id", $memberArrID[$index_start]);
                        $queryMember->bindParam(":member_name", $membersName[$index_start]);
                        $queryMember->execute();

                        // Update team_id for student
                        $sqlUpdateTeamID = "UPDATE ptb_member SET team_id = :team_id WHERE member_id = :member_id";
                        $queryUpdateTeamID = $conn->prepare($sqlUpdateTeamID);
                        $queryUpdateTeamID->bindParam(":team_id", $team_id);
                        $queryUpdateTeamID->bindParam(":member_id", $memberArrID[$index_start]);
                        $queryUpdateTeamID->execute();

                        // Update member_type to 'student'
                        $sqlUpdateTypeStudent = "UPDATE ptb_member SET member_type = 'student' WHERE member_id = :member_id";
                        $queryUpdateTypeStudent = $conn->prepare($sqlUpdateTypeStudent);
                        $queryUpdateTypeStudent->bindParam(":member_id", $memberArrID[$index_start]);
                        $queryUpdateTypeStudent->execute();

                        if ($queryUpdateTypeStudent->rowCount() > 0) {
                            echo "อัพเดตตำแหน่งของนักเรียนเรียบร้อย";
                        } else {
                            echo "อัพเดตตำแหน่งของนักเรียนไม่สำเร็จ";
                        }
                    }

                    $index_start += 1;
                }
            }


            // Insert team data in ptb_team
            $sqlCreateTeam = "INSERT INTO ptb_team(team_name,project_name,detail) VALUES(:team_name,:project_name,:detail)";
            $queryCreateTeam = $conn->prepare($sqlCreateTeam);
            $queryCreateTeam->bindParam(":team_name", $teamName);
            $queryCreateTeam->bindParam(":project_name", $projectName);
            $queryCreateTeam->bindParam(":detail", $detail);

            //all member
            $queryCreateTeam->execute();


            if ($queryCreateTeam->rowCount() > 0) {
                echo "Create team  successfully ";
            } else {
                echo "Create team failed";
            }





            print_r($membersName);
            print_r($memberArrID);
        } catch (PDOException $error) {
            echo "มีข้อผิดพลาด " . $error->getMessage();
        }
    }
}
