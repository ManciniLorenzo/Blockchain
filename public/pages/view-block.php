<?php
include '../../inc/init.php';
include ROOT_PATH . 'public/template-parts/header.php';
require "../../auth/pages/session.php";
?>

<link rel="stylesheet" href="<?php echo ROOT_URL; ?>assets/css/style.css">

<div id="main" class="container" style="margin-top:100px">

  <?php
  include ROOT_PATH . '/DB/DBconnection.php';
  $blockHash = "";
  if (isset($_POST['blockHash'])) {
    $blockHash = $_POST['blockHash'];
  }
  
  $sql = "SELECT * FROM  block WHERE blockHash='" . $blockHash . "' ";
  
  $result = $mysqli->query($sql);
  $row = $result->fetch_array();
  if ($result) {
    if ($result->num_rows > 0) {
    ?>
      <h2>Blocco #<?php echo $row['index_block']; ?></h2>
      <table class="table table-hover">
        <tbody>
        <tr>
            <td scope="row">index_block</td>
            <?php
            echo "<td>" . $row['index_block'] . "</td> ";
            ?>
        </tr>
        <tr class="table-active">
            <th scope="row">blockHash</th>
            <?php
            echo "<td>" . $row['blockHash'] . "</td> ";
            ?>
        </tr>
        <tr>
            <th scope="row">merkleRootHash</th>
            <?php
            echo "<td>" . $row['merkleRootHash'] . "</td> ";
            ?>
        </tr>
        <tr class="table-active">
            <th scope="row">previousBlockHash</th>
            <?php
            echo "<td>" . $row['previousBlockHash'] . "</td> ";
            ?>
        </tr>
        <tr>
            <th scope="row">timestamp</th>
            <?php
            echo "<td>" . $row['timestamp'] . "</td> ";
            ?>
        </tr>
        <tr class="table-active">
            <th scope="row">transaction1</th>
            <?php
            echo "<td>" . $row['transaction1'] . "</td> ";
            ?>
        </tr>
        <tr>
            <th scope="row">transaction2</th>
            <?php
            echo "<td>" . $row['transaction2'] . "</td> ";
            ?>
        </tr>
        <tr class="table-active">
            <th scope="row">target</th>
            <?php
            echo "<td>" . $row['target'] . "</td> ";
            ?>
        </tr>
        <tr>
            <th scope="row">nonce</th>
            <?php
            echo "<td>" . $row['nonce'] . "</td> ";
            ?>
        </tr>
        <tr class="table-active">
            <th scope="row">miner</th>
            <?php
            echo "<td>" . $row['miner'] . "</td> ";
            ?>
        </tr>
    </tbody>
    <?php
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
      </table>
    <?php
    
  } else {
    echo "<br />Errore: " . $mysqli->error . "<br />";
  }
  ?>
  <a href="<?php echo ROOT_URL . 'public/pages/view-blockchain.php'; ?>" class="btn btn-primary btn-lg mb-5 mt-3">&laquo; Indietro</a>
</div>
<?php include ROOT_PATH . 'public/template-parts/footer.php' ?>