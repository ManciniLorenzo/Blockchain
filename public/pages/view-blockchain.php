<?php
include '../../inc/init.php';
include ROOT_PATH . 'public/template-parts/header.php';
require "../../auth/pages/session.php";

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>

<div id="main" class="container" style="margin-top:100px">

  <h2>Visualizza la blockchain</h2>

  <?php
  include ROOT_PATH . '/DB/DBconnection.php';

  //indice blocco
  $index_block = "";
  if (isset($_POST['index_block'])) {
    $index_block = test_input($_POST['index_block']);
  }
  //hash blocco
  $blockHash = "";
  if (isset($_POST['blockHash'])) {
    $blockHash = test_input($_POST['blockHash']);
  }
  //timestamp creazione blocco
  $timestamp = "";
  if (isset($_POST['timestamp'])) {
    $timestamp = $_POST['timestamp'];
  }
  //miner blocco
  $miner = "";
  if (isset($_POST['miner'])) {
    $miner = $_POST['miner'];
  }

  $sql = "SELECT * FROM block WHERE 1=1 ";
  if ($index_block > "") {
    $sql .= "AND index_block = " . $index_block . " ";
  }
  if ($blockHash > "") {
    $sql .= "AND blockHash = '" . $blockHash . "' ";
  }
  if ($timestamp > "") {
    $sql .= "AND timestamp = '" . $timestamp . "' ";
  }
  if ($miner > "") {
    $sql .= "AND miner = '" . $miner . "' ";
  }
  $sql .= "ORDER BY index_block DESC";
  //echo "<pre>sql: " . $sql . "</pre>";
  $result = $mysqli->query($sql);

  if ($result) {
  ?>
    <div style="overflow-x:auto;">
      <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col">index_block</th>
            <th scope="col">blockHash</th>
            <th scope="col">miner</th>
            <th scope="col">timestamp</th>
          </tr>
          <tr class="table-active">
            <form name="filtro" id="filtro" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

              <th scope="col">
                <input type="number" name="index_block" id="index_block" class="form-control mr-sm-2" autocomplete="off" value="<?php echo $index_block; ?>" />
              </th>

              <th scope="col">
                <input type="text" name="blockHash" id="blockHash" class="form-control mr-sm-2" autocomplete="off" value="<?php echo $blockHash; ?>" />
              </th>

              <th scope="col">
                <select name="miner" id="miner" class="custom-select">
                  <option value="">Tutti</option>
                  <?php
                  $sql1 = "SELECT miner FROM block GROUP BY miner";
                  $result1 = $mysqli->query($sql1);
                  if ($result1) {
                    if ($result1->num_rows > 0) {
                      while ($row1 = $result1->fetch_array()) {
                        echo "<option value=\"" . $row1['miner'] . "\" ";
                        if ($row1['miner'] == $miner) {
                          echo " selected ";
                        }
                        echo " >";
                        echo $row1['miner'];
                        echo "</option>\n";
                      }
                    }
                  } else {
                    echo "Errore select miner: " . $mysqli->errno;
                  }
                  ?>
                </select>
              </th>

              <th scope="col">
                <select name="timestamp" id="timestamp" class="custom-select">
                  <option value="">Tutti</option>
                  <?php
                  $sql2 = "SELECT timestamp FROM block GROUP BY timestamp";
                  $result2 = $mysqli->query($sql2);
                  if ($result2) {
                    if ($result2->num_rows > 0) {
                      while ($row2 = $result2->fetch_array()) {
                        echo "<option value=\"" . $row2['timestamp'] . "\" ";
                        if ($row2['timestamp'] == $timestamp) {
                          echo " selected ";
                        }
                        echo " >";
                        echo $row2['timestamp'];
                        echo "</option>\n";
                      }
                    }
                  } else {
                    echo "Errore select timestamp: " . $mysqli->errno;
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
              echo "<td>" . $row['index_block'] . "</td> ";
              echo "<td> <form name='block' action='view-block.php' method='POST'><button type='submit' name='blockHash' class='btn btn-link' value=" . $row['blockHash'] . ">" . $row['blockHash'] . "</button></form></td> ";
              echo "<td> <form name='miner' action='informations.php' method='POST'><button type='submit' name='miner' class='btn btn-link' value=" . $row['miner'] . ">" . $row['miner'] . "</button></form></td> ";
              echo "<td style='white-space:nowrap'>" . $row['timestamp'] . "</td>";
              echo "</tr>\n";
              $i++;
            }
          } else {
          ?>
            <div class="alert alert-dismissible alert-warning">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <h5 class="alert-heading">Avviso!</h5>
              <p class="mb-0">Nessun dato presente nella tabella "block"</p>
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
  <p> clicca sull'hash del blocco o del miner per visualizzare informazioni relative ad esso</p>
  <br>
  <button type="submit" form="filtro" class="btn btn-primary btn-lg">Filtra</button>

  <input type="reset" class="btn btn-primary btn-lg" value="Reset Filtro" onclick="return reset_filtro();" />


  <script>
    function reset_filtro() {
      // alert(o_dip.value);
      //var index_transaction = document.getElementById("index_transaction");

      var index_block = document.getElementById("index_block");
      var blockHash = document.getElementById("blockHash");
      var miner = document.getElementById("miner");
      var timestamp = document.getElementById("timestamp");

      //index_transaction.value = "";
      index_block.value = "";
      blockHash.value = "";
      miner.value = "";
      timestamp.value = "";

      document.filtro.submit();
    }
  </script>
</div>
<?php include ROOT_PATH . 'public/template-parts/footer.php' ?>