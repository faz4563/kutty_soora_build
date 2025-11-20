<?php
// Enhanced CORS headers for better web compatibility
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept');
header('Access-Control-Max-Age: 86400');
header('Content-Type: application/json; charset=utf-8');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

try {
    require_once '../db_config.php';
    require_once '../jwt_auth.php';

    // Require JWT authentication and admin privileges
    $tokenPayload = JWTAuth::requireAuth();
    $authenticated_user_id = $tokenPayload['user_id'];
    $user_role = $tokenPayload['role'] ?? null;

    // Check if user is admin
    if ($user_role !== 'admin') {
        http_response_code(403);
        echo json_encode(["error" => "Admin access required"]);
        exit;
    }

    $method = $_SERVER['REQUEST_METHOD'];
    $data = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        // Get all products for admin management
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = isset($_GET['limit']) ? max(1, min(100, intval($_GET['limit']))) : 20;
        $offset = ($page - 1) * $limit;
        
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $category = isset($_GET['category']) ? trim($_GET['category']) : '';
        
        $whereClause = "WHERE 1=1";
        $params = [];
        
        if (!empty($search)) {
            $whereClause .= " AND (name LIKE ? OR description LIKE ? OR category LIKE ?)";
            $searchTerm = "%$search%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
        }
        
        if (!empty($category)) {
            $whereClause .= " AND category = ?";
            $params[] = $category;
        }
        
        // Get total count
        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM products $whereClause");
        $countStmt->execute($params);
        $totalProducts = $countStmt->fetchColumn();
        
        // Get products
        $stmt = $pdo->prepare("SELECT * FROM products $whereClause ORDER BY id DESC LIMIT ? OFFSET ?");
        $stmt->execute(array_merge($params, [$limit, $offset]));
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Ensure proper type casting for numeric fields and format image_url
        foreach ($products as &$product) {
            $product['id'] = (int)$product['id'];
            $product['price'] = (float)$product['price'];
            $product['stock'] = (int)$product['stock'];
            
            // Format image_url with full backend/images/ path if it's a relative path
            if (isset($product['image_url']) && !empty($product['image_url'])) {
                $imageUrl = trim($product['image_url']);
                if (!preg_match('/^https?:\/\//', $imageUrl)) {
                    // Remove any leading slashes or 'images/' prefix
                    $imageUrl = preg_replace('/^(images\/)?/', '', $imageUrl);
                    $product['image_url'] = 'https://kuttysoora.com/kuttysoora_seafood/backend/images/' . $imageUrl;
                } else {
                    $product['image_url'] = $imageUrl;
                }
            } else {
                $product['image_url'] = '';
            }
        }
        
        // Get categories for filter
        $categoriesStmt = $pdo->query("SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != '' ORDER BY category");
        $categories = $categoriesStmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo json_encode([
            "products" => $products,
            "pagination" => [
                "page" => $page,
                "limit" => $limit,
                "total" => $totalProducts,
                "pages" => ceil($totalProducts / $limit)
            ],
            "categories" => $categories
        ]);
        break;
        
    case 'POST':
        // Create new product
        $requiredFields = ['name', 'description', 'price', 'category'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || trim($data[$field]) === '') {
                http_response_code(400);
                echo json_encode(["error" => "Field '$field' is required"]);
                exit;
            }
        }
        
        $name = trim($data['name']);
        $description = trim($data['description']);
        $price = floatval($data['price']);
        $category = trim($data['category']);
        $stock = isset($data['stock']) ? intval($data['stock']) : 0;
        $brand = isset($data['brand']) ? trim($data['brand']) : 'Kutty Soora';
        $sku = isset($data['sku']) ? trim($data['sku']) : '';
        $availability = isset($data['availability']) ? trim($data['availability']) : 'in_stock';
        $tags = isset($data['tags']) ? implode(',', $data['tags']) : '';
        $imageUrl = isset($data['image_url']) ? trim($data['image_url']) : '';
        
        try {
            $stmt = $pdo->prepare("
                INSERT INTO products (
                    name, description, price, category, stock, brand, sku, 
                    availability, tags, image_url
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $name, $description, $price, $category, $stock, $brand, $sku,
                $availability, $tags, $imageUrl
            ]);
            
            $productId = $pdo->lastInsertId();
            
            // Get the created product
            $getStmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $getStmt->execute([$productId]);
            $product = $getStmt->fetch(PDO::FETCH_ASSOC);
            
            // Ensure proper type casting and format image_url
            if ($product) {
                $product['id'] = (int)$product['id'];
                $product['price'] = (float)$product['price'];
                $product['stock'] = (int)$product['stock'];
                
                // Format image_url with full backend/images/ path if it's a relative path
                if (isset($product['image_url']) && !empty($product['image_url'])) {
                    $imageUrl = trim($product['image_url']);
                    if (!preg_match('/^https?:\/\//', $imageUrl)) {
                        // Remove any leading slashes or 'images/' prefix
                        $imageUrl = preg_replace('/^(images\/)?/', '', $imageUrl);
                        $product['image_url'] = 'https://kuttysoora.com/kuttysoora_seafood/backend/images/' . $imageUrl;
                    } else {
                        $product['image_url'] = $imageUrl;
                    }
                } else {
                    $product['image_url'] = '';
                }
            }
            
            echo json_encode([
                "message" => "Product created successfully",
                "product" => $product
            ]);
            
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["error" => "Failed to create product: " . $e->getMessage()]);
        }
        break;
        
    case 'PUT':
        // Update existing product
        if (!isset($data['id']) || !intval($data['id'])) {
            http_response_code(400);
            echo json_encode([
                "error" => "Product ID is required",
                "received_data" => $data
            ]);
            exit;
        }
        
        // Handle both string and integer IDs
        $productId = $data['id'];
        $productIdInt = intval($productId);
        $productIdStr = str_pad($productIdInt, 3, '0', STR_PAD_LEFT); // Convert 46 to "046"
        
        // Check if product exists (try both formats)
        $checkStmt = $pdo->prepare("SELECT id FROM products WHERE id = ? OR id = ?");
        $checkStmt->execute([$productId, $productIdStr]);
        $existingProduct = $checkStmt->fetch();
        
        if (!$existingProduct) {
            http_response_code(404);
            echo json_encode([
                "error" => "Product not found",
                "product_id" => $productId,
                "searched_formats" => [$productId, $productIdStr],
                "query" => "Searched for product with id: " . $productId . " and " . $productIdStr
            ]);
            exit;
        }
        
        // Use the actual ID from database for further operations
        $actualProductId = $existingProduct['id'];
        
        // Build update query dynamically
        $updateFields = [];
        $updateParams = [];
        
        $allowedFields = ['name', 'description', 'price', 'category', 'stock', 'brand', 'sku', 'availability', 'tags', 'image_url'];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateFields[] = "$field = ?";
                if ($field === 'tags' && is_array($data[$field])) {
                    $updateParams[] = implode(',', $data[$field]);
                } elseif ($field === 'price') {
                    $updateParams[] = floatval($data[$field]);
                } elseif ($field === 'stock') {
                    $updateParams[] = intval($data[$field]);
                } else {
                    $updateParams[] = trim($data[$field]);
                }
            }
        }
        
        if (empty($updateFields)) {
            http_response_code(400);
            echo json_encode(["error" => "No valid fields to update"]);
            exit;
        }
        
        $updateParams[] = $actualProductId;
        
        try {
            $updateQuery = "UPDATE products SET " . implode(', ', $updateFields) . " WHERE id = ?";
            $stmt = $pdo->prepare($updateQuery);
            $stmt->execute($updateParams);
            
            // Get the updated product
            $getStmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $getStmt->execute([$actualProductId]);
            $product = $getStmt->fetch(PDO::FETCH_ASSOC);
            
            // Ensure proper type casting and format image_url
            if ($product) {
                $product['id'] = (int)$product['id'];
                $product['price'] = (float)$product['price'];
                $product['stock'] = (int)$product['stock'];
                
                // Format image_url with full backend/images/ path if it's a relative path
                if (isset($product['image_url']) && !empty($product['image_url'])) {
                    $imageUrl = trim($product['image_url']);
                    if (!preg_match('/^https?:\/\//', $imageUrl)) {
                        // Remove any leading slashes or 'images/' prefix
                        $imageUrl = preg_replace('/^(images\/)?/', '', $imageUrl);
                        $product['image_url'] = 'https://kuttysoora.com/kuttysoora_seafood/backend/images/' . $imageUrl;
                    } else {
                        $product['image_url'] = $imageUrl;
                    }
                } else {
                    $product['image_url'] = '';
                }
            }
            
            echo json_encode([
                "message" => "Product updated successfully",
                "product" => $product
            ]);
            
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["error" => "Failed to update product: " . $e->getMessage()]);
        }
        break;
        
    case 'DELETE':
        // Delete product
        if (!isset($data['id']) || !intval($data['id'])) {
            http_response_code(400);
            echo json_encode(["error" => "Product ID is required"]);
            exit;
        }
        
        $productId = intval($data['id']);
        
        try {
            // Check if product exists and get image info for cleanup
            $checkStmt = $pdo->prepare("SELECT id, image_url FROM products WHERE id = ?");
            $checkStmt->execute([$productId]);
            $product = $checkStmt->fetch();
            
            if (!$product) {
                http_response_code(404);
                echo json_encode(["error" => "Product not found"]);
                exit;
            }
            
            // Delete the product
            $deleteStmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $deleteStmt->execute([$productId]);
            
            // TODO: Clean up image file if needed
            // if ($product['image_url']) {
            //     $imagePath = '../images/' . basename($product['image_url']);
            //     if (file_exists($imagePath)) {
            //         unlink($imagePath);
            //     }
            // }
            
            echo json_encode(["message" => "Product deleted successfully"]);
            
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["error" => "Failed to delete product: " . $e->getMessage()]);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
        break;
}

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Server error",
        "message" => $e->getMessage()
    ]);
}
?>