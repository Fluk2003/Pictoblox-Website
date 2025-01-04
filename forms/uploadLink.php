<?php
session_start();

require_once '../databaseConfigs/configsdb.php';

if (isset($_SESSION["userName"])) {
    $userNameSession = $_SESSION["userName"];
    // debug userNameSession
    // echo $userNameSession ;
}

$sqlFetchUploadLinkData = $conn->prepare("SELECT * FROM users3 WHERE userName = :userName");
$sqlFetchUploadLinkData->bindParam(":userName", $userNameSession);
$sqlFetchUploadLinkData->execute();
$fetUploadLinkData = $sqlFetchUploadLinkData->fetch(PDO::FETCH_ASSOC);

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

        h4 {
            margin: 0;
            line-height: 1.5;
            /* ปรับ line-height ให้สอดคล้อง */
        }

        .btn {
            line-height: 1.5;
            /* ให้ปุ่มมีความสูงเท่ากับข้อความ */
        }
    </style>

</head>

<body>
    <nav class="navbar navbar-expand-lg  " style="background-color: #106eea;">
        <div class="container ">
            <a class="navbar-brand text-white" style="font-size: 1.5em;" href="#">มหาวิทยาลัยราชภัฏอุดรธานี</a>
            <button class="navbar-toggler" style="color: white; border: 1px solid white; opacity: 0.7;" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarScroll">
                <div class="d-flex " action="forms/logout_process.php" method="post">
                    <a class="btn btn-outline-light" href="../userProfile.php">กลับไปหน้าโปรไฟล์</a>
                </div>
            </div>
        </div>
    </nav>
    <div>
        <div class="container mx-auto " style="margin-top: 50px;">
            <div class="container mt-5">
                <div class="row d-flex justify-content-between align-items-center">
                    <div class="col-12 col-md-6 d-flex align-items-center">
                        <h4 class="m-0">อัพโหลดลิงก์งาน</h4>
                    </div>
                    <!-- <div class="col-12 col-md-6 d-flex justify-content-end align-items-center">
                        <a class="btn btn-danger " href="../userProfile.php">กลับไปหน้าโปรไฟล์</a>
                    </div> -->
                </div>
                <hr>
            </div>

            <div>
                <form class="container-fluid" action="uploadLink_process.php" method="post">
                    <div class="mb-5">
                        <label for="exampleInputEmail1" class="form-label">
                            ลิงก์งาน Genially ของคุณ : 
                            <?php 
                                if(!empty($fetUploadLinkData["uploadLink1"])) { ?>
                                    <span style="color:green" >มีงานแล้ว</span>
                                <?php }else { ?>
                                    <span style="color: red;" >ยังไม่มีงาน</span>
                                <?php }
                            ?>
                        </label>
                        <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="uploadLink1" placeholder="วางลิงก์งาน Genially ของคุณ" value="<?php echo $fetUploadLinkData["uploadLink1"]; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">
                            ลิงก์งาน Meta Verse ของคุณ : 
                            <?php 
                                if(!empty($fetUploadLinkData["uploadLink2"])) { ?>
                                    <span style="color:green" >มีงานแล้ว</span>
                                <?php }else { ?>
                                    <span style="color: red;" >ยังไม่มีงาน</span>
                                <?php }
                            ?>
                        </label>
                        <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="uploadLink2" placeholder="วางลิงก์งาน Meta Verse ของคุณ" value="<?php echo $fetUploadLinkData["uploadLink2"]; ?>">
                        <div id="emailHelp" class="form-text mt-3">ถ้าคุณอัพโหลดไฟล์งานแล้วงานของคุณจะแสดงอยู่ที่หน้าโปรไฟล์ของคุณ</div>
                    </div>
                    <button type="submit" class="btn btn-primary" name="submitUploadLink">อัพโหลดลิงก์งาน</button>
                </form>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>