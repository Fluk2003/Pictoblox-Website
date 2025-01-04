<?php
// Include the database connection
session_start();
require_once '../connectDB/configsdb.php';

if(isset($_SESSION["school_id"])) {
    $school_id = $_SESSION["school_id"];
    try {
        // Fetch student data
        $stmt = $conn->prepare("SELECT student_id, CONCAT(fname, ' ', lname) AS student_name, sex, grade, major FROM ptb_student WHERE status = 'false' AND school_id = :school_id ");
        $stmt->bindParam(":school_id",$school_id);
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Return the data as JSON
        echo json_encode($students);
    } catch (PDOException $error) {
        echo json_encode(['error' => $error->getMessage()]);
    }
}

?>
