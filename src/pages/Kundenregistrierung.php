<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Kunden</title>
    <link rel="stylesheet" href="../../css/bootstrap.css" />
    <link rel="stylesheet" href="../../css/main.css" />
  </head>
  <body>
    <?php
      $bootTyp = $_POST["bootTyp"];
      $bootAnzahl = $_POST["bootAnzahl"];
      $anfangsDatum = $_POST["anfangsDatum"];
      $endDatum = $_POST["endDatum"];

      require_once ('config.php');
      $db_link = mysqli_connect(HOST_IP, USER, PASSWORD, DATABASE); 
      if (! $db_link ) {
        echo "<h1>FEHLER</h1>";
      } 
      mysqli_set_charset($db_link, 'utf8');
    ?>
    <nav class="navbar navbar-expand-lg bg-sec mainFontColor">
      <div class="container-fluid">
        <div class="navbar-brand">
          <a href="MainPage.html">
            <img src="../../media/Logo.svg" class="rounded-1" />
          </a>
        </div>

        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-collapse collapse" id="navbarNav">
          <div class="nav-item px-4">
            <a class="nav-link" href="#"> Home</a>
          </div>
          <div class="nav-item px-4">
            <a class="nav-link" href="#">Unsere Boote</a>
          </div>

          <div class="nav-item px-4 py-2">
            <a class="nav-link" href="#">Tourangebote</a>
          </div>

          <div class="nav-item px-4 py-2">
            <a class="nav-link" href="#">Events</a>
          </div>

          <div class="nav-item px-4 py-2">
            <a class="nav-link" href="#">Über </a>
          </div>
        </div>
      </div>
    </nav>
    <?php
      $sqlAvailable = sprintf("select boote.bootid from boote
      where boote.bootid <> 
      IFNULL((select boote.bootid from boote, verleihung, bestellungen
      where verleihung.bootid = boote.bootid
      and boote.typeid = %u
      and bestellungen.BestellungsId = verleihung.BestellungsId
      and bestellungen.Anfangsdatum < %s
      and bestellungen.Enddatum > %s),0)
      and boote.typeid = %u;", $bootTyp, $anfangsDatum, $endDatum, $bootTyp);

      $db_erg = mysqli_query($db_link, $sqlAvailable);
      if (! $db_erg)
      {
        print 'Ungültige Abfrage: ' . mysqli_error($db_link);
      }
      $bootIdsAvailable = array();
      while ($zeile = mysqli_fetch_array($db_erg, MYSQLI_ASSOC))
      {
        array_push($bootIdsAvailable,$zeile["bootid"]);
      }
      mysqli_free_result( $db_erg );
      
    ?>
    <section>
      <form method="post" action="Bestellbestaetiung.php">
        <?php
          echo '<input type="hidden" value="' . $bootTyp . '" id="bootTyp" name="bootTyp">';
          echo '<input type="hidden" value="' . $bootAnzahl . '" id="bootAnzahl" name="bootAnzahl">';
          echo '<input type="hidden" value="' . $anfangsDatum . '" id="anfangsDatum" name="anfangsDatum">';
          echo '<input type="hidden" value="' . $endDatum . '" id="endDatum" name="endDatum">';
        ?>
        <div>
          <input type="text" name="vorname" placeholder="Vorname" />
          <input type="text" name="nachname" placeholder="Nachname" />
        </div>
        <div>
          <input type="text" name="strasse" placeholder="Straße" />
          <input type="text" name="hausNr" placeholder="Haus Nr" />
          <input type="number" name="plz" name="plz" placeholder="PLZ" />
        </div>

        <div>
          <input type="text" name="ort" placeholder="Ort" />
        </div>

        <input type="submit" />
      </form>
    </section>

    <script src="../../js/bootstrap.js"></script>
  </body>
</html>
