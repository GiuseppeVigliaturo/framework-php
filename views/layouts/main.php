<?php

use app\core\Application;
use app\models\RegisterModel;

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="generator" content="Jekyll v4.1.1">
    <title>Framework php</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/contact">Contact</a>
                </li>

            </ul>
            <?php if (Application::isGuest()) :  ?>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="/login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/register">Register</a>
                    </li>

                </ul>
            <?php else : ?>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/profile">Profile</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="/logout"><?php echo "Benvenuto " . Application::$app->user->getDisplayName() ?>
                            (LOGOUT)
                        </a>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">
        <?php if (Application::$app->session->getFlash('success')) : ?>
            <div class="alert alert-success">
                <?php echo Application::$app->session->getFlash('success') ?>
            </div>
        <?php endif; ?>
        {{content}}

    </div>

</body>

</html>