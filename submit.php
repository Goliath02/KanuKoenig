<html>

<head>
  <title> Kontaktformular Bootsmiete </title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel='stylesheet' href='./bootstrap-datepicker.min.css'>
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
  <link rel="stylesheet" href="kanu_inc.css" type="text/css" />
</head>

<body class="overlay">
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand" href="#"><img src="./images/HWS-Kanu.png" alt="..." height="36"></a>
      <button class="navbar-toggler navbar-dark" type="button" data-bs-toggle="collapse" data-bs-target="#main-navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="main-navigation">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a id="boats" class="nav-link" href="#">Unsere Boote</a>
          </li>
          <li class="nav-item">
            <a id="tours" class="nav-link" href="#">Tourenangebote</a>
          </li>
          <li class="nav-item">
            <a id="event" class="nav-link" href="#">Events</a>
          </li>
          <li class="nav-item">
            <a id="about" class="nav-link" href="#">Über uns</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
    <?php
        $bootTyp = $_POST[""];
        $bootAnzahl = $_POST["customRange2"];
        $anfangsDatum = $_POST["date1"];
        $endDatum = $_POST["date2"];

        $vorname = $_POST["info1"];
        $nachname = $_POST["info2"];
        $strasse = $_POST["info3"];
        $hausNr = $_POST["info4"];
        $plz = $_POST["info5"];
        $ort = $_POST["info6"];

require_once ('config.php');
$db_link = mysqli_connect (
 HOST_IP, USER, PASSWORD, DATABASE); 
if (! $db_link ) {
 echo "<h1>FEHLER</h1>";
} 
mysqli_set_charset($db_link, 'utf8');

$sqlAllBoatTypes = "SELECT * FROM mydb.boottypen;";

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
 print 'Ungültige Abfrage: ' . mysqli_error();
}
$bootIdsAvailable = array();
while ($zeile = mysqli_fetch_array($db_erg, MYSQLI_ASSOC))
{
 array_push($bootIdsAvailable,$zeile["bootid"]);
}
mysqli_free_result( $db_erg );


$sqlNewCustomer = sprintf("insert into kunden(Vorname, Nachname, straße, hausnummer, ortid) values 
(%s, %s, %s, %u, (select ort.ortid from ort where ort.plz = %u));", $vorname, $nachname, $strasse, $hausNr, $plz);
$db_erg = mysqli_query($db_link, $sqlNewCustomer);
if (! $db_erg)
{
 print 'Ungültige Abfrage: ' . mysqli_error();
}
$customerId = mysqli_insert_id($mysqli);

/*$sqlCurrentCustomerId = sprintf("Select KundenId from kunden
where Vorname = %s
and Nachname = %s
and Straße = %s
and Hausnummer = %s
and OrtId = (select ort.ortid from ort where ort.plz = %u);", $vorname, $nachname, $strasse, $hausNr, $plz);*/

$sqlNewOrder = sprintf("insert into bestellungen(KundenId, Anzahl, Anfangsdatum, Enddatum) values 
(%u, %u, %s, %s);", $customerId, $bootAnzahl, $anfangsDatum, $endDatum);
$db_erg = mysqli_query($db_link, $sqlNewOrder);
if (! $db_erg)
{
 print 'Ungültige Abfrage: ' . mysqli_error();
}
$orderId = mysqli_insert_id($mysqli);

$sqlCurrentOrderId = sprintf("select bestellungen.BestellungsId
where kundenid = %u
and anzahl = %u
and Anfangsdatum = %s
and enddatum  = %s;", $customerId, $bootAnzahl, $anfangsDatum, $endDatum);

$countBoatAvailable = count($bootIdsAvailable);
for ($i = 0; $i < $countBoatAvailable; $i++) {
  $sqlNewRent = sprintf("insert into verleihung(BootId, BestellungsId) values 
  (%u, %u);", $bootIdsAvailable[$i]["bootid"], $orderId);
  $db_erg = mysqli_query($db_link, $sqlNewRent);
  if (! $db_erg)
  {
    print 'Ungültige Abfrage: ' . mysqli_error();
  }
}



$sqlq = "SELECT * FROM kunden";
$db_erg = mysqli_query($db_link, $sqlq);
if (! $db_erg)
{
 print 'Ungültige Abfrage: ' . mysqli_error();
}
echo '<table border="1">';
while ($zeile = mysqli_fetch_array($db_erg, MYSQLI_ASSOC))
{
 echo "<tr>";
 echo "<td>". $zeile['KundenId'] . "</td>";
 echo "<td>". $zeile['Nachname'] . "</td>";
 echo "<td>". $zeile['Vorname'] . "</td>";
 echo "</tr>";
}
echo "</table>";
mysqli_free_result( $db_erg );
    ?>

</body>