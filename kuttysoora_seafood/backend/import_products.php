<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    // Read the products.json file
    $jsonFile = '../products.json';
    
    if (!file_exists($jsonFile)) {
        throw new Exception("products.json file not found");
    }
    
    $jsonContent = file_get_contents($jsonFile);
    $data = json_decode($jsonContent, true);
    
    if (!isset($data['products']) || !is_array($data['products'])) {
        throw new Exception("Invalid JSON structure");
    }
    
    $products = $data['products'];
    $imported = 0;
    $updated = 0;
    $errors = [];
    
    foreach ($products as $product) {
        try {
            // Extract product data
            $id = $product['id'];
            $name = $product['name'];
            $description = $product['description'];
            $price = $product['price'];
            $category = $product['category'];
            $brand = $product['brand'];
            $sku = $product['sku'];
            $availability = $product['availability'];
            
            // Extract specifications
            $weight = isset($product['specifications']['weight']) ? $product['specifications']['weight'] : '';
            $dimensions = isset($product['specifications']['dimensions']) ? $product['specifications']['dimensions'] : '';
            $material = isset($product['specifications']['material']) ? $product['specifications']['material'] : '';
            $color = isset($product['specifications']['color']) ? $product['specifications']['color'] : '';
            
            // Convert arrays to comma-separated strings
            $images = isset($product['images']) ? implode(',', $product['images']) : '';
            $tags = isset($product['tags']) ? implode(',', $product['tags']) : '';
            
            // Get primary image URL
            $image_url = isset($product['images'][0]) ? $product['images'][0] : '';
            
            // Date fields
            $created_date = $product['created_date'];
            $last_updated = $product['last_updated'];
            
            // Set default stock - in_stock products get 100, out_of_stock get 0
            $stock = ($availability === 'in_stock') ? 100 : 0;
            
            // Check if product with this SKU already exists
            $checkStmt = $pdo->prepare("SELECT id FROM products WHERE sku = ?");
            $checkStmt->execute([$sku]);
            
            if ($checkStmt->rowCount() > 0) {
                // Update existing product
                $sql = "UPDATE products SET 
                        name = ?,
                        description = ?,
                        price = ?,
                        category = ?,
                        brand = ?,
                        availability = ?,
                        weight = ?,
                        dimensions = ?,
                        material = ?,
                        color = ?,
                        images = ?,
                        tags = ?,
                        image_url = ?,
                        stock = ?,
                        created_date = ?,
                        last_updated = ?
                        WHERE sku = ?";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $name,
                    $description,
                    $price,
                    $category,
                    $brand,
                    $availability,
                    $weight,
                    $dimensions,
                    $material,
                    $color,
                    $images,
                    $tags,
                    $image_url,
                    $stock,
                    $created_date,
                    $last_updated,
                    $sku
                ]);
                
                $updated++;
            } else {
                // Insert new product
                $sql = "INSERT INTO products 
                        (name, description, price, category, brand, sku, availability, 
                         weight, dimensions, material, color, images, tags, image_url, 
                         stock, created_date, last_updated) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $name,
                    $description,
                    $price,
                    $category,
                    $brand,
                    $sku,
                    $availability,
                    $weight,
                    $dimensions,
                    $material,
                    $color,
                    $images,
                    $tags,
                    $image_url,
                    $stock,
                    $created_date,
                    $last_updated
                ]);
                
                $imported++;
            }
            
        } catch (Exception $e) {
            $errors[] = "Error processing product: " . $e->getMessage();
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Products import completed',
        'imported' => $imported,
        'updated' => $updated,
        'total_processed' => count($products),
        'errors' => $errors
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
