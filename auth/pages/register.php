<?php

$email = '';
$password = '';
$confirm_password = '';
function test_input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

include '../../inc/init.php';
include ROOT_PATH . 'public/template-parts/header.php';
?>
<div id="main" class="container" style="margin-top:100px">
  <?php
  if (!isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['confirm_password'])) {

  ?>
    <form class="form-signin center" name="dati" method="POST">
      <h1 class="h3 mb-3 font-weight-normal">Registrazione</h1>
      <label for="inputEmail" class="sr-only">Email address</label> <br>
      <input type="email" name="email" class="form-control" placeholder="Email address" autocomplete="off" required><br>

      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" name="password" class="form-control" placeholder="Password" autocomplete="off" required><br>

      <label for="inputPassword" class="sr-only">Ripeti password</label>
      <input type="password" name="confirm_password" class="form-control" placeholder="Ripeti password" required><br>

      <button class="btn btn-lg btn-primary btn-block" type="submit">Registrati</button>
    </form>
    <a href="<?php echo ROOT_URL . 'public/pages/homepage.php'; ?>" class="btn btn-primary btn-lg mb-5 mt-3">&laquo; Indietro</a>

    <?php
  } else {
    if (isset($_POST['email'])) {
      $email = test_input($_POST["email"]);
    }
    if (isset($_POST['password'])) {
      $password = test_input($_POST["password"]);
    }
    if (isset($_POST['confirm_password'])) {
      $confirm_password = test_input($_POST["confirm_password"]);
    }
    include ROOT_PATH . 'DB\DBconnection.php';

    if (strlen($email) != 0 && strlen($password) != 0 && strlen($confirm_password) != 0) {
      if ($password == $confirm_password) {
        $email = hash("sha256", $_POST['email']);
        $password = hash("sha256", $_POST['password']);
        $confirm_password = hash("sha256", $_POST['confirm_password']);
        $sql = "SELECT * FROM utente WHERE email = '" . $email . "'";
        $result = $mysqli->query($sql);
        if ($result->num_rows != 0) {
        ?>
          <div class="alert alert-dismissible alert-danger row col-md-4 col-md-offset-4">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4 class="alert-heading">Errore!</h4>
            <strong>Email gi&agrave presente nel database.</strong>
          </div>
          <br>
          <a href="register.php" class='btn btn-danger btn-lg mb-5 mt-3'>Riprova</a><br> <br>
          Hai gi&agrave un'account? <br>
          <a href="login.php" class='btn btn-success btn-lg mb-5 mt-3'>Accedi</a><br> <br>
        <?php
        } else {
          $mysqli->autocommit(false);
          $sql1 = "INSERT INTO utente (email, password, nome) VALUES ('$email', '$password' , '" . $_POST['password'] . "')";
          //echo "<pre>" . $sql1 . "</pre> <br>";
          $result1 = $mysqli->query($sql1);
          if ($result1) {
            $mysqli->commit();
          ?>
            <div class="alert alert-dismissible alert-success row col-md-4 col-md-offset-4">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <h4 class="alert-heading">Evviva!</h4>
              <strong>Registrazione eseguita con successo!</strong>
            </div>
            <br>
            <?php
            //primo accesso, regalo dalla rete
            $sql_network = "UPDATE utente SET conto = '100,00' WHERE email = '" . $email . "' ";
            //echo "<pre>" . $sql_network . "</pre> <br>";
            $result_network = $mysqli->query($sql_network);
            if ($result_network) {

              $genesis_transaction = hash("sha256", "genesis" . $email);
              $network_hash = hash("sha256", "network");
              $sql_network2 = "INSERT INTO transactions (index_transaction, from_email, to_email, amount, status) ";
              $sql_network2 .= "VALUES ('" . $genesis_transaction . "', '" . $network_hash . "', '" . $email . "',  100.00, 'started')";
              //echo "<pre>" . $sql_network2 . "</pre> <br>";
              $result_network2 = $mysqli->query($sql_network2);
              if ($result_network2) {
                $mysqli->commit();
              ?>
                <div class="alert alert-dismissible alert-success row col-md-4 col-md-offset-4">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  <h4 class="alert-heading">Complimenti!</h4>
                  <strong>Hai ricevuto 100,00 Lor€n$o!</strong>
                </div>
                <br>
                <a href="login.php" class='btn btn-success btn-lg mb-5 mt-3'>Accedi</a><br> <br>
              <?php
              } else {
                $mysqli->rollback();
                echo "ERRORE: " . $mysqli->errno;
              }
            } else {
              $mysqli->rollback();
              ?>
              <div class="alert alert-dismissible alert-danger row col-md-4 col-md-offset-4">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h4 class="alert-heading">Errore!</h4>
                <strong>Errore regalo Lor€n$o annullato</strong>
              </div>
            <?php
            }
          }
        }
        $result->free();
        $mysqli->close();
      } else {
        ?>
        <div class="alert alert-dismissible alert-danger row col-md-4 col-md-offset-4">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <h4 class="alert-heading">Errore!</h4>
          <strong>Password non confermata.</strong>
        </div>
        <br>
        <a href="register.php" class='btn btn-danger btn-lg mb-5 mt-3'>Riprova</a><br> <br>
      <?php
      }
    } else {
      ?>
        <div class="alert alert-dismissible alert-danger row col-md-4 col-md-offset-4">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <h4 class="alert-heading">Errore!</h4>
          <strong>Email/password non validi.</strong>
        </div>
        <br>
        <a href="register.php" class='btn btn-danger btn-lg mb-5 mt-3'>Riprova</a><br> <br>
      <?php
    }
  }
  ?>
</div>
<?php include ROOT_PATH . 'public/template-parts/footer.php' ?>