<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items - Inventory Management System</title>
</head>
<body>
    <h1>Items</h1>

    <?php
    require_once 'vendor/autoload.php';

    Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=warehouse', 'root', 'root'));

    // Retrieve items from the database
    $db = Flight::db();
    $stmt = $db->query('SELECT * FROM inventory');
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if items exist
    if (!empty($items)) {
        echo '<ul>';
        foreach ($items as $item) {
            echo '<li>';
            echo '<strong>Name:</strong> ' . $item['name'] . ', ';
            echo '<strong>Description:</strong> ' . $item['description'] . ', ';
            echo '<strong>Quantity:</strong> ' . $item['quantity'] . ', ';
            echo '<strong>Price:</strong> $' . $item['unitPrice'];
            echo '<strong>Supplier:</strong> ' . $item['supplier'] . ', ';
            echo '<strong>Category:</strong> ' . $item['category'] . ', ';
            echo '<strong>Manufacturer:</strong> ' . $item['manufacturer'] . ', ';
            echo '<strong>Voltage rating:</strong> ' . $item['voltageRating'] . ', ';
            echo '<strong>Amperage rating:</strong> ' . $item['amperageRating'] . ', ';
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No items found.</p>';
    }
    ?>

    <p><a href="items.php">Add New Item</a></p>
</body>
</html>