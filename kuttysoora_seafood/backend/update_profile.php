<?php
// Update user profile
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'db_config.php';
require_once 'jwt_auth.php';

// Validate JWT token and get user info
$tokenPayload = JWTAuth::requireAuth();
$user_id = $tokenPayload['user_id'];

$data = json_decode(file_get_contents('php://input'), true);

// Get profile data
$name = isset($data['name']) ? trim($data['name']) : '';
$email = isset($data['email']) ? trim($data['email']) : '';
$house = isset($data['house']) ? trim($data['house']) : '';
$street = isset($data['street']) ? trim($data['street']) : '';
$area = isset($data['area']) ? trim($data['area']) : '';
$city = isset($data['city']) ? trim($data['city']) : '';
$pin_code = isset($data['pin_code']) ? trim($data['pin_code']) : '';
$landmark = isset($data['landmark']) ? trim($data['landmark']) : '';
$referral = isset($data['referral']) ? trim($data['referral']) : '';

// Validate required fields
if (empty($name)) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Name is required"]);
    exit;
}

if (empty($house)) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "House/Flat number is required"]);
    exit;
}

if (empty($street)) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Street name is required"]);
    exit;
}

if (empty($area)) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Area is required"]);
    exit;
}

if (empty($city)) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "City is required"]);
    exit;
}

if (empty($pin_code)) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "PIN code is required"]);
    exit;
}

// Validate PIN code format
if (!preg_match('/^\d{6}$/', $pin_code)) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "PIN code must be 6 digits"]);
    exit;
}

// Validate email if provided
if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Invalid email format"]);
    exit;
}

try {
    // Build full address
    $full_address = trim("$house, $street, $area, $city - $pin_code");
    if (!empty($landmark)) {
        $full_address .= " (Near $landmark)";
    }
    
    // Update user profile
    $stmt = $pdo->prepare("UPDATE users SET 
        name = ?, 
        email = ?, 
        house = ?, 
        street = ?, 
        area = ?, 
        city = ?, 
        pin_code = ?, 
        landmark = ?, 
        referral = ?,
        address = ?,
        updated_at = CURRENT_TIMESTAMP
        WHERE id = ?");
    
    $stmt->execute([
        $name,
        $email,
        $house,
        $street,
        $area,
        $city,
        $pin_code,
        $landmark,
        $referral,
        $full_address,
        $user_id
    ]);
    
    // Get updated user data
    $stmt = $pdo->prepare("SELECT id, name, email, mobile, role, house, street, area, city, pin_code, landmark, referral, address, created_at FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    echo json_encode([
        "success" => true,
        "message" => "Profile updated successfully",
        "user" => $user
    ]);
    
} catch (PDOException $e) {
    error_log("Update profile error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "Failed to update profile"]);
}
?>
