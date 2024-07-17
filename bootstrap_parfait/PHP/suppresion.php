<?php
# Mes identifiants de connexions
$enregistrement = 'am620105';
$passage = 'MDP_am620105';

try {
    # Connexion à la BDD (Base de Données)
    $donnees = new PDO('mysql:host=localhost;dbname=BUTRT1_am620105', $enregistrement, $passage);
    # Définir le mode d'erreur de PDO sur Exception
    $donnees->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    # Vérifier si une recherche est effectuée
    if (isset($_GET['search'])) {
        $id_film = $_GET['search'];

        # Valider que l'entrée est un entier
        if (filter_var($id_film, FILTER_VALIDATE_INT) !== false) {
            # Supprimer d'abord les rôles liés au film
            $delete_roles_sql = "DELETE FROM role WHERE id_film = ?";
            $delete_roles_stmt = $donnees->prepare($delete_roles_sql);
            $delete_roles_stmt->execute([$id_film]);

            # Ensuite, supprimer les notations liées au film
            $delete_notations_sql = "DELETE FROM notations WHERE id_film = ?";
            $delete_notations_stmt = $donnees->prepare($delete_notations_sql);
            $delete_notations_stmt->execute([$id_film]);

            # Ensuite, supprimer le film correspondant à l'ID
            $delete_film_sql = "DELETE FROM films WHERE id_film = ?";
            $delete_film_stmt = $donnees->prepare($delete_film_sql);
            $delete_film_stmt->execute([$id_film]);

            # Redirection vers la même page pour rafraîchir la liste des films
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Erreur : L'ID du film doit être un entier valide.";
        }
    } else {
        # Requête SQL pour sélectionner tous les films
        $sql = "SELECT * FROM films";
        $result = $donnees->query($sql);
        $films = $result->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    # En cas d'erreur de connexion ou d'exécution de requête, afficher l'erreur
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche de Film</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Liste des films</h1>
    <table>
        <thead>
            <tr>
                <th>Titre</th>
                <th>ID</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($films as $film): ?>
                <tr>
                    <td><?php echo htmlspecialchars($film['titre']); ?></td>
                    <td><?php echo htmlspecialchars($film['id_film']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
