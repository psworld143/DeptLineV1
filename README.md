# DeptLine - Student Concern Management System

DeptLine is a comprehensive Pure PHP system designed to help schools manage student concerns efficiently. It provides a centralized platform where students can submit digital, categorized, and even anonymous concerns, which are automatically routed to the appropriate departments.

## Features

### 🎯 Core Features
- **User-Friendly Interface**: Easy-to-use forms designed for students to submit concerns without confusion
- **Smart Department Routing**: Automatic routing of concerns to the correct department based on category selection
- **Structured Categories**: Organized concern categories to help prioritize and manage different types of issues
- **Anonymous Submission**: Option for students to submit sensitive concerns anonymously
- **Priority Handling**: Automatic prioritization of urgent issues like safety hazards
- **Real-time Tracking**: Students can track the status of their concerns using unique tracking numbers
- **Department Dashboard**: Comprehensive dashboard for departments to monitor and manage concerns

### 🏢 Department Management
- **Filter & Sort**: Advanced filtering by department, status, and priority
- **Statistics Dashboard**: Real-time statistics showing concern counts and status distribution
- **Status Updates**: Easy status management with response capabilities
- **Response System**: Department staff can respond to student concerns
- **Priority Alerts**: Urgent concerns are highlighted for immediate attention

### 📊 Student Features
- **Concern Submission**: Structured form with department and category selection
- **Tracking System**: Real-time status tracking with timeline view
- **Anonymous Option**: Secure anonymous submission for sensitive issues
- **Response History**: View all department responses and updates

## Technology Stack

- **Backend**: Pure PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, Tailwind CSS (CDN), JavaScript
- **Icons**: Font Awesome 6.0 (CDN)

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- PHP PDO extension enabled

### Setup Instructions

1. **Clone/Download the Project**
   ```bash
   # If using git
   git clone <repository-url>
   cd DeptLine
   ```

2. **Database Setup**
   - Create a new MySQL database named `deptline_db`
   - Import the database schema:
   ```bash
   mysql -u root -p deptline_db < database/schema.sql
   ```

3. **Configuration**
   - Edit `config/database.php` with your database credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   define('DB_NAME', 'deptline_db');
   ```

4. **Web Server Configuration**
   - Point your web server to the project directory
   - Ensure PHP has write permissions for any log files
   - For Apache, ensure mod_rewrite is enabled (if using .htaccess)

5. **Access the Application**
   - Navigate to `http://your-domain/index.php`
   - The system should be ready to use!

## File Structure

```
DeptLine/
├── config/
│   └── database.php          # Database configuration
├── database/
│   └── schema.sql           # Database schema and sample data
├── includes/
│   └── functions.php        # Core functions and utilities
├── api/
│   ├── get_categories.php   # API for dynamic category loading
│   └── get_concern_details.php # API for concern details
├── index.php                # Home page
├── submit_concern.php       # Concern submission form
├── track_concern.php        # Concern tracking page
├── department_dashboard.php # Department management dashboard
└── README.md               # This file
```

## Usage Guide

### For Students

1. **Submitting a Concern**
   - Navigate to "Submit Concern"
   - Fill out the form with your concern details
   - Select appropriate department and category
   - Choose priority level
   - Optionally submit anonymously
   - Submit and receive a tracking number

2. **Tracking a Concern**
   - Go to "Track Concern"
   - Enter your tracking number
   - View current status and department responses
   - Check the timeline of updates

### For Department Staff

1. **Accessing Dashboard**
   - Navigate to "Department Dashboard"
   - View all concerns assigned to your department
   - Use filters to find specific concerns

2. **Managing Concerns**
   - Click "View" to see full concern details
   - Click "Update" to change status and add responses
   - Monitor urgent concerns highlighted in red

3. **Responding to Concerns**
   - Update status (Pending → In Progress → Resolved)
   - Add response messages to communicate with students
   - Track all interactions in the system

## Database Schema

### Tables
- **departments**: Stores department information
- **categories**: Concern categories linked to departments
- **concerns**: Main concern records with student info
- **responses**: Department responses to concerns

### Key Fields
- **Tracking Numbers**: Auto-generated unique identifiers (format: DL2024XXXXXX)
- **Priority Levels**: Low, Medium, High, Urgent
- **Status**: Pending, In Progress, Resolved, Closed
- **Anonymous Flag**: Boolean for anonymous submissions

## Security Features

- **Input Sanitization**: All user inputs are sanitized
- **SQL Injection Prevention**: Prepared statements used throughout
- **XSS Protection**: HTML entities encoding
- **Anonymous Protection**: Secure handling of anonymous submissions

## Customization

### Adding New Departments
1. Add department record to `departments` table
2. Add corresponding categories to `categories` table
3. Update the icon mapping in `index.php` if needed

### Modifying Categories
- Edit the `categories` table directly
- Update priority levels as needed
- Categories are automatically linked to departments

### Styling Changes
- Modify Tailwind CSS classes in HTML files
- Update color schemes in `includes/functions.php` (getPriorityColor, getStatusColor)

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check database credentials in `config/database.php`
   - Ensure MySQL service is running
   - Verify database exists

2. **Categories Not Loading**
   - Check API endpoint `/api/get_categories.php`
   - Verify department_id parameter is being passed
   - Check browser console for JavaScript errors

3. **Form Submission Issues**
   - Ensure all required fields are filled
   - Check PHP error logs
   - Verify form validation is working

### Error Logs
- Check PHP error logs for detailed error messages
- Enable error reporting in development:
  ```php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  ```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is open source and available under the [MIT License](LICENSE).

## Support

For support and questions:
- Check the troubleshooting section above
- Review the code comments for implementation details
- Create an issue in the repository

## Future Enhancements

- Email notifications for status updates
- File attachment support
- Advanced reporting and analytics
- Mobile app integration
- Multi-language support
- Advanced search functionality
- Export capabilities for reports

---

**DeptLine** - Empowering students through efficient concern management.
