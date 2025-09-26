# Course Management Report System

A PHP + MySQL + JavaScript application that displays user course enrollments and completion status in a user-friendly report format.

## Features

- **Comprehensive Dashboard**: View statistics including total enrollments, users, courses, and completion rates
- **Advanced Filtering**: Search by user name, course name, or completion status
- **Pagination**: Efficiently handle large datasets (up to 100,000+ records)
- **Responsive Design**: Works seamlessly on desktop and mobile devices
- **Real-time Search**: Debounced search inputs for optimal performance
- **Status Tracking**: Visual status badges for "not started", "in progress", and "completed"

## Database Structure

### Tables
- **users**: Contains user information (user_id, first_name, surname, email)
- **courses**: Contains course information (course_id, description)
- **enrolments**: Links users to courses with completion status (user_id, course_id, completion_status, enrolled_at, completed_at)

## Setup Instructions

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx) or PHP built-in server

### Installation Steps

1. **Clone or download the project files**
   ```bash
   git clone [repository-url]
   cd php-mysql
   ```

2. **Set up the database**
   - Create a MySQL database named `course_management`
   - Import the database schema:
     ```bash
     mysql -u your_username -p course_management < database/schema.sql
     ```
   - Import sample data:
     ```bash
     mysql -u your_username -p course_management < database/sample_data.sql
     ```

3. **Configure database connection**
   - Edit `api/config/database.php`
   - Update the database credentials:
     ```php
     private $host = 'localhost';
     private $db_name = 'course_management';
     private $username = 'your_mysql_username';
     private $password = 'your_mysql_password';
     ```

4. **Start the web server**
   
   **Option A: Using PHP built-in server**
   ```bash
   php -S localhost:8000
   ```
   
   **Option B: Using Apache/Nginx**
   - Place the project files in your web server's document root
   - Ensure the `api` directory is accessible

5. **Access the application**
   - Open your browser and navigate to:
     - PHP built-in server: `http://localhost:8000`
     - Apache/Nginx: `http://localhost/php-mysql` (or your configured path)

## API Endpoints

### GET /api/index.php

**Parameters:**
- `action` (required): The API action to perform
  - `enrolments`: Get enrollment data with filtering and pagination
  - `statistics`: Get dashboard statistics
  - `users`: Get list of all users
  - `courses`: Get list of all courses

**For `enrolments` action:**
- `page`: Page number (default: 1)
- `limit`: Records per page (default: 50, max: 100)
- `user_name`: Filter by user name (partial match)
- `course_name`: Filter by course name (partial match)
- `status`: Filter by completion status ('not started', 'in progress', 'completed')

**Example requests:**
```
GET /api/index.php?action=statistics
GET /api/index.php?action=enrolments&page=1&limit=25
GET /api/index.php?action=enrolments&user_name=John&status=completed
```

## File Structure

```
php-mysql/
├── api/
│   ├── config/
│   │   └── database.php     # Database configuration
│   └── index.php            # Main API endpoint
├── database/
│   ├── schema.sql           # Database schema
│   └── sample_data.sql      # Sample data insertion
├── index.html               # Main HTML page
├── styles.css               # CSS styles
├── script.js                # JavaScript functionality
└── README.md                # This file
```

## Key Features Implementation

### Performance Optimization
- **Indexed Database**: Strategic indexes on frequently queried columns
- **Pagination**: Server-side pagination to handle large datasets
- **Debounced Search**: Reduces API calls during user input
- **Lazy Loading**: Smooth animations and progressive loading

### User Experience
- **Responsive Design**: Mobile-first approach with breakpoints
- **Loading States**: Clear feedback during data fetching
- **Error Handling**: Graceful error messages and recovery
- **Accessibility**: Keyboard navigation and screen reader support

### Security
- **SQL Injection Prevention**: PDO prepared statements
- **XSS Protection**: HTML escaping for user data
- **CORS Headers**: Proper cross-origin resource sharing

## Browser Compatibility

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Verify MySQL is running
   - Check database credentials in `api/config/database.php`
   - Ensure the database exists

2. **API Returns Empty Data**
   - Verify sample data was imported successfully
   - Check PHP error logs
   - Ensure proper file permissions

3. **CORS Issues**
   - If testing locally, ensure you're not mixing HTTP/HTTPS
   - Check that CORS headers are properly set in the API

4. **Performance Issues**
   - Consider adding more database indexes for large datasets
   - Implement caching for frequently accessed data
   - Optimize MySQL configuration for your server

## License

This project is part of the MindAtlas PHP Developer Course Test.

## Support

For issues or questions, please refer to the course materials or contact your instructor.

## React Compiler

The React Compiler is not enabled on this template. To add it, see [this documentation](https://react.dev/learn/react-compiler/installation).

## Expanding the ESLint configuration

If you are developing a production application, we recommend using TypeScript with type-aware lint rules enabled. Check out the [TS template](https://github.com/vitejs/vite/tree/main/packages/create-vite/template-react-ts) for information on how to integrate TypeScript and [`typescript-eslint`](https://typescript-eslint.io) in your project.
