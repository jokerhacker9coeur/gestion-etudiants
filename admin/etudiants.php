<?php
require '../config.php';

// Traitement des actions (ajout, mise à jour, suppression)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $stmt = $pdo->prepare("INSERT INTO etudiants (nom, prenom, matricule, email, niveau) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['nom'], $_POST['prenom'], $_POST['matricule'], $_POST['email'], $_POST['niveau']
        ]);
        header("Location: etudiants.php?msg=added");
        exit;
    }

    if (isset($_POST['update'])) {
        $stmt = $pdo->prepare("UPDATE etudiants SET nom=?, prenom=?, matricule=?, email=?, niveau=? WHERE id=?");
        $stmt->execute([
            $_POST['nom'], $_POST['prenom'], $_POST['matricule'], $_POST['email'], $_POST['niveau'], $_POST['id']
        ]);
        header("Location: etudiants.php?msg=updated");
        exit;
    }
}

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM etudiants WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    header("Location: etudiants.php?msg=deleted");
    exit;
}

// Récupération
$search = $_GET['search'] ?? '';
$query = "SELECT * FROM etudiants WHERE nom LIKE :search OR prenom LIKE :search ORDER BY id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute(['search' => "%$search%"]);
$etudiants = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion Étudiants</title>
  <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../font/css/all.min.css">
  <script src="https://kit.fontawesome.com/a2f3a2f2de.js" crossorigin="anonymous"></script>
</head>
<body class="bg-light">

<!-- navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
  <div class="container-fluid">
    <!-- 👇 Bouton pour afficher la sidebar -->
    <button class="btn btn-outline-light me-2" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
      <i class="fas fa-bars"></i>
    </button>

    <a class="navbar-brand" href="#"><i class="fas fa-graduation-cap me-2 mx-5"></i>Gestion Étudiants</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggle">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarToggle">
      <form class="d-flex ms-auto" method="get">
        <input class="form-control me-2" type="search" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-outline-light" type="submit">OK</button>
      </form>
      <button class="btn btn-outline-light mx-2" id="themeToggle" title="Changer de thème">
        <i class="fas fa-moon" id="themeIcon"></i>
      </button>
    </div>
  </div>
</nav>

<!-- SIDEBAR -->
<div class="offcanvas offcanvas-start bg-light text-dark" tabindex="-1" id="sidebar">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Menu Admin</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <ul class="nav flex-column">
      <li class="nav-item"><a class="nav-link" href="dashboard.php">🏠 Tableau de bord</a></li>
      <li class="nav-item"><a class="nav-link" href="add_prof.php">👨‍🏫 Ajouter un professeur</a></li>
      <li class="nav-item"><a class="nav-link" href="ajouter_matiere.php">📘 Ajouter une matière</a></li>
      <li class="nav-item"><a class="nav-link" href="ajouter_semestre.php">📅 Ajouter un semestre</a></li>
      <li class="nav-item"><a class="nav-link" href="etudiants.php">🎓 Ajouter un étudiant</a></li>
      <li class="nav-item"><a class="nav-link text-danger" href="../logout.php">🚪 Déconnexion</a></li>
    </ul>
  </div>
</div>

<!-- CONTENU -->
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
      <i class="fas fa-user-plus me-1"></i> Ajouter Étudiant
    </button>
    <div>
      <a href="export_pdf.php" class="btn btn-danger"><i class="fas fa-file-pdf"></i> PDF</a>
      <a href="export_excel.php" class="btn btn-success"><i class="fas fa-file-excel"></i> Excel</a>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Nom</th>
          <th>Prénom</th>
          <th>Matricule</th>
          <th>Email</th>
          <th>Niveau</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($etudiants as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['id']) ?></td>
          <td><?= htmlspecialchars($row['nom']) ?></td>
          <td><?= htmlspecialchars($row['prenom']) ?></td>
          <td><?= htmlspecialchars($row['matricule']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td><?= htmlspecialchars($row['niveau']) ?></td>
          <td>
            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">
              <i class="fas fa-edit"></i>
            </button>
            <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Confirmer la suppression ?')">
              <i class="fas fa-trash"></i>
            </a>
          </td>
        </tr>
        <?php include 'modals.php'; ?>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include 'add_modal.php'; ?>

<!-- TOAST -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
  <div id="toastSuccess" class="toast align-items-center text-bg-success border-0" role="alert" data-bs-delay="3000">
    <div class="d-flex">
      <div class="toast-body" id="toastMsg">Action réussie !</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
function showToastFromURL() {
  const urlParams = new URLSearchParams(window.location.search);
  const msg = urlParams.get('msg');
  const toastMsg = {
    added: "Ajouté avec succès",
    updated: "Modifié avec succès",
    deleted: "Supprimé avec succès"
  }[msg];

  if (toastMsg) {
    document.getElementById("toastMsg").textContent = toastMsg;
    new bootstrap.Toast(document.getElementById("toastSuccess")).show();
    window.history.replaceState({}, document.title, window.location.pathname);
  }
}

function applyTheme(theme) {
  document.body.classList.toggle('bg-dark', theme === 'dark');
  document.body.classList.toggle('text-white', theme === 'dark');
  document.body.classList.toggle('bg-light', theme !== 'dark');
  document.body.classList.toggle('text-dark', theme !== 'dark');

  const icon = document.getElementById('themeIcon');
  icon.classList.toggle('fa-moon', theme !== 'dark');
  icon.classList.toggle('fa-sun', theme === 'dark');
}

document.addEventListener("DOMContentLoaded", () => {
  showToastFromURL();
  const savedTheme = localStorage.getItem('theme') || 'light';
  applyTheme(savedTheme);

  document.getElementById('themeToggle').addEventListener('click', () => {
    const isDark = document.body.classList.contains('bg-dark');
    const newTheme = isDark ? 'light' : 'dark';
    localStorage.setItem('theme', newTheme);
    applyTheme(newTheme);
  });
});
</script>
</body>
</html>