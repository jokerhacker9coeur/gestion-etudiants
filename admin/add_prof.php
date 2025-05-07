<?php
// admin/add_prof.php
session_start();
require_once '../config.php';
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $mdp = $_POST['password'];
    $hash = password_hash($mdp, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, 'prof')");
    $stmt->execute([$nom, $email, $hash]);
    header('Location: prof_list.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter un professeur</title>
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../font/css/all.min.css">
  <style>
    .card-hover:hover { transform: translateY(-4px); transition: 0.2s; }
  </style>
</head>
<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <button class="btn btn-outline-light me-3" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
      <i class="fas fa-bars"></i>
    </button>
    <span class="navbar-brand mb-0 h1">Dashboard Admin</span>
    <a href="../logout.php" class="btn btn-outline-light">
      <i class="fas fa-sign-out-alt me-1"></i>Déconnexion
    </a>
  </div>
</nav>

<!-- OFFCANVAS SIDEBAR -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebar">
  <div class="offcanvas-header bg-secondary text-white">
    <h5 class="offcanvas-title">Menu Admin</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body p-0">
    <div class="list-group list-group-flush">
      <a href="dashboard.php" class="list-group-item list-group-item-action">
        <i class="fas fa-home me-2"></i>Accueil
      </a>
      <a href="add_prof.php" class="list-group-item list-group-item-action active">
        <i class="fas fa-user-tie me-2"></i>Ajouter professeur
      </a>
      <a href="ajouter_matiere.php" class="list-group-item list-group-item-action">
        <i class="fas fa-book me-2"></i>Ajouter matière
      </a>
      <a href="ajouter_semestre.php" class="list-group-item list-group-item-action">
        <i class="fas fa-calendar-alt me-2"></i>Ajouter semestre
      </a>
      <a href="etudiants.php" class="list-group-item list-group-item-action">
        <i class="fas fa-user-graduate me-2"></i>Gérer étudiants
      </a>
    </div>
  </div>
</div>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0"><i class="fas fa-user-tie me-2"></i>Ajouter un professeur</h5>
        </div>
        <div class="card-body">
          <form method="post">
            <div class="mb-4">
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                <input type="text" name="nom" class="form-control" placeholder="Nom complet" required>
              </div>
            </div>

            <div class="mb-4">
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="Adresse e-mail" required>
              </div>
            </div>

            <div class="mb-4">
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Mot de passe initial" required>
              </div>
            </div>

            <button type="submit" class="btn btn-success w-100 py-2">
              <i class="fas fa-plus-circle me-1"></i>Créer le professeur
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
