<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- to avoid this error at the top 
timezone_openNotice: session_start(): Ignoring session_start() 
because a session is already active in C:\Users\trapen\OneDrive
- Watlow\Documents\Chi Uni\Year 2\Semester 2\DAB502\snowsnails\
header.php on line 2 -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Snows Nails</title>
    <link rel="icon" type="image/png" href="/assets/images/favicon/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/svg+xml" href="/assets/images/favicon/favicon.svg">
    <link rel="shortcut icon" href="/assets/images/favicon/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/favicon/apple-touch-icon.png">
    <link rel="manifest" href="/assets/images/favicon/site.webmanifest">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>

<body>
    <!-- HEADER -->
    <header>
        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid moving-nav">
                <a class="navbar-brand link logo" href="/index.php">
                    <span class="never-display">Logo that goes to home page</span>
                    <i class="fa-solid fa-b"></i>
                    <i class="fa-brands fa-d-and-d"></i>
                    <i class="fa-solid fa-b"></i></a>
                <button class="navbar-toggler border link hamburger" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarsExample05" aria-controls="navbarsExample05" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon link"><i class="fa-solid fa-bars hamburger"></i></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarsExample05">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active link" aria-current="page" href="/index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link link" href="/bookingform.php">Contact Us</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle link" href="#" id="dropdown05" data-bs-toggle="dropdown"
                                aria-expanded="false">What We Offer</a>
                            <ul class="dropdown-menu dropdown-container" aria-labelledby="dropdown05">
                                <li><a class="dropdown-item link" href="/nails.php">Nails</a></li>
                                <li><a class="dropdown-item link" href="/lashes.php">Lashes</a></li>
                                <li><a class="dropdown-item link" href="/treatments.php">Body Treatments</a></li>
                            </ul>
                        </li>
<?php if (isset($_SESSION['email'])): ?>

    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle link" href="#" id="dropdown05"
           data-bs-toggle="dropdown" aria-expanded="false">
            <?= htmlspecialchars($_SESSION['name']); ?>
        </a>

        <ul class="dropdown-menu dropdown-container" aria-labelledby="dropdown05">
            <li><a class="dropdown-item link" href="/nails.php">Nails</a></li>
            <li><a class="dropdown-item link" href="/lashes.php">Lashes</a></li>
            <li><a class="dropdown-item link" href="/treatments.php">Body Treatments</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item link" href="/users/logout.php">Logout</a></li>
        </ul>
    </li>

<?php else: ?>

    <li class="nav-item">
        <a class="nav-link link" href="/users/login.php">Login</a>
    </li>

    <li class="nav-item">
        <a class="nav-link link" href="/users/register.php">Register</a>
    </li>

<?php endif; ?>
                    </ul>
                </div>
                <!-- <div>
                    <a class="dropdown-item link" href="/users/login.php"><i class="fa-regular fa-circle-user login"></i></a>
                </div> -->
            </div>
        </nav>
    </header>