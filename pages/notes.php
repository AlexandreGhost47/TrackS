<?php
require '../includes/auth.php';
require '../config/db.php';

// Moyenne pondérée (avec les coefficients) de chaque étudiant
$moyennes = $pdo->query("
    SELECT etudiants.matricule, etudiants.nom, etudiants.prenom,
           classes.nom AS classe_nom,
           COUNT(notes.id) AS nb_notes,
           ROUND(SUM(notes.valeur * matieres.coefficient) / SUM(matieres.coefficient), 2) AS moyenne
    FROM etudiants
    INNER JOIN notes ON notes.etudiant_id = etudiants.id
    INNER JOIN matieres ON notes.matiere_id = matieres.id
    LEFT JOIN classes ON etudiants.classe_id = classes.id
    GROUP BY etudiants.id, classes.nom
    ORDER BY moyenne DESC
")->fetchAll();

// Liste de toutes les notes, les plus récentes en premier
$notes = $pdo->query("
    SELECT notes.*, etudiants.matricule, etudiants.nom, etudiants.prenom,
           matieres.nom AS matiere_nom, matieres.coefficient
    FROM notes
    INNER JOIN etudiants ON notes.etudiant_id = etudiants.id
    INNER JOIN matieres ON notes.matiere_id = matieres.id
    ORDER BY notes.date_note DESC, notes.id DESC
")->fetchAll();

include '../includes/header.php';
?>

<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">

  <?php if (isset($_GET['supprime'])): ?>
    <div class="alert alert-success">Note supprimée avec succès !</div>
  <?php endif; ?>

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">

    <h2 class="mb-0">Notes et moyennes</h2>
    <a href="ajouter_note.php" class="btn btn-success">+ Saisir une note</a>
  </div>

  <!-- Moyennes par étudiant -->
  <h4 class="mb-3">Moyennes des étudiants</h4>
  <div class="card shadow-sm mb-5">
    <div class="table-responsive">
      <table class="table table-striped table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>Matricule</th>
            <th>Étudiant</th>
            <th>Classe</th>
            <th>Nombre de notes</th>
            <th>Moyenne /20</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($moyennes) === 0): ?>
            <tr>
              <td colspan="5" class="text-center text-muted py-4">Aucune note enregistrée.</td>
            </tr>
          <?php endif; ?>

          <?php foreach ($moyennes as $m): ?>
            <tr>
              <td><?= htmlspecialchars($m['matricule']) ?></td>
              <td><?= htmlspecialchars($m['nom'] . ' ' . $m['prenom']) ?></td>
              <td><?= htmlspecialchars($m['classe_nom'] ?? '-') ?></td>
              <td><?= $m['nb_notes'] ?></td>
              <td>
                <?php if ($m['moyenne'] >= 10): ?>
                  <span class="badge bg-success"><?= $m['moyenne'] ?></span>
                <?php else: ?>
                  <span class="badge bg-danger"><?= $m['moyenne'] ?></span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Toutes les notes -->
  <h4 class="mb-3">Toutes les notes</h4>
  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-striped table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>Date</th>
            <th>Étudiant</th>
            <th>Matière</th>
            <th>Type</th>
            <th>Note /20</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($notes) === 0): ?>
            <tr>
              <td colspan="5" class="text-center text-muted py-4">Aucune note enregistrée.</td>
            </tr>
          <?php endif; ?>

          <?php foreach ($notes as $n): ?>
            <tr>
              <td><?= date('d/m/Y', strtotime($n['date_note'])) ?></td>
              <td><?= htmlspecialchars($n['nom'] . ' ' . $n['prenom']) ?></td>
              <td><?= htmlspecialchars($n['matiere_nom']) ?> (coef. <?= $n['coefficient'] ?>)</td>
              <td><?= htmlspecialchars($n['type_evaluation'] ?? '') ?></td>
              <td><strong><?= $n['valeur'] ?></strong></td>

             <td>
                <form method="POST" action="supprimer_note.php" class="d-inline"
                      onsubmit="return confirm('Voulez-vous vraiment supprimer cette note ?');">
                  <input type="hidden" name="id" value="<?= $n['id'] ?>">
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