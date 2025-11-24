<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <title><?= $title ?? "" ?></title>
</head>

<body>
    <?php include 'component/navbar.php' ?>
    <main class="container">
        <h1>Ajouter un Film</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <fieldset>
                <label>Saisir le titre du film
                    <input type="text" name="title" placeholder="Saisir le titre du film">
                </label>
                <label>Saisir la description du film
                    <textarea name="description" placeholder="Saisir la description du film..."
                        aria-label="Description du film">
                </textarea>
                </label>
                <label>Saisir la date de sortie
                    <input type="datetime-local" name="publish_at" aria-label="Choix de la date de sortie">
                </label>
                <label>Saisir la durée du film en minutes
                    <input type="text" name="duration" placeholder="Saisir la durée du film..."
                        aria-label="Durée du film">
                </label>
                <select aria-label="Sélectionner les categories..." multiple size="6" name="categories[]">
                    <option disabled>
                        Sélectionner les categories...
                    </option>
                    <!--Boucle pour créer les options de la liste déroulante -->
                    <?php foreach ($data["categories"] as $category) :?>
                        <option value="<?= $category["id"] ?>"><?= $category["name"] ?></option>
                    <?php endforeach ?>
                </select>
                <label>Importer une image du film
                    <input type="file" name="cover">
                </label>
            <fieldset>
                <input type="submit" value="Ajouter" name="submit">
        </form>
        <p><?= $data["error"] ?? "" ?></p>
        <p><?= $data["valid"] ?? "" ?></p>
    </main>
</body>

</html>