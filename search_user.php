<?php

session_start();
require_once 'databaseConfigs/configsdb.php';

    // path localhost
    $dir = 'uploads/';

    // path server
    // $dir = "../forms/uploads/" ;

if (isset($_SESSION["school_id"])) {
    $school_id = $_SESSION["school_id"];
    // echo $school_id ;
}

if (isset($_SESSION["search_firstName"]) || isset($_SESSION["search_lastName"])) {
    // echo $_SESSION["search_firstName"] ;
    // echo $_SESSION["search_lastName"] ;
    $search_firstName = $_SESSION["search_firstName"];
    $search_lastName = $_SESSION["search_lastName"];

    try {

        // fetch Data Search User
        $sqlSearchUser = $conn->prepare("SELECT * FROM users3 JOIN school3 ON users3.school_id = school3.school_id WHERE firstName = :search_firstName AND lastName = :search_lastName");
        $sqlSearchUser->bindParam(":search_firstName", $search_firstName);
        $sqlSearchUser->bindParam(":search_lastName", $search_lastName);
        $sqlSearchUser->execute();

        if ($sqlSearchUser->rowCount() > 0) {
            // echo "have" ;
        } else {
            echo "not have";
        }

        // $fetSearchUser = $sqlSearchUser->fetch(PDO::FETCH_ASSOC) ;

        // fetch school Name
        $sqlSchool = $conn->prepare("SELECT * FROM school3 WHERE school_id = :school_id");
        $sqlSchool->bindParam(":school_id", $school_id);
        $sqlSchool->execute();
        $school = $sqlSchool->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $error) {
        echo "have an error " . $error->getMessage();
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ค้นหาผู้เข้าร่วมโครงการ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Logo title -->
    <link href="https://upload.wikimedia.org/wikipedia/th/3/38/LgUDRU.png" rel="icon">

    <style>
        .navbar-toggler-icon {
            background-image: url('data:image/svg+xml;charset=utf8,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30"%3E%3Cpath stroke="white" stroke-width="2" d="M4 7h22M4 15h22M4 23h22"/%3E%3C/svg%3E');
        }

        .card-img-top {
            width: 100%;
            /* ทำให้รูปภาพขยายเต็มความกว้างของการ์ด */
            height: 200px;
            /* กำหนดความสูงแบบ fixed */
            object-fit: contain;
            /* ตัดส่วนเกินของรูปภาพให้พอดีกับขนาด */
        }
    </style>

</head>

<body>
    <nav class="navbar navbar-expand-lg  " style="background-color: #106eea;">
        <div class="container ">
            <a class="navbar-brand text-white" style="font-size: 1.5em;" href="#"><?php echo $school["school_name"] ?></a>
            <button class="navbar-toggler" style="color: white; border: 1px solid white; opacity: 0.7;" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarScroll">
                <div class="d-flex ">
                    <a class="btn btn-outline-light" href="user_school.php?school_id=<?php echo $school["school_id"]; ?>">กลับหน้าชมผู้เข้าร่วมทั้งหมด</a>
                </div>
            </div>
        </div>
    </nav>
    <div>
        <div class="container mx-auto " style="margin-top: 50px;">
            <div class="container mt-4">
                <div class="row align-items-center">
                    <div class="col-12 col-md-6">
                        <h4>ค้นหาผู้เข้าร่วมโครงการ</h4>
                    </div>
                    <!-- <div class="col-12 col-md-6">
                        <form class="d-flex mb-2 mb-lg-0 mt-4 w-100" method="post" action="forms/search_process.php">
                            <input class="form-control me-2 w-100" style="min-width: 200px; max-width: 500px;" type="search" name="search_user" placeholder="ค้นหาแค่ชื่อจริง หรือ นามกุล" aria-label="Search">
                            <button class="btn btn-primary" type="search" name="submit_search">ค้นหา</button>
                        </form>
                    </div> -->
                </div>
                <hr>
            </div>
            <div>
                <div class="container text-center">
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
                        <?php while ($fetchUserSchoolAll = $sqlSearchUser->fetch(PDO::FETCH_ASSOC)) {  ?>
                            <div class="col">
                                <div class="card h-100" style="width: 100%;">
                                    <img src="<?php echo $dir . $fetchUserSchoolAll["userpic"]; ?>" class="card-img-top mt-3" alt="<?php echo $fetchUserSchoolAll["firstName"] . " " . $fetchUserSchoolAll["lastName"]; ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $fetchUserSchoolAll["firstName"] . " " . $fetchUserSchoolAll["lastName"]; ?></h5>
                                        <p class="card-text">
                                            <?php
                                            if (!empty($fetchUserSchoolAll["uploadLink1"])) {  ?>
                                        <p>มีผลงาน Genially แล้ว : <a href="<?php echo $fetchUserSchoolAll["uploadLink1"]; ?>" target="_blank" style="text-decoration: none;">คลิ๊กที่นี่</a> </p>
                                    <?php } else {
                                                echo "ยังไม่มีผลงาน Genially";
                                            }
                                    ?>
                                    </p>
                                    <p class="card-text">
                                        <?php
                                        if (!empty($fetchUserSchoolAll["uploadLink2"])) {  ?>
                                    <p>มีผลงาน Meta Verse แล้ว : <a href="<?php echo $fetchUserSchoolAll["uploadLink2"]; ?>" target="_blank" style="text-decoration: none;">คลิ๊กที่นี่</a> </p>
                                <?php } else {
                                            echo "ยังไม่มีผลงาน Meta Verse";
                                        }
                                ?>
                                </p>
                                <p class="card-text">ครูสอนวิชา : <?php echo $fetchUserSchoolAll["subject"]; ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>