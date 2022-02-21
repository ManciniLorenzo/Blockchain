<?php include '../../inc/init.php' ?>
<link rel="stylesheet" href="<?php echo ROOT_URL; ?>assets/css/style.css">
<?php include ROOT_PATH . 'public/template-parts/header.php' ?>

<div id="main" class="container" style="margin-top:100px">

<h1>Benvenuti</h1> 
<br>
<br>
<p class="lead">Questo sito illustrerà il funzionamento di una struttura Blockchain creata mediante il linguaggio PHP</p>
<br>
<p class="lead">Per iniziare accedi o registrati</p>
<br>
<a href="<?php echo ROOT_URL . 'auth/pages/login.php'; ?>" class="btn btn-primary btn-lg mb-5 mt-3">Accedi &raquo;</a>
<a href="<?php echo ROOT_URL . 'auth/pages/register.php'; ?>" class="btn btn-primary btn-lg mb-5 mt-3">Registrati &raquo;</a>

</div>
<?php include ROOT_PATH . 'public/template-parts/footer.php' ?>