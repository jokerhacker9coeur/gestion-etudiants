<?php
session_start();
require_once '../config.php'; // connexion PDO avec $pdo

if ($_SESSION['role'] !== 'admin') exit;

$ok = $error = '';

// Charger les inscriptions (jointure avec étudiant + matière)
$inscriptions = $pdo->query("
    SELECT i.id, e.nom AS etu_nom, e.prenom AS etu_prenom, m.nom AS matiere_nom
    FROM inscriptions i
    JOIN etudiants e ON i.etudiant_id = e.id
    JOIN matieres m ON i.matiere_id = m.id
    ORDER BY e.nom, m.nom
")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inscription_id = $_POST['inscription_id'];
    $note_cc = $_POST['note_cc'];
    $note_examen = $_POST['note_examen'];

    try {
        $stmt = $pdo->prepare("INSERT INTO notes (inscription_id, note_cc, note_examen) VALUES (?, ?, ?)
                               ON DUPLICATE KEY UPDATE note_cc = VALUES(note_cc), note_examen = VALUES(note_examen)");
        $stmt->execute([$inscription_id, $note_cc, $note_examen]);
        $ok = "Note enregistrée avec succès.";
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter des notes</title>
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>
<body class="bg-light text-dark">
<div class="container mt-5">
  <div class="card shadow">
    <div class="card-header bg-success text-white">
      <h4>Ajouter des notes</h4>
    </div>
    <div class="card-body">
      <?php if ($ok): ?>
        <div class="alert alert-success"><?= $ok ?></div>
      <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>
      <form method="post">
        <div class="mb-3">
          <label for="inscription_id" class="form-label">Étudiant et matière</label>
          <select name="inscription_id" id="inscription_id" class="form-select" required>
            <option value="">-- Sélectionner --</option>
            <?php foreach ($inscriptions as $i): ?>
              <option value="<?= $i['id'] ?>">
                <?= htmlspecialchars($i['etu_nom']) ?> <?= htmlspecialchars($i['etu_prenom']) ?> - <?= htmlspecialchars($i['matiere_nom']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="note_cc" class="form-label">Note contrôle continu (CC)</label>
          <input type="number" step="0.01" max="20" min="0" name="note_cc" id="note_cc" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="note_examen" class="form-label">Note examen</label>
          <input type="number" step="0.01" max="20" min="0" name="note_examen" id="note_examen" class="form-control" required>
        </div>
        <button class="btn btn-success">Enregistrer</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>
