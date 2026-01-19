<?php
/**
 * Admin Login Page
 * 
 * Handles admin authentication using PHP sessions
 * Simple credentials for demonstration
 */

// Start session first (BEFORE any output)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize error message
$error = '';

// Check if user just logged out
$logged_out = isset($_GET['logged_out']) && $_GET['logged_out'] === 'true';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Admin credentials (hardcoded for demonstration)
    // In production, store credentials in database with hashed passwords
    $admin_username = 'admin';
    $admin_password = 'admin123';

    // Validate credentials
    if (empty($username) || empty($password)) {
        $error = 'Le nom d\'utilisateur et le mot de passe sont requis.';
    } elseif ($username === $admin_username && $password === $admin_password) {
        // Credentials are correct - create session
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        $_SESSION['login_time'] = time();

        // Redirect to index page
        header("Location: index.php");
        exit();
    } else {
        // Credentials are incorrect
        $error = 'Nom d\'utilisateur ou mot de passe invalide.';
    }
}

// Display logout success message
if ($logged_out) {
    $logout_message = 'Vous avez ete deconnecte avec succes.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - Gestion des Etudiants</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', 'Roboto', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            min-height: 100vh;
            padding: 20px;
        }

        .login-box {
            background: white;
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 420px;
            animation: slideUp 0.5s ease;
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

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-header h1 {
            font-size: 2.2rem;
            color: #667eea;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .login-header p {
            color: #666;
            font-size: 0.95rem;
        }

        .login-form .form-group {
            margin-bottom: 25px;
        }

        .login-form label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #333;
            font-size: 0.95rem;
        }

        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: 100%;
            padding: 14px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        .login-form input[type="text"]:focus,
        .login-form input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.5);
        }

        .login-btn:active {
            transform: translateY(-1px);
        }

        .demo-credentials {
            background: #f0f8ff;
            border: 1px solid #bde0ff;
            border-radius: 8px;
            padding: 18px;
            margin-top: 25px;
            font-size: 0.9rem;
            color: #333;
        }

        .demo-credentials strong {
            display: block;
            margin-bottom: 8px;
            color: #667eea;
        }

        .demo-credentials p {
            margin: 5px 0;
            line-height: 1.6;
        }

        .demo-credentials code {
            background: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            color: #764ba2;
        }

        @media (max-width: 480px) {
            .login-box {
                padding: 20px;
            }

            .login-header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <h1>Connexion Admin</h1>
                <p>Systeme de Gestion des Etudiants</p>
            </div>

            <?php
            // Display error message if exists
            if ($error) {
                echo "<div class='alert alert-error'>" . htmlspecialchars($error) . "</div>";
            }

            // Display logout success message
            if ($logged_out) {
                echo "<div class='alert alert-success'>Vous avez ete deconnecte avec succes.</div>";
            }
            ?>

            <!-- Login Form -->
            <form method="POST" action="login.php" class="login-form">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="Entrez le nom d'utilisateur"
                        value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Entrez le mot de passe"
                        required
                    >
                </div>

                <button type="submit" class="login-btn">Connexion</button>
            </form>

           
        </div>
    </div>
</body>
</html>
