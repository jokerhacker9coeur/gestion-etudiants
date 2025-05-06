<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="post">
        <input type="hidden" name="add" value="1">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="addModalLabel"><i class="fas fa-user-plus me-2"></i>Nouvel Étudiant</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3"><label class="form-label">Nom</label><input name="nom" class="form-control" required></div>
          <div class="mb-3"><label class="form-label">Prénom</label><input name="prenom" class="form-control" required></div>
          <div class="mb-3"><label class="form-label">Matricule</label><input name="matricule" class="form-control" required></div>
          <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control"></div>
          <div class="mb-3">
            <label class="form-label">Niveau</label>
            <select class="form-select" name="niveau" required>
              <option value="">-- Sélectionner --</option>
              <option value="L1">L1</option>
              <option value="L2">L2</option>
              <option value="L3">L3</option>
              <option value="M1">M1</option>
              <option value="M2">M2</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary">Ajouter</button>
        </div>
      </form>
    </div>
  </div>
</div>
