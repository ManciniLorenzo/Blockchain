<?php
// error_reporting(E_ERROR | E_PARSE); // non visualizza i warning
error_reporting(E_ALL ^ E_WARNING);
// costanti
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'blockchain');

// connessione
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
    // die("mess");
}
// charset utf-8
$mysqli->set_charset("utf8");
/*
//eliminazione tag pericolosi
$utente = htmlspecialchars($_POST['utente']);
$password = htmlspecialchars($_POST['password']);
// SQLinjection
$utente = $mysqli->real_escape_string($utente);
$password = $mysqli->real_escape_string($password);

// query
$sql = "select * from utente ";
$sql .= "where utente = '" . $utente . "' ";


echo "<pre>" . $sql . "</pre>";

$result = $mysqli->query($sql);

// ... salvar su tabelle di LOG (id, utente, esito, data_ora)

if ($result) {
    //echo "OK";


    if ($result->num_rows != 0) {

        while ($row = $result->fetch_array()) {
            if (md5($password) == $row['password']) {
                //
                echo "OK";

                echo '<a href="introudction.php" title="continua">continua</a>';

                // redirect 

                // ... salvar su tabelle di LOG
            } else {
                echo "password errata";
            }
        }
    } else {
        echo "utente errato";
    }
    $result->close();
} else {
    echo "ERRORE";
}

$mysqli->close();
*/