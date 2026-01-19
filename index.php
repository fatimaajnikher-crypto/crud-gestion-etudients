<?php
// Include session check - redirect to login if not authenticated
include 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Etudiants - Tableau de Bord</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <!-- Page Header -->
    <div class="page-header">
        <h2>Liste des Etudiants</h2>
        <p>Gerez tous les etudiants enregistres dans le systeme</p>
    </div>

    <div class="content">
        <!-- Navigation Buttons -->
        <div class="nav-buttons">
            <a href="add.php" class="btn btn-primary">
                Ajouter un Nouvel Etudiant
            </a>
            <a href="index.php" class="btn btn-success">
                Actualiser la Liste
            </a>
            <?php if (!empty($search_term)): ?>
                <a href="export.php?search=<?php echo urlencode($search_term); ?>" class="btn btn-primary">
                    Exporter Filtree
                </a>
            <?php else: ?>
                <a href="export.php" class="btn btn-primary">
                    Exporter vers Excel
                </a>
            <?php endif; ?>
        </div>

        <?php
        /**
         * Tableau de Bord Principal - Liste de Tous les Etudiants
         * Affiche tous les etudiants avec options de modification et de suppression
         * Inclut une fonctionnalite de recherche pour filtrer par nom ou domaine d'etudes
         */

        // Inclure la connexion a la base de donnees
        include 'db.php';

        // Initialize message variable
        $message = '';
        $message_type = '';

        // Check for success message from other pages
        if (isset($_GET['status'])) {
            if ($_GET['status'] === 'added') {
                $message = '✓ Etudiant ajoute avec succes!';
                $message_type = 'success';
            } elseif ($_GET['status'] === 'updated') {
                $message = '✓ Etudiant mis a jour avec succes!';
                $message_type = 'success';
            } elseif ($_GET['status'] === 'deleted') {
                $message = '✓ Etudiant supprime avec succes!';
                $message_type = 'success';
            }
        }

        // Display message if exists
        if ($message) {
            echo "<div class='alert alert-" . $message_type . "'>" . $message . "</div>";
        }

        // Handle search functionality
        $search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
        $base_query = "SELECT id, nom, prenom, email, filiere, DATE_FORMAT(date_inscription, '%d/%m/%Y') as date_inscription FROM etudiants";
        
        // Build WHERE clause if search term exists
        if (!empty($search_term)) {
            // Use prepared statement for security
            $search_param = "%{$search_term}%";
            $query = $base_query . " WHERE nom LIKE ? OR prenom LIKE ? OR email LIKE ? OR filiere LIKE ? ORDER BY date_inscription DESC";
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, "ssss", $search_param, $search_param, $search_param, $search_param);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
        } else {
            // Query to get all students
            $query = $base_query . " ORDER BY date_inscription DESC";
            $result = mysqli_query($connection, $query);
        }

        // Check if query was successful
        if (!$result) {
            die("Query failed: " . mysqli_error($connection));
        }

        $count = mysqli_num_rows($result);
        ?>

        <!-- Search Form -->
        <div class="search-section">
            <form method="GET" action="index.php" class="search-form">
                <div class="search-container">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Rechercher par nom, email ou domaine d'etudes..." 
                        value="<?php echo htmlspecialchars($search_term); ?>"
                        class="search-input"
                    >
                    <button type="submit" class="btn btn-primary search-btn">Rechercher</button>
                    <?php if (!empty($search_term)): ?>
                        <a href="index.php" class="btn btn-primary search-btn">Effacer</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <?php
        // Display search results info
        if (!empty($search_term)) {
            echo "<div class='alert alert-warning' style='margin-top: 15px;'>";
            echo "Resultats de recherche pour: <strong>" . htmlspecialchars($search_term) . "</strong> (" . $count . " resultat" . ($count !== 1 ? "s" : "") . " trouve)";
            echo "</div>";
        }
        ?>
        <?php
        // Check if there are students
        if ($count > 0) {
            ?>

            <!-- Students Card Layout -->
            <div class="students-container">
                <?php
                // Loop through all students and display as cards
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="student-card">';
                    
                    // Card header with name and badge
                    echo '<div class="card-header">';
                    echo '<div class="card-title">';
                    echo '<h3>' . htmlspecialchars($row['prenom']) . ' ' . htmlspecialchars($row['nom']) . '</h3>';
                    echo '<span class="subtitle">ID: #' . $row['id'] . '</span>';
                    echo '</div>';
                    echo '<span class="card-badge">' . htmlspecialchars($row['filiere']) . '</span>';
                    echo '</div>';
                    
                    // Card content
                    echo '<div class="card-content">';
                    echo '<div class="card-field">';
                    echo '<span class="card-field-label">Email:</span>';
                    echo '<span class="card-field-value">' . htmlspecialchars($row['email']) . '</span>';
                    echo '</div>';
                    echo '<div class="card-field">';
                    echo '<span class="card-field-label">Enregistre:</span>';
                    echo '<span class="card-field-value">' . $row['date_inscription'] . '</span>';
                    echo '</div>';
                    echo '</div>';
                    
                    // Card actions
                    echo '<div class="card-actions">';
                    echo '<a href="edit.php?id=' . $row['id'] . '" class="btn btn-warning btn-small">Modifier</a>';
                    echo '<a href="delete.php?id=' . $row['id'] . '" class="btn btn-danger btn-small">Supprimer</a>';
                    echo '</div>';
                    
                    echo '</div>';
                }
                ?>
            </div>

            <?php
        } else {
            // Show empty state
            echo "<div class='empty-state'>";
            echo "<h3>Aucun etudiant trouve</h3>";
            echo "<p>Commencez par ajouter votre premier etudiant!</p>";
            echo "</div>";
        }

        // Close database connection
        mysqli_close($connection);
        ?>
    </div>

    <?php include 'navbar-footer.php'; ?>
</body>
</html>
