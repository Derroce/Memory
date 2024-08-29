<?php
require_once './autoload.php'; // Inclure le fichier centralisé pour l'autoloading

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);

    // Créer une connexion PDO
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=memory;charset=utf8', 'root', ''); // Remplacez les valeurs par vos propres paramètres de connexion
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Créer un nouvel utilisateur
        $user = new User($login, $password);
        
        // Vérifier si le mot de passe est vide
        if (empty($password)) {
            $error = 'Le mot de passe ne peut pas être vide.';
        } else {
            // Enregistrer l'utilisateur dans la base de données
            $result = $user->register($pdo, $password);

            if ($result === true) {
                $success = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
                header('Location: index.php');
            } else {
                $error = $result; // Message d'erreur si l'inscription échoue
            }
        }
    } catch (PDOException $e) {
        $error = 'Erreur de connexion à la base de données : ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Inscription</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="login">Nom d'utilisateur</label>
                <input type="text" id="login" name="login" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">S'inscrire</button>
        </form>
    </div>
</body>
</html>
