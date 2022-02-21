<?php
include '../../inc/init.php';
include ROOT_PATH . 'public/template-parts/header.php';
require_once "../../auth/pages/session.php";

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>
<link rel="stylesheet" href="<?php echo ROOT_URL; ?>assets/css/style.css">

<div id="main" class="container" style="margin-top:100px">

    <h2>Nuova transazione</h2>
    <p class="lead">
        Effettua una transazione:
    </p>
    <?php
    if (!isset($_POST['to_email']) || !isset($_POST['amount']) || !isset($_POST['password'])) {
    ?>
        <form name="dati" class="form-signin center" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <label for="to_email">Destinatario: </label><br>
            <input type="text" name="to_email" id="to_email" class="form-control" placeholder="HASH destinatario" required autofocus><br>
            <br>
            <div class="form-group">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Lor€n$o</span>
                    </div>
                    <input type="text" name="amount" class="form-control" aria-label="Amount" required>
                    <div class="input-group-append">
                        <span class="input-group-text">.00</span>
                    </div>
                </div>
            </div>
            <label for="password">Password: </label><br>
            <input type="password" name="password" class="form-control" placeholder="Password" autocomplete="off" required>
            <br>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Avvia transazione</button>
        </form>
        <?php
    } else {
        //connessione al DB
        include ROOT_PATH . '/DB/DBconnection.php';
        //mittente transazione ---> utente loggato
        $email = $_SESSION['email'];
        //destinatario transazione
        $to_email = "";
        if (isset($_POST['to_email'])) {
            $to_email = test_input($_POST["to_email"]);
        }
        //ammontare transazione
        $amount = "";
        if (isset($_POST['amount'])) {
            $amount = test_input($_POST["amount"]);
        }
        //password mittente transazione
        $password = "";
        if (isset($_POST['password'])) {
            $password = test_input($_POST["password"]);
        }
        //timestamp transazione
        $time = "";
        //controlli validità transazione
        $sql = "SELECT * FROM utente ";
        $sql .= "WHERE email = '" . $email . "' ";
        //echo "<pre>sql: " . $sql . "</pre>";
        $result = $mysqli->query($sql);
        $row = $result->fetch_array();
        if ($result) {
            if (hash("sha256", $password) == $row['password']) {
                if (isset($_POST['amount'])) {
                    if ($row['conto'] < $_POST['amount']) {
        ?>
                        <div class="alert alert-dismissible alert-danger">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <h5 class="alert-heading">Errore!</h5>
                            <p class="mb-0">Impossibile effettuare la transazione, non hai abbastanza Lor€n$o.</p>
                        </div>
                        <a href='new-transaction.php' class='btn btn-danger btn-lg mb-5 mt-3'>Riprova</a><br>
                        <?php
                    } else {
                        //indice transazione
                        $sql_UTXO = "SELECT * FROM transactions WHERE to_email = '" . $email . "' AND status='confirmed'";
                        //echo "<pre>sql_UTXO: " . $sql_UTXO . "</pre>";
                        $result_UTXO = $mysqli->query($sql_UTXO);
                        if ($result_UTXO) {
                            $row_UTXO = $result_UTXO->fetch_array();
                            $totale = $amount;
                            while ($row_UTXO && $totale < $row['conto']){
                                $totale += $row['conto'];
                                $index_transaction =  hash("sha256", $row_UTXO['index_transaction'] . $row_UTXO['from_email'] . $row_UTXO['to_email'] . $row_UTXO['time'] . $row_UTXO['amount'] . $row_UTXO['status']);
                                $index_transaction .= $index_transaction;
                            }
                            $time = date('Y-m-d H:i:s');
                            $index_transaction = hash("sha256", $index_transaction . $to_email . $time);
                        } else {
                            echo "ERRORE sql_UTXO: " . $mysqli->errno;
                        }
                        //inizio transazione 
                        $mysqli->autocommit(false);

                        $sql1 = "INSERT INTO transactions (index_transaction, from_email, to_email, amount) ";
                        $sql1 .= "VALUES ('" . $index_transaction . "', '" . $email . "', '" . $to_email . "', " . $amount . ")";
                        //echo "<pre>sql1: " . $sql1 . "</pre>";
                        $result1 = $mysqli->query($sql1);
                        if ($result1) {
                            //aggiornamento dei conti
                            $conto_mittente = $row['conto'];
                            $conto_mittente -= $amount;
                            //UPDATE conto mittente
                            $sql2 = "UPDATE utente SET conto = '" . $conto_mittente . "' WHERE email = '" . $email . "' ";
                            //echo "<br><pre>sql2: " . $sql2 . "</pre";
                            $result2 = $mysqli->query($sql2);
                            if ($result2) {
                                $sql3 = "SELECT * FROM utente WHERE email = '" . $to_email . "' ";
                                //echo "<pre>sql3: " . $sql3 . "</pre>";
                                $result3 = $mysqli->query($sql3);
                                $row3 = $result3->fetch_array();
                                if ($result3) {
                                    $conto_destinatario = $row3['conto'];
                                    //UPDATE conto destinatario
                                    $conto_destinatario += $amount;
                                    //UPDATE conto destinatario
                                    $sql4 = "UPDATE utente SET conto = '" . $conto_destinatario . "' WHERE email = '" . $to_email . "' ";
                                    //echo "<pre>sql4: " . $sql4 . "</pre>";
                                    $result4 = $mysqli->query($sql4);
                                    if ($result4) {
                                        //conferma transazione
                                        $sql5 = "UPDATE transactions SET status = 'started' WHERE index_transaction='" . $index_transaction . "' ";
                                        //echo "<pre>sql5: " . $sql5 . "</pre>";
                                        $result5 = $mysqli->query($sql5);
                                        if ($result5) {
                                            $mysqli->commit(); // la transazione ha esito positivo
                        ?>
                                            <div class="alert alert-dismissible alert-success">
                                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                                <h5 class="alert-heading">Evviva!</h5>
                                                <p class="mb-0">Transazione avviata.</p>
                                            </div>
                                            <br>
                                            <a href='new-transaction.php' class='btn btn-success btn-lg mb-5 mt-3'>Effettua un'altra transazione</a><br>
                                        <?php
                                        } else {
                                            //errore sql5
                                            $mysqli->rollback();
                                        ?>
                                            <div class="alert alert-dismissible alert-danger">
                                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                                <h5 class="alert-heading">Errore!</h5>
                                                <p class="mb-0">Errore sql5: </p>
                                                <?php $mysqli->errno; ?>
                                            </div>
                                            <br>
                                            <a href='new-transaction.php' class='btn btn-danger btn-lg mb-5 mt-3'>Riprova</a><br>
                                        <?php
                                        }
                                    } else {
                                        //errore sql4
                                        $mysqli->rollback();
                                        ?>
                                        <div class="alert alert-dismissible alert-danger">
                                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                                            <h5 class="alert-heading">Errore!</h5>
                                            <p class="mb-0">Errore sql4: </p>
                                            <?php $mysqli->errno; ?>
                                        </div>
                                        <br>
                                        <a href='new-transaction.php' class='btn btn-danger btn-lg mb-5 mt-3'>Riprova</a><br>
                                    <?php
                                    }
                                } else {
                                    //errore sql3 -> non serve rollback perchè SELECT non modifica DB
                                    ?>
                                    <div class="alert alert-dismissible alert-danger">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        <h5 class="alert-heading">Errore!</h5>
                                        <p class="mb-0">Errore sql3: </p>
                                        <?php $mysqli->errno; ?>
                                    </div>
                                    <br>
                                    <a href='new-transaction.php' class='btn btn-danger btn-lg mb-5 mt-3'>Riprova</a><br>
                                <?php
                                }
                            } else {
                                //errore sql2
                                $mysqli->rollback();
                                ?>
                                <div class="alert alert-dismissible alert-danger">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <h5 class="alert-heading">Errore!</h5>
                                    <p class="mb-0">Errore sql2: <?php echo "$mysqli->errno"; ?></p>
                                </div>
                                <br>
                                <a href='new-transaction.php' class='btn btn-danger btn-lg mb-5 mt-3'>Riprova</a><br>
                            <?php
                            }
                        } else {
                            //errore sql1
                            //$mysqli->rollback();
                            ?>
                            <div class="alert alert-dismissible alert-danger">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <h5 class="alert-heading">Errore!</h5>
                                <p class="mb-0">Errore sql1: <?php echo "$mysqli->errno"; ?></p>
                            </div>
                            <br>
                            <a href='new-transaction.php' class='btn btn-danger btn-lg mb-5 mt-3'>Riprova</a><br>
                    <?php
                        }
                    }
                } else {
                    //errore quantità da trasferire
                    ?>
                    <div class="alert alert-dismissible alert-warning">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <h5 class="alert-heading">Avviso!</h5>
                        <p class="mb-0">Impossibile effettuare la transazione, inserire Lor€n$o.</p>
                    </div>
                    <br>
                    <a href='new-transaction.php' class='btn btn-warning btn-lg mb-5 mt-3'>Riprova</a><br>
                <?php
                }
            } else {
                //errore password
                ?>
                <div class="alert alert-dismissible alert-danger">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h5 class="alert-heading">Errore!</h5>
                    <p class="mb-0">Non sei autorizzato ad eseguire la transazione.</p>
                </div>
                <br>
                <a href='new-transaction.php' class='btn btn-danger btn-lg mb-5 mt-3'>Riprova</a><br>
    <?php
            }
        } else {
            //errore selezione mittente
            echo "<br />Errore sql iniziale: " . $mysqli->error . "<br />";
        }
    }
    ?>
</div>
<?php include ROOT_PATH . 'public/template-parts/footer.php' ?>