<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

class MockCourseReportAPI {
    private $users;
    private $courses;
    private $enrolments;
    
    public function __construct() {
        // Mock data - in a real scenario this would come from database
        $this->users = [
            ['user_id' => 1, 'first_name' => 'John', 'surname' => 'Smith'],
            ['user_id' => 2, 'first_name' => 'Jane', 'surname' => 'Doe'],
            ['user_id' => 3, 'first_name' => 'Michael', 'surname' => 'Johnson'],
            ['user_id' => 4, 'first_name' => 'Emily', 'surname' => 'Brown'],
            ['user_id' => 5, 'first_name' => 'David', 'surname' => 'Wilson'],
            ['user_id' => 6, 'first_name' => 'Sarah', 'surname' => 'Davis'],
            ['user_id' => 7, 'first_name' => 'Chris', 'surname' => 'Miller'],
            ['user_id' => 8, 'first_name' => 'Lisa', 'surname' => 'Garcia'],
            ['user_id' => 9, 'first_name' => 'Robert', 'surname' => 'Martinez'],
            ['user_id' => 10, 'first_name' => 'Amanda', 'surname' => 'Taylor']
        ];
        
        $this->courses = [
            ['course_id' => 1, 'description' => 'Introduction to Web Development'],
            ['course_id' => 2, 'description' => 'Advanced JavaScript Programming'],
            ['course_id' => 3, 'description' => 'Database Design and Management'],
            ['course_id' => 4, 'description' => 'PHP Backend Development'],
            ['course_id' => 5, 'description' => 'React Frontend Framework'],
            ['course_id' => 6, 'description' => 'Node.js Server Development'],
            ['course_id' => 7, 'description' => 'Mobile App Development'],
            ['course_id' => 8, 'description' => 'Data Science with Python']
        ];
        
        $this->enrolments = [
            ['enrolment_id' => 1, 'user_id' => 1, 'course_id' => 1, 'completion_status' => 'completed', 'enrolled_at' => '2025-01-15 10:30:00', 'completed_at' => '2025-02-20 15:45:00'],
            ['enrolment_id' => 2, 'user_id' => 1, 'course_id' => 2, 'completion_status' => 'in progress', 'enrolled_at' => '2025-02-01 09:15:00', 'completed_at' => null],
            ['enrolment_id' => 3, 'user_id' => 1, 'course_id' => 3, 'completion_status' => 'not started', 'enrolled_at' => '2025-02-10 14:20:00', 'completed_at' => null],
            ['enrolment_id' => 4, 'user_id' => 2, 'course_id' => 1, 'completion_status' => 'completed', 'enrolled_at' => '2025-01-20 11:00:00', 'completed_at' => '2025-03-01 16:30:00'],
            ['enrolment_id' => 5, 'user_id' => 2, 'course_id' => 4, 'completion_status' => 'in progress', 'enrolled_at' => '2025-02-05 08:45:00', 'completed_at' => null],
            ['enrolment_id' => 6, 'user_id' => 3, 'course_id' => 2, 'completion_status' => 'completed', 'enrolled_at' => '2025-01-25 13:10:00', 'completed_at' => '2025-02-28 12:15:00'],
            ['enrolment_id' => 7, 'user_id' => 3, 'course_id' => 5, 'completion_status' => 'not started', 'enrolled_at' => '2025-02-15 10:30:00', 'completed_at' => null],
            ['enrolment_id' => 8, 'user_id' => 4, 'course_id' => 3, 'completion_status' => 'in progress', 'enrolled_at' => '2025-01-30 15:20:00', 'completed_at' => null],
            ['enrolment_id' => 9, 'user_id' => 4, 'course_id' => 6, 'completion_status' => 'completed', 'enrolled_at' => '2025-01-10 09:00:00', 'completed_at' => '2025-02-25 17:45:00'],
            ['enrolment_id' => 10, 'user_id' => 5, 'course_id' => 7, 'completion_status' => 'not started', 'enrolled_at' => '2025-02-20 12:00:00', 'completed_at' => null],
            ['enrolment_id' => 11, 'user_id' => 5, 'course_id' => 8, 'completion_status' => 'completed', 'enrolled_at' => '2025-01-05 14:30:00', 'completed_at' => '2025-02-15 11:20:00'],
            ['enrolment_id' => 12, 'user_id' => 6, 'course_id' => 1, 'completion_status' => 'in progress', 'enrolled_at' => '2025-02-08 16:15:00', 'completed_at' => null],
            ['enrolment_id' => 13, 'user_id' => 6, 'course_id' => 4, 'completion_status' => 'completed', 'enrolled_at' => '2025-01-12 10:45:00', 'completed_at' => '2025-02-22 14:30:00'],
            ['enrolment_id' => 14, 'user_id' => 7, 'course_id' => 2, 'completion_status' => 'not started', 'enrolled_at' => '2025-02-12 13:30:00', 'completed_at' => null],
            ['enrolment_id' => 15, 'user_id' => 7, 'course_id' => 5, 'completion_status' => 'in progress', 'enrolled_at' => '2025-01-28 11:15:00', 'completed_at' => null],
            ['enrolment_id' => 16, 'user_id' => 8, 'course_id' => 3, 'completion_status' => 'completed', 'enrolled_at' => '2025-01-18 09:30:00', 'completed_at' => '2025-02-18 15:00:00'],
            ['enrolment_id' => 17, 'user_id' => 8, 'course_id' => 6, 'completion_status' => 'not started', 'enrolled_at' => '2025-02-18 14:45:00', 'completed_at' => null],
            ['enrolment_id' => 18, 'user_id' => 9, 'course_id' => 7, 'completion_status' => 'completed', 'enrolled_at' => '2025-01-22 12:20:00', 'completed_at' => '2025-02-12 16:45:00'],
            ['enrolment_id' => 19, 'user_id' => 9, 'course_id' => 8, 'completion_status' => 'in progress', 'enrolled_at' => '2025-02-02 10:10:00', 'completed_at' => null],
            ['enrolment_id' => 20, 'user_id' => 10, 'course_id' => 1, 'completion_status' => 'not started', 'enrolled_at' => '2025-02-22 15:30:00', 'completed_at' => null]
        ];
    }
    
