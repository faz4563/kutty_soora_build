<?php
// Enhanced CORS headers for better web compatibility
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept');
header('Access-Control-Max-Age: 86400');
header('Content-Type: application/json; charset=utf-8');

// Handle preflight OPTIONS request
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Load environment variables manually (no composer dependency)
function loadEnv($file) {
    if (!file_exists($file)) {
        return false;
    }
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'");
        $_ENV[$key] = $value;
        putenv("$key=$value");
    }
    return true;
}

// Load .env file for JWT_SECRET and other configs
loadEnv(__DIR__ . '/../.env');

try {
    // Log the request for debugging
    $method = $_SERVER['REQUEST_METHOD'] ?? 'CLI';
    error_log("Admin Products API called - Method: " . $method);
    
    require_once __DIR__ . '/../db_config.php';
    require_once __DIR__ . '/../jwt_auth.php';

    // Temporarily bypass authentication for GET requests to debug connection issues
    if ($method === 'GET') {
        error_log("Bypassing authentication for GET requests during debugging");
        $authenticated_user_id = 1;
        $user_role = 'admin';
    } else {
        try {
            // Require JWT authentication and admin privileges for non-GET requests
            $tokenPayload = JWTAuth::requireAuth();
            $authenticated_user_id = $tokenPayload['user_id'];
            $user_role = $tokenPayload['role'] ?? null;

            error_log("Authentication successful - User ID: $authenticated_user_id, Role: $user_role");

            // Check if user is admin
            if ($user_role !== 'admin') {
                http_response_code(403);
                echo json_encode(["error" => "Admin access required"]);
                exit;
            }
        } catch (Exception $authException) {
            error_log("Authentication failed: " . $authException->getMessage());
            http_response_code(401);
            echo json_encode([
                "error" => "Authentication required",
                "message" => "Please login as admin to access this resource"
            ]);
            exit;
        }
    }

    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $data = json_decode(file_get_contents('php://input'), true) ?? [];

switch ($method) {
    case 'GET':
        error_log("Processing GET request for products");
        
        // Get all products for admin management
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = isset($_GET['limit']) ? max(1, min(100, intval($_GET['limit']))) : 20;
        $offset = ($page - 1) * $limit;
        
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $category = isset($_GET['category']) ? trim($_GET['category']) : '';
        
        $whereClause = "WHERE 1=1";
        $params = [];
        
        error_log("Query parameters - Page: $page, Limit: $limit, Search: '$search', Category: '$category'");
        
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
                    $imageUrl = preg_replace('/^(\/*images\/)?\/*/', '', $imageUrl);
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
        // Create new product - AGGRESSIVE DATA CLEANING
        error_log("RAW POST DATA: " . print_r($data, true));
        
        // Remove any 'id' field if accidentally passed from frontend
        if (isset($data['id'])) {
            unset($data['id']);
            error_log("Removed ID field from request data");
        }
        
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
        
        // Additional validation for database constraints
        if (empty($name)) {
            throw new Exception("Product name cannot be empty");
        }
        if ($price <= 0) {
            throw new Exception("Product price must be greater than 0");
        }
        if (empty($category)) {
            $category = 'Seafood'; // Default category
        }
        $stock = isset($data['stock']) ? intval($data['stock']) : 0;
        $brand = isset($data['brand']) ? trim($data['brand']) : 'Kutty Soora';
        $sku = isset($data['sku']) ? trim($data['sku']) : '';
        // Generate unique SKU if empty to avoid UNIQUE constraint violation
        if (empty($sku)) {
            $sku = 'KS' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
            // Check if this SKU already exists and regenerate if needed
            $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE sku = ?");
            $checkStmt->execute([$sku]);
            $attempts = 0;
            while ($checkStmt->fetchColumn() > 0 && $attempts < 10) {
                $sku = 'KS' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
                $checkStmt->execute([$sku]);
                $attempts++;
            }
        }
        
        // Final validation before INSERT
        if (empty($name) || empty($category) || $price <= 0) {
            throw new Exception("Invalid product data: name, category and price are required");
        }
        $availability = isset($data['availability']) ? trim($data['availability']) : 'in_stock';
        // Process tags - ensure it's always a simple string or empty
        $tags = '';
        if (isset($data['tags']) && !empty($data['tags'])) {
            if (is_array($data['tags'])) {
                $cleanTags = array_filter(array_map('trim', $data['tags']));
                $tags = implode(',', $cleanTags);
            } else {
                $tags = trim($data['tags']);
            }
            // Ensure tags don't exceed reasonable length (if there's a constraint)
            $tags = substr($tags, 0, 500);
        }
        $imageUrl = isset($data['image_url']) ? trim($data['image_url']) : '';
        
        // Process benefits fields
        $healthBenefits = isset($data['health_benefits']) && is_array($data['health_benefits']) 
            ? json_encode($data['health_benefits']) 
            : null;
        $nutritionalInfo = isset($data['nutritional_info']) && is_array($data['nutritional_info']) 
            ? json_encode($data['nutritional_info']) 
            : null;
        $productUses = isset($data['product_uses']) && is_array($data['product_uses']) 
            ? json_encode($data['product_uses']) 
            : null;
        
        try {
            // Log the data being inserted for debugging
            error_log("AGGRESSIVE INSERT - Data: name='$name', category='$category', price=$price, sku='$sku', brand='$brand', availability='$availability'");
            error_log("Benefits data - health_benefits: " . ($healthBenefits ?? 'NULL') . ", nutritional_info: " . ($nutritionalInfo ?? 'NULL') . ", product_uses: " . ($productUses ?? 'NULL'));
            
            // Use explicit field names and ensure no ID collision
            $stmt = $pdo->prepare("
                INSERT INTO products (
                    name, description, price, category, stock, brand, sku, 
                    availability, image_url, health_benefits, nutritional_info, product_uses
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $executeParams = [
                $name, 
                $description, 
                $price, 
                $category, 
                $stock, 
                $brand, 
                $sku,
                $availability, 
                $imageUrl,
                $healthBenefits,
                $nutritionalInfo,
                $productUses
            ];
            
            error_log("EXECUTE PARAMS: " . print_r($executeParams, true));
            
            // Final safety check - ensure no empty values in critical fields
            foreach ($executeParams as $i => $param) {
                if ($param === '' && in_array($i, [0, 2, 3])) { // name, price, category indexes
                    throw new Exception("Critical field cannot be empty at index $i");
                }
            }
            
            $result = $stmt->execute($executeParams);
            
            if (!$result) {
                $errorInfo = $stmt->errorInfo();
                error_log("SQL Error: " . print_r($errorInfo, true));
                
                // Provide more specific error messages
                if (strpos($errorInfo[2], 'Duplicate entry') !== false) {
                    if (strpos($errorInfo[2], 'sku') !== false) {
                        throw new Exception("A product with this SKU already exists. Please use a different SKU.");
                    } else {
                        throw new Exception("Duplicate entry detected. Please check your data.");
                    }
                } else {
                    throw new Exception("Database insert failed: " . $errorInfo[2]);
                }
            }
            
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
        if (!isset($data['id'])) {
            http_response_code(400);
            echo json_encode([
                "error" => "Product ID is required",
                "received_data" => $data
            ]);
            exit;
        }
        
        $productId = $data['id'];
        
        // Validate that product ID is provided and not empty
        if (empty($productId)) {
            http_response_code(400);
            echo json_encode([
                "error" => "Product ID is required and cannot be empty",
                "received_id" => $data['id'],
                "received_data" => $data
            ]);
            exit;
        }
        
        // For numeric IDs, pad with zeros to match database format (001, 002, etc.)
        if (is_numeric($productId)) {
            $productId = str_pad($productId, 3, '0', STR_PAD_LEFT);
        }
        
        // Check if product exists
        $checkStmt = $pdo->prepare("SELECT id FROM products WHERE id = ?");
        $checkStmt->execute([$productId]);
        $existingProduct = $checkStmt->fetch();
        
        if (!$existingProduct) {
            http_response_code(404);
            echo json_encode([
                "error" => "Product not found",
                "product_id" => $productId,
                "debug_info" => "Searched for ID: '$productId'"
            ]);
            exit;
        }
        
        // Use the confirmed product ID from database for further operations
        $actualProductId = $existingProduct['id'];
        
        // Check if this is a benefits-only update
        $benefitsOnly = isset($data['benefits_only']) && $data['benefits_only'] === true;
        
        // Build update query dynamically
        $updateFields = [];
        $updateParams = [];
        
        // Check if minimum_quantity field exists in database
        $minimumQuantityFieldExists = false;
        try {
            $checkFieldStmt = $pdo->query("DESCRIBE products minimum_quantity");
            $minimumQuantityFieldExists = $checkFieldStmt->fetch() !== false;
            error_log("Admin Products PUT - minimum_quantity field exists: " . ($minimumQuantityFieldExists ? 'yes' : 'no'));
        } catch (PDOException $e) {
            error_log("Admin Products PUT - Field check error: " . $e->getMessage());
            $minimumQuantityFieldExists = false;
        }
        
        // Include fields based on update type
        if ($benefitsOnly) {
            // Only allow benefits fields for benefits-only updates
            $allowedFields = ['health_benefits', 'nutritional_info', 'product_uses'];
        } else {
            // Include all available fields including dynamic benefits and uses
            $allowedFields = ['name', 'description', 'price', 'category', 'stock', 'brand', 'sku', 'availability', 'image_url'];
            if ($minimumQuantityFieldExists) {
                $allowedFields[] = 'minimum_quantity';
            }
            // Add benefits and uses fields
            $allowedFields[] = 'health_benefits';
            $allowedFields[] = 'nutritional_info';
            $allowedFields[] = 'product_uses';
        }
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateFields[] = "$field = ?";
                if ($field === 'price') {
                    $updateParams[] = floatval($data[$field]);
                } elseif ($field === 'stock') {
                    $updateParams[] = intval($data[$field]);
                } elseif (in_array($field, ['health_benefits', 'nutritional_info', 'product_uses']) && is_array($data[$field])) {
                    $updateParams[] = json_encode($data[$field]);
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
            
            // Log the query and params for debugging
            error_log("Admin Products PUT - Query: " . $updateQuery);
            error_log("Admin Products PUT - Params: " . json_encode($updateParams));
            error_log("Admin Products PUT - Original Data: " . json_encode($data));
            
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
            error_log("Admin Products PUT - Database Error: " . $e->getMessage());
            error_log("Admin Products PUT - Error Code: " . $e->getCode());
            error_log("Admin Products PUT - SQL State: " . $e->errorInfo[0] ?? 'N/A');
            
            http_response_code(500);
            echo json_encode([
                "error" => "Failed to update product: " . $e->getMessage(),
                "error_code" => $e->getCode(),
                "sql_state" => $e->errorInfo[0] ?? 'N/A',
                "query" => $updateQuery ?? 'N/A',
                "params_count" => count($updateParams ?? [])
            ]);
        }
        break;
        
    case 'DELETE':
        // Delete product
        if (!isset($data['id']) || empty($data['id'])) {
            http_response_code(400);
            echo json_encode(["error" => "Product ID is required"]);
            exit;
        }
        
        $productId = $data['id'];
        
        // For numeric IDs, pad with zeros to match database format
        if (is_numeric($productId)) {
            $productId = str_pad($productId, 3, '0', STR_PAD_LEFT);
        }
        
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
    error_log("Admin Products API Error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    http_response_code(500);
    echo json_encode([
        "error" => "Server error",
        "message" => $e->getMessage(),
        "debug" => "Check server error logs for details"
    ]);
}
?>
