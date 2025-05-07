<?php
session_start();
require_once '../config.php';

// Redirection si non authentifié ou pas admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

// Récupération des données
$profs     = $pdo->query("SELECT id, nom, email FROM utilisateurs WHERE role = 'prof'")->fetchAll();
$semestres = $pdo->query("SELECT * FROM semestres ORDER BY annee_etude, numero")->fetchAll();
$query     = "SELECT m.nom AS matiere,  s.annee_etude, s.numero
               FROM matieres m
               JOIN semestres s ON m.semestre_id = s.id
               ORDER BY s.annee_etude, s.numero";
$matieres  = $pdo->query($query)->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Admin - Tableau de bord</title>
  <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../font/css/all.min.css" rel="stylesheet">
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
      <a href="dashboard.php" class="list-group-item list-group-item-action active">
        <i class="fas fa-home me-2"></i>Accueil
      </a>
      <a href="add_prof.php" class="list-group-item list-group-item-action">
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

<!-- MAIN CONTENT -->
<div class="container mt-4">
  <div class="row g-4">
    <!-- Profs Card -->
    <div class="col-md-4">
      <div class="card card-hover shadow-sm">
        <div class="card-body">
          <h5 class="card-title text-primary"><i class="fas fa-users me-2"></i>Professeurs</h5>
          <p class="card-text">Total : <?= count($profs) ?></p>
          <a href="prof_list.php" class="btn btn-outline-primary btn-sm">Voir la liste</a>
        </div>
      </div>
    </div>
    <!-- Semestres Card -->
    <div class="col-md-4">
      <div class="card card-hover shadow-sm">
        <div class="card-body">
          <h5 class="card-title text-secondary"><i class="fas fa-calendar-check me-2"></i>Semestres</h5>
          <p class="card-text">Total : <?= count($semestres) ?></p>
        </div>
      </div>
    </div>
    <!-- Matières Card -->
    <div class="col-md-4">
      <div class="card card-hover shadow-sm">
        <div class="card-body">
          <h5 class="card-title text-dark"><i class="fas fa-book-open me-2"></i>Matières</h5>
          <p class="card-text">Total : <?= count($matieres) ?></p>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Detailed Tables -->
  <div class="mt-5">
    <h4>Liste des professeurs</h4>
    <table class="table table-hover table-bordered">
      <thead class="table-light"><tr><th>Nom</th><th>Email</th></tr></thead>
      <tbody>
        <?php foreach($profs as $prof): ?>
        <tr><td><?= htmlspecialchars($prof['nom']) ?></td><td><?= htmlspecialchars($prof['email']) ?></td></tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <h4 class="mt-5">Semestres</h4>
    <ul class="list-group">
      <?php foreach($semestres as $sem): ?>
      <li class="list-group-item">
        Licence <?= $sem['annee_etude'] ?> – Sem. <?= $sem['numero'] ?>
      </li>
      <?php endforeach; ?>
    </ul>

    <h4 class="mt-5">Matières</h4>
    <table class="table table-striped">
      <thead><tr><th>Matière</th><th>Semestre</th></tr></thead>
      <tbody>
        <?php foreach($matieres as $m): ?>
        <tr>
          <td><?= htmlspecialchars($m['matiere']) ?></td>
          <td>L<?= $m['annee_etude'] ?> – S<?= $m['numero'] ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

