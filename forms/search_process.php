<?php

if($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    require_once '../databaseConfigs/configsdb.php' ;

    if(isset($_SESSION["school_id"])) {
        $school_id = $_SESSION["school_id"] ;
    }

    if(isset($_POST["submit_search"])) {
        $search_user = htmlspecialchars($_POST["search_user"]) ;
        // echo $search_user ;
        // echo $school_id ;

        try{
            $sqlSearchUser = $conn->prepare("SELECT * FROM users3 WHERE firstName LIKE :search_firstName OR lastName LIKE :search_lastName ") ;
            $sqlSearchUser->bindParam(":search_firstName" , $search_user);
            $sqlSearchUser->bindParam(":search_lastName" , $search_user);
            $sqlSearchUser->execute() ;

            $fetSearchUser = $sqlSearchUser->fetch(PDO::FETCH_ASSOC );

            

            if($sqlSearchUser->rowCount() > 0) {
                // echo $fetSearchUser["firstName"] ;
                $_SESSION["search_firstName"] = $fetSearchUser["firstName"] ;
                $_SESSION["search_lastName"] = $fetSearchUser["lastName"] ;

                header("location:../user_school.php") ;
            }else {
                echo "not have" ;
            }

        }catch(PDOException $error ) {
            echo "have an error " . $error->getMessage(); 
        }
    }
}


?>