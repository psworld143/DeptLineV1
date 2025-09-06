<?php
require_once '../includes/functions.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $concern_id = (int)$_GET['id'];
    
    // Get concern details
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT c.*, cat.name as category_name, d.name as department_name 
                          FROM concerns c 
                          LEFT JOIN categories cat ON c.category_id = cat.id 
                          LEFT JOIN departments d ON c.department_id = d.id 
                          WHERE c.id = ?");
    $stmt->execute([$concern_id]);
    $concern = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($concern) {
        $responses = getResponses($concern_id);
        ?>
        <div class="flex justify-between items-start mb-4">
            <h3 class="text-lg font-medium text-gray-900"><?php echo htmlspecialchars($concern['title']); ?></h3>
            <button onclick="document.getElementById('viewModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="space-y-4">
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Tracking Number</p>
                    <p class="font-mono font-semibold"><?php echo htmlspecialchars($concern['tracking_number']); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Status</p>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?php echo getStatusColor($concern['status']); ?>">
                        <?php echo ucfirst(str_replace('_', ' ', $concern['status'])); ?>
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Department</p>
                    <p class="font-semibold"><?php echo htmlspecialchars($concern['department_name']); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Category</p>
                    <p class="font-semibold"><?php echo htmlspecialchars($concern['category_name']); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Priority</p>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?php echo getPriorityColor($concern['priority_level']); ?>">
                        <?php echo ucfirst($concern['priority_level']); ?>
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Submitted</p>
                    <p><?php echo date('F j, Y \a\t g:i A', strtotime($concern['submitted_at'])); ?></p>
                </div>
            </div>
            
            <?php if (!$concern['is_anonymous']): ?>
            <div>
                <p class="text-sm text-gray-600">Student Information</p>
                <div class="grid md:grid-cols-2 gap-4 mt-2">
                    <div>
                        <p class="text-sm text-gray-600">Name</p>
                        <p class="font-semibold"><?php echo htmlspecialchars($concern['student_name']); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold"><?php echo htmlspecialchars($concern['student_email']); ?></p>
                    </div>
                    <?php if (!empty($concern['student_id'])): ?>
                    <div>
                        <p class="text-sm text-gray-600">Student ID</p>
                        <p class="font-semibold"><?php echo htmlspecialchars($concern['student_id']); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php else: ?>
            <div>
                <p class="text-sm text-gray-600">Student Information</p>
                <p class="text-sm text-gray-500"><i class="fas fa-user-secret mr-1"></i>Submitted anonymously</p>
            </div>
            <?php endif; ?>
            
            <div>
                <p class="text-sm text-gray-600">Description</p>
                <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                    <p class="text-gray-700 whitespace-pre-wrap"><?php echo htmlspecialchars($concern['description']); ?></p>
                </div>
            </div>
            
            <?php if (!empty($responses)): ?>
            <div>
                <p class="text-sm text-gray-600 mb-2">Department Responses</p>
                <div class="space-y-3">
                    <?php foreach ($responses as $response): ?>
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-3 rounded-r-lg">
                        <div class="flex justify-between items-start mb-1">
                            <p class="font-semibold text-blue-800 text-sm"><?php echo htmlspecialchars($response['responded_by']); ?></p>
                            <p class="text-xs text-blue-600"><?php echo date('M j, Y g:i A', strtotime($response['responded_at'])); ?></p>
                        </div>
                        <p class="text-blue-700 text-sm whitespace-pre-wrap"><?php echo htmlspecialchars($response['response_text']); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="mt-6 flex justify-end">
            <button onclick="updateStatus(<?php echo $concern['id']; ?>, '<?php echo $concern['status']; ?>')" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300">
                <i class="fas fa-edit mr-2"></i>Update Status
            </button>
        </div>
        <?php
    } else {
        echo '<p class="text-red-600">Concern not found.</p>';
    }
} else {
    echo '<p class="text-red-600">Invalid concern ID.</p>';
}
?>
