<?php
header('Content-Type: application/json');
$servername = "sql12.freesqldatabase.com";
$username = "sql12798576";
$password = "WThGsDbZxH";
$dbname = "sql12798576";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Insert product
    $input = json_decode(file_get_contents('php://input'), true);
    $name = $conn->real_escape_string($input['name']);
    $price = floatval($input['price']);
    $description = isset($input['description']) ? $conn->real_escape_string($input['description']) : '';
    $image_url = $conn->real_escape_string($input['image_url']);
    $barcode_data = $conn->real_escape_string($input['barcode_data']);
    
    $sql = "INSERT INTO products (name, price, description, image_url, barcode_data) VALUES ('$name', $price, '$description', '$image_url', '$barcode_data')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'id' => $conn->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Insert failed: ' . $conn->error]);
    }
}

elseif ($method === 'GET') {
    // Get product by barcode (from query string: ?barcode=xxx)
    if (!isset($_GET['barcode'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Barcode is required']);
        exit;
    }
    $barcode = $conn->real_escape_string($_GET['barcode']);
    $sql = "SELECT id, name, price, description, image_url, barcode_data FROM products WHERE barcode_data = '$barcode' LIMIT 1";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Product not found']);
    }
}

else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}

$conn->close();
?>
