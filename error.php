<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <?php include('menu.php'); ?>
    </header>

    <main>
        <h1>Error</h1>
        <p>
            <?php
                if (isset($_GET['error'])) {
                    echo htmlspecialchars($_GET['error']);
                } else {
                    echo "An unexpected error occurred.";
                }
            ?>
        </p>
    </main>

    <footer>
        <p>&copy; 2023 Nerdy Social Media Site</p>
    </footer>
</body>
</html>
