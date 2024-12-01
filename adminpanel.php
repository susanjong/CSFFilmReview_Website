<?php
include __DIR__. "/Handling/database.php";

// Define the base directory for your film folders

try {
    // Establish the connection
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname;", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to find all tables with a user_id foreign key
    $query = "SELECT table_name 
              FROM information_schema.columns
              WHERE column_name = 'user_id'
              AND table_schema = 'public'";  // Modify schema if needed

    // Execute the query
    $stmt = $pdo->query($query);
    $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Start building the final query for selecting from all tables with user_id foreign key
    $final_query = "";
    foreach ($tables as $table) {
        $table_name = $table['table_name'];
        // Construct the query for each table
        $final_query .= "SELECT * FROM $table_name JOIN users ON $table_name.user_id = users.id UNION ";
    }

    // Remove the trailing UNION
    $final_query = rtrim($final_query, " UNION ");

    // Execute the final query and fetch results
    $stmt = $pdo->query($final_query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!-- Display the results in a table -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - User Data</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="adminpanel.css">
</head>
<body>
    <div class="container mt-5">
        <!-- Go to Film Button -->
        <div class="text-center mb-4">
            <a href="/PROJEK%20AKHIR_PEMWEB/PROJEK%20PEMWEB%20AKHIR/tampilan%20awal/film.html" class="btn btn-primary">Go to Homepage</a>
        </div>

        <h1 class="text-center mb-4 text-white">User Data from All Tables</h1>
        
        <?php if ($results && count($results) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <!-- Dynamically generate table headers based on result keys -->
                            <?php foreach ($results[0] as $key => $value): ?>
                                <th><?php echo htmlspecialchars($key); ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Display the fetched data -->
                        <?php foreach ($results as $row): ?>
                            <tr>
                                <?php foreach ($row as $value): ?>
                                    <td><?php echo htmlspecialchars($value); ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-warning" role="alert">
                No data found.
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS (Optional, for functionality like modals, tooltips) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>