<?php 

require_once('../../private/initialize.php');
require_once('index.php');

    if($_SERVER["REQUEST_METHOD"] !== "POST") {
        header('Location: ' . 'index.php');
        exit;
    } else {
        $user_info = [
            "id" => $_SESSION["id"],
            "passwordID" => $_POST["password-box-id"],
            "passwordValue" => strip_space($_POST["password-box-value"])
        ];
        
        if(update_password($user_info)) {
            $_POST["password-box-id"] = "";
            $_POST["password-box-value"] = "";
            echo <<<EOF
                <script type="text/javascript">
                alert("Update Successful!");
                window.location.href="index.php";
                </script>;
            EOF;
        } else {
            echo <<<EOF
                <script type="text/javascript">
                alert("Password Cannot Be Update");
                window.location.href="index.php";
                </script>;
            EOF;
        }
       

    }

?>
