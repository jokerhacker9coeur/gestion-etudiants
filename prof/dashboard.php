<?php
// prof/dashboard.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'prof') {
    header('Location: ../login.php');
    exit();
}
require_once '../config.php';

$profId = $_SESSION['user_id'];
// Récupérer les matières du prof avec leur semestre
$stmt = $pdo->prepare("
    SELECT m.id AS matiere_id, m.nom AS matiere, s.annee_etude, s.numero
    FROM matieres m
    JOIN semestres s ON m.semestre_id = s.id
    WHERE m.professeur_id = ?
    ORDER BY s.annee_etude, s.numero
");
$stmt->execute([$profId]);
$cours = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Espace Professeur - Mes Cours</title>
  <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { min-height: 100vh; display: flex; flex-direction: column; }
    main { flex: 1; }
  </style>
</head>
<body class="bg-light">

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Mes Cours</a>
      <div class="d-flex">
        <a href="../logout.php" class="btn btn-outline-light">Déconnexion</a>
      </div>
    </div>
  </nav>

  <!-- CONTENU PRINCIPAL -->
  <main class="container py-4">
    <h2 class="mb-4">Cours que j’enseigne</h2>

    <?php if (empty($cours)): ?>
      <div class="alert alert-info">Aucun cours trouvé pour votre compte.</div>
    <?php else: ?>
      <div class="row g-4">
        <?php foreach($cours as $c): ?>
          <div class="col-sm-6 col-lg-4">
            <div class="card h-100 shadow-sm">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title">Licence <?= $c['annee_etude'] ?> - Sem. <?= $c['numero'] ?></h5>
                <p class="card-text flex-grow-1"><?= htmlspecialchars($c['matiere']) ?></p>
                <a href="notes.php?matiere_id=<?= $c['matiere_id'] ?>" 
                   class="btn btn-primary mt-2">Gérer les notes</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>
  <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- FOOTER -->
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
