<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $this->env(
      "base_url"
    ) ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title><?= $title ?></title>
   
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="<?= $this->env("base_url") ?>">GradShare</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a href="<?= $this->env("base_url") ?>" class="nav-link"><i class="fas fa-home"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a href="<?= $this->env("base_url") ?>upload" class="nav-link" id="uploadBtn"><i class="fas fa-upload"></i> Upload Project</a>
                </li>
                <li class="nav-item">
                    <a href="<?= $this->env("base_url") ?>profile" class="nav-link"><i class="fas fa-user"></i> Profile</a>
                </li>
                <li class="nav-item">
                    <a href="<?= $this->env("base_url") ?>about" class="nav-link"><i class="fas fa-info-circle"></i> About</a> <!-- Added About link -->
                </li>
                <li class="nav-item">
                    <a href="<?= $this->env("base_url") ?>logout" class="nav-link" id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Log Out</a>
                </li>
            </ul>
        </div>
    </div>
</nav>