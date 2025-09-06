<?php
require_once 'includes/functions.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $title = sanitizeInput($_POST['title'] ?? '');
    $description = sanitizeInput($_POST['description'] ?? '');
    $department_id = (int)($_POST['department_id'] ?? 0);
    $category_id = (int)($_POST['category_id'] ?? 0);
    $student_name = sanitizeInput($_POST['student_name'] ?? '');
    $student_email = sanitizeInput($_POST['student_email'] ?? '');
    $student_id = sanitizeInput($_POST['student_id'] ?? '');
    $is_anonymous = isset($_POST['is_anonymous']) ? true : false;
    $priority_level = sanitizeInput($_POST['priority_level'] ?? 'medium');

    // Validation
    $errors = [];
    
    if (empty($title)) {
        $errors[] = "Title is required";
    }
    
    if (empty($description)) {
        $errors[] = "Description is required";
    }
    
    if ($department_id <= 0) {
        $errors[] = "Please select a department";
    }
    
    if ($category_id <= 0) {
        $errors[] = "Please select a category";
    }
    
    if (!$is_anonymous) {
        if (empty($student_name)) {
            $errors[] = "Student name is required when not submitting anonymously";
        }
        
        if (empty($student_email)) {
            $errors[] = "Student email is required when not submitting anonymously";
        } elseif (!validateEmail($student_email)) {
            $errors[] = "Please enter a valid email address";
        }
    }

    if (empty($errors)) {
        $concern_data = [
            'title' => $title,
            'description' => $description,
            'department_id' => $department_id,
            'category_id' => $category_id,
            'student_name' => $is_anonymous ? '' : $student_name,
            'student_email' => $is_anonymous ? '' : $student_email,
            'student_id' => $student_id,
            'is_anonymous' => $is_anonymous,
            'priority_level' => $priority_level
        ];

        $tracking_number = submitConcern($concern_data);
        
        if ($tracking_number) {
            $message = "Concern submitted successfully! Your tracking number is: <strong>$tracking_number</strong>";
            $message_type = 'success';
            
            // Clear form data
            $_POST = [];
        } else {
            $message = "Error submitting concern. Please try again.";
            $message_type = 'error';
        }
    } else {
        $message = implode('<br>', $errors);
        $message_type = 'error';
    }
}

$departments = getDepartments();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Concern - DeptLine</title>
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
        <div class="bg-white rounded-lg shadow-md p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Submit Your Concern</h1>
                <p class="text-gray-600">We're here to help. Submit your concern and we'll route it to the appropriate department.</p>
            </div>

            <?php if ($message): ?>
            <div class="mb-6 p-4 rounded-lg <?php echo $message_type === 'success' ? 'bg-green-100 text-green-700 border border-green-300' : 'bg-red-100 text-red-700 border border-red-300'; ?>">
                <?php echo $message; ?>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <!-- Concern Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-heading mr-2"></i>Concern Title *
                    </label>
                    <input type="text" id="title" name="title" required
                           value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Brief title describing your concern">
                </div>

                <!-- Department Selection -->
                <div>
                    <label for="department_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-building mr-2"></i>Department *
                    </label>
                    <select id="department_id" name="department_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select a department</option>
                        <?php foreach ($departments as $dept): ?>
                        <option value="<?php echo $dept['id']; ?>" 
                                <?php echo (isset($_POST['department_id']) && $_POST['department_id'] == $dept['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($dept['name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Category Selection -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tags mr-2"></i>Category *
                    </label>
                    <select id="category_id" name="category_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select a category</option>
                    </select>
                </div>

                <!-- Priority Level -->
                <div>
                    <label for="priority_level" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Priority Level
                    </label>
                    <select id="priority_level" name="priority_level"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="low" <?php echo (isset($_POST['priority_level']) && $_POST['priority_level'] === 'low') ? 'selected' : ''; ?>>Low - General inquiry</option>
                        <option value="medium" <?php echo (isset($_POST['priority_level']) && $_POST['priority_level'] === 'medium') ? 'selected' : ''; ?>>Medium - Standard concern</option>
                        <option value="high" <?php echo (isset($_POST['priority_level']) && $_POST['priority_level'] === 'high') ? 'selected' : ''; ?>>High - Important issue</option>
                        <option value="urgent" <?php echo (isset($_POST['priority_level']) && $_POST['priority_level'] === 'urgent') ? 'selected' : ''; ?>>Urgent - Requires immediate attention</option>
                    </select>
                </div>

                <!-- Anonymous Option -->
                <div class="flex items-center">
                    <input type="checkbox" id="is_anonymous" name="is_anonymous" 
                           <?php echo (isset($_POST['is_anonymous']) && $_POST['is_anonymous']) ? 'checked' : ''; ?>
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_anonymous" class="ml-2 block text-sm text-gray-700">
                        <i class="fas fa-user-secret mr-2"></i>Submit anonymously
                    </label>
                </div>

                <!-- Student Information (hidden if anonymous) -->
                <div id="student_info" class="space-y-4">
                    <div>
                        <label for="student_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2"></i>Student Name *
                        </label>
                        <input type="text" id="student_name" name="student_name"
                               value="<?php echo htmlspecialchars($_POST['student_name'] ?? ''); ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Your full name">
                    </div>

                    <div>
                        <label for="student_email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2"></i>Student Email *
                        </label>
                        <input type="email" id="student_email" name="student_email"
                               value="<?php echo htmlspecialchars($_POST['student_email'] ?? ''); ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="your.email@school.edu">
                    </div>

                    <div>
                        <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-id-card mr-2"></i>Student ID (Optional)
                        </label>
                        <input type="text" id="student_id" name="student_id"
                               value="<?php echo htmlspecialchars($_POST['student_id'] ?? ''); ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Your student ID number">
                    </div>
                </div>

                <!-- Concern Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-comment mr-2"></i>Detailed Description *
                    </label>
                    <textarea id="description" name="description" rows="6" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Please provide a detailed description of your concern..."><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
                        <i class="fas fa-paper-plane mr-2"></i>Submit Concern
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Handle anonymous checkbox
        const anonymousCheckbox = document.getElementById('is_anonymous');
        const studentInfo = document.getElementById('student_info');
        const studentNameInput = document.getElementById('student_name');
        const studentEmailInput = document.getElementById('student_email');

        function toggleStudentInfo() {
            if (anonymousCheckbox.checked) {
                studentInfo.style.display = 'none';
                studentNameInput.removeAttribute('required');
                studentEmailInput.removeAttribute('required');
            } else {
                studentInfo.style.display = 'block';
                studentNameInput.setAttribute('required', 'required');
                studentEmailInput.setAttribute('required', 'required');
            }
        }

        anonymousCheckbox.addEventListener('change', toggleStudentInfo);
        toggleStudentInfo(); // Initial state

        // Handle department change to load categories
        const departmentSelect = document.getElementById('department_id');
        const categorySelect = document.getElementById('category_id');

        departmentSelect.addEventListener('change', function() {
            const departmentId = this.value;
            categorySelect.innerHTML = '<option value="">Select a category</option>';
            
            if (departmentId) {
                fetch(`api/get_categories.php?department_id=${departmentId}`)
                    .then(response => response.json())
                    .then(categories => {
                        categories.forEach(category => {
                            const option = document.createElement('option');
                            option.value = category.id;
                            option.textContent = category.name;
                            categorySelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error loading categories:', error);
                    });
            }
        });
    </script>
</body>
</html>
