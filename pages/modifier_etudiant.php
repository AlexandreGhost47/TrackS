<?php
require '../includes/auth.php';
require '../config/db.php';

// Récupère l'id dans l'adresse
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: etudiants.php');
    exit;
}

// Cherche l'étudiant dans la base
$stmt = $pdo->prepare("SELECT * FROM etudiants WHERE id = ?");
$stmt->execute([$id]);
$etudiant = $stmt->fetch();

// Si l'étudiant n'existe pas, retour à la liste
if (!$etudiant) {
    header('Location: etudiants.php');
    exit;
}

$erreur = '';
$classes = $pdo->query("SELECT * FROM classes ORDER BY nom")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $etudiant['matricule'] = trim($_POST['matricule']);
    $etudiant['nom'] = trim($_POST['nom']);
    $etudiant['prenom'] = trim($_POST['prenom']);
    $etudiant['email'] = trim($_POST['email']);
    $etudiant['telephone'] = trim($_POST['telephone']);
    $etudiant['classe_id'] = $_POST['classe_id'] !== '' ? $_POST['classe_id'] : null;

    try {
        $stmt = $pdo->prepare("UPDATE etudiants SET matricule = ?, nom = ?, prenom = ?, email = ?, telephone = ?, classe_id = ? WHERE id = ?");
        $stmt->execute([
            $etudiant['matricule'],
            $etudiant['nom'],
            $etudiant['prenom'],
            $etudiant['email'],
            $etudiant['telephone'],
            $etudiant['classe_id'],
            $id
        ]);
        header('Location: etudiants.php?modifie=1');
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $erreur = 'Ce matricule existe déjà.';
        } else {
            $erreur = 'Une erreur est survenue.';
        }
    }
}

include '../includes/header.php';
?>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
  <h2 class="mb-4">Modifier un étudiant</h2>

  <?php if ($erreur): ?>
    <div class="alert alert-danger"><?= $erreur ?></div>
  <?php endif; ?>

  <form method="POST" class="card card-body shadow-sm">
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Matricule *</label>
        <input type="text" name="matricule" class="form-control" value="<?= htmlspecialchars($etudiant['matricule']) ?>" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Classe</label>
        <select name="classe_id" class="form-select">
          <option value="">-- Aucune --</option>
          <?php foreach ($classes as $classe): ?>
            <option value="<?= $classe['id'] ?>" <?= $classe['id'] == $etudiant['classe_id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($classe['nom']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Nom *</label>
        <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($etudiant['nom']) ?>" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Prénom *</label>
        <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($etudiant['prenom']) ?>" required>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($etudiant['email'] ?? '') ?>">
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Téléphone</label>
        <input type="text" name="telephone" class="form-control" value="<?= htmlspecialchars($etudiant['telephone'] ?? '') ?>">
      </div>
    </div>
    <div>
      <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
      <a href="etudiants.php" class="btn btn-secondary">Annuler</a>
    </div>
  </form>
</div>

<?php include '../includes/footer.php'; ?>