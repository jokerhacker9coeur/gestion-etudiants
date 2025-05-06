<?php
// admin/add_prof.php (extrait)
require_once '../config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $mdp = $_POST['password'];
    $hash = password_hash($mdp, PASSWORD_DEFAULT); // Hachage sécurisé
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, 'prof')");
    $stmt->execute([$nom, $email, $hash]);
    header('Location: prof_list.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter un professeur</title>
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
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
            <li class="nav-item"><a class="nav-link" href="dashboard.php">🏠 Tableau de bord</a></li>
            <li class="nav-item"><a class="nav-link" href="add_prof.php">👨‍🏫 Ajouter un professeur</a></li>
            <li class="nav-item"><a class="nav-link" href="ajouter_matiere.php">📘 Ajouter une matière</a></li>
            <li class="nav-item"><a class="nav-link" href="ajouter_semestre.php">📅 Ajouter un semestre</a></li>
            <li class="nav-item"><a class="nav-link" href="etudiants.php">🎓 Ajouter un étudiant</a></li>
            <li class="nav-item"><a class="nav-link text-danger" href="../logout.php">🚪 Déconnexion</a></li>
        </ul>
    </div>
</div>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Ajouter un professeur</h5>
          </div>
          <div class="card-body">
            <form method="post">
              <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" name="nom" id="nom" class="form-control" required placeholder="Nom du professeur">
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Adresse Email</label>
                <input type="email" name="email" id="email" class="form-control" required placeholder="Email">
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Mot de passe initial</label>
                <input type="password" name="password" id="password" class="form-control" required placeholder="Mot de passe">
              </div>
              <button type="submit" class="btn btn-success w-100">Créer le professeur</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- TOAST -->
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

<!-- JS Bootstrap et thème -->
<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
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

    document.querySelectorAll('h1, h4').forEach(title => {
        title.classList.toggle('text-light', theme === 'dark');
        title.classList.toggle('text-dark', theme !== 'dark');
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

        if (newTheme === 'dark') {
            toastMsg.innerHTML = `<i class="fas fa-moon me-2"></i> Thème sombre activé`;
            toastEl.classList.remove('bg-warning', 'text-dark');
            toastEl.classList.add('bg-secondary', 'text-white');
        } else {
            toastMsg.innerHTML = `<i class="fas fa-sun me-2"></i> Thème clair activé`;
            toastEl.classList.remove('bg-secondary', 'text-white');
            toastEl.classList.add('bg-warning', 'text-dark');
        }

        toast.show();
    });
});
</script>
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
