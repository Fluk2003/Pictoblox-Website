<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the userType is set and valid
    $userType = isset($_POST['userType']) ? $_POST['userType'] : '';

    // Redirect based on the userType selected
    if ($userType === 'teacher') {
        header("Location: login_process_teacher.php");  // Redirect to teacher login process
    } elseif ($userType === 'student') {
        header("Location: login_process_student.php");  // Redirect to student login process
    } else {
        echo "Invalid user type.";  // Error message if no valid userType is selected
    }
    exit;  // Ensure no further code is executed after the redirect
}
?>
