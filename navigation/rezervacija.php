<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/navigation/style.css">
    <link rel="stylesheet" href="/navigation/schedule.css">
    <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="356353610861-7bn2jbgc7g6deda9gaod33ldh1ucuco5.apps.googleusercontent.com">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <title>Rezervacija</title>
</head>


<body>
    <header>
        <div class="navbar" class="navbar-top">
            <h1>Klupsys</h1>
            <nav>
                <ul>
                    <li><a href="/navigation/index.html">Home</a></li>
                    <li><a href="/navigation/tretmani.html">Tretmani</a></li>
                    <li><a>Rezervacija</a></li>
                    <li><a href="/navigation/cjenik.html">Cjenik</a></li>
                    <li><a href="/navigation/kontakt.html">Kontakt</a></li>
                    <li><a href="/navigation/login.php"><i class="fas fa-user-circle"></i></a></li>
                </ul>
            </nav>
        </div>
    </header>

    <?php
    //spoji se na bazu i postavi vremensku zonu (zbog rezervacije)
        include("connect.php");
        date_default_timezone_set('Europe/Sarajevo');
    ?>
    <!-- Kreiraj formu za rezervaciju  -->
    <form action="/navigation/insert.php" method="post" class="reservation">
      <div class = "field-group">
          <input name="name" id="name" type="text" class = "input-field" placeholder="Ime i prezime" required>
          <label for="name" class="input-label">Ime i prezime</label>
      </div>
      <div class = "field-group">
          <input name="email" id="email" type="email" class = "input-field" placeholder="E-mail" required>
          <label for="email" class="input-label">E-mail</label>
      </div>
      <div class = "field-group">
          <input name="telephone" id="telephone" type="tel" class = "input-field" placeholder="Broj telefona" required>
          <label for="telephone" class="input-label">Broj telefona</label>
      </div>
      <script>
      </script>

      <div class = "field-group">
            <select name="group" id="group" class = "input-field" placeholder="Odaberi vrstu tretmana">
                <option value="select">Odaberi vrstu tretmana</option>
                <?php
                //tretmani u bazi su redani po grupama jedan iza drugog pa se moze na ovakav nacin dohvacati razlicite grupe
                //inace bi se morao dodati array pa u njega spremati opcije i provjeravati jesu unique
                    $sql = "SELECT * FROM tretmani";
                    $result = $conn->query($sql);
                    while($row = $result->fetch_assoc()) {
                    if($old_res != $row['group']) echo "<option value='". $row['group']. "'>" . $row['group']. "</option>";
                    $old_res = $row['group'];
                }
                ?>
            </select>
              <label for="group" class="input-label">Vrsta tretmana</label>
      </div>
      <div class = "field-group">
            <select name="treatment" id="treatment" class = "input-field" placeholder="Odaberi tretman">
                <!-- Dok se ne odabere grupa jedina opcija za odabir je 'Odaberi tretman'  -->
                <option value="select">Odaberi tretman</option>
            </select>
            <label for="treatment" class="input-label">Tretman</label>
      </div>
      <div class = "field-group">
          <?php
          //za odabir datuma su omogucena tjedna (trenutni i naredna)
          //ako je proslo 19h vise se ne moze rezervirati termin za trenutni datum nego tek od sutrasnjeg datuma
            if(date("H")>date("H", strtotime("19:00"))){
                $min_day = date('Y-m-d', strtotime('+1 day'));
            }
            else{
                $min_day = date('Y-m-d');
            }
            //trenutni i iduca 3, iz cega s racuna max_day (zanji an za rezervaciju)
            $week = date('w') + 3*7;
            $max_day = date('Y-m-d', strtotime("+". (intval($week/7)*7-($week%7-1)+4) ."days"));
            //min_day i max_day se predaju kao opseg za kalendar
            echo "<input name='date' id='date' type='date' required class = 'input-field' min='" .$min_day ."' max='" .$max_day ."'>"
          ?>
          <label for="date" class="input-label">Datum</label>
      </div>
      <div class = "field-group">
        <!-- Radno vrijeme od 13-20h, ali se mora dodatno provjeriti zbog trajanja termina -->
          <input name="time" id="time" type="time" required class = "input-field" min="13:00" max="20:00">
          <label for="time" class="input-label">Vrijeme</label>
      </div>
      <button type="submit" class="button-submit">Rezerviraj</button>
  </form>

  <div class="left-right">
        <span></span>
        <?php
            //dohvati iz url-a week=n i spremi u varijablu page iz koje se racuna trazeni tjeda za prikaz (week)
            $page = intval($_GET["week"]);
            $week = date('w') + $page*7;
            //ako je week=0 onda strelica za prethodi tjedan ne radi nista
            if($page == 0){
                echo "<a><i class='fa fa-chevron-left' aria-hidden='true'></i></a>";
            }
            //ako nije ostavi url za week=trenutni-1
            else{
                echo "<a href='?week=" .($page-1) ."'><i class='fa fa-chevron-left' aria-hidden='true'></i></a>";
            }
            //izracuaj prvi i zadnji dan u tjednu za prikaz u rasporedu
            $first_day_of_week = date('d.m.Y', strtotime("+". (intval($week/7)*7-($week%7-1)) ." days"));
            $last_day_of_week = date('d.m.Y', strtotime("+". (intval($week/7)*7-($week%7-1)+4) ."days"));
            echo "<span>" .$first_day_of_week ." - " .$last_day_of_week ."</span>";
            //ako je week=3 onda strelica za otvaranje iduceg tjedna ne radi nista
            if($page == 3){
                echo "<a><i class='fa fa-chevron-right' aria-hidden='true'></i></a>";
            }
            else{
                echo "<a href='?week=" .($page+1) ."'><i class='fa fa-chevron-right' aria-hidden='true'></i></a>";
            }
        ?>
        <span></span>
  </div>

