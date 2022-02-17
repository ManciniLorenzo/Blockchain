<?php
include '../../inc/init.php';
include ROOT_PATH . 'public/template-parts/header.php';
$email = '';
$password = '';
function test_input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<div id="main" class="container" style="margin-top:100px">
  <?php
  if (!isset($_POST['email']) || !isset($_POST['password'])) {

  ?>

    <form class="form-signin center" name="dati" method="POST">
      <h1 class="h3 mb-3 font-weight-normal">Login</h1> <br>
      <label for="inputEmail" class="sr-only">Email address</label><br>
      <input type="email password" name="email" class="form-control" placeholder="Email address" autocomplete="off" required>
      <label for="inputPassword" class="sr-only">Password</label><br>
      <input type="password" name="password" class="form-control" placeholder="Password" autocomplete="off" required>
      <br>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    </form>

    <a href="<?php echo ROOT_URL . 'public/pages/homepage.php'; ?>" class="btn btn-primary btn-lg mb-5 mt-3">&laquo; Indietro</a>

  <?php
  } else {
  ?>
    <h1>Login</h1>

    <?php
    //recupero email e password
    if (isset($_POST['email'])) {
      $email = test_input($_POST["email"]);
    }
    if (isset($_POST['password'])) {
      $password = test_input($_POST["password"]);
    }
    //cifratura email e password
    $email = hash("sha256", $email);
    $password = hash("sha256", $password);

    if (strlen($email) != 0 && strlen($password) != 0) {
      include ROOT_PATH . '/DB/DBconnection.php';
      $sql = "SELECT * FROM utente WHERE email = '" . $email . "'";
      $result = $mysqli->query($sql);
      if ($result->num_rows == 0) {
    ?>
        <div class="alert alert-dismissible alert-danger row col-md-4 col-md-offset-4">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <h4 class="alert-heading">Errore!</h4>
          <strong>Email sconosciuta!</strong>
        </div>
        <?php
        echo "<br>";
        echo "<a href='login.php' class='btn btn-danger btn-lg mb-5 mt-3'>Riprova</a><br>";
      } else {
        $row_utente = $result->fetch_array();
        //verifica password
        if ($password == $row_utente['password']) {
          //distruzione sessione precedente
          session_start();
          session_unset();
          session_destroy();
          //inizializzazione nuova sessione
          session_start();
          $_SESSION['login'] = true;
          $_SESSION['email'] = $email;
          $_SESSION['start_time'] = time();
        ?>
          <div class="alert alert-dismissible alert-success row col-md-4 col-md-offset-4">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4 class="alert-heading">Evviva!</h4>
            <strong>Accesso eseguito con successo!</strong>
          </div>
        <?php
          echo "<br>";
          echo "<a href='" . ROOT_URL . "public/pages/dashboard.php' class='btn btn-success btn-lg mb-5 mt-3'>Continua</a>";
          echo "<br>";
          echo "<a href='logout.php' class='btn btn-info btn-lg'>Logout</a><br>";
        } else {
          $_SESSION['login'] = false;
        ?>
          <div class="alert alert-dismissible alert-danger row col-md-4 col-md-offset-4">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4 class="alert-heading">Errore!</h4>
            <strong>Password errata!</strong>
          </div>
      <?php
          echo "<br>";
          echo "<a href='login.php' class='btn btn-danger btn-lg mb-5 mt-3'>Riprova</a><br>";
        }
      }
      $result->free();
      $mysqli->close();
    } else {
      ?>
      <div class="alert alert-dismissible alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h4 class="alert-heading">Errore!</h4>
        <strong>Utente/Password non validi!</strong>
      </div>
  <?php
      echo "<br>";
      echo "<a href='login.php' class='btn btn-danger btn-lg mb-5 mt-3'>Riprova</a><br>";
    }
  }
  ?>

</div>

<?php include ROOT_PATH . 'public/template-parts/footer.php' ?>