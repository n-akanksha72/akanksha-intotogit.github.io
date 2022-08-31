
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

<!DOCTYPE html>
<html>
    <head>
        <title>Jose David Rueda Torres</title>
        <link type="text/css" rel="stylesheet" href="style.css">
        <h1>Please Log In</h1>
    </head>
    <body>
        <form method="POST">
            <p><label for="login_input01">Email:</label><input type="text" name="email" id="login_input01" size="20"></p>
            <p><label for="login_input02">Password:</label><input type="password" name="pass" id="login_input02" size="20"></p>
            <p>
                <input type="submit" id="button" name="login" onclick="return doValidate()" value="Log In">
            </p>
        </form>
        <form method="POST" action="">
            <input type="submit" id="button" name="cancel" value="Cancel">
        </form>
        

        <script>
            function doValidate(){
                console.log("Validating");
                try{
                    email = document.getElementById("login_input01").value;
                    password = document.getElementById("login_input02").value;

                    if (email == "" || email == null || password == "" || password == null){
                        alert("Both fields must be filled out");
                        return false;
                    }else if (email.indexOf('@') == -1 ) {
                        alert("Email address must contain @");
                        return false;
                    }else{
                        return true;
                    }
                }catch (e){
                    return false;
                }
        }
        </script>
    </body>
</html>
