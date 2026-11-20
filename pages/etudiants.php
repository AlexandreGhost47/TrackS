<?php
require '../includes/auth.php';
require '../config/db.php';

// Texte tapé dans la barre de recherche (vide si rien)
$recherche = trim($_GET['q'] ?? '');

if ($recherche !== '') {
    // Recherche : on cherche le texte dans le nom, le prénom ou le matricule
    $stmt = $pdo->prepare("
        SELECT etudiants.*, classes.nom AS classe_nom
        FROM etudiants
        LEFT JOIN classes ON etudiants.classe_id = classes.id
        WHERE etudiants.nom LIKE ? OR etudiants.prenom LIKE ? OR etudiants.matricule LIKE ?
        ORDER BY etudiants.nom, etudiants.prenom
    ");
    $motif = '%' . $recherche . '%';
    $stmt->execute([$motif, $motif, $motif]);
    $etudiants = $stmt->fetchAll();
} else {
    // Pas de recherche : on affiche tout le monde
    $etudiants = $pdo->query("
        SELECT etudiants.*, classes.nom AS classe_nom
        FROM etudiants
        LEFT JOIN classes ON etudiants.classe_id = classes.id
        ORDER BY etudiants.nom, etudiants.prenom
    ")->fetchAll();
}

include '../includes/header.php';
?>

<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
  <?php if (isset($_GET['modifie'])): ?>
<div class="alert alert-success">Étudiant modifié avec succès !</div>
  <?php endif; ?>

    <?php if (isset($_GET['supprime'])): ?>
    <div class="alert alert-success">Étudiant supprimé avec succès !</div>
  <?php endif; ?>

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">

    <h2 class="mb-0">Liste des étudiants</h2>
    <a href="ajouter_etudiant.php" class="btn btn-primary">+ Ajouter un étudiant</a>
  </div>

    <form method="GET" class="row g-2 mb-4">
    <div class="col-md-8">
      <input type="text" name="q" class="form-control"
             placeholder="Rechercher par nom, prénom ou matricule..."
             value="<?= htmlspecialchars($recherche) ?>">
    </div>
    <div class="col-md-4">
      <button type="submit" class="btn btn-primary">Rechercher</button>
      <?php if ($recherche !== ''): ?>
        <a href="etudiants.php" class="btn btn-secondary">Réinitialiser</a>
      <?php endif; ?>
    </div>
  </form>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-striped table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>Matricule</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Classe</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($etudiants) === 0): ?>
            <tr>
              <td colspan="7" class="text-center text-muted py-4">Aucun étudiant enregistré.</td>
            </tr>
          <?php endif; ?>

          <?php foreach ($etudiants as $e): ?>
            <tr>
              <td><?= htmlspecialchars($e['matricule']) ?></td>
              <td><?= htmlspecialchars($e['nom']) ?></td>
              <td><?= htmlspecialchars($e['prenom']) ?></td>
              <td><?= htmlspecialchars($e['classe_nom'] ?? '-') ?></td>
              <td><?= htmlspecialchars($e['email'] ?? '') ?></td>
              <td><?= htmlspecialchars($e['telephone'] ?? '') ?></td>
                            <td>
                <a href="modifier_etudiant.php?id=<?= $e['id'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                <form method="POST" action="supprimer_etudiant.php" class="d-inline"
                      onsubmit="return confirm('Voulez-vous vraiment supprimer cet étudiant ?');">
                  <input type="hidden" name="id" value="<?= $e['id'] ?>">
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