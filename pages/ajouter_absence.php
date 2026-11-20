<?php
require '../includes/auth.php';
require '../config/db.php';

$message = '';
$erreur = '';

// Liste des étudiants pour la liste déroulante
$etudiants = $pdo->query("
    SELECT id, matricule, nom, prenom
    FROM etudiants
    ORDER BY nom, prenom
")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $etudiant_id = $_POST['etudiant_id'];
    $date_absence = $_POST['date_absence'];
    $motif = trim($_POST['motif']);
    // La case cochée envoie une valeur, sinon rien n'est envoyé
    $justifiee = isset($_POST['justifiee']) ? 1 : 0;

    if ($etudiant_id === '' || $date_absence === '') {
        $erreur = 'Veuillez choisir un étudiant et une date.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO absences (etudiant_id, date_absence, motif, justifiee) VALUES (?, ?, ?, ?)");
        $stmt->execute([$etudiant_id, $date_absence, $motif, $justifiee]);
        $message = 'Absence enregistrée avec succès !';
    }
}

include '../includes/header.php';
?>

<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
  <h2 class="mb-4">Enregistrer une absence</h2>

  <?php if ($message): ?>
    <div class="alert alert-success"><?= $message ?></div>
  <?php endif; ?>
  <?php if ($erreur): ?>
    <div class="alert alert-danger"><?= $erreur ?></div>
  <?php endif; ?>

  <form method="POST" class="card card-body shadow-sm">
    <div class="row">
      <div class="col-md-8 mb-3">
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
      <div class="col-md-4 mb-3">
        <label class="form-label">Date de l'absence *</label>
        <input type="date" name="date_absence" class="form-control" value="<?= date('Y-m-d') ?>" required>
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label">Motif</label>
      <input type="text" name="motif" class="form-control" placeholder="Ex : maladie, raison familiale...">
    </div>
    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" name="justifiee" id="justifiee">
      <label class="form-check-label" for="justifiee">Absence justifiée</label>
    </div>
    <div>
      <button type="submit" class="btn btn-primary">Enregistrer</button>
      <a href="dashboard.php" class="btn btn-secondary">Annuler</a>
    </div>
  </form>
</div>

<?php include '../includes/footer.php'; ?>