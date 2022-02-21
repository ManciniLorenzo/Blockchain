<?php
// Prevent from direct access

include_once '../../inc/init.php';
include_once ROOT_PATH . 'public/template-parts/header.php';
require_once "../../auth/pages/session.php";

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>

<div id="main" class="container" style="margin-top:100px">

  <h2>Visualizza transazioni</h2>

  <?php
  include ROOT_PATH . '/DB/DBconnection.php';

  //indice blocco
  $index_block = "";
  if (isset($_POST['index_block'])) {
    $index_block = $_POST['index_block'];
  }
  //indice transazione
  $index_transaction = "";
  if (isset($_POST['index_transaction'])) {
    $index_transaction = $_POST['index_transaction'];
  }
  //mittente transazione
  $from_email = "";
  if (isset($_POST['from_email'])) {
    $from_email = $_POST['from_email'];
  }
  //destinatario transazione
  $to_email = "";
  if (isset($_POST['to_email'])) {
    $to_email = $_POST['to_email'];
  }
  //time transazione
  $time = "";
  if (isset($_POST['time'])) {
    $time = $_POST['time'];
  }
  //ammontare transazione
  $amount = "";
  if (isset($_POST['amount'])) {
    $amount = test_input($_POST['amount']);
  } //stato transazione
  $status = "";
  if (isset($_POST['status'])) {
    $status = $_POST['status'];
  }

  $sql = "SELECT * FROM  transactions ";
  $sql .= "WHERE 1=1 ";
  if ($index_transaction > "") {
    $sql .= "AND index_transaction = " . $index_transaction . " ";
  }
  if ($from_email > "") {
    $sql .= "AND from_email = '" . $from_email . "' ";
  }
  if ($to_email > "") {
    $sql .= "AND to_email = '" . $to_email . "' ";
  }
  if ($time > "") {
    $sql .= "AND time = '" . $time . "' ";
  }
  if ($amount > "") {
    $sql .= "AND amount = " . $amount . " ";
  }
  if ($status > "") {
    $sql .= "AND status = '" . $status . "' ";
  }
  $sql .= "ORDER BY time DESC";
  //echo "<pre>sql: " . $sql . "</pre>";
  $result = $mysqli->query($sql);

  if ($result) {
  ?>
  
    <div style="overflow-x:auto;">
      <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col">from_email</th>
            <th scope="col">to_email</th>
            <th scope="col">time</th>
            <th scope="col">Lor€n$o</th>
            <th scope="col">status</th>
            <th scope="col">index_block</th>
          </tr>
          <tr class="table-active">
            <form name="filtro" id="filtro" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

              <th scope="col">
                <select name="from_email" id="from_email" class="custom-select">
                  <option value="">Tutti</option>
                  <?php
                  $sql1 = "SELECT from_email FROM transactions GROUP BY from_email";
                  $result1 = $mysqli->query($sql1);
                  if ($result1) {
                    if ($result1->num_rows > 0) {
                      while ($row1 = $result1->fetch_array()) {
                        echo "<option value=\"" . $row1['from_email'] . "\" ";
                        if ($row1['from_email'] == $from_email) {
                          echo " selected ";
                        }
                        echo " >";
                        echo $row1['from_email'];
                        echo "</option>\n";
                      }
                    }
                  }
                  ?>
                </select>
              </th>

              <th scope="col">
                <select name="to_email" id="to_email" class="custom-select">
                  <option value="">Tutti</option>
                  <?php
                  $sql2 = "SELECT to_email FROM transactions GROUP BY to_email";
                  $result2 = $mysqli->query($sql2);
                  if ($result2) {
                    if ($result2->num_rows > 0) {
                      while ($row2 = $result2->fetch_array()) {
                        echo "<option value=\"" . $row2['to_email'] . "\" ";
                        if ($row2['to_email'] == $to_email) {
                          echo " selected ";
                        }
                        echo " >";
                        echo $row2['to_email'];
                        echo "</option>\n";
                      }
                    }
                  }
                  ?>
                </select>
              </th>
              <th scope="col">
                <select name="time" id="time" class="custom-select">
                  <option value="">Tutti</option>
                  <?php
                  $sql3 = "SELECT time FROM transactions GROUP BY time";
                  $result3 = $mysqli->query($sql3);
                  if ($result3) {
                    if ($result3->num_rows > 0) {
                      while ($row3 = $result3->fetch_array()) {
                        echo "<option value=\"" . $row3['time'] . "\" ";
                        if ($row3['time'] == $time) {
                          echo " selected ";
                        }
                        echo " >";
                        echo $row3['time'];
                        echo "</option>\n";
                      }
                    }
                  }
                  ?>
                </select>
              </th>
              <th scope="col">
                <input type="text" name="amount" id="amount" class="form-control mr-sm-2" style="width:auto; white-space:nowrap" placeholder="Lor€n$o" value="<?php echo $amount; ?>" />
              </th>
              <th scope="col">
                <select name="status" id="status" style="width:auto; white-space:nowrap" class="custom-select">
                  <option value="">Tutti</option>
                  <?php
                  $sql4 = "SELECT status FROM transactions GROUP BY status";
                  $result4 = $mysqli->query($sql4);
                  if ($result4) {
                    if ($result4->num_rows > 0) {
                      while ($row4 = $result4->fetch_array()) {
                        echo "<option value=\"" . $row4['status'] . "\" ";
                        if ($row4['status'] == $status) {
                          echo " selected ";
                        }
                        echo " >";
                        echo $row4['status'];
                        echo "</option>\n";
                      }
                    }
                  }
                  ?>
                </select>
              </th>
              <th scope="col">
                <select name="index_block" id="index_block" style="width:auto; white-space:nowrap" class="custom-select">
                  <option value="">Tutti</option>
                  <?php
                  $sql5 = "SELECT index_block FROM transactions GROUP BY index_block";
                  $result5 = $mysqli->query($sql5);
                  if ($result5) {
                    if ($result5->num_rows > 0) {
                      while ($row5 = $result5->fetch_array()) {
                        echo "<option value=\"" . $row5['index_block'] . "\" ";
                        if ($row5['index_block'] == $index_block) {
                          echo " selected ";
                        }
                        echo " >";
                        echo $row5['index_block'];
                        echo "</option>\n";
                      }
                    }
                  }
                  ?>
                </select>
              </th>
          </tr>
          </form>

        </thead>
        <tbody>
          <?php
          if ($result->num_rows > 0) {
            $i = 0;
            while ($row = $result->fetch_array()) {
              if ($i % 2 == 0) {
                echo "<tr>";
              } else {
                echo "<tr class=\"table-active\">";
              }
              //inserisco i dati del DB nella tabella
              echo "<td>" . $row['from_email'] . "</td>";
              echo "<td>" . $row['to_email'] . "</td>";
              echo "<td style='white-space:nowrap'>" . $row['time'] . "</td>";
              echo "<td style='white-space:nowrap'>" . $row['amount'] . "</td>";
              echo "<td style='white-space:nowrap'>" . $row['status'] . "</td>";
              echo "<td style='white-space:nowrap'>" . $row['index_block'] . "</td>";
              echo "</tr>\n";
              $i++;
            }
          } else {
          ?>
            <div class="alert alert-dismissible alert-warning">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <h5 class="alert-heading">Avviso!</h5>
              <p class="mb-0">Nessun dato presente nella tabella "transactions"</p>
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
    echo "<br />Errore: " . $mysqli->error . "<br />";
  }
  ?>
  <br> <br>
  <button type="submit" form="filtro" class="btn btn-primary btn-lg">Filtra</button>

  <input type="reset" class="btn btn-primary btn-lg" value="Reset Filtro" onclick="return reset_filtro();" />

  <script>
    function reset_filtro() {
      // alert(o_dip.value);
      //var index_transaction = document.getElementById("index_transaction");

      var from_email = document.getElementById("from_email");
      var to_email = document.getElementById("to_email");
      var time = document.getElementById("time");
      var amount = document.getElementById("amount");
      var status = document.getElementById("status");

      //index_transaction.value = "";
      from_email.value = "";
      to_email.value = "";
      time.value = "";
      amount.value = "";
      status.value = "";

      document.filtro.submit();
    }
  </script>
</div>
<?php include ROOT_PATH . 'public/template-parts/footer.php' ?>