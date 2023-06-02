<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="schedule.css">
    <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id"
        content="356353610861-7bn2jbgc7g6deda9gaod33ldh1ucuco5.apps.googleusercontent.com">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <title>Administrator login</title>
</head>


<body>
    <header>
        <div class="navbar" class="navbar-top">
            <h1>Klupsy</h1>
            <nav>
                <ul>
                    <li><a href="/navigation/index.html">Home</a></li>
                    <li><a href="/navigation/tretmani.html">Tretmani</a></li>
                    <li><a href="/navigation/rezervacija.php/?week=0">Rezervacija</a></li>
                    <li><a href="/navigation/cjenik.html">Cjenik</a></li>
                    <li><a href="/navigation/kontakt.html">Kontakt</a></li>
                    <li><a><i class="fas fa-user-circle"></i></a></li>
                </ul>
            </nav>
        </div>
    </header>
    <?php
    //ako cookie postoji napravi formu za odjavit se i ispisi trenutnog korisnika
        if(isset($_COOKIE["user"])){
            echo "<form action='administrator_check.php' method='post' class='content'>";
            echo "<h2>Administratorska prijava</h2>";
            echo "<p>Već ste prijavljeni kao " .$_COOKIE["user"] ."</p>";
            echo "<button type='submit' class='button-submit'>Odjavi se</button>";
            echo "</form>";
        }
    //ako cookie ne postoji napravi formu za prijavu admina
        else{
            echo "<form action='administrator_check.php' method='post' class='content'>";
            echo "<h2>Administratorska prijava</h2>";
            echo "<div class='field-group'>";
                echo "<input name='username' id='username' type='text' class='input-field' placeholder='Username' required>";
                echo "<label for='username' class='input-label'>Username</label>";
            echo "</div>";
            echo "<div class='field-group'>";
                echo "<input name='password' id='password' type='password' class='input-field' placeholder='Pasword' required>";
                echo "<label for='password' class='input-label'>Password</label>";
            echo "</div>";
            echo "<button type='submit' class='button-submit'>Prijavi se</button>";
            echo "</form>";
        }
    ?>


    <footer class="footer">
        <div class="social">
            <a href="#"><i class="fab fa-facebook fa-2x"></i></a>
            <a href="#"><i class="fab fa-instagram fa-2x"></i></a>
        </div>
        <p>Copyright &copy; 2022 - Kozmetički salon Klupsy</p>
    </footer>

    <script src="../navigation/index.js"></script>

</body>

</html>