<!-- Kreiraj container za raspored termina u kalendaru  -->
<div class = "calendar-container">

<div class="calendar-header">
  <ul class="weekdays">
      <li>PON</li>
      <li>UTO</li>
      <li>SRI</li>
      <li>ČET</li>
      <li>PET</li>
  </ul>
  <ul class = "dates">
  <?php
  //iz nekog razloga moram ponovo definirati ili mi rasporedd bude prazan
      $first_day_of_week = date('Y-m-d', strtotime("+". (intval($week/7)*7-($week%7-1)) ." days"));
      $last_day_of_week = date('Y-m-d', strtotime("+". (intval($week/7)*7-($week%7-1)+4) ."days"));
      //ispisi sve radne datume u tjednu u rasporedu
      for($i=0; $i<5; $i++){
          $temp_date = date('d.m.Y.', strtotime($first_day_of_week ."+" .$i ."days"));
          echo "<li>" .$temp_date ."</li>";
      }
  ?>
  </ul>
</div>
<div class= "timeslots-container">
  <ul class="timeslots">
    <!-- vremena -->
      <li>13<sup>00</li>
      <li>14<sup>00</li>
      <li>15<sup>00</li>
      <li>16<sup>00</li>
      <li>17<sup>00</li>
      <li>18<sup>00</li>
      <li>19<sup>00</li>
  </ul>
