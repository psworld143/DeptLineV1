<?php
require_once 'config/database.php';

// Generate unique tracking number
function generateTrackingNumber() {
    $prefix = 'DL';
    $year = date('Y');
    $random = strtoupper(substr(md5(uniqid()), 0, 6));
    return $prefix . $year . $random;
}

// Get all departments
function getDepartments() {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT * FROM departments ORDER BY name");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get categories by department
function getCategoriesByDepartment($department_id) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE department_id = ? ORDER BY name");
    $stmt->execute([$department_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get all categories
function getAllCategories() {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT c.*, d.name as department_name FROM categories c 
                        LEFT JOIN departments d ON c.department_id = d.id 
                        ORDER BY d.name, c.name");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Submit new concern
function submitConcern($data) {
    $pdo = getDBConnection();
    
    $tracking_number = generateTrackingNumber();
    
    $sql = "INSERT INTO concerns (tracking_number, title, description, category_id, department_id, 
            student_name, student_email, student_id, is_anonymous, priority_level) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        $tracking_number,
        $data['title'],
        $data['description'],
        $data['category_id'],
        $data['department_id'],
        $data['student_name'],
        $data['student_email'],
        $data['student_id'],
        $data['is_anonymous'] ? 1 : 0,
        $data['priority_level']
    ]);
    
    if ($result) {
        return $tracking_number;
    }
    return false;
}

// Get concern by tracking number
function getConcernByTracking($tracking_number) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT c.*, cat.name as category_name, d.name as department_name 
                          FROM concerns c 
                          LEFT JOIN categories cat ON c.category_id = cat.id 
                          LEFT JOIN departments d ON c.department_id = d.id 
                          WHERE c.tracking_number = ?");
    $stmt->execute([$tracking_number]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get concerns for department dashboard
function getConcernsForDepartment($department_id = null, $status = null, $priority = null) {
    $pdo = getDBConnection();
    
    $sql = "SELECT c.*, cat.name as category_name, d.name as department_name 
            FROM concerns c 
            LEFT JOIN categories cat ON c.category_id = cat.id 
            LEFT JOIN departments d ON c.department_id = d.id 
            WHERE 1=1";
    
    $params = [];
    
    if ($department_id) {
        $sql .= " AND c.department_id = ?";
        $params[] = $department_id;
    }
    
    if ($status) {
        $sql .= " AND c.status = ?";
        $params[] = $status;
    }
    
    if ($priority) {
        $sql .= " AND c.priority_level = ?";
        $params[] = $priority;
    }
    
    $sql .= " ORDER BY c.priority_level DESC, c.submitted_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Update concern status
function updateConcernStatus($concern_id, $status) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("UPDATE concerns SET status = ? WHERE id = ?");
    return $stmt->execute([$status, $concern_id]);
}

// Add response to concern
function addResponse($concern_id, $response_text, $responded_by) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("INSERT INTO responses (concern_id, response_text, responded_by) VALUES (?, ?, ?)");
    return $stmt->execute([$concern_id, $response_text, $responded_by]);
}

// Get responses for a concern
function getResponses($concern_id) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM responses WHERE concern_id = ? ORDER BY responded_at ASC");
    $stmt->execute([$concern_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get dashboard statistics
function getDashboardStats($department_id = null) {
    $pdo = getDBConnection();
    
    $where_clause = $department_id ? "WHERE department_id = ?" : "";
    $params = $department_id ? [$department_id] : [];
    
    // Total concerns
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM concerns $where_clause");
    $stmt->execute($params);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Pending concerns
    $stmt = $pdo->prepare("SELECT COUNT(*) as pending FROM concerns $where_clause AND status = 'pending'");
    $stmt->execute($params);
    $pending = $stmt->fetch(PDO::FETCH_ASSOC)['pending'];
    
    // In progress concerns
    $stmt = $pdo->prepare("SELECT COUNT(*) as in_progress FROM concerns $where_clause AND status = 'in_progress'");
    $stmt->execute($params);
    $in_progress = $stmt->fetch(PDO::FETCH_ASSOC)['in_progress'];
    
    // Resolved concerns
    $stmt = $pdo->prepare("SELECT COUNT(*) as resolved FROM concerns $where_clause AND status = 'resolved'");
    $stmt->execute($params);
    $resolved = $stmt->fetch(PDO::FETCH_ASSOC)['resolved'];
    
    // Urgent concerns
    $stmt = $pdo->prepare("SELECT COUNT(*) as urgent FROM concerns $where_clause AND priority_level = 'urgent'");
    $stmt->execute($params);
    $urgent = $stmt->fetch(PDO::FETCH_ASSOC)['urgent'];
    
    return [
        'total' => $total,
        'pending' => $pending,
        'in_progress' => $in_progress,
        'resolved' => $resolved,
        'urgent' => $urgent
    ];
}

// Validate email format
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Sanitize input
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Get priority color class
function getPriorityColor($priority) {
    switch ($priority) {
        case 'urgent':
            return 'text-red-600 bg-red-100';
        case 'high':
            return 'text-orange-600 bg-orange-100';
        case 'medium':
            return 'text-yellow-600 bg-yellow-100';
        case 'low':
            return 'text-green-600 bg-green-100';
        default:
            return 'text-gray-600 bg-gray-100';
    }
}

// Get status color class
function getStatusColor($status) {
    switch ($status) {
        case 'pending':
            return 'text-yellow-600 bg-yellow-100';
        case 'in_progress':
            return 'text-blue-600 bg-blue-100';
        case 'resolved':
            return 'text-green-600 bg-green-100';
        case 'closed':
            return 'text-gray-600 bg-gray-100';
        default:
            return 'text-gray-600 bg-gray-100';
    }
}
?>
