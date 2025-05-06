<?php
// admin/prof_list.php
session_start();
if ($_SESSION['role'] !== 'admin') exit;
require_once '../config.php';
$profs = $pdo->query("SELECT id, nom, email FROM utilisateurs WHERE role = 'prof'")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Liste des professeurs</title>
  <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light text-dark">
  <div class="container mt-5">
    <div class="card shadow">
      <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Liste des professeurs</h4>
      </div>
      <div class="card-body">
        <?php if ($profs): ?>
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Nom</th>
              <th>Email</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($profs as $p): ?>
              <tr>
                <td><?= htmlspecialchars($p['nom']) ?></td>
                <td><?= htmlspecialchars($p['email']) ?></td>
                <td>
                  <a href="edit_prof.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                  <a href="delete_prof.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php else: ?>
          <div class="alert alert-info">Aucun professeur trouvé.</div>
        <?php endif; ?>

        <a href="add_prof.php" class="btn btn-success mt-3">Ajouter un professeur</a>
      </div>
    </div>
  </div>
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

