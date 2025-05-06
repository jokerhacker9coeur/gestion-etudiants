<!-- Modal de modification pour l'étudiant ID <?= $row['id'] ?> -->
<div class="modal fade" id="editModal<?= $row['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $row['id']; ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="post">
        <input type="hidden" name="update" value="1">
        <input type="hidden" name="id" value="<?= $row['id']; ?>">
        <div class="modal-header bg-warning text-dark">
          <h5 class="modal-title" id="editModalLabel<?= $row['id']; ?>"><i class="fas fa-edit me-2"></i>Modifier Étudiant</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3"><label class="form-label">Nom</label>
            <input name="nom" class="form-control" value="<?= htmlspecialchars($row['nom']); ?>" required>
          </div>
          <div class="mb-3"><label class="form-label">Prénom</label>
            <input name="prenom" class="form-control" value="<?= htmlspecialchars($row['prenom']); ?>" required>
          </div>
          <div class="mb-3"><label class="form-label">Matricule</label>
            <input name="matricule" class="form-control" value="<?= htmlspecialchars($row['matricule']); ?>" required>
          </div>
          <div class="mb-3"><label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($row['email']); ?>">
          </div>
          <div class="mb-3"><label class="form-label">Niveau</label>
            <select class="form-select" name="niveau" required>
              <option value="">-- Sélectionner --</option>
              <option value="L1" <?= $row['niveau'] == 'L1' ? 'selected' : '' ?>>L1</option>
              <option value="L2" <?= $row['niveau'] == 'L2' ? 'selected' : '' ?>>L2</option>
              <option value="L3" <?= $row['niveau'] == 'L3' ? 'selected' : '' ?>>L3</option>
              <option value="M1" <?= $row['niveau'] == 'M1' ? 'selected' : '' ?>>M1</option>
              <option value="M2" <?= $row['niveau'] == 'M2' ? 'selected' : '' ?>>M2</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-warning">Modifier</button>
        </div>
      </form>
    </div>
  </div>
</div>
