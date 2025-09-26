<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

class CourseReportAPI {
    private $conn;
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';  // 您需要在這裡設置您的 MySQL 密碼
    private $database = 'course_management';
    
    public function __construct() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
        
        if ($this->conn->connect_error) {
            die(json_encode(['success' => false, 'error' => 'Database connection failed: ' . $this->conn->connect_error]));
        }
        
        $this->conn->set_charset('utf8');
    }
    
    public function getEnrolmentData($page = 1, $limit = 50, $filters = []) {
        try {
            $offset = ($page - 1) * $limit;
            
            // Build the query with filters
            $query = "
                SELECT 
                    e.enrolment_id,
                    u.user_id,
                    u.first_name,
                    u.surname,
                    c.course_id,
                    c.description as course_description,
                    e.completion_status,
                    e.enrolled_at,
                    e.completed_at
                FROM enrolments e
                JOIN users u ON e.user_id = u.user_id
                JOIN courses c ON e.course_id = c.course_id
                WHERE 1=1
            ";
            
            $whereConditions = [];
            $params = [];
            $types = '';
            
            // Apply filters
            if (!empty($filters['user_name'])) {
                $whereConditions[] = "(u.first_name LIKE ? OR u.surname LIKE ?)";
                $searchTerm = '%' . $filters['user_name'] . '%';
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $types .= 'ss';
            }
            
            if (!empty($filters['course_name'])) {
                $whereConditions[] = "c.description LIKE ?";
                $params[] = '%' . $filters['course_name'] . '%';
                $types .= 's';
            }
            
            if (!empty($filters['status'])) {
                $whereConditions[] = "e.completion_status = ?";
                $params[] = $filters['status'];
                $types .= 's';
            }
            
            if (!empty($whereConditions)) {
                $query .= " AND " . implode(" AND ", $whereConditions);
            }
            
            // Count total records for pagination
            $countQuery = str_replace("SELECT e.enrolment_id, u.user_id, u.first_name, u.surname, c.course_id, c.description as course_description, e.completion_status, e.enrolled_at, e.completed_at", "SELECT COUNT(*) as total", $query);
            
            if (!empty($params)) {
                $countStmt = $this->conn->prepare($countQuery);
                $countStmt->bind_param($types, ...$params);
                $countStmt->execute();
                $countResult = $countStmt->get_result();
                $totalRecords = $countResult->fetch_assoc()['total'];
                $countStmt->close();
            } else {
                $countResult = $this->conn->query($countQuery);
                $totalRecords = $countResult->fetch_assoc()['total'];
            }
            
            // Add ordering and pagination
            $query .= " ORDER BY e.enrolled_at DESC LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
            $types .= 'ii';
            
            if (!empty($params)) {
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param($types, ...$params);
                $stmt->execute();
                $result = $stmt->get_result();
                $records = $result->fetch_all(MYSQLI_ASSOC);
                $stmt->close();
            } else {
                $result = $this->conn->query($query);
                $records = $result->fetch_all(MYSQLI_ASSOC);
            }
            
            return [
                'success' => true,
                'data' => $records,
                'pagination' => [
                    'current_page' => $page,
                    'total_records' => $totalRecords,
                    'total_pages' => ceil($totalRecords / $limit),
                    'records_per_page' => $limit
                ]
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    public function getStatistics() {
        try {
            $stats = [];
            
            // Total enrolments
            $result = $this->conn->query("SELECT COUNT(*) as total FROM enrolments");
            $stats['total_enrolments'] = $result->fetch_assoc()['total'];
            
            // Status breakdown
            $result = $this->conn->query("
                SELECT 
                    completion_status, 
                    COUNT(*) as count 
                FROM enrolments 
                GROUP BY completion_status
            ");
            $statusBreakdown = [];
            while ($row = $result->fetch_assoc()) {
                $statusBreakdown[$row['completion_status']] = $row['count'];
            }
            $stats['status_breakdown'] = $statusBreakdown;
            
            // Total users
            $result = $this->conn->query("SELECT COUNT(*) as total FROM users");
            $stats['total_users'] = $result->fetch_assoc()['total'];
            
            // Total courses
            $result = $this->conn->query("SELECT COUNT(*) as total FROM courses");
            $stats['total_courses'] = $result->fetch_assoc()['total'];
            
            // Completion rate
            $completedCount = $statusBreakdown['completed'] ?? 0;
            $stats['completion_rate'] = $stats['total_enrolments'] > 0 
                ? round(($completedCount / $stats['total_enrolments']) * 100, 2) 
                : 0;
            
            return [
                'success' => true,
                'data' => $stats
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    public function getUsers() {
        try {
            $result = $this->conn->query("
                SELECT user_id, first_name, surname 
                FROM users 
                ORDER BY first_name, surname
            ");
            $users = $result->fetch_all(MYSQLI_ASSOC);
            
            return [
                'success' => true,
                'data' => $users
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    public function getCourses() {
        try {
            $result = $this->conn->query("
                SELECT course_id, description 
                FROM courses 
                ORDER BY description
            ");
            $courses = $result->fetch_all(MYSQLI_ASSOC);
            
            return [
                'success' => true,
                'data' => $courses
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}

// Handle the API request
$api = new CourseReportAPI();

$action = $_GET['action'] ?? 'enrolments';
$page = intval($_GET['page'] ?? 1);
$limit = intval($_GET['limit'] ?? 50);

// Limit the maximum records per page to prevent performance issues
$limit = min($limit, 100);

$filters = [
    'user_name' => $_GET['user_name'] ?? '',
    'course_name' => $_GET['course_name'] ?? '',
    'status' => $_GET['status'] ?? ''
];

switch ($action) {
    case 'enrolments':
        $result = $api->getEnrolmentData($page, $limit, $filters);
        break;
    case 'statistics':
        $result = $api->getStatistics();
        break;
    case 'users':
        $result = $api->getUsers();
        break;
    case 'courses':
        $result = $api->getCourses();
        break;
    default:
        $result = [
            'success' => false,
            'error' => 'Invalid action'
        ];
}

echo json_encode($result);
?>