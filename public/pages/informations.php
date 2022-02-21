<?php
// Prevent from direct access

include '../../inc/init.php';
include ROOT_PATH . 'public/template-parts/header.php';
require "../../auth/pages/session.php";
?>

<div id="main" class="container" style="margin-top:100px">

    <h2>Informazioni account</h2>
    <?php
    $email = "";
    if(isset($_POST['miner'])){
        $email = $_POST['miner'];
    } else {
        $email = $_SESSION['email'];
    }
    include ROOT_PATH . '/DB/DBconnection.php';
    $sql = "SELECT * FROM utente WHERE email = '" . $email . "' ";
    $result = $mysqli->query($sql);
    //echo "<pr> $sql </pre>";
    $row = $result->fetch_array();
    if ($result) {
        if ($result->num_rows > 0) {
            $sql1 = "SELECT COUNT(index_transaction) AS totale FROM transactions WHERE from_email = '" . $email . "' OR to_email = '" . $email . "' ";
            $result1 = $mysqli->query($sql1);
            if($result1){
                $row1 = $result1->fetch_array();
            } else {
                echo "Errore count transazioni: " . $mysqli->errno;
            }
            $sql2 = "SELECT SUM(amount) AS inviati FROM transactions WHERE from_email = '" . $email . "' ";
            $result2 = $mysqli->query($sql2);
            if($result2){
                $row2 = $result2->fetch_array();
            } else {
                echo "ERRORE sql2 inviati";
            }
            $sql3 = "SELECT SUM(amount) AS ricevuti FROM transactions WHERE to_email = '" . $email . "' ";
            $result3 = $mysqli->query($sql3);
            if($result3){
                $row3 = $result3->fetch_array();
            } else {
                echo "ERRORE sql3 ricevuti";
            }
            $sql4 = "SELECT COUNT(miner) AS cont_miner FROM block WHERE miner = '" . $email . "' ";
            $result4 = $mysqli->query($sql4);
            if($result4){
                $row4 = $result4->fetch_array();
            } else {
                echo "ERRORE sql4 count miner";
            }            /* 
                <th scope="row">Totale ricevuto</th> //SUM amount where to_email=$email
                <th scope="row">Totale inviato</th> <?php//SUB amount where from_email=$email
            */
            //inserisco i dati del DB nella tabella
            echo "<table class='table table-hover'>";
            echo "<tr class=\"table-active\">";
            echo "<th scope='row'>Email</th>";
            echo "<td>" . $row['email'] . "</td> ";
            echo "</tr>\n";
            echo "<tr>";
            echo "<th scope='row'>Transazioni</th>";
            echo "<td>" . $row1['totale'] . "</td> ";
            echo "</tr>\n";
            echo "<tr class=\"table-active\">";
            echo "<th scope='row'>Contocorrente</th>";
            echo "<td>" . $row['conto'] . "</td>";
            echo "</tr>\n";
            echo "<th scope='row'>Totale inviato</th>";
            echo "<td>" . $row2['inviati'] . "</td>";
            echo "<tr class=\"table-active\">";
            echo "<th scope='row'>Totale ricevuto</th>";
            echo "<td>" . $row3['ricevuti'] . "</td>";
            echo "</tr>\n";
            echo "<th scope='row'>Blocchi validati</th>";
            echo "<td>" . $row4['cont_miner'] . "</td>";
            echo "</tr>\n";
            echo "</table >\n";
        } else {
            ?>
                <div class="alert alert-dismissible alert-warning">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h5 class="alert-heading">Avviso!</h5>
                    <p class="mb-0">Nessun dato recuperato</p>
                </div>
            <?php
        }
        $result->close();
            ?>
        <?php
    } else {
        echo "<br />Errore: " . $mysqli->error . "<br />";
    }
    ?>
    <a href="<?php echo ROOT_URL . 'public/pages/view-blockchain.php'; ?>" class="btn btn-primary btn-lg mb-5 mt-3">&laquo; Indietro</a>

</div>
<?php include ROOT_PATH . 'public/template-parts/footer.php' ?>