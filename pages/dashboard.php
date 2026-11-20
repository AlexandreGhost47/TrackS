<?php
require '../includes/auth.php';
require '../config/db.php';

$nb_etudiants = $pdo->query("SELECT COUNT(*) FROM etudiants")->fetchColumn();
$nb_classes = $pdo->query("SELECT COUNT(*) FROM classes")->fetchColumn();
$nb_absences = $pdo->query("SELECT COUNT(*) FROM absences")->fetchColumn();
$nb_notes = $pdo->query("SELECT COUNT(*) FROM notes")->fetchColumn();

include '../includes/header.php';
?>

<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
  <h2 class="mb-1">Tableau de bord</h2>
  <p class="text-muted mb-4">Bienvenue, <?= htmlspecialchars($_SESSION['user_nom']) ?>. Voici un aperçu de votre établissement.</p>

  <!-- Boutons d'actions rapides -->
  <div class="d-flex flex-wrap gap-2 mb-4">
    <a href="ajouter_etudiant.php" class="btn btn-primary">+ Ajouter un étudiant</a>
    <a href="etudiants.php" class="btn btn-outline-primary">Voir les étudiants</a>
    <a href="ajouter_absence.php" class="btn btn-outline-danger">Enregistrer une absence</a>
    <a href="ajouter_note.php" class="btn btn-outline-success">Saisir une note</a>
  </div>

  <div class="row">

    <!-- Carte : étudiants -->
    <div class="col-6 col-md-3 mb-3">
      <div class="card carte-stat shadow-sm">
        <div class="card-body text-center">
          <h1 class="text-primary"><?= $nb_etudiants ?></h1>
          <p class="mb-0 text-muted">Étudiants</p>
        </div>
      </div>
    </div>

    <!-- Carte : classes -->
    <div class="col-6 col-md-3 mb-3">
      <div class="card carte-stat vert shadow-sm">
        <div class="card-body text-center">
          <h1 class="text-success"><?= $nb_classes ?></h1>
          <p class="mb-0 text-muted">Classes</p>
        </div>
      </div>
    </div>

    <!-- Carte : absences -->
    <div class="col-6 col-md-3 mb-3">
      <div class="card carte-stat rouge shadow-sm">
        <div class="card-body text-center">
          <h1 class="text-danger"><?= $nb_absences ?></h1>
          <p class="mb-0 text-muted">Absences</p>
        </div>
      </div>
    </div>

    <!-- Carte : notes -->
    <div class="col-6 col-md-3 mb-3">
      <div class="card carte-stat jaune shadow-sm">
        <div class="card-body text-center">
          <h1 class="text-warning"><?= $nb_notes ?></h1>
          <p class="mb-0 text-muted">Notes</p>
        </div>
      </div>
    </div>

  </div>
</div>

<?php include '../includes/footer.php'; ?>