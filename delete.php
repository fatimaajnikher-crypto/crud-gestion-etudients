<?php
// Include session check - redirect to login if not authenticated
include 'session.php';
include 'db.php';

$error = '';
$student = null;

// Check if student ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $error = "Student ID is missing.";
} else {
    $student_id = intval($_GET['id']);

    // Handle deletion (POST request with confirmed action)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Confirm the action is from the form
        if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
            // Prepare delete statement (secure query using prepared statement)
            $delete_query = "DELETE FROM etudiants WHERE id = ?";
            $stmt = mysqli_prepare($connection, $delete_query);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $student_id);

                if (mysqli_stmt_execute($stmt)) {
                    // Redirect to index with success message
                    mysqli_stmt_close($stmt);
                    mysqli_close($connection);
                    header("Location: index.php?status=deleted");
                    exit();
                } else {
                    $error = "Error deleting student: " . mysqli_error($connection);
                }
                mysqli_stmt_close($stmt);
            } else {
                $error = "Prepare failed: " . mysqli_error($connection);
            }
        }
    } else {
        // Fetch student data for display
        $query = "SELECT id, nom, prenom, email, filiere, DATE_FORMAT(date_inscription, '%d/%m/%Y') as date_inscription FROM etudiants WHERE id = ?";
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer Etudiant - CRUD</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Modal Confirmation Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            animation: fadeIn 0.3s ease;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            max-width: 450px;
            width: 90%;
            animation: slideUp 0.4s ease;
            text-align: center;

        }

        .modal-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            animation: shake 0.5s ease;
        }

        .modal-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .modal-message {
            font-size: 1rem;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .modal-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .modal-btn {
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            min-width: 120px;
        }

        .modal-btn-delete {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
        }

        .modal-btn-delete:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(231, 76, 60, 0.5);
        }

        .modal-btn-cancel {
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(149, 165, 166, 0.3);
        }

        .modal-btn-cancel:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(149, 165, 166, 0.5);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes shake {
            0%, 100% { transform: scale(1); }
            25% { transform: scale(1.1) rotate(-2deg); }
            75% { transform: scale(1.1) rotate(2deg); }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="main-wrapper">
        <div class="main-content">

        <div class="content">
            <div class="page-header">
                <h1>Supprimer Etudiant</h1>
                <p>Confirmer la suppression de l'enregistrement etudiant</p>
            </div>

            <?php
            // Display error message if exists
            if ($error) {
                echo "<div class='alert alert-error'>" . htmlspecialchars($error) . "</div>";
            }

            // Check if student was found
            if ($student) {
                ?>

                <!-- Delete Confirmation Card -->
                <div class="delete-warning-card">
                    <div class="warning-icon"></div>
                    <p class="warning-text">
                        <strong>Attention:</strong> Cette action ne peut pas etre annulee!
                    </p>
                    <p class="warning-subtitle">Veuillez confirmer que vous souhaitez supprimer definitivement cet enregistrement etudiant.</p>
                </div>

                <!-- Student Information -->
                <div class="student-info-card">
                    <h3 class="card-title">Details de l'Etudiant</h3>
                    <table class="detail-table">
                        <tr>
                            <td class="label">ID:</td>
                            <td><?php echo $student['id']; ?></td>
                        </tr>
                        <tr>
                            <td class="label">Nom:</td>
                            <td><?php echo htmlspecialchars($student['prenom']) . " " . htmlspecialchars($student['nom']); ?></td>
                        </tr>
                        <tr>
                            <td class="label">Email:</td>
                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                        </tr>
                        <tr>
                            <td class="label">Domaine d'etudes:</td>
                            <td><?php echo htmlspecialchars($student['filiere']); ?></td>
                        </tr>
                        <tr>
                            <td class="label">Date d'inscription:</td>
                            <td><?php echo $student['date_inscription']; ?></td>
                        </tr>
                    </table>
                </div>

                <!-- Confirmation Form -->
                <form method="POST" action="delete.php?id=<?php echo $student['id']; ?>" class="delete-form" id="deleteForm">
                    <div class="confirmation-prompt">
                        <p>
                            Cliquez sur le bouton ci-dessous pour proceder a la suppression de <strong class="student-name"><?php echo htmlspecialchars($student['prenom']); ?></strong>
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-actions">
                        <button type="button" id="deleteBtn" class="btn btn-danger" onclick="showDeleteConfirmation(event)">
                            🗑️ Supprimer Definitivement
                        </button>
                        <button type="button" class="btn btn-primary" onclick="goBack()">
                            ✕ Annuler
                        </button>
                    </div>
                    <input type="hidden" name="confirm" id="confirmValue" value="">
                </form>

                <!-- Confirmation Modal -->
                <div id="confirmModal" class="modal">
                    <div class="modal-content">
                        <div class="modal-icon">⚠️</div>
                        <h2 class="modal-title">Confirmer la Suppression</h2>
                        <p class="modal-message">
                            Etes-vous sur que vous voulez supprimer definitivement <strong><?php echo htmlspecialchars($student['prenom'] . ' ' . $student['nom']); ?></strong>? 
                            <br><br>
                            Cette action <strong>ne peut pas etre annulee</strong>.
                        </p>
                        <div class="modal-actions">
                            <button class="modal-btn modal-btn-delete" onclick="confirmDelete()">
                                ✓ Supprimer
                            </button>
                            <button class="modal-btn modal-btn-cancel" onclick="cancelDelete()">
                                ✕ Annuler
                            </button>
                        </div>
                    </div>
                </div>

                <script>
                    function showDeleteConfirmation(event) {
                        event.preventDefault();
                        document.getElementById('confirmModal').classList.add('show');
                    }

                    function confirmDelete() {
                        document.getElementById('confirmValue').value = 'yes';
                        document.getElementById('deleteForm').submit();
                    }

                    function cancelDelete() {
                        document.getElementById('confirmModal').classList.remove('show');
                    }

                    function goBack() {
                        window.location.href = 'index.php';
                    }

                    // Close modal when clicking outside of it
                    window.onclick = function(event) {
                        var modal = document.getElementById('confirmModal');
                        if (event.target == modal) {
                            modal.classList.remove('show');
                        }
                    }

                    // Close modal with Escape key
                    document.addEventListener('keydown', function(event) {
                        if (event.key === 'Escape') {
                            document.getElementById('confirmModal').classList.remove('show');
                        }
                    });
                </script>

                <?php
            } elseif (!$error) {
                // Show message if no error and no student
                echo "<div class='alert alert-warning'>Impossible de charger les donnees de l'etudiant.</div>";
                echo "<div class='form-actions' style='margin-top: 20px;'>";
                echo "<a href='index.php' class='btn btn-primary'>Retour a la Liste</a>";
                echo "</div>";
            }

            // Close database connection
            mysqli_close($connection);
            ?>
        </div>
    </div>
    </div>

    <?php include 'navbar-footer.php'; ?>
</body>
</html>