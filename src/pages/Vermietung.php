<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Vermietung</title>
    <link rel="stylesheet" href="../../css/bootstrap.css" />
    <link rel="stylesheet" href="../../css/main.css" />
  </head>
  <body>
    <?php
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

    <section>
      <form method="post" action="Kundenregistrierung.php">
        <div>
          <select name="bootTyp">
            <option value="" disabled selected>Boot wählen...</option>
            <?php
              $sqlAllBoatTypes = "SELECT * FROM mydb.boottypen;";
              $db_erg = mysqli_query($db_link, $sqlAllBoatTypes);
              if (! $db_erg)
              {
                print 'Ungültige Abfrage: ' . mysqli_error($db_link);
              }
              while ($zeile = mysqli_fetch_array($db_erg, MYSQLI_ASSOC))
              {
                echo '<option value="' . $zeile["TypeId"] . '">' . $zeile["Bezeichnung"] . '</option>';
              }
              mysqli_free_result( $db_erg );
            ?>
          </select>

          <input type="number" id="bootAnzahl" name="bootAnzahl" />
        </div>

        <div>
          <input type="date" name="anfangsDatum"/>
          <input type="date" name="endDatum"/>
        </div>
        <input type="submit" />
      </form>
    </section>

    <script src="../../js/bootstrap.js"></script>
  </body>
</html>
