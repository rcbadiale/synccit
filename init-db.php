<?php
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$name = getenv('DB_NAME') ?: 'rddtsync';

echo "Waiting for database...\n";
$conn = null;
for ($i = 0; $i < 30; $i++) {
    $conn = @new mysqli($host, $user, $pass, $name);
    if (!$conn->connect_error) {
        break;
    }
    sleep(2);
}

if ($conn->connect_error) {
    fwrite(STDERR, "Failed to connect to database: " . $conn->connect_error . "\n");
    exit(1);
}

echo "Initializing schema...\n";

$sqlFiles = [
    '/var/www/html/mysql.sql',
    '/var/www/html/api/apps.sql',
];

foreach ($sqlFiles as $file) {
    $sql = file_get_contents($file);
    if ($conn->multi_query($sql)) {
        do {
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->more_results() && $conn->next_result());
    }
    if ($conn->errno) {
        fwrite(STDERR, "Error applying $file: " . $conn->error . "\n");
        exit(1);
    }
    echo "Applied: $file\n";
}

// Run migrations
$migrations = [
    "ALTER TABLE `links` MODIFY `lastcommentcount` INT(6) NOT NULL DEFAULT -1",
];

foreach ($migrations as $sql) {
    if (!$conn->query($sql)) {
        fwrite(STDERR, "Migration failed: " . $conn->error . "\n");
        exit(1);
    }
}
echo "Migrations applied.\n";

echo "Schema ready.\n";
$conn->close();
