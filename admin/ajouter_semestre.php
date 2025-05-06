<?php
// ajouter_semestre.php
session_start();
require_once '../config.php';
if ($_SESSION['role'] !== 'admin') exit;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $annee = $_POST['annee_etude'];
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
<html>
<head>
  <meta charset="UTF-8">
  <title>Ajouter semestre</title>
  <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light text-dark">
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
    <div class="card-header bg-secondary text-white">
      <h4>Ajouter un semestre</h4>
    </div>
    <div class="card-body">
      <?php if (isset($ok)) echo "<div class='alert alert-success'>$ok</div>"; ?>
      <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
      <form method="post">
        <div class="mb-3">
          <label for="annee_etude" class="form-label">Année d'étude</label>
          <select name="annee_etude" id="annee_etude" class="form-select" required onchange="updateSemestres()">
            <option value="">-- Sélectionner --</option>
            <option value="1">Licence 1</option>
            <option value="2">Licence 2</option>
            <option value="3">Licence 3</option>
            <option value="4">Master 1</option>
            <option value="5">Master 2</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="numero" class="form-label">Numéro de semestre</label>
          <select name="numero" id="numero" class="form-select" required>
            <option value="">-- Choisir une année d'abord --</option>
          </select>
        </div>
        <button class="btn btn-primary">Ajouter</button>
      </form>
    </div>
  </div>
</div>

<script>
function updateSemestres() {
  const annee = document.getElementById('annee_etude').value;
  const numero = document.getElementById('numero');
  numero.innerHTML = '';

  const semestres = {
    1: [1, 2],
    2: [3, 4],
    3: [5, 6],
    4: [7, 8],
    5: [9, 10]
  };

  if (semestres[annee]) {
    numero.innerHTML = semestres[annee]
      .map(n => `<option value="${n}">Semestre ${n}</option>`)
      .join('');
  } else {
    numero.innerHTML = '<option value="">-- Choisir une année d\'abord --</option>';
  }
}
</script>

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- JS pour le thème -->
<script>
function applyTheme(theme) {
    document.body.classList.toggle('bg-dark', theme === 'dark');
    document.body.classList.toggle('text-white', theme === 'dark');
    document.body.classList.toggle('bg-light', theme !== 'dark');
    document.body.classList.toggle('text-dark', theme !== 'dark');

    document.querySelectorAll('.offcanvas, .offcanvas-body, .position-fixed').forEach(el => {
        el.classList.toggle('bg-dark', theme === 'dark');
        el.classList.toggle('text-white', theme === 'dark');
        el.classList.toggle('bg-light', theme !== 'dark');
        el.classList.toggle('text-dark', theme !== 'dark');
    });

    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.toggle('text-white', theme === 'dark');
        link.classList.toggle('text-dark', theme !== 'dark');
    });

    document.querySelectorAll('.card').forEach(card => {
        card.classList.toggle('bg-dark', theme === 'dark');
        card.classList.toggle('text-white', theme === 'dark');
        card.classList.toggle('bg-white', theme !== 'dark');
        card.classList.toggle('text-dark', theme !== 'dark');
    });

    const icon = document.getElementById('themeIcon');
    if (theme === 'dark') {
        icon.classList.remove('fa-moon');
        icon.classList.add('fa-sun');
    } else {
        icon.classList.remove('fa-sun');
        icon.classList.add('fa-moon');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme') || 'light';
    applyTheme(savedTheme);

    const toastEl = document.getElementById('themeToast');
    const toastMsg = document.getElementById('themeToastMessage');
    const toast = new bootstrap.Toast(toastEl);

    document.getElementById('themeToggle').addEventListener('click', () => {
        const isDark = document.body.classList.contains('bg-dark');
        const newTheme = isDark ? 'light' : 'dark';
        localStorage.setItem('theme', newTheme);
        applyTheme(newTheme);

        toastMsg.innerHTML = newTheme === 'dark'
          ? `<i class="fas fa-moon me-2"></i> Thème sombre activé`
          : `<i class="fas fa-sun me-2"></i> Thème clair activé`;

        toastEl.className = `toast align-items-center border-0 ${newTheme === 'dark' ? 'bg-secondary text-white' : 'bg-warning text-dark'}`;
        toast.show();
    });
});
</script>

<!-- Toast -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
  <div id="themeToast" class="toast align-items-center border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body d-flex align-items-center" id="themeToastMessage">
        <i class="fas fa-sun me-2"></i> Thème activé
      </div>
      <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
    </div>
  </div>
</div>

<footer class="bg-dark text-white py-4 mt-5">
  <div class="container text-center">
    <p>&copy; <?= date('Y') ?> Université | Tous droits réservés.</p>
  </div>
</footer>
</body>
</html>
