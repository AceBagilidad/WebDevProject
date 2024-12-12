<?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Check if a redirect parameter is set, otherwise fallback to index.php
$redirectPage = isset($_GET['redirect']) ? $_GET['redirect'] : '../index.php';

// Prevent open redirection attacks by allowing only specific pages
$allowedPages = ['../loginPage.php', '../index.php'];
if (!in_array($redirectPage, $allowedPages)) {
    $redirectPage = '../index.php'; // Default to index.php if page is not allowed
}

header("Location: $redirectPage"); // Redirect to the specified page
exit();
?>
