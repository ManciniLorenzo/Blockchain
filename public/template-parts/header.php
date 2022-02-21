<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://bootswatch.com/4/cyborg/bootstrap.css">
    <link rel="stylesheet" href="<?php echo ROOT_URL; ?>assets/css/style.css">
    <title>Blockchain in PHP</title>
</head>
<body>
<!--<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top"> -->
<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="<?php echo ROOT_URL; ?>public/pages/dashboard.php">Lor€n$o</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
        
        <li class="nav-item">
            <a class="nav-link" href="<?php echo ROOT_URL; ?>public/pages/new-transaction.php">Nuova transazione</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo ROOT_URL; ?>public/pages/my-transaction.php">Le mie transazioni</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo ROOT_URL; ?>public/pages/mining.php">Mining</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo ROOT_URL; ?>public/pages/view-transactions.php">Transazioni</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo ROOT_URL; ?>public/pages/view-blockchain.php">Blockchain</a>
        </li>
        </ul>

        <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Area utente</a>
            <div class="dropdown-menu" aria-labelledby="dropdown01">
            <a class="dropdown-item" href="<?php echo ROOT_URL; ?>public/pages/informations.php">Informazioni</a>
            <a class="dropdown-item" href="<?php echo ROOT_URL; ?>auth/pages/logout.php">Logout</a>
            </div>
        </li>
        </ul>

    </div>
  </div>
</nav>