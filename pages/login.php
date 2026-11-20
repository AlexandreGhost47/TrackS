<?php
session_start();
require '../config/db.php';

// Déjà connecté : inutile de revoir la page de connexion
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $motdepasse = $_POST['mot_de_passe'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($motdepasse, $user['mot_de_passe'])) {

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nom'] = $user['nom'];
        header('Location: dashboard.php');
        exit;
    } else {
        $erreur = 'Email ou mot de passe incorrect.';
    }
}

include '../includes/header.php';
?>

<div class="fond-login">
<div class="container">
<div class="row justify-content-center">

    <div class="col-md-5">
      <div class="card shadow">
        <div class="card-body p-4">

                  <div class="text-center mb-3">
            <img src="/tracks/assets/img/logo.png" alt="Logo TrackS" height="70">
          </div>
          <h2 class="text-center mb-4">TrackS</h2>

          <p class="text-center text-muted">Connexion à votre espace</p>

          <?php if ($erreur): ?>
            <div class="alert alert-danger"><?= $erreur ?></div>
          <?php endif; ?>

          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Mot de passe</label>
              <input type="password" name="mot_de_passe" id="mot_de_passe" class="form-control" required>
              <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" id="voir_mdp" onclick="basculerMotDePasse()">
                <label class="form-check-label" for="voir_mdp">Afficher le mot de passe</label>
              </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<script>
function basculerMotDePasse() {
  var champ = document.getElementById('mot_de_passe');
  champ.type = (champ.type === 'password') ? 'text' : 'password';
}
</script>

<?php include '../includes/footer.php'; ?>