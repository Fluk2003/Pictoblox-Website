<?php

require '../connectDB/configsdb.php';

// Check if team_id and teacher_id are provided
if (!isset($_GET['team_id']) || empty($_GET['team_id']) || !isset($_GET['teacher_id']) || empty($_GET['teacher_id'])) {
    die("Error: Team ID and Teacher ID are required.");
}

$team_id = $_GET['team_id'];
$teacher_id = $_GET['teacher_id'];

try {
    // Begin Transaction
    $conn->beginTransaction();

    // Delete the team from ptb_team
    $stmt = $conn->prepare("DELETE FROM ptb_team WHERE team_id = :team_id");
    $stmt->bindParam(':team_id', $team_id, PDO::PARAM_INT);
    $stmt->execute();

    // Delete the teacher from ptb_team_teacher (remove the link between teacher and team)
    $stmt = $conn->prepare("DELETE FROM ptb_team_teacher WHERE team_id = :team_id AND teacher_id = :teacher_id");
    $stmt->bindParam(':team_id', $team_id, PDO::PARAM_INT);
    $stmt->bindParam(':teacher_id', $teacher_id, PDO::PARAM_INT);
    $stmt->execute();

    // Update all teachers (set status to 'false' and team_id to NULL)
    // $stmt = $conn->prepare("UPDATE ptb_teacher SET status = 'false', leader = 'false', team_id = NULL WHERE team_id = :team_id");
    // $stmt->bindParam(':team_id', $team_id, PDO::PARAM_INT);
    // $stmt->execute();

    // Update all students (set status to 'false' and team_id to NULL)
    $stmt = $conn->prepare("UPDATE ptb_student SET status = 'false', team_id = NULL WHERE team_id = :team_id");
    $stmt->bindParam(':team_id', $team_id, PDO::PARAM_INT);
    $stmt->execute();

    // Commit Transaction
    $conn->commit();

    // Redirect back to teacherProfile.php with teacher_id and success status
    header("Location: ../teacherProfile.php?teacher_id=" . urlencode($teacher_id) . "&status=deleted");
    exit();
} catch (PDOException $e) {
    // Rollback the transaction if something goes wrong
    $conn->rollBack();
    echo "Error deleting team and updating related records: " . $e->getMessage();
}

?>
