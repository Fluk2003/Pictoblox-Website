<?php

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    session_start();
    require_once 'connectDB/configsdb.php';

    if (isset($_GET["school_id"])) {

        $_SESSION["school_id"] = $_GET["school_id"];
        $school_id = $_SESSION["school_id"];

        // Path for uploaded files
        $dir = 'uploads/';

        try {
            // Fetch users (teachers and students)
            $sqlFetchUserSchool = $conn->prepare("
                SELECT 'teacher' AS role, fname, lname, picture ,email
                FROM ptb_teacher 
                WHERE school_id = :teacher_school_id

                UNION ALL

                SELECT 'student' AS role, fname, lname, picture , email
                FROM ptb_student 
                WHERE school_id = :student_school_id
            ");
            $sqlFetchUserSchool->bindParam(":teacher_school_id", $school_id);
            $sqlFetchUserSchool->bindParam(":student_school_id", $school_id);
            $sqlFetchUserSchool->execute();

            // Fetch school name
            $sqlSchool = $conn->prepare("SELECT school_name FROM ptb_school WHERE school_id = :school_id");
            $sqlSchool->bindParam(":school_id", $school_id);
            $sqlSchool->execute();
            $school = $sqlSchool->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $error) {
            echo "Error: " . $error->getMessage();
            exit;
        }
    } else {
        header("location:search_user.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรไฟล์</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Logo title -->
    <link href="https://upload.wikimedia.org/wikipedia/th/3/38/LgUDRU.png" rel="icon">

    <style>
        .navbar-toggler-icon {
            background-image: url('data:image/svg+xml;charset=utf8,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30"%3E%3Cpath stroke="white" stroke-width="2" d="M4 7h22M4 15h22M4 23h22"/%3E%3C/svg%3E');
        }

        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: contain;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg" style="background-color: #4b0082;">
        <div class="container">
            <a class="navbar-brand text-white" style="font-size: 1.5em;" href="#">ผู้เข้าร่วมโครงการ : <?php echo htmlspecialchars($school["school_name"]); ?></a>
            <button class="navbar-toggler" style="color: white; border: 1px solid white; opacity: 0.7;" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarScroll">
                <div class="d-flex">
                    <a class="btn btn-outline-light" href="index.php">กลับหน้าหลัก</a>
                </div>
            </div>
        </div>
    </nav>
    <div>
        <div class="container-fluid mx-auto" style="margin: 50px 0;">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                <?php while ($fetchUserSchool = $sqlFetchUserSchool->fetch(PDO::FETCH_ASSOC)) {
                    if ($fetchUserSchool['fname'] != 'admin') { ?>
                        <div class="col">
                            <div class="card h-100">
                                <img src="<?php echo $dir . htmlspecialchars($fetchUserSchool["picture"]); ?>"
                                    class="card-img-top mt-3"
                                    alt="<?php echo htmlspecialchars($fetchUserSchool["fname"] . " " . $fetchUserSchool["lname"]); ?>">
                                <div class="card-body">
                                    <p class="card-title">
                                        <?php echo htmlspecialchars($fetchUserSchool["fname"] . " " . $fetchUserSchool["lname"]); ?>
                                    </p>
                                    <p><?php echo htmlspecialchars($fetchUserSchool["email"]); ?></p>
                                    <p class="text-muted">
                                        สถานะ:
                                        <?php
                                        echo $fetchUserSchool['role'] === 'teacher'
                                            ? 'ครู'
                                            : 'นักเรียน';
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>