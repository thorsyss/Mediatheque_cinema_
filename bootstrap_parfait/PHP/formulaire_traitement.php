<?php

# Ecrire les infos
echo '<pre>';
echo 'GET:'; print_r($_GET);
echo 'POST:'; print_r($_POST);
echo 'SESSION:'; print_r($_SESSION);
echo '</pre>';

# Chercher les infos dans la page
$email = $_GET['email'];
$nom = $_GET['nom'];
$prenom = $_GET['prenom'];
$password = $_GET['password'];
$annee = $_GET['annee_naissance'];
$region = $_GET['id_region'];

# Valeur du formulaire (commenté)
/*
echo "nom: $nom </br>";
echo "prenom: $prenom </br>";
echo "email: $email </br>";
echo "password: $password </br>";
*/

# Mes identifiants de connexion
$enregistrement = 'am620105';
$passage = 'MDP_am620105';

# Connexion à la BDD
try {
    $donnees = new PDO('mysql:host=localhost;dbname=BUTRT1_am620105', $enregistrement, $passage);
    $donnees->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO internautes (id_email, nom, prenom, mot_de_passe, annee_naissance, id_region) VALUES (?, ?, ?, ?, ?, ?)";
    echo "c'est bon !";
    $stmt = $donnees->prepare($sql);
    $success = $stmt->execute([$email, $nom, $prenom, $password, $annee, $region]);
    if ($success) {
        echo " ca marche";
    } else {
        echo "marche pas";
    }
} catch (Exception $e) {
    die("Erreur " . $e->getMessage());
} finally {
    # Fermeture de la connexion
    $donnees = null;
}
?>
