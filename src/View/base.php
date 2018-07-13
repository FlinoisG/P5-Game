<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="favicon.ico">
        <title><?= $title ?></title>
        <link href="https://fonts.googleapis.com/css?family=Roboto|Ubuntu" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">

        <link href="assets/CSS/base.css" rel="stylesheet">
        <?php if (isset($customStyle)) echo $customStyle; ?>
        <?php if (isset($scriptHead)) echo $scriptHead; ?>
    </head>
    <body>
    <nav id="nav" class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
      <a class="navbar-brand" href="#">Navbar</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="?p=home">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="?p=home">Classement</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="?p=home">Contact</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="?p=home">Aide</a>
          </li>          
          <?php
            if (isset($_SESSION['auth'])) {
                echo '
                <li class="nav-item">
                  <a class="nav-link" href="?p=home.settings">User Settings</a>
                </li>
                <li class="nav-item">
                  <a class="btn btn-danger btn-logout" href="?p=home&logout=true">Déconnextion</a>
                </li>';
            } else {
                echo '
                <li class="nav-item">
                  <a class="nav-link" href="?p=login">Connection</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="?p=login.register">S\'inscrire</a>
                </li>';
            }
            ?>
        </ul>
      </div>
    </nav>
    <div id="main">
      <?= $content ?>
    </div>
    <?php if (isset($scriptBody)) echo $scriptBody; ?>
    </body>
</html>