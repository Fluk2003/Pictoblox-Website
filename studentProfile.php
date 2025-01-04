<?php
session_start();
require_once 'connectDB/configsdb.php';

// Ensure session variable exists and user is authenticated
if (isset($_SESSION["userName"])) {
    $userNameSession = $_SESSION["userName"];
} else {
    // Redirect to login page if not authenticated
    header("location:index.php");
    exit();
}

try {
    // Fetch user data from the database using a prepared statement
    $sqlFetchUserData = $conn->prepare("SELECT * FROM ptb_student 
                                        JOIN ptb_school ON ptb_student.school_id = ptb_school.school_id  
                                        WHERE ptb_student.username = :userName");
    $sqlFetchUserData->bindParam(":userName", $userNameSession);
    $sqlFetchUserData->execute();

    // Fetch the result
    $result = $sqlFetchUserData->fetch(PDO::FETCH_ASSOC);

    // Handle case where no data was returned
    if (!$result) {
        throw new Exception("No user found with this username.");
    }

    // Path for user images
    // $dir = 'uploads/';
    $dir = '../forms/uploads/';
} catch (PDOException $error) {
    // Handle SQL errors
    echo "Database error: " . $error->getMessage();
    exit();
} catch (Exception $e) {
    // Handle other errors like no data found
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรไฟล์</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://upload.wikimedia.org/wikipedia/th/3/38/LgUDRU.png" rel="icon">
    <style>
        .navbar-toggler-icon {
            background-image: url('data:image/svg+xml;charset=utf8,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30"%3E%3Cpath stroke="white" stroke-width="2" d="M4 7h22M4 15h22M4 23h22"/%3E%3C/svg%3E');
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg" style="background-color: #6a0dad;">
        <div class="container">
            <a class="navbar-brand text-white" style="font-size: 1.5em;" href="#">มหาวิทยาลัยราชภัฏอุดรธานี</a>
            <button class="navbar-toggler" style="color: white; border: 1px solid white; opacity: 0.7;" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarScroll">
                <form class="d-flex" action="forms/logout_process.php" method="post">
                    <button class="btn btn-outline-light" type="submit" name="logout">ออกจากระบบ</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mb-5" style="margin-top: 50px;">
        <div class="container mt-4">
            <div class="row align-items-center">
                <div class="col-12 col-md-6">
                    <h4>หน้าโปรไฟล์</h4>
                </div>
                <div class="col-12 col-md-6 text-md-end text-center mt-3 mt-md-0">
                    <div class="d-flex flex-wrap justify-content-center justify-content-md-end gap-2">
                        <a class="btn btn-warning mb-2"
                            href="forms/student_edit.php?student_id=<?php echo $result["student_id"]; ?>">แก้ไขข้อมูลส่วนตัว</a>
                    </div>
                </div>
            </div>
            <hr>
        </div>

        <div class="container text-center mt-5">
            <div class="row flex-column flex-md-row">
                <div class="col mb-5">
                    <img style="border: none; width: 50%; background-size: contain;"
                        src="<?php echo $dir . $result["picture"]; ?>" alt="Profile Picture">
                </div>
                <div class="col text-start">
                    <h4>ข้อมูลของคุณ
                        <?php echo htmlspecialchars($result["fname"]) . " " . htmlspecialchars($result["lname"]); ?>
                    </h4>
                    <hr>
                    <p>ชื่อจริง : <?php echo htmlspecialchars($result["fname"]); ?></p>
                    <p>นามสกุล : <?php echo htmlspecialchars($result["lname"]); ?></p>
                    <p>ชั้นการเรียน : <?php echo htmlspecialchars($result["grade"]); ?></p>
                    <p>สายการเรียน : <?php echo htmlspecialchars($result["major"]); ?></p>
                    <p>Facebook : <?php echo htmlspecialchars($result["fb"]); ?></p>
                    <p>Line : <?php echo htmlspecialchars($result["line"]); ?></p>
                    <p>Email : <?php echo htmlspecialchars($result["email"]); ?></p>
                    <p>เบอร์โทรศัพท์ : <?php echo htmlspecialchars($result["number"]); ?></p>
                    <p>โรงเรียน : <?php echo htmlspecialchars($result["school_name"]); ?></p>
                    <p style="display: inline;">ใบประกาศของคุณ :
                        <?php
                        if (empty($result["certificate"])) {
                            echo "<span>ยังไม่มีใบประกาศ</span>";
                        } else {
                            if ($result["cert_status"] == "false") {
                                echo '<span class="text-warning">รอการอนุมัติ</span>';
                            } else {
                                // localhost path
                                $dir = "../forms/uploads";

                                // server path
                                // $dir = "../forms/uploads/" ;

                                echo '<span style="display: inline;" class="text-success" >ได้รับแล้ว <a href="' . $dir . $result["certificate"] . '" target="_blank">คลิ๊กเพื่อเช็คใบประกาศ</a></span>';
                            }
                        }
                        ?>

                    </p>
                    <div class="mt-3" style="display: flex; align-items: center; gap: 10px;">
                        <?php if ($result["status"] === "false" || $result["status"] === 0): ?>
                            <div>คุณยังไม่มีทีม</div>
                        <?php else: ?>
                            <a href="forms/manage_team copy.php?student_id=<?php echo htmlspecialchars($result["student_id"]); ?>"
                                class="btn btn-success">ตรวจสอบรายละเอียดทีมได้ที่นี่</a>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>