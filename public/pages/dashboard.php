<?php
// Prevent from direct access

include '../../inc/init.php';
include ROOT_PATH . 'public/template-parts/header.php';
require "../../auth/pages/session.php";
?>

<div id="main" class="container" style="margin-top:100px">

<link rel="stylesheet" href="<?php echo ROOT_URL; ?>assets/css/style.css">

<h1>Blockchain in PHP</h1>
<div style="font-size:x-large">
<p class="lead">
    In questo sito potrai:
    <ul>
        <li>Eseguire una <strong>NUOVA</strong> transazione</li>
        <li>Monitorare le <strong>TUE</strong> transazioni</li>
        <li><strong>VALIDARE</strong> i blocchi della catena</li>
        <li>Visualizzare <strong>TUTTE</strong> le transazioni</li>
        <li>Monitorare l'intera <strong>CATENA</strong> di blocchi</li>
    </ul>
</p>
</div>
</div>

<?php include ROOT_PATH . 'public/template-parts/footer.php' ?>