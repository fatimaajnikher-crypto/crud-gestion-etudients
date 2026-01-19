<?php
/**
 * Navigation Component
 * Include this file after the opening body tag
 */
?>
<!-- Sidebar Navigation -->
<div class="sidebar">
    <div class="sidebar-logo">
        <h2>GESTION STUDENT</h2>
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="index.php" <?php echo (basename($_SERVER['PHP_SELF']) === 'index.php') ? 'class="active"' : ''; ?>>
                Tableau de Bord
            </a>
        </li>
        <li>
            <a href="add.php" <?php echo (basename($_SERVER['PHP_SELF']) === 'add.php') ? 'class="active"' : ''; ?>>
                Ajouter Etudiant
            </a>
        </li>
        <li>
            <a href="index.php" <?php echo (basename($_SERVER['PHP_SELF']) === 'index.php') ? 'class="active"' : ''; ?>>
                Liste des Etudiants
            </a>
        </li>
        <li>
            <a href="index.php" <?php echo (basename($_SERVER['PHP_SELF']) === 'index.php') ? 'class="active"' : ''; ?>>
                Rechercher
            </a>
        </li>
        <li>
            <a href="logout.php">
                Deconnexion
            </a>
        </li>
    </ul>
</div>

<!-- Main Wrapper -->
<div class="main-wrapper">
    <!-- Top Navigation Bar -->
    <div class="topbar">
        <h1>GESTION DES ETUDIANTS</h1>
        <div class="topbar-right">
            <div class="user-section">
                <span><?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?></span>
                <div class="user-avatar">U</div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
