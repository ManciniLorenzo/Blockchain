<?php
    session_start();
    session_unset();
    session_destroy();
    $_SESSION = array();

    include '../../inc/init.php';
    require "../../auth/pages/session.php";

    ?>
    
    <link rel="stylesheet" href="<?php echo ROOT_URL; ?>assets/css/style.css">
    <?php include ROOT_PATH . 'public/template-parts/header.php';
?>

    <div id="main" class="container" style="margin-top:100px">
        Sessione conclusa o scaduta: <br>
        Torna al Login oppure esci dal sito <br>
        <a href="<?php echo ROOT_URL . 'auth/pages/login.php'; ?>" class="btn btn-warning btn-lg mb-5 mt-3">Login</a>
    </div>
<?php include ROOT_PATH . 'public/template-parts/footer.php' ?>