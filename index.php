<?php
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DeptLine - Student Concern Management System</title>
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

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    Student Concern Management
                </h1>
                <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto">
                    Submit, track, and manage your concerns efficiently. Our centralized platform ensures your voice is heard and issues are resolved promptly.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="submit_concern.php" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
                        <i class="fas fa-plus mr-2"></i>Submit a Concern
                    </a>
                    <a href="track_concern.php" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition duration-300">
                        <i class="fas fa-search mr-2"></i>Track Your Concern
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Why Choose DeptLine?</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center p-6 bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-friends text-2xl text-blue-600"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2 text-gray-800">User-Friendly</h3>
                <p class="text-gray-600">Easy-to-use interface designed for students to submit concerns without confusion.</p>
            </div>
            
            <div class="text-center p-6 bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300">
                <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-sitemap text-2xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2 text-gray-800">Smart Routing</h3>
                <p class="text-gray-600">Automatic routing to the correct department reduces delays and miscommunication.</p>
            </div>
            
            <div class="text-center p-6 bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300">
                <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-secret text-2xl text-purple-600"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2 text-gray-800">Anonymous Option</h3>
                <p class="text-gray-600">Submit sensitive concerns anonymously while still receiving proper attention.</p>
            </div>
            
            <div class="text-center p-6 bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300">
                <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2 text-gray-800">Priority Handling</h3>
                <p class="text-gray-600">Urgent issues like safety hazards are automatically prioritized for immediate attention.</p>
            </div>
        </div>
    </div>

    <!-- Departments Section -->
    <div class="bg-gray-100 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Our Departments</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                $departments = getDepartments();
                foreach ($departments as $dept) {
                    $icon_class = '';
                    switch (strtolower($dept['name'])) {
                        case 'academic affairs':
                            $icon_class = 'fas fa-book text-blue-600';
                            break;
                        case 'student services':
                            $icon_class = 'fas fa-users text-green-600';
                            break;
                        case 'mental health services':
                            $icon_class = 'fas fa-heart text-pink-600';
                            break;
                        case 'campus safety':
                            $icon_class = 'fas fa-shield-alt text-red-600';
                            break;
                        case 'it support':
                            $icon_class = 'fas fa-laptop text-purple-600';
                            break;
                        case 'financial aid':
                            $icon_class = 'fas fa-dollar-sign text-yellow-600';
                            break;
                        default:
                            $icon_class = 'fas fa-building text-gray-600';
                    }
                ?>
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    <div class="flex items-center mb-4">
                        <i class="<?php echo $icon_class; ?> text-2xl mr-3"></i>
                        <h3 class="text-xl font-semibold text-gray-800"><?php echo htmlspecialchars($dept['name']); ?></h3>
                    </div>
                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($dept['description']); ?></p>
                    <div class="text-sm text-gray-500">
                        <p><i class="fas fa-envelope mr-2"></i><?php echo htmlspecialchars($dept['email']); ?></p>
                        <p><i class="fas fa-phone mr-2"></i><?php echo htmlspecialchars($dept['phone']); ?></p>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p>&copy; 2024 DeptLine. All rights reserved.</p>
                <p class="mt-2 text-gray-400">Empowering students through efficient concern management.</p>
            </div>
        </div>
    </footer>

    <script>
        // Add smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>
