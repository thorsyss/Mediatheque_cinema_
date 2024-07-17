<?php
# Mes identifiants de connexions
$enregistrement = 'am620105';
$passage = 'MDP_am620105';

try {
    # Connexion à la BDD
    
    
    $donnees = new PDO('mysql:host=localhost;dbname=BUTRT1_am620105', $enregistrement, $passage);
    $donnees->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    // Vérifie si la requête est une soumission de formulaire en POST
    
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
        # Récupérer les données du formulaire
        $id_film = $_POST['id_film'];
        $titre = $_POST['titre'];
        $annee = $_POST['annee'];
        $id_realisateur = $_POST['id_realisateur'];
        $id_genre = $_POST['id_genre'];
        $resume = $_POST['resume'];
        $id_code_pays = $_POST['id_code_pays'];

        
        
        # Valider les données
        
        
        if (filter_var($id_film, FILTER_VALIDATE_INT) !== false && filter_var($annee, FILTER_VALIDATE_INT) !== false) { // Vérifie si les valeurs d'ID et d'année sont des entiers
            # Vérifier si l'ID du film existe déjà
            $check_sql = "SELECT * FROM films WHERE id_film = ?";
            $check_stmt = $donnees->prepare($check_sql);
            $check_stmt->execute([$id_film]);
            $existing_film = $check_stmt->fetch(PDO::FETCH_ASSOC);

            if ($existing_film) {
                
                
                # Si l'ID existe déjà, afficher un message d'erreur
                echo "Erreur : L'ID du film " . htmlspecialchars($id_film) . " est déjà utilisé pour le film '" . htmlspecialchars($existing_film['titre']) . "'.";
            } else {
                
                
                # Préparer la requête SQL pour insérer le nouveau film
                $insert_sql = "INSERT INTO films (id_film, titre, annee, id_realisateur, id_genre, resume, id_code_pays) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $insert_stmt = $donnees->prepare($insert_sql);
                $insert_stmt->execute([$id_film, $titre, $annee, $id_realisateur, $id_genre, $resume, $id_code_pays]);

                echo "Film ajouté avec succès.";
            }
        } else {
            echo "Erreur : Veuillez fournir des valeurs valides.";
        }
    }
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
