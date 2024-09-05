<?php
$host = '102.177.161.34';
$db = 'alumnosueb';
$user = 'UEB';
$pass = 'Cu3nta_BdUEB';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
