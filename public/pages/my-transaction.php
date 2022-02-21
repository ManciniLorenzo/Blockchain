<?php

include_once '../../inc/init.php';
include_once ROOT_PATH . 'public/template-parts/header.php';
require_once "../../auth/pages/session.php";
?>


<div id="main" class="container" style="margin-top:100px">

  <h2>Le mie transazioni</h2>
  <?php

  $email = $_SESSION['email'];
  include ROOT_PATH . '/DB/DBconnection.php';

  //select transazioni effettuate dall'utente
  $sql = "SELECT * FROM  transactions WHERE from_email = '" . $email . "' ORDER BY time DESC";

  //echo "<pre>sql: " . $sql . "</pre>";
  $result = $mysqli->query($sql);

  if ($result) {
    ?>
    <br>
      <h3>Effettuate: </h3>
    <?php
    if ($result->num_rows > 0) {
    ?>
    <div style="overflow-x:auto;">
      <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col">index_transaction</th>
            <th scope="col">to_email</th>
            <th scope="col">time</th>
            <th scope="col">amount</th>
            <th scope="col">status</th>
          </tr>
          <?php
          $i = 0;
          while ($row = $result->fetch_array()) {
            if ($i % 2 == 0) {
              echo "<tr class=\"table-active\">";
            } else {
              echo "<tr>";
            }
            //inserimento dati del DB nella tabella
            echo "<td>" . $row['index_transaction'] . "</td> ";
            echo "<td>" . $row['to_email'] . "</td> ";
            echo "<td style='white-space:nowrap'>" . $row['time'] . "</td>";
            echo "<td>" . $row['amount'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            echo "</tr>\n";
            $i++;
          }
        } else {
          ?>
          <div class="alert alert-dismissible alert-warning">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h5 class="alert-heading">Avviso!</h5>
            <p class="mb-0">Nessuna transazione effettuata</p>
          </div>
        <?php
        }
        $result->close();
        ?>
        </tbody>
      </table>
      </div>
    <?php
  } else {
    echo "<br />Errore from_email: " . $mysqli->error . "<br />";
  }
  $sql = "SELECT * FROM transactions WHERE to_email = '" . $email . "' ORDER BY time DESC";
  $result = $mysqli->query($sql);
  if ($result) {
      ?>
      <br>
        <h3>Ricevute: </h3>
      <?php
      if ($result->num_rows > 0) {
      ?>
      <div style="overflow-x:auto;">
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">index_transaction</th>
              <th scope="col">from_email</th>
              <th scope="col">time</th>
              <th scope="col">amount</th>
              <th scope="col">status</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i = 0;
            while ($row = $result->fetch_array()) {
              //$table_color = $row["index_transaction"];
              if ($i % 2 == 0) {
                echo "<tr class=\"table-active\">";
              } else {
                echo "<tr>";
              }
              //inserimento dati del DB nella tabella
              echo "<td>" . $row['index_transaction'] . "</td> ";
              echo "<td>" . $row['from_email'] . "</td> ";
              echo "<td style='white-space:nowrap'>" . $row['time'] . "</td>";
              echo "<td>" . $row['amount'] . "</td>";
              echo "<td>" . $row['status'] . "</td>";
              echo "</tr>\n";
              $i++;
            }
          } else {
            ?>
            <div class="alert alert-dismissible alert-warning">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <h5 class="alert-heading">Avviso!</h5>
              <p class="mb-0">Nessuna transazione ricevuta</p>
            </div>
          <?php
          }
          $result->close();
          ?>
          </tbody>
        </table>
        </div>
      <?php
    } else {
      echo "<br />Errore to_email: " . $mysqli->error . "<br />";
    }
  ?>
</div>
<?php include ROOT_PATH . 'public/template-parts/footer.php' ?>