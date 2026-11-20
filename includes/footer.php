<?php
// Les liens du pied de page ne s'affichent que si la personne est connectée
$connecte = isset($_SESSION['user_id']);
?>

<footer class="pied-page bg-dark text-light mt-5">
  <div class="container py-4">
    <div class="row">

      <!-- Colonne 1 : présentation -->
      <div class="col-md-4 mb-4">
        <h5 class="d-flex align-items-center">
          <img src="/tracks/assets/img/logo.png" alt="Logo TrackS" height="28" class="me-2">
          TrackS
        </h5>
        <p class="text-secondary mb-0">
          Plateforme de suivi des étudiants : gestion des classes, des absences et des notes en un seul endroit.
        </p>
      </div>

      <!-- Colonne 2 : liens rapides -->
      <div class="col-md-4 mb-4">
        <h5>Navigation</h5>
        <?php if ($connecte): ?>
          <ul class="list-unstyled mb-0">
            <li><a href="/tracks/pages/dashboard.php" class="text-secondary text-decoration-none">Tableau de bord</a></li>
            <li><a href="/tracks/pages/etudiants.php" class="text-secondary text-decoration-none">Étudiants</a></li>
            <li><a href="/tracks/pages/classes.php" class="text-secondary text-decoration-none">Classes</a></li>
            <li><a href="/tracks/pages/absences.php" class="text-secondary text-decoration-none">Absences</a></li>
            <li><a href="/tracks/pages/notes.php" class="text-secondary text-decoration-none">Notes</a></li>
          </ul>
        <?php else: ?>
          <p class="text-secondary mb-0">Connectez-vous pour accéder à votre espace.</p>
        <?php endif; ?>
      </div>

      <!-- Colonne 3 : informations -->
      <div class="col-md-4 mb-4">
        <h5>Informations</h5>
        <ul class="list-unstyled text-secondary mb-0">
          <li>Établissement : ESTLC</li>
          <li>Email : alexpolaski@icloud.com</li>
          <li>Projet réalisé avec HTML, CSS, Bootstrap et PHP</li>
        </ul>
      </div>

    </div>

    <hr class="border-secondary">

    <!-- Bas du pied de page -->
    <div class="d-flex flex-column flex-md-row justify-content-between text-secondary small">
      <span>&copy; <?= date('Y') ?> TrackS. Tous droits réservés.</span>
      <span>Conçu par <?= 'Alexandre Polaski Eleve Ingenieur' ?></span>
    </div>
  </div>
</footer>

<script src="/tracks/assets/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.alert').forEach(function (alerte) {
  setTimeout(function () {
    alerte.style.transition = 'opacity 0.5s';
    alerte.style.opacity = '0';
    setTimeout(function () { alerte.remove(); }, 500);
  }, 4000);
});
</script>
</body>
</html>