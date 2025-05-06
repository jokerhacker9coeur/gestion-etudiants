<?php
// prof/notes.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'prof') {
    header('Location: ../login.php');
    exit();
}
require_once '../config.php';

$profId    = $_SESSION['user_id'];
$matiereId = $_GET['matiere_id'] ?? 0;

// Vérifier que le prof enseigne cette matière (sécurité)
$stmt = $pdo->prepare("SELECT nom FROM matieres WHERE id = ? AND professeur_id = ?");
$stmt->execute([$matiereId, $profId]);
$matiere = $stmt->fetch();
if (!$matiere) {
    exit('Accès refusé');
}

// Traitement du formulaire de mise à jour des notes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['notes'] as $insc_id => $notes) {
        $note_cc     = floatval($notes['cc']);
        $note_examen = floatval($notes['examen']);
        $up = $pdo->prepare("
            INSERT INTO notes (inscription_id, note_cc, note_examen)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE note_cc = VALUES(note_cc), note_examen = VALUES(note_examen)
        ");
        $up->execute([$insc_id, $note_cc, $note_examen]);
    }
    $message = "Les notes ont été mises à jour avec succès.";
}

// Récupérer la liste des étudiants inscrits à la matière
$stmt = $pdo->prepare("
  SELECT i.id AS insc_id, e.nom, e.prenom, n.note_cc, n.note_examen
  FROM inscriptions i
  JOIN etudiants e ON i.etudiant_id = e.id
  LEFT JOIN notes n ON i.id = n.inscription_id
  WHERE i.matiere_id = ?
");
$stmt->execute([$matiereId]);
$liste = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Notes — <?= htmlspecialchars($matiere['nom']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand" href="dashboard.php">Espace Professeur</a>
      <div class="d-flex">
        <a href="../logout.php" class="btn btn-outline-light">Déconnexion</a>
      </div>
    </div>
  </nav>

  <!-- CONTENU PRINCIPAL -->
  <main class="container py-4">
    <h2 class="mb-4">Notes — <?= htmlspecialchars($matiere['nom']) ?></h2>

    <?php if (isset($message)): ?>
      <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="table-responsive mb-3">
        <table class="table table-striped align-middle">
          <thead class="table-light">
            <tr>
              <th scope="col">Étudiant</th>
              <th scope="col">Note CC</th>
              <th scope="col">Note Examen</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($liste as $et): ?>
              <tr>
                <td><?= htmlspecialchars($et['nom'] . ' ' . $et['prenom']) ?></td>
                <td>
                  <input type="number" step="0.01"
                         name="notes[<?= $et['insc_id'] ?>][cc]"
                         value="<?= $et['note_cc'] ?? '' ?>"
                         class="form-control">
                </td>
                <td>
                  <input type="number" step="0.01"
                         name="notes[<?= $et['insc_id'] ?>][examen]"
                         value="<?= $et['note_examen'] ?? '' ?>"
                         class="form-control">
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-between">
        <a href="dashboard.php" class="btn btn-secondary">Retour aux cours</a>
        <button type="submit" class="btn btn-primary">Enregistrer les notes</button>
      </div>
    </form>
  </main>

  <!-- FOOTER -->
  <footer class="bg-white text-center py-3 border-top">
    &copy; <?= date('Y') ?> Université — Gestion des notes
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
