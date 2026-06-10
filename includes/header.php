<?php


declare(strict_types=1);

$pageTitle = $pageTitle ?? 'TTRPG Grimoire';
$isLoggedIn = function_exists('isLoggedIn') && isLoggedIn();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?> — Doom Grimoire</title>

  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=Metal+Mania&family=Source+Serif+4:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

    
    <link rel="stylesheet" href="assets/css/doom-theme.css">
</head>
<body class="doom-body">

<nav class="navbar navbar-expand-lg navbar-dark doom-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand doom-brand" href="<?= $isLoggedIn ? 'index.php' : 'login.php' ?>">
            <span class="brand-icon">&#9760;</span> Doom Grimoire
        </a>

        <button class="navbar-toggler border-danger" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <?php if ($isLoggedIn): ?>
                    <li class="nav-item">
                        <a class="nav-link doom-nav-link" href="index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link doom-nav-link" href="character_create.php">Forge Character</a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link doom-user-badge">
                            <?= currentUsername() ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-sm btn-doom-outline" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link doom-nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-sm btn-doom" href="register.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="container py-5">
