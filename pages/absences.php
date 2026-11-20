<?php
require '../includes/auth.php';
require '../config/db.php';

// Récupère les absences avec les infos de l'étudiant concerné
$absences = $pdo->query("
    SELECT absences.*, etudiants.matricule, etudiants.nom, etudiants.prenom
    FROM absences
    INNER JOIN etudiants ON absences.etudiant_id = etudiants.id
    ORDER BY absences.date_absence DESC, absences.id DESC
")->fetchAll();

include '../includes/header.php';
?>

<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">

  <?php if (isset($_GET['supprime'])): ?>
    <div class="alert alert-success">Absence supprimée avec succès !</div>
  <?php endif; ?>

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">

    <h2 class="mb-0">Liste des absences</h2>
    <a href="ajouter_absence.php" class="btn btn-danger">+ Enregistrer une absence</a>
  </div>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-striped table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>Date</th>
            <th>Matricule</th>
            <th>Étudiant</th>
            <th>Motif</th>
            <th>Statut</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($absences) === 0): ?>
            <tr>
              <td colspan="5" class="text-center text-muted py-4">Aucune absence enregistrée.</td>
            </tr>
          <?php endif; ?>

          <?php foreach ($absences as $a): ?>
            <tr>
              <td><?= date('d/m/Y', strtotime($a['date_absence'])) ?></td>
              <td><?= htmlspecialchars($a['matricule']) ?></td>
              <td><?= htmlspecialchars($a['nom'] . ' ' . $a['prenom']) ?></td>
              <td><?= htmlspecialchars($a['motif'] ?? '') ?></td>
              <td>
                <?php if ($a['justifiee']): ?>
                  <span class="badge bg-success">Justifiée</span>
                <?php else: ?>
                  <span class="badge bg-danger">Non justifiée</span>
                <?php endif; ?>
              </td>

            <td>
                <form method="POST" action="supprimer_absence.php" class="d-inline"
                      onsubmit="return confirm('Voulez-vous vraiment supprimer cette absence ?');">
                  <input type="hidden" name="id" value="<?= $a['id'] ?>">
                  <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                </form>
              </td>  

            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>