    private function getUserById($userId) {
        foreach ($this->users as $user) {
            if ($user['user_id'] == $userId) {
                return $user;
            }
        }
        return null;
    }
    
    private function getCourseById($courseId) {
        foreach ($this->courses as $course) {
            if ($course['course_id'] == $courseId) {
                return $course;
            }
        }
        return null;
    }
    
    public function getEnrolmentData($page = 1, $limit = 50, $filters = []) {
        try {
            $filteredEnrolments = $this->enrolments;
            
            // Apply filters
            if (!empty($filters['user_name'])) {
                $filteredEnrolments = array_filter($filteredEnrolments, function($enrolment) use ($filters) {
                    $user = $this->getUserById($enrolment['user_id']);
                    if ($user) {
                        $fullName = strtolower($user['first_name'] . ' ' . $user['surname']);
                        return strpos($fullName, strtolower($filters['user_name'])) !== false;
                    }
                    return false;
                });
            }
            
            if (!empty($filters['course_name'])) {
                $filteredEnrolments = array_filter($filteredEnrolments, function($enrolment) use ($filters) {
                    $course = $this->getCourseById($enrolment['course_id']);
                    if ($course) {
                        return strpos(strtolower($course['description']), strtolower($filters['course_name'])) !== false;
                    }
                    return false;
                });
            }
            
            if (!empty($filters['status'])) {
                $filteredEnrolments = array_filter($filteredEnrolments, function($enrolment) use ($filters) {
                    return $enrolment['completion_status'] === $filters['status'];
                });
            }
            
            $totalRecords = count($filteredEnrolments);
            
            // Apply pagination
            $offset = ($page - 1) * $limit;
            $pagedEnrolments = array_slice($filteredEnrolments, $offset, $limit);
            
            // Enrich data with user and course information
            $enrichedData = [];
            foreach ($pagedEnrolments as $enrolment) {
                $user = $this->getUserById($enrolment['user_id']);
                $course = $this->getCourseById($enrolment['course_id']);
                
                $enrichedData[] = [
                    'enrolment_id' => $enrolment['enrolment_id'],
                    'user_id' => $enrolment['user_id'],
                    'first_name' => $user['first_name'],
                    'surname' => $user['surname'],
                    'course_id' => $enrolment['course_id'],
                    'course_description' => $course['description'],
                    'completion_status' => $enrolment['completion_status'],
                    'enrolled_at' => $enrolment['enrolled_at'],
                    'completed_at' => $enrolment['completed_at']
                ];
            }
            
            return [
                'success' => true,
                'data' => $enrichedData,
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
            $totalEnrolments = count($this->enrolments);
            
            // Status breakdown
            $statusBreakdown = [];
            foreach ($this->enrolments as $enrolment) {
                $status = $enrolment['completion_status'];
                $statusBreakdown[$status] = ($statusBreakdown[$status] ?? 0) + 1;
            }
            
            $completedCount = $statusBreakdown['completed'] ?? 0;
            $completionRate = $totalEnrolments > 0 ? round(($completedCount / $totalEnrolments) * 100, 2) : 0;
            
            return [
                'success' => true,
                'data' => [
                    'total_enrolments' => $totalEnrolments,
                    'total_users' => count($this->users),
                    'total_courses' => count($this->courses),
                    'completion_rate' => $completionRate,
                    'status_breakdown' => $statusBreakdown
                ]
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    public function getUsers() {
        return [
            'success' => true,
            'data' => $this->users
        ];
    }
    
    public function getCourses() {
        return [
            'success' => true,
            'data' => $this->courses
        ];
    }
}

// Handle the API request
$api = new MockCourseReportAPI();

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