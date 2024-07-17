<?php
    # Mes identifiants de connexions
    $enregistrement = 'am620105';
    $passage = 'MDP_am620105';

    try {
        # Connexion à la BDD
        $donnees = new PDO('mysql:host=localhost;dbname=BUTRT1_am620105', $enregistrement, $passage);
        $donnees->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        # Requête SQL pour obtenir les films avec les informations de genre et de réalisateur
        $sql = "SELECT  *
                FROM films
                INNER JOIN genres ON films.id_genre = genres.id_genre
                INNER JOIN artistes ON films.id_realisateur = artistes.id_artiste";

        # Vérifier si une recherche est effectuée
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            # Modifier la requête SQL pour inclure la recherche
            $sql .= " WHERE films.titre LIKE ?";
            $stmt = $donnees->prepare($sql);
            $stmt->execute(['%' . $search . '%']);
        } else {
            $stmt = $donnees->query($sql);
        }

        $films = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        # Capture des erreurs
        die("Erreur : " . $e->getMessage());
    } finally {
        # Fermeture de la connexion
        $donnees = null;
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des films</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        /* Coloration des lignes impaires */
        tr:nth-child(odd) {
            background-color: #e6f7ff; /* Bleu clair */
        }
    </style>
</head>
<body>
    <h2>Liste des films</h2>
    <table>
        <tr>
            <th>Titre</th>
            <th>Année</th>
            <th>Réalisateur</th>
            <th>Genre</th>
            <th>Résumé</th>
        </tr>
        <?php foreach ($films as $film): ?>
        <tr>
            <td><?php echo $film['titre']; ?></td>
            <td><?php echo $film['annee']; ?></td>
            <td><?php echo $film['nom']; ?></td>
            <td><?php echo $film['genre_film']; ?></td>
            <td><?php echo $film['resume']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>