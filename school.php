<?php
require_once 'configs/configsdb.php' ;

if(isset($_GET["school_id"])) {
    $school_id = filter_input(INPUT_GET,'school_id' , FILTER_SANITIZE_NUMBER_INT);

    $sqlSchool = $conn->prepare("SELECT * FROM school3 WHERE school_id = :school_id") ;
    $sqlSchool->bindParam(":school_id",$school_id) ;
    $sqlSchool -> execute() ;
    $fetchData = $sqlSchool -> fetch(PDO::FETCH_ASSOC) ;

    echo $fetchData["school_name"] ;
}

?>