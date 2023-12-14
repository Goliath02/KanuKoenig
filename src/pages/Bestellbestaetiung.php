<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Bestätigung</title>
    <link rel="stylesheet" href="../../css/bootstrap.css" />
    <link rel="stylesheet" href="../../css/main.css" />
  </head>
  <body>
    <?php
      $bootTyp = $_POST["bootTyp"];
      $bootAnzahl = $_POST["bootAnzahl"];
      $anfangsDatum = $_POST["anfangsDatum"];
      $endDatum = $_POST["endDatum"];

      $vorname = $_POST["vorname"];
      $nachname = $_POST["nachname"];
      $strasse = $_POST["strasse"];
      $hausNr = $_POST["hausNr"];
      $plz = $_POST["plz"];
      $ort = $_POST["ort"];

      require_once ('config.php');
      $db_link = mysqli_connect (
      HOST_IP, USER, PASSWORD, DATABASE); 
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
      $sqlNewCustomer = sprintf("insert into kunden(Vorname, Nachname, straße, hausnummer, ortid) values 
      (%s, %s, %s, %u, (select ort.ortid from ort where ort.plz = %u));", $vorname, $nachname, $strasse, $hausNr, $plz);
      $db_erg = mysqli_query($db_link, $sqlNewCustomer);
      if (! $db_erg)
      {
        print 'Ungültige Abfrage: ' . mysqli_error($db_link);
      }
      $customerId = mysqli_insert_id($db_link);

      $sqlNewOrder = sprintf("insert into bestellungen(KundenId, Anzahl, Anfangsdatum, Enddatum) values 
      (%u, %u, %s, %s);", $customerId, $bootAnzahl, $anfangsDatum, $endDatum);
      $db_erg = mysqli_query($db_link, $sqlNewOrder);
      if (! $db_erg)
      {
        print 'Ungültige Abfrage: ' . mysqli_error($db_link);
      }
      $orderId = mysqli_insert_id($db_link);

      $sqlCurrentOrderId = sprintf("select bestellungen.BestellungsId
      where kundenid = %u
      and anzahl = %u
      and Anfangsdatum = %s
      and enddatum  = %s;", $customerId, $bootAnzahl, $anfangsDatum, $endDatum);


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

      $countBoatAvailable = count($bootIdsAvailable);
      for ($i = 0; $i < $countBoatAvailable; $i++) {
        echo $bootIdsAvailable[$i]["bootid"];
        $sqlNewRent = sprintf("insert into verleihung(BootId, BestellungsId) values 
        (%u, %u);", $bootIdsAvailable[$i]["bootid"], $orderId);
        $db_erg = mysqli_query($db_link, $sqlNewRent);
        if (! $db_erg)
        {
          print 'Ungültige Abfrage: ' . mysqli_error($db_link);
        }
      }
    ?>
    <section>Bestellt</section>

    <script src="../../js/bootstrap.js"></script>
  </body>
</html>
