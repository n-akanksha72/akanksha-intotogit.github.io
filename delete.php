<?php
    require_once "pdo.php";
    session_start();
    $salt = 'XyZzy12*_';

    if (isset($_POST["cancel"])){
        header('Location: index.php');
    }

    if (isset($_SESSION['error']) ) {
        echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
        unset($_SESSION['error']);
    }

    if (isset($_POST['login']) ) {
        $sql = ('SELECT user_id, name FROM users WHERE password = :pass AND email = :email');

        $prep = $pdo -> prepare($sql);
        $prep -> execute(array(
            ':pass' => hash('md5', $salt.$_POST['pass']),
            ':email' => $_POST['email']
        ));
        $row = $prep->fetch(PDO::FETCH_ASSOC);
   
        if ($row !== false){
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['name'] = $row['name'];
            header("Location: view.php");
        }else{
            $_SESSION['error'] = 'Wrong data';
            header("Location: login.php");
        }  
    }
?>
