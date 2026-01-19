<?php
// Include session check - redirect to login if not authenticated
include 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <!-- Page Header -->
    <div class="page-header">
        <h2>Edit Student Information</h2>
        <p>Update student details and information</p>
    </div>

        <div class="content">
            <?php
            /**
             * Edit Student Form
             * Handles both form display and student update
             */

            include 'db.php';

            $error = '';
            $success = false;
            $student = null;

            // Check if student ID is provided
            if (!isset($_GET['id']) || empty($_GET['id'])) {
                $error = "Student ID is missing.";
            } else {
                $student_id = intval($_GET['id']);

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
                        // Check if email already exists for another student
                        $check_query = "SELECT id FROM etudiants WHERE email = ? AND id != ?";
                        $stmt = mysqli_prepare($connection, $check_query);
                        mysqli_stmt_bind_param($stmt, "si", $email, $student_id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        if (mysqli_num_rows($result) > 0) {
                            $error = "This email is already registered by another student.";
                        } else {
                            // Prepare update statement (secure query using prepared statement)
                            $update_query = "UPDATE etudiants SET nom = ?, prenom = ?, email = ?, filiere = ? WHERE id = ?";
                            $stmt = mysqli_prepare($connection, $update_query);

                            if ($stmt) {
                                // Bind parameters
                                mysqli_stmt_bind_param($stmt, "ssssi", $nom, $prenom, $email, $filiere, $student_id);

                                // Execute query
                                if (mysqli_stmt_execute($stmt)) {
                                    $success = true;
                                    // Redirect to index with success message
                                    header("Location: index.php?status=updated");
                                    exit();
                                } else {
                                    $error = "Error updating student: " . mysqli_error($connection);
                                }
                                mysqli_stmt_close($stmt);
                            } else {
                                $error = "Prepare failed: " . mysqli_error($connection);
                            }
                        }
                    }
                } else {
                    // Fetch student data for display
                    $query = "SELECT id, nom, prenom, email, filiere FROM etudiants WHERE id = ?";
                    $stmt = mysqli_prepare($connection, $query);

                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "i", $student_id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        if ($result && mysqli_num_rows($result) > 0) {
                            $student = mysqli_fetch_assoc($result);
                        } else {
                            $error = "Student not found.";
                        }
                        mysqli_stmt_close($stmt);
                    } else {
                        $error = "Query failed: " . mysqli_error($connection);
                    }
                }
            }

            // Display error message if exists
            if ($error) {
                echo "<div class='alert alert-error'>" . $error . "</div>";
            }

            // Check if student was found
            if ($student) {
                ?>

                <!-- Edit Student Form -->
                <form method="POST" action="edit.php?id=<?php echo $student['id']; ?>" style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="prenom">First Name *</label>
                            <input 
                                type="text" 
                                id="prenom" 
                                name="prenom" 
                                placeholder="e.g., Ahmed"
                                value="<?php echo htmlspecialchars($student['prenom']); ?>"
                                required
                            >
                        </div>
                        <div class="form-group">
                            <label for="nom">Last Name *</label>
                            <input 
                                type="text" 
                                id="nom" 
                                name="nom" 
                                placeholder="e.g., Boucher"
                                value="<?php echo htmlspecialchars($student['nom']); ?>"
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
                                placeholder="e.g., ahmed.boucher@email.com"
                                value="<?php echo htmlspecialchars($student['email']); ?>"
                                required
                            >
                        </div>
                        <div class="form-group">
                            <label for="filiere">Field of Study *</label>
                            <select id="filiere" name="filiere" required>
                                <option value="">-- Select a field --</option>
                                <option value="Computer Science" <?php echo $student['filiere'] === 'Computer Science' ? 'selected' : ''; ?>>Computer Science</option>
                                <option value="Business" <?php echo $student['filiere'] === 'Business' ? 'selected' : ''; ?>>Business</option>
                                <option value="Engineering" <?php echo $student['filiere'] === 'Engineering' ? 'selected' : ''; ?>>Engineering</option>
                                <option value="Medicine" <?php echo $student['filiere'] === 'Medicine' ? 'selected' : ''; ?>>Medicine</option>
                                <option value="Law" <?php echo $student['filiere'] === 'Law' ? 'selected' : ''; ?>>Law</option>
                                <option value="Arts" <?php echo $student['filiere'] === 'Arts' ? 'selected' : ''; ?>>Arts</option>
                                <option value="Other" <?php echo $student['filiere'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success">Save Changes</button>
                        <a href="index.php" class="btn btn-primary">Back to List</a>
                    </div>
                </form>

                <?php
            } elseif (!$error) {
                // Show message if no error and no student
                echo "<div class='alert alert-warning'>Could not load student data.</div>";
            }

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
             * Edit Student Form
             * Handles both form display and student update
             */

            include 'db.php';

            $error = '';
            $success = false;
            $student = null;

            // Check if student ID is provided
            if (!isset($_GET['id']) || empty($_GET['id'])) {
                $error = "Student ID is missing.";
            } else {
                $student_id = intval($_GET['id']);

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
                        // Check if email already exists for another student
                        $check_query = "SELECT id FROM etudiants WHERE email = ? AND id != ?";
                        $stmt = mysqli_prepare($connection, $check_query);
                        mysqli_stmt_bind_param($stmt, "si", $email, $student_id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        if (mysqli_num_rows($result) > 0) {
                            $error = "This email is already registered by another student.";
                        } else {
                            // Prepare update statement (secure query using prepared statement)
                            $update_query = "UPDATE etudiants SET nom = ?, prenom = ?, email = ?, filiere = ? WHERE id = ?";
                            $stmt = mysqli_prepare($connection, $update_query);

                            if ($stmt) {
                                // Bind parameters
                                mysqli_stmt_bind_param($stmt, "ssssi", $nom, $prenom, $email, $filiere, $student_id);

                                // Execute query
                                if (mysqli_stmt_execute($stmt)) {
                                    $success = true;
                                    // Redirect to index with success message
                                    header("Location: index.php?status=updated");
                                    exit();
                                } else {
                                    $error = "Error updating student: " . mysqli_error($connection);
                                }
                                mysqli_stmt_close($stmt);
                            } else {
                                $error = "Prepare failed: " . mysqli_error($connection);
                            }
                        }
                    }
                } else {
                    // Fetch student data for display
                    $query = "SELECT id, nom, prenom, email, filiere FROM etudiants WHERE id = ?";
                    $stmt = mysqli_prepare($connection, $query);

                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "i", $student_id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        if ($result && mysqli_num_rows($result) > 0) {
                            $student = mysqli_fetch_assoc($result);
                        } else {
                            $error = "Student not found.";
                        }
                        mysqli_stmt_close($stmt);
                    } else {
                        $error = "Query failed: " . mysqli_error($connection);
                    }
                }
            }

            // Display error message if exists
            if ($error) {
                echo "<div class='alert alert-error'>" . $error . "</div>";
            }

            // Check if student was found
            if ($student) {
                ?>

                <?php
            } elseif (!$error) {
                // Show message if no error and no student
                echo "<div class='alert alert-warning'>Could not load student data.</div>";
            }

            // Close database connection
            mysqli_close($connection);
            ?>
        </div>
    </div>
</body>
</html>
