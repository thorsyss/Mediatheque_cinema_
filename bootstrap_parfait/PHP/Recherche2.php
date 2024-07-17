<?php
// Identifiants de connexion
$enregistrement = 'am620105';
$passage = 'MDP_am620105';

// Récupération des informations de la requête GET
$table = $_GET['table'] ?? '';
$recherche = $_GET['recherche'] ?? '';

$results = [];

if (!empty($table) && !empty($recherche)) {
    try {
        // Connexion à la base de données
        $bdd = new PDO('mysql:host=localhost;dbname=BUTRT1_am620105', $enregistrement, $passage);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Préparer la requête SQL
        $columns = $bdd->query("DESCRIBE $table")->fetchAll(PDO::FETCH_COLUMN);
        if ($columns) {
            $conditions = array_map(fn($col) => "$col LIKE :recherche", $columns);
            $sql = "SELECT * FROM $table WHERE " . implode(' OR ', $conditions);

            // Exécuter la requête préparée
            $stmt = $bdd->prepare($sql);
            $stmt->execute(['recherche' => "%$recherche%"]);

            // Récupérer les résultats
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            throw new Exception("La table spécifiée n'existe pas.");
        }
    } catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    } finally {
        // Fermeture de la connexion
        $bdd = null;
    }
} else {
    echo "Les variables 'table' et 'recherche' doivent être définies et non vides.";
}
?>

<!-- Affichage des résultats dans un tableau HTML -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de la recherche</title>
</head>
<body>
    <h1>Résultats de la recherche</h1>
    <?php if (!empty($results)): ?>
        <table border="1">
            <thead>
                <tr>
                    <?php foreach (array_keys($results[0]) as $header): ?>
                        <th><?php echo htmlspecialchars($header); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row): ?>
                    <tr>
                        <?php foreach ($row as $cell): ?>
                            <td><?php echo htmlspecialchars($cell); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun résultat trouvé.</p>
    <?php endif; ?>
</body>
</html>
