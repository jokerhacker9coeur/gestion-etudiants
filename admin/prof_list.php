<?php
// admin/prof_list.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}
require_once '../config.php';
$profs = $pdo->query("SELECT id, nom, email FROM utilisateurs WHERE role = 'prof'")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Liste des professeurs</title>
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

<!-- MAIN CONTENT -->
<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-users me-2"></i>Liste des professeurs</h2>
    <a href="add_prof.php" class="btn btn-success">
      <i class="fas fa-user-plus me-1"></i>Ajouter
    </a>
  </div>

  <?php if ($profs): ?>
  <div class="list-group">
    <?php foreach ($profs as $p): ?>
      <div class="list-group-item d-flex justify-content-between align-items-center card-hover">
        <div>
          <i class="fas fa-user-tie me-2 text-primary"></i>
          <strong><?= htmlspecialchars($p['nom']) ?></strong>
          <span class="text-muted ms-2"><?= htmlspecialchars($p['email']) ?></span>
        </div>
        <div>
          <a href="edit_prof.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-warning me-1">
            <i class="fas fa-edit"></i>
          </a>
          <a href="delete_prof.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirmer la suppression ?')">
            <i class="fas fa-trash-alt"></i>
          </a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
    <div class="alert alert-info mt-3">Aucun professeur trouvé.</div>
  <?php endif; ?>
</div>

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
