<?php
// Nom de la page actuelle, pour mettre en évidence le lien du menu
$page = basename($_SERVER['PHP_SELF']);
$pages_etudiants = ['etudiants.php', 'ajouter_etudiant.php', 'modifier_etudiant.php'];
$pages_absences = ['absences.php', 'ajouter_absence.php'];
$pages_notes = ['notes.php', 'ajouter_note.php'];

// Initiale du nom pour le petit cercle de l'utilisateur
$initiale = mb_strtoupper(mb_substr($_SESSION['user_nom'], 0, 1));
?>
<nav class="navbar navbar-expand-lg navbar-dark navbar-tracks sticky-top">
  <div class="container">

    <!-- Logo et nom du site -->
    <a href="dashboard.php" class="navbar-brand d-flex align-items-center">
      <span class="logo-nav me-2">
        <img src="/tracks/assets/img/logo.png" alt="Logo TrackS" height="30">
      </span>
      TrackS
    </a>

    <!-- Bouton qui apparaît sur téléphone -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="menu">

      <!-- Liens principaux -->
      <ul class="navbar-nav me-auto mt-3 mt-lg-0">
        <li class="nav-item">
          <a class="nav-link <?= $page === 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">Tableau de bord</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= in_array($page, $pages_etudiants) ? 'active' : '' ?>" href="etudiants.php">Étudiants</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $page === 'classes.php' ? 'active' : '' ?>" href="classes.php">Classes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= in_array($page, $pages_absences) ? 'active' : '' ?>" href="absences.php">Absences</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= in_array($page, $pages_notes) ? 'active' : '' ?>" href="notes.php">Notes</a>
        </li>
      </ul>

      <!-- Menu de l'utilisateur -->
      <div class="dropdown mt-3 mt-lg-0">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
           data-bs-toggle="dropdown">
          <span class="avatar-nav me-2"><?= htmlspecialchars($initiale) ?></span>
          <span><?= htmlspecialchars($_SESSION['user_nom']) ?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow">
          <li><span class="dropdown-item-text text-muted small"><br><strong><?= htmlspecialchars($_SESSION['user_nom']) ?></strong></span></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-danger" href="logout.php">Déconnexion</a></li>
        </ul>
      </div>

    </div>
  </div>
</nav>