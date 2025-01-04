<!-- PHP Section -->
<?php

session_start();
session_destroy();


require_once 'connectDB/configsdb.php';
$sqlSchool = $conn->prepare("SELECT * FROM ptb_school WHERE school_name != 'admin'");
$sqlSchool->execute();

?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>PictoBlox UDRU</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="https://upload.wikimedia.org/wikipedia/th/3/38/LgUDRU.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- Carousel Link -->
  <!-- Swiper CSS -->
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">


  <!-- =======================================================
  * Template Name: Bootslander
  * Template URL: https://bootstrapmade.com/bootslander-free-bootstrap-landing-page-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">


      <a href="index.php" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <img style="height: 40px ;" src="https://upload.wikimedia.org/wikipedia/th/3/38/LgUDRU.png" alt="duru_logo">
        <h1 class="sitename">PictoBlox UDRU</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">หน้าหลัก</a></li>
          <!-- <li><a href="#about">รายละเอียดโครงการ</a></li> -->
          <!-- <li><a href="#features">Features</a></li>
          <li><a href="#gallery">Gallery</a></li> -->
          <!-- <li><a href="#team">Team</a></li> -->
          <!-- <li><a href="#pricing">Pricing</a></li> -->
          <li class="dropdown"><a href="#"><span>เกี่ยวกับ</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="principal.html">หลักการเเละเหตุผล</a></li>
              <li><a href="objective.html">วัตถุประสงค์</a></li>
              <li><a href="matching.html">ความสอดคล้องกับบริบทที่เกี่ยวข้อง</a></li>
              <li><a href="result.html">ผลสัมฤทธิ์ที่คาดว่าจะได้รับ</a></li>
              <li><a href="indicators.html">ตัวชี้วัดตามแผนยุทธศาสตร์</a></li>
              <li><a href="sdg.html">เป้าหมายการพัฒนาที่ยั่งยืน</a></li>
              <li><a href="goal.html">กลุ่มเป้าหมาย</a></li>
              <!-- <li><a href="indicators2.html">ตัวชี้วัดตามแผนยุทธศาสตร์</a></li>   -->
              <li><a href="timing.html">ระยเวลาและสถานที่</a></li>
            </ul>
          </li>
          <li class="dropdown"><a href="#"><span>โรงเรียน</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <?php
              while ($data = $sqlSchool->fetch(PDO::FETCH_ASSOC)) { ?>
                <li><a href="user_school.php?school_id=<?php echo $data["school_id"]; ?>"><?php echo $data["school_name"]; ?></a></li>
              <?php }
              ?>
            </ul>
            </i></a>
          <li><a href="#contact">ติดต่อ</a></li>
          <li><a href="forms/login.php">เข้าสู่ระบบ</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">
      <img src="assets/img/hero-bg-2.jpg" alt="" class="hero-bg">

      <div class="container">
        <div class="row gy-4 justify-content-between">
          <div class="col-lg-4 order-lg-last hero-img" data-aos="zoom-out" data-aos-delay="100">
            <img src="assets/img/unnamed.png" class="img-fluid animated" alt="">
          </div>

          <div class="col-lg-6  d-flex flex-column justify-content-center" data-aos="fade-in">
            <h1> <span>PictoBlox UDRU</span></h1>
            <p>โครงการอบรมการเรียนรู้และพัฒนาโครงงานด้านเทคโนโลยีสารสนเทศด้วยปัญญาประดิษฐ์เพื่อสร้าง
              นวัตกรดิจิทัลรุ่นเยาว์และยกระดับพลเมืองสู่โลกยุคดิจิทัล</p>
            <div class="d-flex">
              <a href="#about" class="btn-get-started">รายละเอียดเพิ่มเติม</a>
              <a href="https://www.youtube.com/watch?v=wB7ONAcT55I"
                class="glightbox btn-watch-video d-flex align-items-center"><i class="bi bi-play-circle"></i><span>ชมวิดีทัศน์</span></a>
            </div>
          </div>

        </div>
      </div>

      <svg class="hero-waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
        viewBox="0 24 150 28 " preserveAspectRatio="none">
        <defs>
          <path id="wave-path" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"></path>
        </defs>
        <g class="wave1">
          <use xlink:href="#wave-path" x="50" y="3"></use>
        </g>
        <g class="wave2">
          <use xlink:href="#wave-path" x="50" y="0"></use>
        </g>
        <g class="wave3">
          <use xlink:href="#wave-path" x="50" y="9"></use>
        </g>
      </svg>

    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row align-items-xl-center gy-5">

          <div class="col-xl-5 content">
            <h3>เกี่ยวกับ Pictoblox</h3>
            <h2>Pictoblox เป็นแพลตฟอร์มที่โดดเด่นในการสร้างโครงการด้านการเขียนโค้ดและหุ่นยนต์</h2>
            <p>โดยช่วยให้ผู้ใช้สามารถสร้างสรรค์โค้ดและสำรวจเทคโนโลยีได้อย่างง่ายดาย นอกจากนี้ยังมีฟีเจอร์ที่ช่วยเพิ่มความเข้าใจและพัฒนาทักษะด้าน STEM อย่างมีประสิทธิภาพ เหมาะสำหรับการเรียนรู้ที่สนุกและสร้างแรงบันดาลใจ</p>
            <!-- ปุ่ม Read More -->
            <!-- <a href="#" class="read-more"><span>Read More</span><i class="bi bi-arrow-right"></i></a> -->
          </div>

          <div class="col-xl-7">
            <div class="row gy-4 icon-boxes">

              <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                <img src="https://i.ytimg.com/vi/BmqFn2_yN_4/maxresdefault.jpg" class="img-fluid animated" alt="">
              </div> <!-- End Icon Box -->

              <div class="col-md-6" data-aos="fade-up" data-aos-delay="300">
                <img src="https://thestempedia.com/wp-content/uploads/2023/06/AI-for-Kids-1.png" class="img-fluid animated" alt="">
              </div> <!-- End Icon Box -->

              <div class="col-md-6" data-aos="fade-up" data-aos-delay="400">
                <img src="https://www.98thpercentile.com/hs-fs/hubfs/What%20services%20does%20Pictoblox%20AI%20offers%20for%20Kids%202.png?width=1200&height=628&name=What%20services%20does%20Pictoblox%20AI%20offers%20for%20Kids%202.png" class="img-fluid animated" alt="">
              </div> <!-- End Icon Box -->

              <div class="col-md-6" data-aos="fade-up" data-aos-delay="500">
                <img src="https://miro.medium.com/v2/resize:fit:1024/0*IN1Ln71Dal_bk5AG.png" class="img-fluid animated" alt="">
              </div> <!-- End Icon Box -->

            </div>
          </div>

        </div>
      </div>

    </section><!-- /About Section -->

    <!-- Features Section -->
    <section id="features" class="features section">

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="100">
            <div class="features-item">
              <img src="https://thestempedia.com/wp-content/uploads/2023/06/Blocks.png" class="img-fluid animated" alt="Blocks" style="width: 50px; height: auto;">
              <h3><a href="" class="stretched-link">Block Coding</a></h3>
            </div>
          </div><!-- End Feature Item -->

          <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="200">
            <div class="features-item">
              <img src="https://thestempedia.com/wp-content/uploads/2023/08/PictoBlox-Python-Logo.png" class="img-fluid animated" alt="Blocks" style="width: 50px; height: auto;">
              <h3><a href="" class="stretched-link">Python Coding</a></h3>
            </div>
          </div><!-- End Feature Item -->

          <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="300">
            <div class="features-item">
              <img src="https://thestempedia.com/wp-content/uploads/2023/06/Machine-Learning.png" class="img-fluid animated" alt="Blocks" style="width: 50px; height: auto;">
              <h3><a href="" class="stretched-link">Machine Learning</a></h3>
            </div>
          </div><!-- End Feature Item -->

          <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="400">
            <div class="features-item">
              <img src="https://thestempedia.com/wp-content/uploads/2023/06/XR-Environment.png" class="img-fluid animated" alt="Blocks" style="width: 50px; height: auto;">
              <h3><a href="" class="stretched-link">3D and XR Studio</a></h3>
            </div>
          </div><!-- End Feature Item -->


        </div>

      </div>

    </section><!-- /Features Section -->

    <!-- Stats Section -->
    <section id="stats" class="stats section light-background">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
            <i class="bi bi-emoji-smile"></i>
            <div class="stats-item">
              <span data-purecounter-start="0" data-purecounter-end="232" data-purecounter-duration="1"
                class="purecounter"></span>
              <p>Happy Clients</p>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
            <i class="bi bi-journal-richtext"></i>
            <div class="stats-item">
              <span data-purecounter-start="0" data-purecounter-end="521" data-purecounter-duration="1"
                class="purecounter"></span>
              <p>Projects</p>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
            <i class="bi bi-headset"></i>
            <div class="stats-item">
              <span data-purecounter-start="0" data-purecounter-end="1463" data-purecounter-duration="1"
                class="purecounter"></span>
              <p>Hours Of Support</p>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
            <i class="bi bi-people"></i>
            <div class="stats-item">
              <span data-purecounter-start="0" data-purecounter-end="15" data-purecounter-duration="1"
                class="purecounter"></span>
              <p>Hard Workers</p>
            </div>
          </div><!-- End Stats Item -->

        </div>

      </div>

    </section><!-- /Stats Section -->

    <!-- Details Section -->
    <section id="details" class="details section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>รายละเอียด</h2>
        <div><span>รายละเอียด</span> <span class="description-title">เพิ่มเติม</span></div>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4 align-items-center features-item">
          <div class="col-md-5 d-flex align-items-center" data-aos="zoom-out" data-aos-delay="100">
            <img src="https://thestempedia.com/wp-content/uploads/2023/06/Block-Coding-1.png" class="img-fluid" alt="">
          </div>
          <div class="col-md-7" data-aos="fade-up" data-aos-delay="100">
            <h3>สร้างเกมและแอนิเมชันด้วยการเขียนโค้ดแบบบล็อก</h3>
            <ul>
              <li><i class="bi bi-check"></i><span> อินเทอร์เฟซที่ใช้งานง่ายสำหรับเด็ก ช่วยสร้างเกมและแอนิเมชันแบบอินเทอร์แอคทีฟ พร้อมเครื่องมือแก้ไขชุดตัวละครและเสียง</span></li>
              <li><i class="bi bi-check"></i> <span>ลากและวางบล็อกสีสันสดใสมาประกอบกันเพื่อสร้างสคริปต์ (โปรแกรม) เพื่อควบคุมตัวละคร</span>
              </li>
              <li><i class="bi bi-check"></i> <span>ค้นหา คัดลอก และส่งออกบล็อก รวมถึงบันทึกสคริปต์ไว้ใช้ในอนาคต
                  บันทึกผลลัพธ์ของโปรเจกต์เป็นวิดีโอได้ง่ายๆ เพียงคลิกปุ่มเดียว
                </span></li>
            </ul>
          </div>
        </div><!-- Features Item -->

        <div class="row gy-4 align-items-center features-item">
          <div class="col-md-5 order-1 order-md-2 d-flex align-items-center" data-aos="zoom-out" data-aos-delay="200">
            <img src="https://thestempedia.com/wp-content/uploads/2023/06/Python-Coding-1.png" class="img-fluid" alt="">
          </div>
          <div class="col-md-7 order-2 order-md-1" data-aos="fade-up" data-aos-delay="200">
            <h3>การเขียนโปรแกรม Python สำหรับเด็ก พร้อมฟีเจอร์ทรงพลัง</h3>
            <ul>
              <li><i class="bi bi-check"></i><span> ตัวแก้ไข Python 3 ที่ครบถ้วน พร้อมฟังก์ชันเสริม เช่น การเพิ่มสไปรต์ ไฟล์โปรเจกต์ และไลบรารี Python</span></li>
              <li><i class="bi bi-check"></i> <span>เครื่องมือแสดงและดีบักโค้ดแบบทีละขั้นตอน พร้อมฟีเจอร์เติมโค้ดอัตโนมัติ
                  โหมด REPL สำหรับการเขียนโค้ดแบบโต้ตอบ พร้อมตัวจัดการแพ็กเกจ PIP ในตัว</span>
              </li>
              <li><i class="bi bi-check"></i> <span>เทอร์มินัลแบบโต้ตอบเพื่อแสดงผลลัพธ์อย่างชัดเจน
                </span></li>
            </ul>
          </div>
        </div><!-- Features Item -->

        <div class="row gy-4 align-items-center features-item">
          <div class="col-md-5 d-flex align-items-center" data-aos="zoom-out">
            <img src="https://thestempedia.com/wp-content/uploads/2023/06/AI-for-Kids-1.png" class="img-fluid" alt="">
          </div>
          <div class="col-md-7" data-aos="fade-up">
            <h3>ปัญญาประดิษฐ์ (AI) เพื่อทำให้โปรเจกต์ของคุณมีความอินเทอร์แอคทีฟ!</h3>
            <ul>
              <li><i class="bi bi-check"></i> <span>เพิ่มความสามารถด้าน AI ให้กับโปรเจกต์ของคุณได้ง่ายๆ เพียงคลิกเดียว! เขียนโค้ดด้วย Block หรือ Python เพื่อรวมฟีเจอร์ AI เข้ากับโปรเจกต์</span></li>
              <li><i class="bi bi-check"></i><span>ใช้ AI ร่วมกับฮาร์ดแวร์เพื่อสร้างโปรเจกต์ เช่น รถยนต์ไร้คนขับ ระบบลงทะเบียนเข้าเรียนอัตโนมัติ เป็นต้น</span>
              </li>
              <li><i class="bi bi-check"></i> <span>สร้างแชทบอทด้วย Chat GPT, การรู้จำเสียงพูด (Speech Recognition) และส่วนขยายแปลงข้อความเป็นเสียงพูด (Text to Speech)!</span>.
              </li>
            </ul>
          </div>
        </div><!-- Features Item -->

        <div class="row gy-4 align-items-center features-item">
          <div class="col-md-5 order-1 order-md-2 d-flex align-items-center" data-aos="zoom-out">
            <img src="https://thestempedia.com/wp-content/uploads/2023/06/Machine-Learning-for-Kids-1.png" class="img-fluid" alt="">
          </div>
          <div class="col-md-7 order-2 order-md-1" data-aos="fade-up">
            <h3>การเรียนรู้ของเครื่อง (Machine Learning) สำหรับเด็ก</h3>
            <ul>
              <li><i class="bi bi-check"></i> <span>ไม่จำเป็นต้องมีประสบการณ์ในการเขียนโค้ดก็สามารถสร้างโมเดล Machine Learning ได้</span></li>
              <li><i class="bi bi-check"></i><span>สร้างโมเดล Machine Learning ใน 7 ประเภทต่างๆ ได้แก่ การจำแนกภาพ (Image Classifier), การตรวจจับวัตถุ (Object Detection), การจำแนกท่ามือ (Hand Pose Classifier), การจำแนกท่าทาง (Pose Classifier), การจำแนกเสียง (Audio Classifier), การจำแนกข้อความ (Text Classifier) และการจำแนกตัวเลขและการทำนาย (Number Classifier & Regression)</span>
              </li>
              <li><i class="bi bi-check"></i> <span>การนำเข้าข้อมูล การฝึกฝน การทดสอบ และการส่งออกโมเดล Machine Learning ไปยังสภาพแวดล้อม Block หรือ Python ทำได้ง่าย</span>.
              </li>
            </ul>
          </div>
        </div><!-- Features Item -->

      </div>

    </section><!-- /Details Section -->

    <!-- Gallery Section -->
    <section id="gallery" class="gallery section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>รูปภาพ</h2>
        <div><span>ตัวอย่าง</span> <span class="description-title">คลังรูปภาพ</span></div>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-0">

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-1.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="https://thestempedia.com/wp-content/uploads/2023/06/QuarkDrive-The-Intelligent-Mobility-Solution-using-Quarky.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-2.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="https://thestempedia.com/wp-content/uploads/2023/06/T-Shirt-Folding-Bot-%E2%80%93-Automate-Your-Surroundings.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-3.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="https://thestempedia.com/wp-content/uploads/2023/06/Kisan-Dost-%E2%80%93-Automation-in-Agriculture.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-4.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="https://thestempedia.com/wp-content/uploads/2023/06/AI-Sign-Language-Interpreter-and-Emergency-System.png" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-5.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="https://thestempedia.com/wp-content/uploads/2023/06/Machine-Learning-Supported-Touchless-Smart-Vending-Device.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-6.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="https://thestempedia.com/wp-content/uploads/2023/06/Squid-Game-v1.2-Experience-the-Thrilling-Red-Light-Green-Light-Game.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-7.jpg" class="glightbox" data-gallery="images-gallery">
                <img src=https://thestempedia.com/wp-content/uploads/2023/06/ParentPal-Smart-New-Parent-Assistant.png" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-8.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="https://thestempedia.com/wp-content/uploads/2023/06/Soil-Doctor-%E2%80%93-Helping-Farmers-Choose-Crops-in-a-Changing-Environment.png" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

        </div>

      </div>

    </section><!-- /Gallery Section -->



    <!-- Team Section -->
    <section id="team" class="team section">
      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>ทีม</h2>
        <div><span>ทีม</span> <span class="description-title">วิทยากร</span></div>
      </div>
      <!-- End Section Title -->

      <div class="container">
        <div class="row gy-4">
          <!-- Team Member -->
          <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="100">
            <div class="member">
              <div class="pic"><img src="assets/img/teach/6.jpg" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h4>อาจารย์คุณาวุฒิ บุญกว้าง</h4>
                <span>Kunawut Boonkwang</span>
              </div>
            </div>
          </div>
          <!-- End Team Member -->

          <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="200">
            <div class="member">
              <div class="pic"><img src="assets/img/teach/1.jpg" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h4>ผศ.ดร.พิศณุ ชัยจิตวณิชกุล</h4>
                <span>Asst.Prof.Dr.Pitsanu Chaichitwanidchakul</span>
              </div>
            </div>
          </div>
          <!-- End Team Member -->

          <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="300">
            <div class="member">
              <div class="pic"><img src="assets/img/teach/2.jpg" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h4>ดร.ปิยวัจน์ ค้าสบาย</h4>
                <span>Dr.Piyawad Kasabai</span>
              </div>
            </div>
          </div>
          <!-- End Team Member -->

          <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="400">
            <div class="member">
              <div class="pic"><img src="assets/img/teach/3.jpg" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h4>ผศ.วิไลพร กุลตังวัฒนา</h4>
                <span>Asst.Prof.Wilaiporn Kultangwattana</span>
              </div>
            </div>
          </div>
          <!-- End Team Member -->

          <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="500">
            <div class="member">
              <div class="pic"><img src="assets/img/teach/4.jpg" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h4>ดร.วรรณสิริ ธุระชน</h4>
                <span>Dr.Wannasiri Thurachon</span>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="500">
            <div class="member">
              <div class="pic"><img src="assets/img/teach/5.jpg" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h4>อาจารย์ภาณุพันธุ์ ชื่นบุญ</h4>
                <span>Phanupan Chuenboon</span>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="500">
            <div class="member">
              <div class="pic"><img src="assets/img/teach/7.jpg" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h4>อาจารย์ขวัญชัย สุขแสน</h4>
                <span>Khwanchai Suksaen</span>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="500">
            <div class="member">
              <div class="pic"><img src="assets/img/teach/8.jpg" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h4>อาจารย์เรวดี พิพัฒน์สูงเนิน</h4>
                <span>Rewadee Piputsoongnern</span>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-4" data-aos="fade-up" data-aos-delay="500">
            <div class="member">
              <div class="pic"><img src="assets/img/teach/9.jpg" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h4>อาจารย์ณรรฐวรรณ์ พูลสน</h4>
                <span>Natthawan Phoonson</span>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>





    <!-- FAQ Section -->
    <section id="faq" class="faq section light-background">
      <div class="container-fluid">
        <div class="row gy-4">
          <!-- FAQ Content -->
          <div class="col-lg-7 d-flex flex-column justify-content-center order-2 order-lg-1">
            <div class="content px-xl-5" data-aos="fade-up" data-aos-delay="100">
              <h3><span>คำถาม </span><strong>ที่พบบ่อย</strong></h3>
              <p>
                คำถามที่พบบ่อยเกี่ยวกับ PictoBlox ซึ่งเป็นแพลตฟอร์มการเรียนรู้การเขียนโปรแกรมและ STEM
                สำหรับเด็กและผู้เริ่มต้น
              </p>
            </div>

            <div class="faq-container px-xl-5" data-aos="fade-up" data-aos-delay="200">
              <!-- FAQ Item 1 -->
              <div class="faq-item faq-active">
                <i class="faq-icon bi bi-question-circle"></i>
                <h3>PictoBlox คืออะไร?</h3>
                <div class="faq-content">
                  <p>
                    PictoBlox เป็นแพลตฟอร์มการเขียนโปรแกรมด้วยบล็อกและ Python ที่ออกแบบมาเพื่อช่วยให้เด็กและผู้เริ่มต้นเรียนรู้วิทยาศาสตร์คอมพิวเตอร์
                    วิศวกรรม และ STEM อย่างสนุกสนาน โดยสามารถสร้างโครงการที่เกี่ยวกับหุ่นยนต์ AI และ IoT ได้ง่ายๆ
                  </p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div>
              <!-- FAQ Item 2 -->
              <div class="faq-item">
                <i class="faq-icon bi bi-question-circle"></i>
                <h3>จะเริ่มต้นใช้งาน PictoBlox ได้อย่างไร?</h3>
                <div class="faq-content">
                  <p>
                    คุณสามารถดาวน์โหลด PictoBlox ได้จาก
                    <a href="https://pictoblox.ai" target="_blank">เว็บไซต์ทางการ</a> รองรับทั้ง Windows, macOS และ Android
                    จากนั้นติดตั้งโปรแกรมและเริ่มต้นเขียนโปรแกรมได้ทันที
                  </p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div>
              <!-- FAQ Item 3 -->
              <div class="faq-item">
                <i class="faq-icon bi bi-question-circle"></i>
                <h3>PictoBlox ใช้ได้กับอุปกรณ์ใดบ้าง?</h3>
                <div class="faq-content">
                  <p>
                    PictoBlox รองรับทั้งคอมพิวเตอร์ (Windows, macOS), สมาร์ทโฟน และแท็บเล็ต (Android, iOS)
                    รวมถึงสามารถใช้งานร่วมกับบอร์ดไมโครคอนโทรลเลอร์ต่างๆ เช่น Arduino, ESP32, และ Raspberry Pi
                  </p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div>
              <!-- FAQ Item 4 -->
              <div class="faq-item">
                <i class="faq-icon bi bi-question-circle"></i>
                <h3>มีโหมด AI หรือ Machine Learning ใน PictoBlox หรือไม่?</h3>
                <div class="faq-content">
                  <p>
                    ใช่! PictoBlox มีโหมด AI และ Machine Learning ที่ช่วยให้ผู้เรียนสร้างโครงการเกี่ยวกับการจดจำภาพ
                    การวิเคราะห์ข้อความ และการสร้างโมเดล Machine Learning แบบง่ายๆ
                  </p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div>
            </div>
          </div>

          <!-- FAQ Image -->
          <div class="col-lg-5 order-1 order-lg-2">
            <img src="assets/img/faq.jpg" class="img-fluid" alt="FAQ Illustration" data-aos="zoom-in" data-aos-delay="100">
          </div>
        </div>
      </div>
    </section>


    <!-- Contact Section -->
    <section id="contact" class="contact section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>ช่องทาง</h2>
        <div><span>ช่องทางการติดต่อ</span> <span class="description-title">กับเรา</span></div>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-lg-4">
            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="200">
              <i class="bi bi-geo-alt flex-shrink-0"></i>
              <div>
                <h3>ที่อยู่</h3>
                <p>เลขที่ 234 หมู่ 12 อาคาร 100 ปี ถนนบ้านเหล่า-ดอนกลอย ตำบลสามพร้าว อำเภอเมือง
                  จังหวัดอุดรธานี 41000
                </p>
              </div>
            </div><!-- End Info Item -->

            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
              <i class="bi bi-telephone flex-shrink-0"></i>
              <div>
                <h3>เบอร์ติดต่อ</h3>
                <p>0-4221-1040- 59 ต่อ 5061-5062 </p>
              </div>
            </div><!-- End Info Item -->

            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
              <i class="bi bi-envelope flex-shrink-0"></i>
              <div>
                <h3>อีเมลล์</h3>
                <p>Kunawut.bo@udru.ac.th</p>
              </div>
            </div><!-- End Info Item -->

          </div>

          <div class="col-lg-8">
            <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up"
              data-aos-delay="200"
              onclick="alertContact()">
              <div class="row gy-4">

                <div class="col-md-6">
                  <input type="text" name="name" class="form-control" placeholder="ชื่อ-นามสกุล" required>
                </div>

                <div class="col-md-6 ">
                  <input type="email" class="form-control" name="email" placeholder="อีเมลล์" required>
                </div>

                <div class="col-md-12">
                  <input type="text" class="form-control" name="subject" placeholder="วิชา" required>
                </div>

                <div class="col-md-12">
                  <textarea class="form-control" name="message" rows="6" placeholder="ข้อความ" required></textarea>
                </div>

                <div class="col-md-12 text-center">
                  <div class="loading">Loading</div>
                  <div class="error-message"></div>
                  <div class="sent-message">Your message has been sent. Thank you!</div>

                  <button type="submit">ส่งข้อความ</button>
                </div>

                <script>
                  const alertContact = () => {
                    alert("ข้อความของคุณถูกส่งแล้ว");
                  }
                </script>

              </div>
            </form>
          </div><!-- End Contact Form -->

        </div>

      </div>

    </section><!-- /Contact Section -->

  </main>

  <footer id="footer" class="footer dark-background">

    <div class="container footer-top">
      <div class="row gy-4 d-flex justify-content-between">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="index.html" class="logo d-flex align-items-center">
            <span class="sitename">PictoBlox UDRU</span>
          </a>
          <div class="footer-contact pt-3">
            <p>เลขที่ 234 หมู่ 12 อาคาร 100 ปี</p>
            <p>ปี ถนนบ้านเหล่า-ดอนกลอย ตำบลสามพร้าว<br> อำเภอเมือง
              จังหวัดอุดรธานี 41000
            </p>
            <p class="mt-3"><strong>เบอร์ติดต่อ:</strong> <span>0-4221-1040- 59 ต่อ 5061-5062</span></p>
            <p><strong>อีเมลล์ :</strong> <span>Kunawut.bo@udru.ac.th</span></p>
          </div>
          <div class="social-links d-flex mt-4">
            <a href=""><i class="bi bi-twitter-x"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

        <div class="col-lg-4 col-md-12 footer-newsletter">
          <h4>ข่าวสาร</h4>
          <p> รับข่าวสารจากเราและรับข้อมูลข่าวสารล่าสุดเกี่ยวกับผลิตภัณฑ์และบริการของ PictoBlox UDRU!</p>
          <form action="forms/newsletter.php" method="post" class="php-email-form">
            <div class="newsletter-form"><input type="email" name="email" placeholder="กรุณากรอกอีเมลของคุณ"><input type="submit" value="สมัครสมาชิก"></div>
            <div class="loading">กำลังโหลด...</div>
            <div class="error-message"></div>
            <div class="sent-message">คำขอสมัครสมาชิกของคุณได้รับการส่งแล้ว ขอบคุณ!</div>
          </form>
        </div>
      </div>
    </div>


    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename"></strong> <span>UDON THANI RAJABHAT UNIVERSITY</span>
      </p>

    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
