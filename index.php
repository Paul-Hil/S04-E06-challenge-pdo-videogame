<?php
// Inclusion du fichier s'occupant de la connexion à la DB (TODO)
require __DIR__ . '/inc/db.php'; // Pour __DIR__ => http://php.net/manual/fr/language.constants.predefined.php
// Rappel : la variable $pdo est disponible dans ce fichier
//          car elle a été créée par le fichier inclus ci-dessus

// Initialisation de variables (évite les "NOTICE - variable inexistante")
$videogameList = array();
$platformList = array();
$name = '';
$editor = '';
$release_date = '';
$platform = '';

// Si le formulaire a été soumis
if (!empty($_POST)) {
    // Récupération des valeurs du formulaire dans des variables
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $editor = isset($_POST['editor']) ? $_POST['editor'] : '';
    $release_date = isset($_POST['release_date']) ? $_POST['release_date'] : '';
    $platform = isset($_POST['platform']) ? intval($_POST['platform']) : 0;
    $nameDelete = isset($_POST['nameDelete']) ? $_POST['nameDelete'] : '';


    // TODO #3 (optionnel) valider les données reçues (ex: donnée non vide)
    // --- START OF YOUR CODE ---
    if (empty($name) || empty($editor) || empty($release_date) || empty($platform)) {
        if (empty($nameDelete)) {
            print_r("Données invalides");
            require __DIR__ . '/view/videogame.php';
            exit;
        }
        else {
            $deleteQuery = "DELETE FROM videogame WHERE name = ('{$nameDelete}')";
            $pdoStatement = $pdo->exec($deleteQuery);
            header("Location: index.php");
        }
    } else {
        $insertQuery = "
        INSERT INTO videogame (name, editor, release_date, platform_id)
        VALUES ('{$name}', '{$editor}', '{$release_date}', {$platform}) ";
        $pdoStatement = $pdo->exec($insertQuery);
        header("Location: index.php");
    }




    // --- END OF YOUR CODE ---
}

// Liste des consoles de jeux
// TODO #4 (optionnel) récupérer cette liste depuis la base de données
// --- START OF YOUR CODE ---
$platformList = 'SELECT name FROM platform';
$pdoStatement = $pdo->query($platformList);
$platformList = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
$newPlat = [];
foreach ($platformList as $contentPlat) {
    array_push($newPlat, $contentPlat['name']);
}
$platformList = $newPlat;
// --- END OF YOUR CODE ---

// TODO #1 écrire la requête SQL permettant de récupérer les jeux vidéos en base de données (mais ne pas l'exécuter maintenant)
// --- START OF YOUR CODE ---
$videogameList = 'SELECT * FROM videogame';
// --- END OF YOUR CODE ---

// Si un tri a été demandé, on réécrit la requête
if (!empty($_GET['order'])) {
    // Récupération du tri choisi
    $order = trim($_GET['order']);
    if ($order == 'name') {
        // TODO #2 écrire la requête avec un tri par nom croissant
        // --- START OF YOUR CODE ---
        $videogameList = "SELECT * FROM videogame ORDER BY name";
        // --- END OF YOUR CODE ---
    } else if ($order == 'editor') {
        // TODO #2 écrire la requête avec un tri par editeur croissant
        // --- START OF YOUR CODE ---
        $videogameList = "SELECT * FROM videogame ORDER BY editor";
        // --- END OF YOUR CODE ---
    }
}
// TODO #1 exécuter la requête contenue dans $sql et récupérer les valeurs dans la variable $videogameList
// --- START OF YOUR CODE ---
$pdoStatement = $pdo->query($videogameList);
$videogameList = $pdoStatement->fetchall(PDO::FETCH_ASSOC);
// --- END OF YOUR CODE ---

// Inclusion du fichier s'occupant d'afficher le code HTML
// Je fais cela car mon fichier actuel est déjà assez gros, donc autant le faire ailleurs (pas le métier hein ! ;) )
require __DIR__ . '/view/videogame.php';
