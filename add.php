<?php
// Include session check - redirect to login if not authenticated
include 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Etudiant - Systeme de Gestion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <!-- Page Header -->
    <div class="page-header">
        <h2>Ajouter un Nouvel Etudiant</h2>
        <p>Remplissez le formulaire pour enregistrer un nouvel etudiant dans le systeme</p>
    </div>

        <div class="content">
            <?php
            /**
             * Add Student Form
             * Handles both form display and student insertion
             */

            include 'db.php';

            $error = '';
            $success = false;

            // Handle form submission (POST request)
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Get form data
                $nom = trim($_POST['nom'] ?? '');
                $prenom = trim($_POST['prenom'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $filiere = trim($_POST['filiere'] ?? '');

                // Basic validation
                if (empty($nom)) {
                    $error = "Last name is required.";
                } elseif (empty($prenom)) {
                    $error = "First name is required.";
                } elseif (empty($email)) {
                    $error = "Email is required.";
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = "Invalid email format.";
                } elseif (empty($filiere)) {
                    $error = "Field of study is required.";
                } else {
                    // Check if email already exists
                    $check_query = "SELECT id FROM etudiants WHERE email = ?";
                    $stmt = mysqli_prepare($connection, $check_query);
                    mysqli_stmt_bind_param($stmt, "s", $email);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) > 0) {
                        $error = "This email is already registered.";
                    } else {
                        // Prepare insert statement (secure query using prepared statement)
                        $insert_query = "INSERT INTO etudiants (nom, prenom, email, filiere, date_inscription) VALUES (?, ?, ?, ?, NOW())";
                        $stmt = mysqli_prepare($connection, $insert_query);

                        if ($stmt) {
                            // Bind parameters
                            mysqli_stmt_bind_param($stmt, "ssss", $nom, $prenom, $email, $filiere);

                            // Execute query
                            if (mysqli_stmt_execute($stmt)) {
                                $success = true;
                                // Redirect to index with success message
                                header("Location: index.php?status=added");
                                exit();
                            } else {
                                $error = "Error adding student: " . mysqli_error($connection);
                            }
                            mysqli_stmt_close($stmt);
                        } else {
                            $error = "Prepare failed: " . mysqli_error($connection);
                        }
                    }
                }
            }

            // Display error message if exists
            if ($error) {
                echo "<div class='alert alert-error'>" . $error . "</div>";
            }
            ?>

            <!-- Add Student Form -->
            <form method="POST" action="add.php" style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                <div class="form-row">
                    <div class="form-group">
                        <label for="prenom">First Name *</label>
                        <input 
                            type="text" 
                            id="prenom" 
                            name="prenom" 
                            placeholder="ex: Fatima"
                            value="<?php echo isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : ''; ?>"
                            required
                        >
                    </div>
                    <div class="form-group">
                        <label for="nom">Last Name *</label>
                        <input 
                            type="text" 
                            id="nom" 
                            name="nom" 
                            placeholder="ex: El Ezzhara"
                            value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>"
                            required
                        >
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="ex: fatiamezzahra@email.com"
                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                            required
                        >
                    </div>
                    <div class="form-group">
                        <label for="filiere">Field of Study *</label>
                        <select id="filiere" name="filiere" required>
                            <option value=""> Select a field </option>
                            <option value="Computer Science" <?php echo (isset($_POST['filiere']) && $_POST['filiere'] === 'Computer Science') ? 'selected' : ''; ?>>Computer Science</option>
                            <option value="Business" <?php echo (isset($_POST['filiere']) && $_POST['filiere'] === 'Business') ? 'selected' : ''; ?>>Business</option>
                            <option value="Engineering" <?php echo (isset($_POST['filiere']) && $_POST['filiere'] === 'Engineering') ? 'selected' : ''; ?>>Engineering</option>
                            <option value="Medicine" <?php echo (isset($_POST['filiere']) && $_POST['filiere'] === 'Medicine') ? 'selected' : ''; ?>>Medicine</option>
                            <option value="Law" <?php echo (isset($_POST['filiere']) && $_POST['filiere'] === 'Law') ? 'selected' : ''; ?>>Law</option>
                            <option value="Arts" <?php echo (isset($_POST['filiere']) && $_POST['filiere'] === 'Arts') ? 'selected' : ''; ?>>Arts</option>
                            <option value="Other" <?php echo (isset($_POST['filiere']) && $_POST['filiere'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-success">Save Student</button>
                    <a href="index.php" class="btn btn-primary">Back to List</a>
                </div>
            </form>

            <?php
            // Close database connection
            mysqli_close($connection);
            ?>
        </div>
    </div>

    <?php include 'navbar-footer.php'; ?>
</body>
</html>
            <?php
            /**
             * Add Student Form
             * Handles both form display and student insertion
             */

            include 'db.php';

            $error = '';
            $success = false;

            // Handle form submission (POST request)
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Get form data
                $nom = trim($_POST['nom'] ?? '');
                $prenom = trim($_POST['prenom'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $filiere = trim($_POST['filiere'] ?? '');

                // Basic validation
                if (empty($nom)) {
                    $error = "Last name is required.";
                } elseif (empty($prenom)) {
                    $error = "First name is required.";
                } elseif (empty($email)) {
                    $error = "Email is required.";
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = "Invalid email format.";
                } elseif (empty($filiere)) {
                    $error = "Field of study is required.";
                } else {
                    // Check if email already exists
                    $check_query = "SELECT id FROM etudiants WHERE email = ?";
                    $stmt = mysqli_prepare($connection, $check_query);
                    mysqli_stmt_bind_param($stmt, "s", $email);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) > 0) {
                        $error = "This email is already registered.";
                    } else {
                        // Prepare insert statement (secure query using prepared statement)
                        $insert_query = "INSERT INTO etudiants (nom, prenom, email, filiere, date_inscription) VALUES (?, ?, ?, ?, NOW())";
                        $stmt = mysqli_prepare($connection, $insert_query);

                        if ($stmt) {
                            // Bind parameters
                            mysqli_stmt_bind_param($stmt, "ssss", $nom, $prenom, $email, $filiere);

                            // Execute query
                            if (mysqli_stmt_execute($stmt)) {
                                $success = true;
                                // Redirect to index with success message
                                header("Location: index.php?status=added");
                                exit();
                            } else {
                                $error = "Error adding student: " . mysqli_error($connection);
                            }
                            mysqli_stmt_close($stmt);
                        } else {
                            $error = "Prepare failed: " . mysqli_error($connection);
                        }
                    }
                }
            }

            // Display error message if exists
            if ($error) {
                echo "<div class='alert alert-error'>" . $error . "</div>";
            }
            ?>


               
                   
            <?php
            // Close database connection
            mysqli_close($connection);
            ?>
        </div>
    </div>
</body>
</html>
