<?php
require_once 'includes/functions.php';

$concern = null;
$responses = [];
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tracking_number'])) {
    $tracking_number = sanitizeInput($_POST['tracking_number']);
    
    if (!empty($tracking_number)) {
        $concern = getConcernByTracking($tracking_number);
        
        if ($concern) {
            $responses = getResponses($concern['id']);
        } else {
            $message = "No concern found with tracking number: $tracking_number";
            $message_type = 'error';
        }
    } else {
        $message = "Please enter a tracking number";
        $message_type = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Concern - DeptLine</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <i class="fas fa-graduation-cap text-2xl mr-3"></i>
                    <span class="text-xl font-bold">DeptLine</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="index.php" class="hover:text-blue-200 transition">Home</a>
                    <a href="submit_concern.php" class="hover:text-blue-200 transition">Submit Concern</a>
                    <a href="track_concern.php" class="hover:text-blue-200 transition">Track Concern</a>
                    <a href="department_dashboard.php" class="hover:text-blue-200 transition">Department Dashboard</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Search Form -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Track Your Concern</h1>
                <p class="text-gray-600">Enter your tracking number to check the status of your concern</p>
            </div>

            <form method="POST" class="max-w-md mx-auto">
                <div class="flex gap-4">
                    <input type="text" name="tracking_number" 
                           value="<?php echo htmlspecialchars($_POST['tracking_number'] ?? ''); ?>"
                           placeholder="Enter tracking number (e.g., DL2024ABC123)"
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition duration-300">
                        <i class="fas fa-search mr-2"></i>Track
                    </button>
                </div>
            </form>

            <?php if ($message): ?>
            <div class="mt-4 p-4 rounded-lg <?php echo $message_type === 'success' ? 'bg-green-100 text-green-700 border border-green-300' : 'bg-red-100 text-red-700 border border-red-300'; ?>">
                <?php echo $message; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Concern Details -->
        <?php if ($concern): ?>
        <div class="bg-white rounded-lg shadow-md p-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($concern['title']); ?></h2>
                    <p class="text-gray-600">Tracking Number: <span class="font-mono font-semibold"><?php echo htmlspecialchars($concern['tracking_number']); ?></span></p>
                </div>
                <div class="text-right">
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold <?php echo getStatusColor($concern['status']); ?>">
                        <?php echo ucfirst(str_replace('_', ' ', $concern['status'])); ?>
                    </span>
                    <br>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold mt-2 <?php echo getPriorityColor($concern['priority_level']); ?>">
                        <?php echo ucfirst($concern['priority_level']); ?> Priority
                    </span>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Concern Details</h3>
                    <div class="space-y-2 text-sm">
                        <p><strong>Department:</strong> <?php echo htmlspecialchars($concern['department_name']); ?></p>
                        <p><strong>Category:</strong> <?php echo htmlspecialchars($concern['category_name']); ?></p>
                        <p><strong>Submitted:</strong> <?php echo date('F j, Y \a\t g:i A', strtotime($concern['submitted_at'])); ?></p>
                        <?php if ($concern['updated_at'] !== $concern['submitted_at']): ?>
                        <p><strong>Last Updated:</strong> <?php echo date('F j, Y \a\t g:i A', strtotime($concern['updated_at'])); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!$concern['is_anonymous']): ?>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Student Information</h3>
                    <div class="space-y-2 text-sm">
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($concern['student_name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($concern['student_email']); ?></p>
                        <?php if (!empty($concern['student_id'])): ?>
                        <p><strong>Student ID:</strong> <?php echo htmlspecialchars($concern['student_id']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Description</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-700 whitespace-pre-wrap"><?php echo htmlspecialchars($concern['description']); ?></p>
                </div>
            </div>

            <!-- Status Timeline -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Status Timeline</h3>
                <div class="relative">
                    <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                    
                    <div class="relative flex items-center mb-4">
                        <div class="bg-green-500 w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold z-10">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="ml-6">
                            <p class="font-semibold text-gray-800">Concern Submitted</p>
                            <p class="text-sm text-gray-600"><?php echo date('F j, Y \a\t g:i A', strtotime($concern['submitted_at'])); ?></p>
                        </div>
                    </div>

                    <?php if ($concern['status'] !== 'pending'): ?>
                    <div class="relative flex items-center mb-4">
                        <div class="bg-blue-500 w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold z-10">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="ml-6">
                            <p class="font-semibold text-gray-800">In Progress</p>
                            <p class="text-sm text-gray-600">Department is reviewing your concern</p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($concern['status'] === 'resolved' || $concern['status'] === 'closed'): ?>
                    <div class="relative flex items-center">
                        <div class="bg-green-500 w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold z-10">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <div class="ml-6">
                            <p class="font-semibold text-gray-800">Resolved</p>
                            <p class="text-sm text-gray-600">Your concern has been addressed</p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Responses -->
            <?php if (!empty($responses)): ?>
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Department Responses</h3>
                <div class="space-y-4">
                    <?php foreach ($responses as $response): ?>
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                        <div class="flex justify-between items-start mb-2">
                            <p class="font-semibold text-blue-800"><?php echo htmlspecialchars($response['responded_by']); ?></p>
                            <p class="text-sm text-blue-600"><?php echo date('F j, Y \a\t g:i A', strtotime($response['responded_at'])); ?></p>
                        </div>
                        <p class="text-blue-700 whitespace-pre-wrap"><?php echo htmlspecialchars($response['response_text']); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div class="mt-8 flex justify-between items-center">
                <a href="submit_concern.php" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition duration-300">
                    <i class="fas fa-plus mr-2"></i>Submit New Concern
                </a>
                <button onclick="window.print()" class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700 transition duration-300">
                    <i class="fas fa-print mr-2"></i>Print Details
                </button>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script>
        // Auto-focus on tracking number input
        document.querySelector('input[name="tracking_number"]').focus();
        
        // Add tracking number format validation
        document.querySelector('input[name="tracking_number"]').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    </script>
</body>
</html>
