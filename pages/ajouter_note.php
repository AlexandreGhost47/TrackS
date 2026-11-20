<?php
require '../includes/auth.php';
require '../config/db.php';

$message = '';
$erreur = '';

// Listes pour les menus déroulants
$etudiants = $pdo->query("
    SELECT id, matricule, nom, prenom
    FROM etudiants
    ORDER BY nom, prenom
")->fetchAll();

$matieres = $pdo->query("SELECT * FROM matieres ORDER BY nom")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $etudiant_id = $_POST['etudiant_id'];
    $matiere_id = $_POST['matiere_id'];
    // On accepte la virgule (14,5) en la remplaçant par un point (14.5)
    $valeur = str_replace(',', '.', trim($_POST['valeur']));
    $type_evaluation = trim($_POST['type_evaluation']);
    $date_note = $_POST['date_note'];

    if ($etudiant_id === '' || $matiere_id === '' || $date_note === '') {
        $erreur = 'Veuillez remplir tous les champs obligatoires.';
    } elseif (!is_numeric($valeur) || $valeur < 0 || $valeur > 20) {
        $erreur = 'La note doit être un nombre entre 0 et 20.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO notes (etudiant_id, matiere_id, valeur, type_evaluation, date_note) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$etudiant_id, $matiere_id, $valeur, $type_evaluation, $date_note]);
        $message = 'Note enregistrée avec succès !';
    }
}

include '../includes/header.php';
?>

<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
  <h2 class="mb-4">Saisir une note</h2>

  <?php if ($message): ?>
    <div class="alert alert-success"><?= $message ?></div>
  <?php endif; ?>
  <?php if ($erreur): ?>
    <div class="alert alert-danger"><?= $erreur ?></div>
  <?php endif; ?>

  <form method="POST" class="card card-body shadow-sm">
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Étudiant *</label>
        <select name="etudiant_id" class="form-select" required>
          <option value="">-- Choisir un étudiant --</option>
          <?php foreach ($etudiants as $e): ?>
            <option value="<?= $e['id'] ?>">
              <?= htmlspecialchars($e['nom'] . ' ' . $e['prenom'] . ' (' . $e['matricule'] . ')') ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Matière *</label>
        <select name="matiere_id" class="form-select" required>
          <option value="">-- Choisir une matière --</option>
          <?php foreach ($matieres as $m): ?>
            <option value="<?= $m['id'] ?>">
              <?= htmlspecialchars($m['nom']) ?> (coef. <?= $m['coefficient'] ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label">Note sur 20 *</label>
        <input type="text" name="valeur" class="form-control" placeholder="Ex : 14,5" required>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Type d'évaluation</label>
        <select name="type_evaluation" class="form-select">
          <option value="Devoir">Devoir</option>
          <option value="Examen">Examen</option>
          <option value="TP">TP</option>
          <option value="Interrogation">Interrogation</option>
        </select>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Date *</label>
        <input type="date" name="date_note" class="form-control" value="<?= date('Y-m-d') ?>" required>
      </div>
    </div>
    <div>
      <button type="submit" class="btn btn-primary">Enregistrer</button>
      <a href="dashboard.php" class="btn btn-secondary">Annuler</a>
    </div>
  </form>
</div>

<?php include '../includes/footer.php'; ?>