<?php

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

    <h2>Valida blocco</h2>
    <p class="lead">
        Indovina il nonce esatto per validare il blocco <br>
        Ricevi 20.00 Lor€n$o di ricompensa<br>
    </p>
    <?php

    if (!isset($_SESSION['difficulty'])) $_SESSION['difficulty'] = rand(1, 10);
    $difficulty = $_SESSION['difficulty'];
    if (!isset($_SESSION['nonce'])) $_SESSION['nonce'] = rand(1, $difficulty);
    $nonce = $_SESSION['nonce'];
    if (!isset($_POST['nonce_inserito'])) {
    ?>
        <form name="mining" class="form-signin center" action="mining.php" method="POST">
            <?php //PROOF OF WORK 
            ?>
            <label for="nonce">Inserisci un numero compreso tra 1 e <?php echo $difficulty . "     NONCE=" . $nonce; ?> </label><br>
            <input type='hidden' name='difficulty' value='<?php echo $difficulty; ?>' method="POST" />
            <input type='hidden' name='nonce' value='<?php echo $nonce; ?>' method="POST" />
            <input type="text" name="nonce_inserito" id="nonce_inserito" class="form-control" placeholder="nonce" required autofocus><br>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Verifica</button>
        </form>

        <?php
    } else {
        if ($_POST['nonce_inserito'] == test_input($nonce)) {
        ?>
            <div class="alert alert-dismissible alert-warning">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h5 class="alert-heading">Avviso!</h5>
                <p class="mb-0">Validazione blocco avviata...</p>
            </div>
            <br>
            <?php
            unset($_SESSION['difficulty']);
            unset($_SESSION['nonce']);
            ?>
            <?php
            //informazioni da inserire nel blocco
            /*
            INSERT INTO block(blockHash, merkleRootHash, previousBlockHash, index_block, timestamp, transaction1, transaction2, target, nonce, miner)
            VALUES ()
            */
            //miner
            $miner = $_SESSION['email'];
            //nonce 
            //target
            $target = $difficulty;
            include_once ROOT_PATH . '/DB/DBconnection.php';
            //transaction1
            $sql1 = "SELECT * FROM  transactions WHERE status='started' ORDER BY time ASC LIMIT 1";
            $result1 = $mysqli->query($sql1);
            if ($result1) {
                $row1 = $result1->fetch_array();
                $transaction1 = $row1['index_transaction'];
                $from_email1 = $row1['from_email'];
                $to_email1 = $row1['to_email'];
            } else {
                echo "Errore transazione1: " . $mysqli->errno;
            }
            //transaction2
            $sql2 = " SELECT * FROM transactions WHERE status='started' AND index_transaction != '" . $transaction1 . "' ORDER BY time ASC";
            $result2 = $mysqli->query($sql2);
            if ($result2) {
                $row2 = $result2->fetch_array();
                $transaction2 = $row2['index_transaction'];
                $from_email2 = $row2['from_email'];
                $to_email2 = $row2['to_email'];
            } else {
                echo "Errore transazione2: " . $mysqli->errno;
            }
            if ($miner == $from_email1 || $miner == $to_email1 || $miner == $from_email2 || $miner == $to_email2) {
            ?>
                <div class="alert alert-dismissible alert-danger">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h5 class="alert-heading">Errore!</h5>
                    <p class="mb-0">Errore miner: non sei autorizzato a validare il prossimo blocco.</p>
                    <?php $mysqli->errno; ?>
                </div>
                <br>
                <a href='mining.php' class='btn btn-danger btn-lg mb-5 mt-3'>Riprova</a><br>
                <?php
            } else {
                //timestamp creazione del blocco
                $timestamp = date('Y-m-d H:i:s');
                //echo "<pre> timestamp: $timestamp </pre>";
                //index_block
                $sql_index = "SELECT MAX(index_block) AS indx FROM block";
                //echo "<pre> $sql_index </pre>";
                $result_index = $mysqli->query($sql_index);
                if ($result_index) {
                    $row_index = $result_index->fetch_array();
                    $index_block = $row_index['indx'];
                    if (is_null($index_block)) {
                        $index_block = 0;
                    } else {
                        $index_block++;
                    }
                    //echo "index: $index_block <br>";
                } else {
                    echo "Errore indice blocco: " . $mysqli->errno;
                }
                //previousBlockHash
                if ($index_block == 0) {
                    $previousBlockHash = "";
                } else {
                    $i = $index_block - 1;
                    $sql_previousHash = "SELECT blockHash FROM block WHERE index_block = '" . $i . "' ";
                    //echo "<pre> $sql_previousHash </pre>";
                    $result_previousHash = $mysqli->query($sql_previousHash);
                    if ($result_previousHash) {
                        $row_previousHash = $result_previousHash->fetch_array();
                        $previousBlockHash = $row_previousHash['blockHash'];
                    } else {
                        echo "Errore blocco precedente: " . $mysqli->errno;
                    }
                }
                //merkleRootHash
                $sql_merkle = "SELECT index_transaction FROM transactions WHERE status = 'started' ORDER BY time ASC LIMIT 2";
                //echo "<pre> $sql_merkle </pre>";
                $result_merkle = $mysqli->query($sql_merkle);
                if ($result_merkle->num_rows <= 1) {
                ?>
                    <div class="alert alert-dismissible alert-danger">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <h5 class="alert-heading">Errore!</h5>
                        <p class="mb-0">Errore sql_merkle: non ci sono blocchi da validare.</p>
                        <?php $mysqli->errno; ?>
                    </div>
                    <br>
                    <a href='mining.php' class='btn btn-danger btn-lg mb-5 mt-3'>Riprova</a><br>
                    <?php
                } else {
                    $i = 0;
                    while ($row_merkle = $result_merkle->fetch_array(MYSQLI_NUM)) {
                        $array_nuovo[$i] = $row_merkle[0];
                        $i++;
                    }

                    function merkleroot(array $trx_array)
                    {
                        //Fermo la funzione se 1 solo valore rimasto -> Merkle root
                        if (count($trx_array) == 1) {
                            //concatenazione con se stessa
                            $first_transaction = $trx_array[0];
                            $second_transaction = $trx_array[0];
                            //eseguo hash sulla stessa trx 2 volte
                            $string_transaction = $first_transaction . $second_transaction;
                            $double_hash[] = hash("sha256", hash("sha256", $string_transaction));
                            $trx_array = array_values($trx_array);
                            $merkleroot = $trx_array[0];
                            unset($trx_array[0]);
                            //var_dump($trx_array);
                            return $merkleroot;
                        } else {
                            while (count($trx_array) > 1) {
                                //se ci sono più di 2 transazioni
                                if (count($trx_array) >= 2) {
                                    //recupero le prime 2 transazioni
                                    $first_transaction = $trx_array[0];
                                    $second_transaction = $trx_array[1];
                                    //var_dump($trx_array);
                                    //sha256 due volte
                                    $string_transaction[0] = $first_transaction . $second_transaction;
                                    $double_hash[] = hash("sha256", hash("sha256", $string_transaction[0]));
                                    //scarto le prime 2 transazioni
                                    unset($trx_array[0]);
                                    unset($trx_array[1]);
                                    //imposto le 2 successive come prime
                                    $trx_array = array_values($trx_array);
                                }
                            }
                            //richiamo la funzione sul nuovo array
                            return merkleroot($double_hash);
                        }
                    }
                    $merkleRootHash = merkleroot($array_nuovo);

                    //blockHash
                    $blockHash = hash("sha256", $merkleRootHash . $previousBlockHash . $index_block . $timestamp . $transaction1 . $transaction2 . $target . $nonce . $miner);
                    if ($index_block == 0) {
                        $previousBlockHash = $blockHash;
                    }
                    //gestione esito INSERT tramite transazioni
                    $mysqli->autocommit(false);
                    //BEGIN OF TRANSACTION
                    
                    $final_sql = "INSERT INTO block (blockHash, merkleRootHash, previousBlockHash, index_block, timestamp, transaction1, transaction2, target, nonce, miner) ";
                    $final_sql .= "VALUES ('$blockHash', '$merkleRootHash', '$previousBlockHash', $index_block, '$timestamp', '$transaction1', '$transaction2', $target, $nonce, '$miner')";
                    //echo "<pre> INSERIMENTO: <br> " . $final_sql . "</pre>";
                    $final_result = $mysqli->query($final_sql);
                    if ($final_sql) {
                        //inserimento indice blocco nella tabella transazioni
                        $sql_confirm1 = "UPDATE transactions SET status='confirmed', transactions.index_block = $index_block WHERE index_transaction='" . $transaction1 . "' ";
                        $sql_confirm2 = "UPDATE transactions SET status='confirmed', transactions.index_block = $index_block WHERE index_transaction='" . $transaction2 . "' ";
                        //echo "<pre>sql_confirm: " . $sql_confirm . "</pre>";
                        $result_update1 = $mysqli->query($sql_confirm1);
                        $result_update2 = $mysqli->query($sql_confirm2);
                        if ($result_update1 && $result_update2) {
                            //END OF TRANSACTION
                            $mysqli->commit(); // validazione blocco confermata
                        } else {
                            //END OF TRANSACTION
                            //errore sql_confirm
                            $mysqli->rollback(); //validazione blocco annullata
                        ?>
                            <div class="alert alert-dismissible alert-danger">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <h5 class="alert-heading">Errore!</h5>
                                <p class="mb-0">Errore sql_confirm: transazioni non aggiornate.</p>
                                <?php $mysqli->errno; ?>
                            </div>
                            <br>
                            <a href='mining.php' class='btn btn-danger btn-lg mb-5 mt-3'>Riprova</a><br>
                        <?php
                        }
                        //BEGIN OF TRANSACTION
                        //update conto miner -> ricompensa 20.00 Lor€n$o
                        $sql_user = "SELECT * FROM utente WHERE email='" . $miner . "' ";
                        $result_user = $mysqli->query($sql_user);
                        $row_user = $result_user->fetch_array();
                        $conto = $row_user['conto'];
                        $conto += 20;
                        $sql_reward = "UPDATE utente SET conto=" . $conto . " WHERE email='" . $miner . "' ";
                        //echo "<pre>sql_reward: " . $sql_reward . "</pre>";
                        $result_update = $mysqli->query($sql_reward);
                        if ($result_update) {
                            //END OF TRANSACTION
                            $mysqli->commit(); // la transazione ha esito positivo
                        ?>
                            <div class="alert alert-dismissible alert-success">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <h5 class="alert-heading">Evviva!</h5>
                                <p class="mb-0">Hai validato un blocco!</p>
                                <p class="mb-0">Hai guadagnato 20.00 Lor€n$o.</p>
                            </div>
                            <br>
                            <a href='mining.php' class='btn btn-success btn-lg mb-5 mt-3'>Continua</a><br>
                        <?php
                        } else {
                            //END OF TRANSACTION
                            //errore sql_reward
                            $mysqli->rollback();
                        ?>
                            <div class="alert alert-dismissible alert-danger">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <h5 class="alert-heading">Errore!</h5>
                                <p class="mb-0">Errore sql_reward: transazioni non aggiornate.</p>
                                <?php $mysqli->errno; ?>
                            </div>
                            <br>
                            <a href='mining.php' class='btn btn-danger btn-lg mb-5 mt-3'>Riprova</a><br>
                        <?php
                        }
                    } else {
                        //errore final_sql
                        $mysqli->rollback(); //recupero versione precedente DB in caso di errore
                    ?>
                        <div class="alert alert-dismissible alert-danger">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <h5 class="alert-heading">Errore!</h5>
                            <p class="mb-0">Errore final_sql: <?php echo "$mysqli->errno $final_sql $final_result"; ?> </p>
                        </div>
                        <br>
                        <a href='mining.php' class='btn btn-danger btn-lg mb-5 mt-3'>Riprova</a><br>
            <?php
                    }
                }
            }
        } else {
            ?>
            <div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h5 class="alert-heading">Errore!</h5>
                <p class="mb-0">Nonce non valido.</p>
            </div>
            <br>
            <a href='mining.php' class='btn btn-danger btn-lg mb-5 mt-3'>Riprova</a><br>
    <?php
        }
    }
    ?>
</div>
<?php include_once ROOT_PATH . 'public/template-parts/footer.php' ?>