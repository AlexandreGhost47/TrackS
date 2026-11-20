<?php
require '../includes/auth.php';
require '../config/db.php';

$erreur = '';

// Ajout d'une classe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);

    if ($nom === '') {
        $erreur = 'Le nom de la classe est obligatoire.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO classes (nom) VALUES (?)");
        $stmt->execute([$nom]);
        // On recharge la page pour éviter un double ajout si on actualise
        header('Location: classes.php?ajoute=1');
        exit;
    }
}

// Liste des classes avec le nombre d'étudiants de chacune
$classes = $pdo->query("
    SELECT classes.*, COUNT(etudiants.id) AS nb_etudiants
    FROM classes
    LEFT JOIN etudiants ON etudiants.classe_id = classes.id
    GROUP BY classes.id
    ORDER BY classes.nom
")->fetchAll();

include '../includes/header.php';
?>

<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
  <h2 class="mb-4">Gestion des classes</h2>

  <?php if (isset($_GET['ajoute'])): ?>
    <div class="alert alert-success">Classe ajoutée avec succès !</div>
  <?php endif; ?>
  <?php if (isset($_GET['supprime'])): ?>
    <div class="alert alert-success">Classe supprimée avec succès !</div>
  <?php endif; ?>
  <?php if ($erreur): ?>
    <div class="alert alert-danger"><?= $erreur ?></div>
  <?php endif; ?>

  <!-- Formulaire d'ajout -->
  <form method="POST" class="row g-2 mb-4">
    <div class="col-md-8">
      <input type="text" name="nom" class="form-control" placeholder="Nom de la nouvelle classe (ex : Licence 4)" required>
    </div>
    <div class="col-md-4">
      <button type="submit" class="btn btn-primary">+ Ajouter la classe</button>
    </div>
  </form>

  <!-- Tableau des classes -->
  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-striped table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>Classe</th>
            <th>Nombre d'étudiants</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($classes) === 0): ?>
            <tr>
              <td colspan="3" class="text-center text-muted py-4">Aucune classe enregistrée.</td>
            </tr>
          <?php endif; ?>

          <?php foreach ($classes as $c): ?>
            <tr>
              <td><?= htmlspecialchars($c['nom']) ?></td>
              <td><?= $c['nb_etudiants'] ?></td>
              <td>
                <form method="POST" action="supprimer_classe.php" class="d-inline"
                      onsubmit="return confirm('Supprimer cette classe ? Les étudiants ne seront pas supprimés, ils n\'auront simplement plus de classe.');">
                  <input type="hidden" name="id" value="<?= $c['id'] ?>">
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