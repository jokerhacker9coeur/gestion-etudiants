<?php
// ajouter_matiere.php
session_start();
require_once '../config.php';

if ($_SESSION['role'] !== 'admin') {
    header('HTTP/1.0 403 Forbidden');
    exit('Accès refusé');
}

// Traitement formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom            = trim($_POST['nom']);
    $semestre_id    = $_POST['semestre_id'];
    $professeur_id  = $_POST['professeur_id'];
    $coef           = $_POST['coefficient'];

    try {
        $stmt = $pdo->prepare("
            INSERT INTO matieres (nom, semestre_id, professeur_id, coefficient)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$nom, $semestre_id, $professeur_id, $coef]);
        $success = "Matière ajoutée avec succès.";
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}

// Récupération des semestres et professeurs
$semestres = $pdo
    ->query("SELECT id, annee_etude, numero FROM semestres ORDER BY annee_etude, numero")
    ->fetchAll();

$profs = $pdo
    ->query("SELECT id, nom FROM utilisateurs WHERE role = 'prof' ORDER BY nom")
    ->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter une matière</title>
  <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../font/css/all.min.css">
</head>
<body class="bg-light text-dark">
  <!-- NAVBAR -->
<nav class="navbar navbar-dark bg-dark px-3">
    <div class="d-flex align-items-center">
        <button class="btn btn-outline-light me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <button class="btn btn-outline-light" id="themeToggle" title="Changer de thème">
            <i class="fas fa-moon" id="themeIcon"></i>
        </button>
    </div>
    <span class="navbar-text text-white ms-auto">Panneau d’administration</span>
</nav>

<!-- SIDEBAR -->
<div class="offcanvas offcanvas-start bg-light text-dark" tabindex="-1" id="sidebar">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fermer"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="#">🏠 Tableau de bord</a></li>
            <li class="nav-item"><a class="nav-link" href="add_prof.php">👨‍🏫 Ajouter un professeur</a></li>
            <li class="nav-item"><a class="nav-link" href="ajouter_matiere.php">📘 Ajouter une matière</a></li>
            <li class="nav-item"><a class="nav-link" href="ajouter_semestre.php">📅 Ajouter un semestre</a></li>
            <li class="nav-item"><a class="nav-link" href="etudiants.php">🎓 Ajouter un étudiant</a></li>
            <li class="nav-item"><a class="nav-link text-danger" href="../logout.php">🚪 Déconnexion</a></li>
        </ul>
    </div>
</div>
  <div class="container mt-5">
    <div class="card shadow">
      <div class="card-header bg-dark text-white">
        <h4 class="mb-0">Ajouter une matière</h4>
      </div>
      <div class="card-body">
        <?php if (!empty($success)): ?>
          <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
          <div class="mb-3">
            <label for="nom" class="form-label">Nom de la matière</label>
            <input type="text" name="nom" id="nom" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="semestre_id" class="form-label">Semestre</label>
            <select name="semestre_id" id="semestre_id" class="form-select" required>
              <option value="">-- Choisir --</option>
              <?php foreach ($semestres as $s): ?>
                <option value="<?= $s['id'] ?>">
                  Licence <?= $s['annee_etude'] ?> - Semestre <?= $s['numero'] ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="professeur_id" class="form-label">Professeur</label>
            <select name="professeur_id" id="professeur_id" class="form-select" required>
              <option value="">-- Choisir --</option>
              <?php foreach ($profs as $p): ?>
                <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nom']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="coefficient" class="form-label">Coefficient</label>
            <input type="number" name="coefficient" id="coefficient" step="0.1" class="form-control" required>
          </div>

          <button type="submit" class="btn btn-primary">Enregistrer</button>
          <a href="admin_dashboard.php" class="btn btn-secondary ms-2">Annuler</a>
        </form>
      </div>
    </div>
  </div>
  <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
  <footer class="bg-dark text-white py-4 mt-5">
  <div class="container">
    <div class="row">
      <!-- À propos -->
      <div class="col-md-4 mb-3">
        <h5>🎓 Université</h5>
        <p>Plateforme de gestion académique pour les étudiants et professeurs.</p>
      </div>
    </div>

    <hr class="bg-secondary">
    <p class="text-center mb-0">&copy; <?= date('Y') ?> Université | Tous droits réservés.</p>
  </div>
</footer>
</body>
</html>
