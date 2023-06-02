
<!DOCTYPE html>
<html>
 
<head>
    <title>Insert reservation</title>
</head>
 
<body>
        <?php
        //Koristi PHPMailer za slanje mailova jer mi pomocu mail() funkcije nije radilo
        use PHPMailer\PHPMailer\PHPMailer;
        require_once '../PHPMailer/src/Exception.php';
        require_once '../PHPMailer/src/PHPMailer.php';
        require_once '../PHPMailer/src/SMTP.php';
        $mail = new PHPMailer(true);
        
        //spoji se na bazu
        include("connect.php");
        $is_found = FALSE;
        //Dohvati varijable iz POST requesta koji salje forma
        $name = $_REQUEST['name'];
        $email = $_REQUEST['email'];
        $telephone = $_REQUEST['telephone'];
        $group = $_REQUEST['group'];
        $treatment = $_REQUEST['treatment'];
        $date = $_REQUEST['date'];
        $time = $_REQUEST['time'];

        //izracunaj dan u tjednu i ako je nedjelja ili subota odbij spremanje, 
        //ispisi poruku 3s i ponovno ucitaj stranicu za rezerviranje
        $weekday = intval(date("w", strtotime($date)))%7;
        if($weekday==0 || $weekday==6){
            echo "<h4>Termini se ne mogu rezervirati za vikend! Pokušajte ponovo..<h4>";
            echo "<script>
                var timer = setTimeout(function() {
                window.location='rezervacija.php/?week=0'
                }, 3000);
                </script>";
            return;
        }

        //Probaj dohvatiti tretman iz baze i provjeri podudara li se grupa tretmana, 
        //ako da dohvati trajanje tretmana, ako ne ispisi pogresku i vrati ponovno na rezervaciju
        $sql = "SELECT * FROM `tretmani` WHERE `treatment`='$treatment'";
        if($result = mysqli_query($conn, $sql)){
            while($row = $result->fetch_assoc()){
                if($row['group']==$group){
                    $duration = $row['duration'];
                    $is_found = TRUE;
                }
            }
            if(!$is_found){
                echo "<h4>Pogreška! Pokušajte ponovo..<h4>";
                echo "<script>
                    var timer = setTimeout(function() {
                    window.location='rezervacija.php/?week=0'
                    }, 3000);
                    </script>";
                    return;
            }
        }

        //odredi pocetak i kraj termina te provjeri ulazi li u termin pauze
        $start = strtotime($time);
        $end = date("H:i",strtotime('+' .$duration .' minutes', $start));
        $start = date("H:i", $start);
        $pause_start = date("H:i",strtotime("16:00"));
        $pause_end = date("H:i",strtotime("16:30"));
        if((($start>=$pause_start) && ($start<$pause_end))  || (($end>$pause_start) && ($end<=$pause_end))  || (($pause_start>=$start) && ($pause_start<$end))){
            echo "<h4>Rezervacija ulazi u termin pauze! Pokušajte ponovo...<h4>";
            echo "<script>
                var timer = setTimeout(function() {
                window.location='rezervacija.php/?week=0'
                }, 3000);
                </script>";
                return;
        }

        //provjeri izlazi li termin van radnog vremena
        $work_start = date("H:i" ,strtotime("13:00"));
        $work_end = date("H:i" ,strtotime("20:00"));
        if($start < $work_start || $end > $work_end){
            echo "<h4>Rezervacija izlazi van termina radnog vremena! Pokušajte ponovno...</h4>";
            echo "<script>
                var timer = setTimeout(function() {
                window.location='rezervacija.php/?week=0'
                }, 3000);
                </script>";
                return;
        }

        //provjeri preklapa li se termin s vec postojecim terminom u bazi
        $sql = "SELECT * FROM `rezervacije` WHERE `date`='$date';";
        $result = mysqli_query($conn, $sql);
        while($row = $result->fetch_assoc()){
            $start_comp = strtotime($row['start_time']);
            $end_comp = date("H:i",strtotime('+' .$row['duration'] .' minutes', $start_comp));
            $start_comp = date("H:i", $start_comp);
            if((($start>=$start_comp) && ($start<$end_comp))  || (($end>$start_comp) && ($end<=$end_comp))  || (($start_comp>=$start) && ($start_comp<$end))){
                echo "<h4>Rezervacija ulazi u zauzeti termin! Pokušajte ponovo...<h4>";
                echo "<script>
                var timer = setTimeout(function() {
                window.location='rezervacija.php/?week=0'
                }, 3000);
                </script>";
                return;
            }
        }

        //ako je sve uredno proslo unesi rezervaciju u bazu i posalji mail potvrde
        $sql = "INSERT INTO `rezervacije` (`date`, `start_time`, `duration`, `group`, `treatment`, `name`, `email`, `number`)
                VALUES ('$date','$time','$duration','$group','$treatment','$name', '$email', '$telephone')";
        if(mysqli_query($conn, $sql)){
            echo "Rezervacija uspješna! Preusmjeravanje na početnu...";

            //Postavljanje i slanje maila
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'klupsys@gmail.com';
            $mail->Password = 'kmoagudkcnyukbgu';
            $mail->SMTPSecure = 'tls';
            $mail->Port = '587';
            $mail->setFrom('klupsys@gmail.com');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "Potvrda rezervacije";
            $mail->Body =  'Rezerviran tretman ' .$group .': ' .$treatment .' u terminu ' .$date .' s početkom u ' .$time .' za osobu ' .$name .'. <br>
                            U slučaju bilo kakvih pitanja obratite nam se putem maila klupsys@gmail.com ili putem telefona +35897276027<br>
                            Hvala Vam na povjerenju!';
            $mail->send();
            
        } else{
            echo "ERROR: Hush! Sorry $sql. "
                . mysqli_error($conn);
        }
         
        // Close connection
        mysqli_close($conn);
        ?>
    <!-- Vrati na pocetnu ako je rezervacija uspjela  -->
    <script>
        var timer = setTimeout(function() {
           window.location='index.html'
        }, 3000);
    </script>

</body>
 
</html>