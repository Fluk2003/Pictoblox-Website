<?php
include '../sweet_alert.php';
session_start();
require_once '../connectDB/configsdb.php';  // DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_SESSION["student_id"]) {
        // Receive data from the form
        $student_id = $_SESSION["student_id"];
        $fname = htmlspecialchars($_POST["firstName"]);
        $lname = htmlspecialchars($_POST["lastName"]);
        $sex = $_POST["sex"];
        $school_id = $_POST["school_id"];
        $grade = htmlspecialchars($_POST["grade"]);
        $major = htmlspecialchars($_POST["major"]);
        $fb = htmlspecialchars($_POST["facebook"]);
        $line = htmlspecialchars($_POST["line"]);
        $email = htmlspecialchars($_POST["email"]);
        $number = htmlspecialchars($_POST["phone"]);
        $interest = htmlspecialchars($_POST["interest"]);
        $username = htmlspecialchars($_POST["userName"]);
        $password = htmlspecialchars($_POST["password"]);
        $confirm_password = htmlspecialchars($_POST["confirm_password"]);

        // Check if passwords match
        if ($password !== $confirm_password) {
            echo '<script>Swal.fire("Error", "Passwords do not match!", "error");</script>';
            exit;
        }

        // Handling the file upload
        $userpic = '';  // Default value if no file is uploaded

        // Check if a new picture is uploaded
        if (isset($_FILES['userpic']) && $_FILES['userpic']['error'] == 0) {
            $fileTmpPath = $_FILES['userpic']['tmp_name'];
            $fileName = $_FILES['userpic']['name'];
            $fileSize = $_FILES['userpic']['size'];
            $fileType = $_FILES['userpic']['type'];

            // Get file extension
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            // Validate file type
            if (in_array(strtolower($fileExtension), $allowedExtensions)) {
                // Define the upload directory
                // $uploadDir = '../uploads/';
                $uploadDir = '../../forms/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);  // Create the directory if it doesn't exist
                }

                // Create a unique file name to avoid conflicts
                $newFileName = uniqid() . '.' . $fileExtension;
                $destPath = $uploadDir . $newFileName;

                // Move the uploaded file to the destination
                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $userpic = $newFileName;  // Store the file name in the variable
                } else {
                    echo '<script>Swal.fire("Error", "There was an error uploading your profile picture.", "error");</script>';
                    exit;
                }
            } else {
                echo '<script>Swal.fire("Error", "Invalid file type. Only jpg, jpeg, png, and gif are allowed.", "error");</script>';
                exit;
            }
        } else {
            // If no new picture is uploaded, fetch the existing picture from the database
            $stmt = $conn->prepare("SELECT picture FROM ptb_student WHERE student_id = :student_id");
            $stmt->bindParam(":student_id", $student_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $userpic = $result['picture'];  // Use the existing picture
        }

        try {
            // Update the student data
            $sqlUpdate = $conn->prepare("UPDATE ptb_student 
                                         SET fname = :fname, lname = :lname, sex = :sex, 
                                             school_id = :school_id, grade = :grade, major = :major, 
                                             fb = :fb, line = :line, email = :email, number = :number, 
                                             interest = :interest, username = :username, password = :password,
                                             picture = :picture
                                         WHERE student_id = :student_id");

            // Bind parameters to prevent SQL injection
            $sqlUpdate->bindParam(":fname", $fname);
            $sqlUpdate->bindParam(":lname", $lname);
            $sqlUpdate->bindParam(":sex", $sex);
            $sqlUpdate->bindParam(":school_id", $school_id);
            $sqlUpdate->bindParam(":grade", $grade);
            $sqlUpdate->bindParam(":major", $major);
            $sqlUpdate->bindParam(":fb", $fb);
            $sqlUpdate->bindParam(":line", $line);
            $sqlUpdate->bindParam(":email", $email);
            $sqlUpdate->bindParam(":number", $number);
            $sqlUpdate->bindParam(":interest", $interest);
            $sqlUpdate->bindParam(":username", $username);
            $sqlUpdate->bindParam(":password", $password);
            $sqlUpdate->bindParam(":picture", $userpic);  // Save the uploaded file name or the existing one to the DB
            $sqlUpdate->bindParam(":student_id", $student_id);

            // Execute the query
            $sqlUpdate->execute();

            // SweetAlert Success for Successful Update
            echo '<script>Swal.fire("Success", "ข้อมูลได้รับการอัพเดทเรียบร้อยแล้ว!", "success").then(function() { window.location = "../studentProfile.php"; });</script>';
            exit;
        } catch (PDOException $error) {
            // SweetAlert Error for Database Issues
            echo '<script>Swal.fire("Error", "Error: ' . $error->getMessage() . '", "error");</script>';
            exit;
        }
    }
}
