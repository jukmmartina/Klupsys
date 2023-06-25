<?php
//provjeri postoji li cookie, ako da onda je admin prijavljen i poslan je zahtjev za odjavom
// poništi cookie i postavi ga na null i vrijeme na -1 (traje -1 sekundu, tj def ga vise nema)
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
//ako cookie ne postoji onda je poslan zahtjev za prijavom, spoji se na bazu, dohvati argumente
//koje je poslala forma i provjeri postoji li u bazi taj admin, ako postoji kreiraj cookie i preusmjeri 
//ga na rezervacije da moze provjeriti tko je sta rezervirao, ako prijava nije uspjesna ispisi poruku
//i vrati na login
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
            }, 30);
            </script>";
            return;
    }
    else{
        echo "<h1>Neuspješna prijava! Pokušajte ponovo...</h1>";
        echo "<script>
            var timer = setTimeout(function() {
            window.location='login.php'
            }, 3000);
            </script>";
            return;
    }
}
?>