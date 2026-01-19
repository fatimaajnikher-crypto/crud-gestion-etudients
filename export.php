<?php
/**
 * Export Students to Excel (CSV Format)
 * 
 * Exports all students or filtered students to CSV file
 * CSV files can be opened directly in Excel
 */

// Include session check - redirect to login if not authenticated
include 'session.php';

// Include database connection
include 'db.php';

// Get search term if provided
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build query based on search
$base_query = "SELECT nom, prenom, email, filiere, DATE_FORMAT(date_inscription, '%d/%m/%Y') as date_inscription FROM etudiants";

if (!empty($search_term)) {
    // Use prepared statement for security
    $search_param = "%{$search_term}%";
    $query = $base_query . " WHERE nom LIKE ? OR prenom LIKE ? OR email LIKE ? OR filiere LIKE ? ORDER BY date_inscription DESC";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $search_param, $search_param, $search_param, $search_param);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $filename = 'students_' . date('Y-m-d_His') . '_filtered.csv';
} else {
    $query = $base_query . " ORDER BY date_inscription DESC";
    $result = mysqli_query($connection, $query);
    $filename = 'students_' . date('Y-m-d_His') . '.csv';
}

// Check if query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}

// Set headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Open output stream
$output = fopen('php://output', 'w');

// Add BOM for UTF-8 to display special characters correctly in Excel
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Write CSV header
fputcsv($output, array('Last Name', 'First Name', 'Email', 'Field of Study', 'Date Registered'), ',');

// Write data rows
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, array(
        $row['nom'],
        $row['prenom'],
        $row['email'],
        $row['filiere'],
        $row['date_inscription']
    ), ',');
}

// Close output stream
fclose($output);

// Close database connection
mysqli_close($connection);

exit();

?>
