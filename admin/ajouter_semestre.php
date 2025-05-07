<?php
// admin/ajouter_semestre.php
session_start();
require_once '../config.php';
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$ok = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $annee  = $_POST['annee_etude'];
    $numero = $_POST['numero'];
    try {
        $stmt = $pdo->prepare("INSERT INTO semestres (annee_etude, numero) VALUES (?, ?)");
        $stmt->execute([$annee, $numero]);
        $ok = "Semestre ajouté avec succès.";
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter un semestre</title>
  <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
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
      <a href="add_prof.php" class="list-group-item list-group-item-action">
        <i class="fas fa-user-tie me-2"></i>Ajouter professeur
      </a>
      <a href="ajouter_matiere.php" class="list-group-item list-group-item-action">
        <i class="fas fa-book me-2"></i>Ajouter matière
      </a>
      <a href="ajouter_semestre.php" class="list-group-item list-group-item-action active">
        <i class="fas fa-calendar-alt me-2"></i>Ajouter semestre
      </a>
      <a href="etudiants.php" class="list-group-item list-group-item-action">
        <i class="fas fa-user-graduate me-2"></i>Gérer étudiants
      </a>
    </div>
  </div>
</div>

  <!-- FORMULAIRE -->
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-lg">
          <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="fas fa-calendar-plus me-2"></i>Ajouter un semestre</h5>
          </div>
          <div class="card-body">
            <?php if ($ok): ?><div class="alert alert-success"><?= htmlspecialchars($ok) ?></div><?php endif; ?>
            <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

            <form method="post">
              <div class="mb-4">
                <label for="annee_etude" class="form-label"><i class="fas fa-graduation-cap me-1"></i>Année d'étude</label>
                <select id="annee_etude" name="annee_etude" class="form-select" required>
                  <option value="">-- Sélectionner --</option>
                  <option value="1">Licence 1</option>
                  <option value="2">Licence 2</option>
                  <option value="3">Licence 3</option>
                  <option value="4">Master 1</option>
                  <option value="5">Master 2</option>
                </select>
              </div>

              <div class="mb-4">
                <label for="numero" class="form-label"><i class="fas fa-list-ol me-1"></i>Numéro de semestre</label>
                <select id="numero" name="numero" class="form-select" required>
                  <option value="">-- Choisir une année d'abord --</option>
                </select>
              </div>

              <button type="submit" class="btn btn-primary w-100 py-2">
                <i class="fas fa-plus-circle me-1"></i>Ajouter le semestre
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- SCRIPTS -->
  <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const selectAnnee  = document.getElementById('annee_etude');
      const selectNumero = document.getElementById('numero');
      const mapSem = {1:[1,2], 2:[3,4], 3:[5,6], 4:[7,8], 5:[9,10]};

      function updateSemestres() {
        const vals = mapSem[selectAnnee.value] || [];
        selectNumero.innerHTML = vals.length
          ? vals.map(n => `<option value="${n}">Semestre ${n}</option>`).join('')
          : '<option value="">-- Choisir une année d\'abord --</option>';
      }

      selectAnnee.addEventListener('change', updateSemestres);
    });
  </script>

</body>
</html>
