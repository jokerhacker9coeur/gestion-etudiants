<?php
// admin/ajouter_matiere.php
session_start();
require_once '../config.php';
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 1. Insertion de la matière
        $stmt = $pdo->prepare(
            "INSERT INTO matieres (nom, semestre_id, professeur_id, coefficient) VALUES (:nom, :semestre, :prof, :coef)"
        );
        $stmt->execute([
            ':nom'      => $_POST['nom'],
            ':semestre' => $_POST['semestre_id'],
            ':prof'     => $_POST['professeur_id'],
            ':coef'     => $_POST['coefficient']
        ]);
        $matiereId = $pdo->lastInsertId();

        // 2. Récupérer les infos du semestre créé
        $sem = $pdo->prepare("SELECT annee_etude, numero FROM semestres WHERE id = ?");
        $sem->execute([$_POST['semestre_id']]);
        $info = $sem->fetch();
        $anneeEtude = (int)$info['annee_etude'];
        $numero     = (int)$info['numero'];

        // 3. Déterminer le niveau à inscrire automatiquement
        if ($anneeEtude >= 1 && $anneeEtude <= 3) {
            $niveauCible = 'L' . $anneeEtude;
        } elseif ($anneeEtude >= 4 && $anneeEtude <= 5) {
            $niveauCible = 'M' . ($anneeEtude - 3);
        } else {
            $niveauCible = null;
        }

        // 4. Auto-inscription si on a bien un niveau cible
        if ($niveauCible) {
            $insc = $pdo->prepare(
                "INSERT IGNORE INTO inscriptions (etudiant_id, matiere_id) SELECT id, :mid FROM etudiants WHERE niveau = :niv"
            );
            $insc->execute([':mid' => $matiereId, ':niv' => $niveauCible]);
        }

        $success = "Matière créée et étudiants {$niveauCible} inscrits automatiquement.";
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}

// Récupérer semestres et professeurs pour le formulaire
$semestres = $pdo->query("SELECT id, annee_etude, numero FROM semestres ORDER BY annee_etude, numero")->fetchAll();
$profs     = $pdo->query("SELECT id, nom FROM utilisateurs WHERE role = 'prof' ORDER BY nom")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter une matière</title>
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
      <a href="dashboard.php" class="list-group-item list-group-item-action ">
        <i class="fas fa-home me-2"></i>Accueil
      </a>
      <a href="add_prof.php" class="list-group-item list-group-item-action">
        <i class="fas fa-user-tie me-2"></i>Ajouter professeur
      </a>
      <a href="ajouter_matiere.php" class="list-group-item list-group-item-action active">
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

  <!-- CONTENU -->
  <div class="container mt-5">
    <div class="card shadow">
      <div class="card-header bg-dark text-white">
        <h4 class="mb-0"><i class="fas fa-book me-2"></i>Ajouter une matière</h4>
      </div>
      <div class="card-body">
        <?php if ($success): ?>
          <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php elseif ($error): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
          <div class="mb-3">
            <label for="nom" class="form-label"><i class="fas fa-heading me-1"></i>Nom de la matière</label>
            <input type="text" id="nom" name="nom" class="form-control" placeholder="Entrer le nom" required>
          </div>

          <div class="mb-3">
            <label for="semestre_id" class="form-label"><i class="fas fa-calendar-alt me-1"></i>Semestre</label>
            <select id="semestre_id" name="semestre_id" class="form-select" required>
              <option value="">-- Sélectionner --</option>
              <?php foreach ($semestres as $s): ?>
                <?php
                  if ($s['annee_etude'] <= 3) {
                    $label = "Licence {$s['annee_etude']} - Sem {$s['numero']}";
                  } else {
                    $label = "Master " . ($s['annee_etude'] - 3) . " - Sem {$s['numero']}";
                  }
                ?>
                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($label) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="professeur_id" class="form-label"><i class="fas fa-user-tie me-1"></i>Professeur</label>
            <select id="professeur_id" name="professeur_id" class="form-select" required>
              <option value="">-- Sélectionner --</option>
              <?php foreach ($profs as $p): ?>
                <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nom']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="coefficient" class="form-label"><i class="fas fa-percent me-1"></i>Coefficient</label>
            <input type="number" step="0.01" id="coefficient" name="coefficient" class="form-control" placeholder="e.g. 1.5" required>
          </div>

          <button type="submit" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i>Enregistrer
          </button>
        </form>
      </div>
    </div>
  </div>

  <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