</div>
<div class="event-container">
  <div class="slot pauza">
    <!-- Odmah ubaci pauzu u raspored -->
      <span>PAUZA</span><br>
      <span>16:00-16:30</span>
  </div>
  <?php
  //spoji se na bazu i dohvati sve rezervacije i prikazi one koje se nalaze izmedu first i last day of week,
  // pojedine grupe tretmana su prikazane razlicitom bojom radi lakseg snalazenja, a velicina kontenjera odgovara
  //trajanju tretmana
      include("connect.php");
      $sql = "SELECT * FROM `rezervacije`";
      $rezervacije = mysqli_query($conn, $sql);
      while($row = $rezervacije->fetch_assoc()){
          if($row['date']>=$first_day_of_week && $row['date']<=$last_day_of_week){
              $start = strtotime($row['start_time']);
              $roww = (date("H", $start)-13)*60/5 + date("i", $start)/5;
              $column = date('w', strtotime($row['date']));
              $duration = $row['duration'];
              $end = date("H:i",strtotime('+' .$duration .' minutes', $start));
              $start = date("H:i", $start);
              $group = $row['group'];
              switch($group) {
                  case "Tretmani lica":
                      $color = "#ECDB54";
                      break;
                  case "Depilacija voskom":
                      $color = "#6CA0DC";
                      break;
                  case "Depilacija šećernom pastom":
                      $color = "#944743";
                      break;
                  case "SHR trajna depilacija za žene":
                      $color = "#DBB2D1";
                      break;
                  case "SHR trajna depilacija za muškarce":
                      $color = "#EC9787";
                      break;
                  case "Fibroblast (plazma) tretman: nekirurško zatezanje kože":
                      $color = "#00A68C";
                      break;
                  case "Pedikura":
                      $color = "#645394";
                      break;
                  case "Obrve":
                      $color = "#6C4F3D";
                      break;
                  case "Trepavice":
                      $color = "#EBE1DF";
                      break;
                  case "HIFU tretmani":
                      $color = "#BC6CA7";
                      break;
                  case "Tretmani tijela":
                      $color = "#BFD833";
                      break;
              }
              $from_to = $start ."-" .$end;
              $style = "height:" .$duration . "px; grid-row:" .$roww ."; grid-column:" .$column ."/" .$column ."; background: " .$color .";";
              //ukoliko je administrator prijavljen prikazi dodatne informacije o tretmani (tko je rezervirao, kontakt i kada)
              if(isset($_COOKIE["user"])){
                $additional_info = $row['name'] .", " .$row['email'] .", " .$row['number'];
                echo "<div class='slot' style='" .$style ."'><a href='' style='text-decoration:none; color=black;' title='" .$additional_info ."'>" .$row['treatment'] ."(" .$from_to .")</a></div>";
              }
              //ako nije prijavljen administrator samo pokazi da je termin zauzet
              else{
                echo "<div class='slot' style='" .$style ."'>Rezervirano (" .$from_to .")</a></div>";
              }
          }
      }
  ?>
</div>
</div>


<footer class="footer">
        <div class="social">
            <a href="#"><i class="fab fa-facebook fa-2x"></i></a>
            <a href="#"><i class="fab fa-instagram fa-2x"></i></a>
        </div>
        <div class="desc">Copyright &copy; 2022 - Kozmetički salon Klupsy</div>
    </footer>

   <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
    //ovo bi trebalo kada se odabere grupa tretmana prikazati samo tretmane iz te grupe za laksi odabir
    //ali iz nekog razloga je POST uvjek prazan (prbala sam i GET i REQUEST i s cookies napraviti, ali
    // je uvijek pazno sve, a cookies mi kasne), mozda ga zezaju nasi znakovi (č, ć...), ali osim toga radi
    //sto je ocekivano, kada se odabere grupa tretmana izlistaju se svi tretmani (nalazost ne samo iz te grupe)
    var code =
    `<option value="select">Odaberi tretman</option>
    <?php 
        if(isset($_REQUEST["group"]) && !empty($_REQUEST["group"])){
            $sql = "SELECT * FROM tretmani WHERE group=" .$_REQUEST["group"];
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
                echo "<option value='". $row['treatment']. "'>" . $row['treatment']. "</option>";
            }
        }
        else {
            $sql = "SELECT * FROM tretmani";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
                echo "<option value='". $row['treatment']. "'>" . $row['treatment']. "</option>";
            }
        }
    ?>`;
    $(document).ready(function() 
    { 
        //kada se promijeni vrijednost grupe putem selecta dohvati novu vrijednost
        $('#group').on('change',function(){ 
            var group = $(this).val();  
            if(group) 
            { 
                console.log(group);
                //Posalji post zahtjev na stranicu rezervacija  koja onda (ne)moze na osnovu grupe filtrirati tretmane
                $.ajax({ type:'POST', url:'/navigation/rezervacija.php',  data: ('group=' + group), contentType: "html", success:function(html) 
                { 
                    $('#treatment').html(code);
                }  
                }); 
            } 
            else{  
                //ako nema grupe posalji da treba odabrati grupu tretmana
                $('#treatment').html('<option value="">Odabrite grupu tretmana</option>'); 
            } 
        });
    });
    </script>
</body>

</html>