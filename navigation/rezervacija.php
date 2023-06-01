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
        include("connect.php");
        date_default_timezone_set('Europe/Sarajevo');
    ?>

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
                <option value="select">Odaberi tretman</option>
                <?php
                    if(isset($_POST["group"]) && !empty($_POST["group"])){
                        $sql = "SELECT * FROM tretmani WHERE group=" .$_POST["group"];
                        $result = $conn->query($sql);
                        while($row = $result->fetch_assoc()) {
                            echo "<option value='". $row['treatment']. "'>" . $row['treatment']. "</option>";
                        }
                    }
                    else{
                        $result = $conn->query($sql);
                        while($row = $result->fetch_assoc()) {
                            echo "<option value='". $row['treatment']. "'>" . $row['treatment']. "</option>";
                        } 
                    }
                ?>
            </select>
            <label for="treatment" class="input-label">Tretman</label>
      </div>
      <div class = "field-group">
          <?php
            if(date("H")>date("H", strtotime("19:00"))){
                $min_day = date('Y-m-d', strtotime('+1 day'));
            }
            else{
                $min_day = date('Y-m-d');
            }
            $week = date('w') + 3*7;
            $max_day = date('Y-m-d', strtotime("+". (intval($week/7)*7-($week%7-1)+4) ."days"));
            echo "<input name='date' id='date' type='date' required class = 'input-field' min='" .$min_day ."' max='" .$max_day ."'>"
          ?>
          <label for="date" class="input-label">Datum</label>
      </div>
      <div class = "field-group">
          <input name="time" id="time" type="time" required class = "input-field" min="13:00" max="20:00">
          <label for="time" class="input-label">Vrijeme</label>
      </div>
      <button type="submit" class="button-submit">Rezerviraj</button>
  </form>

  <div class="left-right">
        <span></span>
        <?php
            $page = intval($_GET["week"]);
            $week = date('w') + $page*7;
            if($page == 0){
                echo "<a><i class='fa fa-chevron-left' aria-hidden='true'></i></a>";
            }
            else{
                echo "<a href='?week=" .($page-1) ."'><i class='fa fa-chevron-left' aria-hidden='true'></i></a>";
            }
            $first_day_of_week = date('d.m.Y', strtotime("+". (intval($week/7)*7-($week%7-1)) ." days"));
            $last_day_of_week = date('d.m.Y', strtotime("+". (intval($week/7)*7-($week%7-1)+4) ."days"));
            echo "<span>" .$first_day_of_week ." - " .$last_day_of_week ."</span>";
            if($page == 3){
                echo "<a><i class='fa fa-chevron-right' aria-hidden='true'></i></a>";
            }
            else{
                echo "<a href='?week=" .($page+1) ."'><i class='fa fa-chevron-right' aria-hidden='true'></i></a>";
            }
        ?>
        <span></span>
  </div>


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
      $first_day_of_week = date('Y-m-d', strtotime("+". (intval($week/7)*7-($week%7-1)) ." days"));
      $last_day_of_week = date('Y-m-d', strtotime("+". (intval($week/7)*7-($week%7-1)+4) ."days"));
      for($i=0; $i<5; $i++){
          $temp_date = date('d.m.Y.', strtotime($first_day_of_week ."+" .$i ."days"));
          echo "<li>" .$temp_date ."</li>";
      }
  ?>
  </ul>
</div>
<div class= "timeslots-container">
  <ul class="timeslots">
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
      <span>PAUZA</span><br>
      <span>16:00-16:30</span>
  </div>
  <?php
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
              if(isset($_COOKIE["user"])){
                $additional_info = $row['name'] .", " .$row['email'] .", " .$row['number'];
                echo "<div class='slot' style='" .$style ."'><a href='' style='text-decoration:none; color=black;' title='" .$additional_info ."'>" .$row['treatment'] ."(" .$from_to .")</a></div>";
              }
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
        
    var code =
    `<option value="select">Odaberi tretman</option>
    <?php 
        if(isset($_POST["group"]) && !empty($_POST["group"])){
            $sql = "SELECT * FROM tretmani WHERE group=" .$_POST["group"];
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
                echo "<option value='". $row['treatment']. "'>" . $row['treatment']. "</option>";
            }
        }
        else {
            echo "<option value='error'>Nema POST varijable</option>";
        }
    ?>`;
    $(document).ready(function() 
    { 
        $('#group').on('change',function(){ 
            var group = $(this).val();  
            if(group) 
            { 
                console.log(group);
                $.ajax({ type:'POST', url:'/navigation/rezervacija.php',  data: ('group=' + group), contentType: "html", success:function(html) 
                { 
                    //var date = new Date();
                    //date.setTime(date.getTime()+(30*1000));
                    //document.cookie = "POST=" + group +"; expires=" + date.toGMTString() + ";path=/";
                    //console.log(document.cookie)
                    $('#treatment').html(code);
                }  
                }); 
            } 
            else{  
                $('#treatment').html('<option value="">Odabrite grupu tretmana</option>'); 
            } 
        });
    });
    </script>
</body>

</html>