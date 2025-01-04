<?php
include '../sweet_alert.php';
session_start();
require_once '../connectDB/configsdb.php';  // DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION["teacher_id"])) {
        // Receive data from the form
        $teacher_id = $_SESSION["teacher_id"];
        $fname = htmlspecialchars($_POST["firstName"]);
        $lname = htmlspecialchars($_POST["lastName"]);
        $sex = $_POST["sex"];
        $school_id = $_POST["school_id"];
        $subject = htmlspecialchars($_POST["subject"]);
        $email = htmlspecialchars($_POST["email"]);
        $number = htmlspecialchars($_POST["phone"]);
        $username = htmlspecialchars($_POST["userName"]);
        $password = htmlspecialchars($_POST["password"]);
        $confirm_password = htmlspecialchars($_POST["confirm_password"]);

        // Check if passwords match
        if ($password !== $confirm_password) {
            echo "<script>Swal.fire('Error!', 'Passwords do not match!', 'error');</script>";
            exit;
        }

        // Handling the file upload
        $userpic = '';  // Default value if no file is uploaded
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
                    echo "<script>Swal.fire('Error!', 'There was an error uploading your profile picture.', 'error');</script>";
                    exit;
                }
            } else {
                echo "<script>Swal.fire('Error!', 'Invalid file type. Only jpg, jpeg, png, and gif are allowed.', 'error');</script>";
                exit;
            }
        } else {
            // If no file is uploaded, keep the existing picture
            // Retrieve the current picture from the database
            $sqlSelect = $conn->prepare("SELECT picture FROM ptb_teacher WHERE teacher_id = :teacher_id");
            $sqlSelect->bindParam(":teacher_id", $teacher_id);
            $sqlSelect->execute();
            $currentPicture = $sqlSelect->fetch(PDO::FETCH_ASSOC);

            // Keep the existing picture if no new picture is uploaded
            $userpic = $currentPicture['picture'];
        }

        try {
            // Update the teacher data
            $sqlUpdate = $conn->prepare("UPDATE ptb_teacher 
                                         SET fname = :fname, lname = :lname, sex = :sex, 
                                             school_id = :school_id, subject = :subject, 
                                             email = :email, number = :number, 
                                             username = :username, password = :password,
                                             picture = :picture
                                         WHERE teacher_id = :teacher_id");

            // Bind parameters to prevent SQL injection
            $sqlUpdate->bindParam(":fname", $fname);
            $sqlUpdate->bindParam(":lname", $lname);
            $sqlUpdate->bindParam(":sex", $sex);
            $sqlUpdate->bindParam(":school_id", $school_id);
            $sqlUpdate->bindParam(":email", $email);
            $sqlUpdate->bindParam(":number", $number);
            $sqlUpdate->bindParam(":username", $username);
            $sqlUpdate->bindParam(":password", $password); 
            $sqlUpdate->bindParam(":picture", $userpic);  // Save the uploaded file name or the existing one to the DB
            $sqlUpdate->bindParam(":subject", $subject);
            $sqlUpdate->bindParam(":teacher_id", $teacher_id);

            // Execute the query
            $sqlUpdate->execute();

            // Success message with SweetAlert
            echo "<script>Swal.fire('Success!', 'ข้อมูลได้รับการอัพเดทเรียบร้อยแล้ว!', 'success').then(() => window.location.href = '../teacherProfile.php');</script>";
            exit;
        } catch (PDOException $error) {
            echo "<script>Swal.fire('Error!', 'Error: " . $error->getMessage() . "', 'error');</script>";
        }
    }
}
?>
