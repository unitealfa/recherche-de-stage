<?php

$DB_NAME = 'search_stage';
$DB_USER = 'mourad';
$DB_PASS = 'mourad12345678';

try {
    $db = new PDO('mysql:host=localhost;dbname=' . $DB_NAME, $DB_USER, $DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
