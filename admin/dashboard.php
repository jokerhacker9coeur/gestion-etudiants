<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}
require_once '../config.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Tableau de bord</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font/css/all.min.css">
    <style>
        body {
            transition: background-color 0.3s, color 0.3s;
        }
    </style>
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

<!-- CONTENU PRINCIPAL -->
<div class="container mt-5">
    <h1 class="mb-4 text-dark">Bienvenue, Admin 👋</h1>

    <!-- Professeurs -->
    <div class="card mb-4 bg-white text-dark">
        <div class="card-header bg-primary text-white">Liste des professeurs</div>
        <div class="card-body">
            <?php
            $stmt = $pdo->query("SELECT id, nom, email FROM utilisateurs WHERE role = 'prof'");
            $profs = $stmt->fetchAll();
            if ($profs):
            ?>
            <table class="table table-striped">
                <thead>
                    <tr><th>Nom</th><th>Email</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($profs as $prof): ?>
                        <tr>
                            <td><?= htmlspecialchars($prof['nom']) ?></td>
                            <td><?= htmlspecialchars($prof['email']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p>Aucun professeur trouvé.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Semestres -->
    <div class="card mb-4 bg-white text-dark">
        <div class="card-header bg-secondary text-white">Semestres</div>
        <div class="card-body">
            <?php
            $semestres = $pdo->query("SELECT * FROM semestres ORDER BY annee_etude, numero")->fetchAll();
            if ($semestres):
            ?>
            <ul class="list-group">
                <?php foreach ($semestres as $sem): ?>
                    <li class="list-group-item">Licence <?= $sem['annee_etude'] ?> - Semestre <?= $sem['numero'] ?></li>
                <?php endforeach; ?>
            </ul>
            <?php else: ?>
                <p>Aucun semestre défini.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Matières -->
    <div class="card mb-4 bg-white text-dark">
        <div class="card-header bg-dark text-white">Toutes les matières</div>
        <div class="card-body">
            <?php
            $query = "SELECT m.nom AS matiere, u.nom AS prof, s.annee_etude, s.numero
                      FROM matieres m
                      JOIN utilisateurs u ON m.professeur_id = u.id
                      JOIN semestres s ON m.semestre_id = s.id
                      ORDER BY s.annee_etude, s.numero";
            $matieres = $pdo->query($query)->fetchAll();
            if ($matieres):
            ?>
            <table class="table">
                <thead>
                    <tr><th>Matière</th><th>Professeur</th><th>Semestre</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($matieres as $m): ?>
                        <tr>
                            <td><?= htmlspecialchars($m['matiere']) ?></td>
                            <td><?= htmlspecialchars($m['prof']) ?></td>
                            <td>L<?= $m['annee_etude'] ?> - S<?= $m['numero'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p>Aucune matière enregistrée.</p>
            <?php endif; ?>
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
