<?php
header('Content-Type: application/json');
require_once '../includes/functions.php';

if (isset($_GET['department_id']) && is_numeric($_GET['department_id'])) {
    $department_id = (int)$_GET['department_id'];
    $categories = getCategoriesByDepartment($department_id);
    
    echo json_encode($categories);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid department ID']);
}
?>
