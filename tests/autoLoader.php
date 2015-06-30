<?php

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

// Name of the file
$filename = 'sql/captcha.sql';
// MySQL host
$db_host = 'localhost';
// MySQL username
$db_user = 'root';
// MySQL password
$db_pass = '';
// Database name
$db_database = 'captcha_dev';

$hostString = "mysql:host={$db_host};dbname=captcha_dev;charset=utf8";
$pdo = new PDO($hostString, $db_user,$db_pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

try {
    $pdo->exec("drop database IF EXISTS $db_database");

} catch (PDOException $ex) {
    print('Error performing query 1: ' . $ex->getMessage() . "\r\n\r\n");
    exit(1);
}

try {
    $pdo->exec("create database IF NOT EXISTS $db_database");

} catch (PDOException $ex) {
    print('Error performing query 2: ' . $ex->getMessage() . "\r\n\r\n");
    exit(1);
}

try {
    $pdo->exec("USE $db_database");
    unset($query);

} catch (PDOException $ex) {
    print('Error performing query 3: ' . $ex->getMessage() . "\r\n\r\n");
    exit(1);
}

// Temporary variable, used to store current query
$templine = '';
// Read in entire file
$lines = file($filename);
// Loop through each line
foreach ($lines as $line)
{
// Skip it if it's a comment
    if (substr($line, 0, 2) == '--' || $line == '')
        continue;

// Add this line to the current segment
    $templine .= $line;
// If it has a semicolon at the end, it's the end of the query
    if (substr(trim($line), -1, 1) == ';')
    {
        try {
            // Perform the query
            $query = $pdo->prepare($templine);
            $query->execute();
        } catch (PDOException $ex) {
            print('Error performing query on file \'' . $templine . '\': ' . $ex->getMessage() . "\r\n\r\n");
            exit(1);
        }
        // Reset temp variable to empty
        $templine = '';
    }
}
echo "Tables imported successfully\r\n\r\n";

$GLOBALS["db_database"] = $db_database;
?>