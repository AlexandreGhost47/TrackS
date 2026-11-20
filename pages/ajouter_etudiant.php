<?php
require '../includes/auth.php';
require '../config/db.php';

$message = '';
$erreur = '';

$classes = $pdo->query("SELECT * FROM classes ORDER BY nom")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matricule = trim($_POST['matricule']);
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $classe_id = $_POST['classe_id'] !== '' ? $_POST['classe_id'] : null;

    try {
        $stmt = $pdo->prepare("INSERT INTO etudiants (matricule, nom, prenom, email, telephone, classe_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$matricule, $nom, $prenom, $email, $telephone, $classe_id]);
        $message = 'Étudiant ajouté avec succès !';
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
  <h2 class="mb-4">Ajouter un étudiant</h2>

  <?php if ($message): ?>
    <div class="alert alert-success"><?= $message ?></div>
  <?php endif; ?>
  <?php if ($erreur): ?>
    <div class="alert alert-danger"><?= $erreur ?></div>
  <?php endif; ?>

  <form method="POST" class="card card-body shadow-sm">
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Matricule *</label>
        <input type="text" name="matricule" class="form-control" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Classe</label>
        <select name="classe_id" class="form-select">
          <option value="">-- Aucune --</option>
          <?php foreach ($classes as $classe): ?>
            <option value="<?= $classe['id'] ?>"><?= htmlspecialchars($classe['nom']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Nom *</label>
        <input type="text" name="nom" class="form-control" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Prénom *</label>
        <input type="text" name="prenom" class="form-control" required>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control">
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Téléphone</label>
        <input type="text" name="telephone" class="form-control">
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
  </form>
</div>

<?php include '../includes/footer.php'; ?>