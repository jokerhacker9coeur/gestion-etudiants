<?php
session_start();
require_once '../config.php'; // connexion PDO à $pdo

if ($_SESSION['role'] !== 'admin') exit;

$ok = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $etudiant_id = $_POST['etudiant_id'];
    $matiere_id = $_POST['matiere_id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO inscriptions (etudiant_id, matiere_id) VALUES (?, ?)");
        $stmt->execute([$etudiant_id, $matiere_id]);
        $ok = "Inscription réussie.";
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') {
            $error = "Cette inscription existe déjà.";
        } else {
            $error = "Erreur : " . $e->getMessage();
        }
    }
}

// Charger les étudiants et matières
$etudiants = $pdo->query("SELECT id, nom, prenom FROM etudiants ORDER BY nom")->fetchAll();
$matieres = $pdo->query("SELECT id, nom FROM matieres ORDER BY nom")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter une inscription</title>
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>
<body class="bg-light text-dark">
<div class="container mt-5">
  <div class="card shadow">
    <div class="card-header bg-primary text-white">
      <h4>Inscrire un étudiant à une matière</h4>
    </div>
    <div class="card-body">
      <?php if ($ok): ?>
        <div class="alert alert-success"><?= $ok ?></div>
      <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>
      <form method="post">
        <div class="mb-3">
          <label for="etudiant_id" class="form-label">Étudiant</label>
          <select name="etudiant_id" id="etudiant_id" class="form-select" required>
            <option value="">-- Sélectionner --</option>
            <?php foreach ($etudiants as $e): ?>
              <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nom']) ?> <?= htmlspecialchars($e['prenom']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="matiere_id" class="form-label">Matière</label>
          <select name="matiere_id" id="matiere_id" class="form-select" required>
            <option value="">-- Sélectionner --</option>
            <?php foreach ($matieres as $m): ?>
              <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nom']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <button class="btn btn-success">Enregistrer</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>
