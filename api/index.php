<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

class MySQLCourseReportAPI {
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'course_management';
    
    
    private function executeQuery($query) {
        // 清理查詢字符串，移除多餘的空白和換行
        $cleanQuery = preg_replace('/\s+/', ' ', trim($query));
        
        // 創建臨時SQL文件來避免命令行長度和特殊字符問題
        $tempFile = sys_get_temp_dir() . '/query_' . uniqid() . '.sql';
        file_put_contents($tempFile, $cleanQuery);
        
        // 使用文件執行查詢
        $command = sprintf(
            'mysql -h %s -u %s %s -B < "%s"',
            $this->host,
            $this->username,
            $this->database,
            $tempFile
        );
        
        $output = shell_exec($command);
        
        // 清理臨時文件
        if (file_exists($tempFile)) {
            unlink($tempFile);
        }
        
        if ($output === null) {
            throw new Exception('MySQL 查詢執行失敗: shell_exec 返回 null');
        }
        
        if (trim($output) === '') {
            throw new Exception('MySQL 查詢執行失敗: 無輸出');
        }
        
        return $this->parseTSVOutput($output);
    }
    
    private function parseTSVOutput($output) {
        $lines = explode("\n", trim($output));
        if (count($lines) < 1) {
            return [];
        }
        
        // 第一行是標題
        $headers = explode("\t", $lines[0]);
        $results = [];
        
        // 從第二行開始是數據
        for ($i = 1; $i < count($lines); $i++) {
            if (trim($lines[$i]) === '') continue;
            
            $values = explode("\t", $lines[$i]);
            $row = [];
            
            for ($j = 0; $j < count($headers); $j++) {
                $value = isset($values[$j]) ? $values[$j] : null;
                // 處理 NULL 值
                if ($value === 'NULL') {
                    $value = null;
                }
                $row[$headers[$j]] = $value;
            }
            
            $results[] = $row;
        }
        
        return $results;
    }
    
    public function getEnrolmentData($page = 1, $limit = 50, $filters = []) {
        try {
            $offset = ($page - 1) * $limit;
            
            // 構建查詢
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
            
            // 應用過濾器
            if (!empty($filters['user_name'])) {
                $searchTerm = addslashes($filters['user_name']);
                $query .= " AND (u.first_name LIKE '%{$searchTerm}%' OR u.surname LIKE '%{$searchTerm}%')";
            }
            
            if (!empty($filters['course_name'])) {
                $searchTerm = addslashes($filters['course_name']);
                $query .= " AND c.description LIKE '%{$searchTerm}%'";
            }
            
            if (!empty($filters['status'])) {
                $status = addslashes($filters['status']);
                $query .= " AND e.completion_status = '{$status}'";
            }
            
            // 獲取總記錄數 - 先構建計數查詢
            $countQuery = "SELECT COUNT(*) as total FROM enrolments e JOIN users u ON e.user_id = u.user_id JOIN courses c ON e.course_id = c.course_id WHERE 1=1";
            
            // 添加同樣的過濾條件
            if (!empty($filters['user_name'])) {
                $searchTerm = addslashes($filters['user_name']);
                $countQuery .= " AND (u.first_name LIKE '%{$searchTerm}%' OR u.surname LIKE '%{$searchTerm}%')";
            }
            
            if (!empty($filters['course_name'])) {
                $searchTerm = addslashes($filters['course_name']);
                $countQuery .= " AND c.description LIKE '%{$searchTerm}%'";
            }
            
            if (!empty($filters['status'])) {
                $status = addslashes($filters['status']);
                $countQuery .= " AND e.completion_status = '{$status}'";
            }
            
            $countResult = $this->executeQuery($countQuery);
            $totalRecords = isset($countResult[0]['total']) ? intval($countResult[0]['total']) : 0;
            
            // 添加分頁
            $query .= " ORDER BY e.enrolled_at DESC LIMIT {$limit} OFFSET {$offset}";
            
            $records = $this->executeQuery($query);
            
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
            // 總註冊數
            $totalEnrolmentsResult = $this->executeQuery("SELECT COUNT(*) as total FROM enrolments");
            $totalEnrolments = intval($totalEnrolmentsResult[0]['total']);
            
            // 狀態分佈
            $statusResult = $this->executeQuery("
                SELECT completion_status, COUNT(*) as count 
                FROM enrolments 
                GROUP BY completion_status
            ");
            
            $statusBreakdown = [];
            foreach ($statusResult as $row) {
                $statusBreakdown[$row['completion_status']] = intval($row['count']);
            }
            
            // 總用戶數
            $totalUsersResult = $this->executeQuery("SELECT COUNT(*) as total FROM users");
            $totalUsers = intval($totalUsersResult[0]['total']);
            
            // 總課程數
            $totalCoursesResult = $this->executeQuery("SELECT COUNT(*) as total FROM courses");
            $totalCourses = intval($totalCoursesResult[0]['total']);
            
            // 完成率
            $completedCount = isset($statusBreakdown['completed']) ? $statusBreakdown['completed'] : 0;
            $completionRate = $totalEnrolments > 0 ? round(($completedCount / $totalEnrolments) * 100, 2) : 0;
            
            return [
                'success' => true,
                'data' => [
                    'total_enrolments' => $totalEnrolments,
                    'total_users' => $totalUsers,
                    'total_courses' => $totalCourses,
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
        try {
            $users = $this->executeQuery("
                SELECT user_id, first_name, surname 
                FROM users 
                ORDER BY first_name, surname
            ");
            
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
            $courses = $this->executeQuery("
                SELECT course_id, description 
                FROM courses 
                ORDER BY description
            ");
            
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
}

// Handle the API request
$api = new MySQLCourseReportAPI();

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