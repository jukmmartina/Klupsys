<?php
if(isset($_COOKIE["user"])){
    session_destroy();
    unset($_COOKIE['user']); 
    setcookie('user', null, -1);
    echo "<script>
            var timer = setTimeout(function() {
            window.location='login.php'
            }, 100);
            </script>";
            return;
}
else{
    include("connect.php");
    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];
    $sql="SELECT * FROM `administrator` WHERE `username`='$username' AND `password`='$password'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result)){
        echo "Uspješna prijava! Preuspjeravanje na rezervacije...";
        session_start();
        $cookie_name = "user";
        $cookie_value = $username;
        //cookie expires after 8h
        setcookie($cookie_name, $cookie_value, time() + 60*60*8);
        echo "<script>
            var timer = setTimeout(function() {
            window.location='rezervacija.php/?week=0'
            }, 3000);
            </script>";
            return;
    }
    else{
        echo "<h1>Neuspješna prijava! Pokušajte ponovo...</h1>";
        echo "<script>
            var timer = setTimeout(function() {
            window.location='login.html'
            }, 3000);
            </script>";
            return;
    }
}
?>