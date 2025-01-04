<?php
// Include database connection
require_once '../connectDB/configsdb.php';

// Fetch all team data
try {
    $stmt = $conn->prepare("
        SELECT 
            t.team_id, 
            t.team_name, 
            t.project_name, 
            t.detail, 
            t.file, 
            sc.school_name,
            GROUP_CONCAT(DISTINCT CONCAT(pt.fname, ' ', pt.lname) ORDER BY ttt.id SEPARATOR ', ') AS teachers,
            GROUP_CONCAT(DISTINCT CONCAT(ps.fname, ' ', ps.lname) ORDER BY ps.student_id SEPARATOR ', ') AS students
        FROM ptb_team t
        LEFT JOIN ptb_team_teacher ttt ON ttt.team_id = t.team_id
        LEFT JOIN ptb_teacher pt ON pt.teacher_id = ttt.teacher_id
        LEFT JOIN ptb_student ps ON ps.team_id = t.team_id
        LEFT JOIN ptb_school sc ON sc.school_id = pt.school_id
        GROUP BY 
            t.team_id, 
            t.team_name, 
            t.project_name, 
            t.detail, 
            t.file, 
            sc.school_name
        ORDER BY t.team_name ASC
    ");
    $stmt->execute();
    $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching teams: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Teams</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>

        table {
            margin-top: 20px;
        }

        .btn {
            min-width: 90px;
        }

        .text-muted {
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">ทีมทั้งหมด</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-primary">
                    <tr class="text-center">
                        <th>ลำดับที่</th>
                        <th>รหัสทีม</th>
                        <th>ชื่อทีม</th>
                        <th>ชื่อโครงการ</th>
                        <th>รายละเอียดของโครงการ</th>
                        <th>ครูที่ปรึกษา</th>
                        <th>นักเรียน</th>
                        <th>ไฟล์</th>
                        <!-- <th>Actions</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($teams)) : ?>
                        <?php foreach ($teams as $index => $team) : ?>
                            <tr>
                                <td class="text-center"><?= $index + 1; ?></td>
                                <td><?= htmlspecialchars($team['team_id']); ?></td>
                                <td><?= htmlspecialchars($team['team_name']); ?></td>
                                <td><?= htmlspecialchars($team['project_name']); ?></td>
                                <td><?= htmlspecialchars($team['detail']); ?></td>
                                <td><?= htmlspecialchars($team['teachers']); ?></td>
                                <td><?= htmlspecialchars($team['students']); ?></td>
                                <!-- <td class="text-center">
                                    <a href="edit_team.php?team_id=<?= $team['team_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete_team.php?team_id=<?= $team['team_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this team?');">Delete</a>
                                </td> -->
                                <td class="text-center">
                                    <?php if ($team['file']) : ?>
                                        <a href="../../forms/uploads/<?= htmlspecialchars($team['file']); ?>" target="_blank" class="btn btn-info btn-sm">ดูไฟล์</a>
                                    <?php else : ?>
                                        <span class="text-muted">No File</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">No teams available</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="text-center mt-4">
            <a href="admin.php" class="btn btn-primary">กลับหน้าหลักแอดมิน</a>
        </div>
    </div>
</body>

</html>