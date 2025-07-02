<?php
header('Content-Type: application/json');
require_once '../includes/auth.php';
require_once '../includes/db.php';

$projects = getProjects();
echo json_encode($projects);

$user = authenticateUser();
if (!$user) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $search = $_GET['search'] ?? '';
    $category = $_GET['category'] ?? '';
    $skill = $_GET['skill'] ?? '';
    
    try {
        $query = "
            SELECT 
                p.project_id,
                p.judul_project,
                p.deskripsi_project,
                p.status,
                p.max_members,
                p.image_path,
                p.tgl_dibuat,
                u.nama AS creator_name,
                u.jurusan AS creator_major,
                COALESCE((SELECT COUNT(*) FROM project_member pm WHERE pm.project_id = p.project_id), 0) AS current_members,
                GREATEST(p.max_members - COALESCE((SELECT COUNT(*) FROM project_member pm WHERE pm.project_id = p.project_id), 0), 0) AS available_slots
            FROM project p
            JOIN pengguna u ON p.creator_id = u.pengguna_id
            WHERE p.status = 'Merekrut'
        ";
        
        $conditions = [];
        $params = [];
        
        if (!empty($search)) {
            $conditions[] = "p.judul_project LIKE :search";
            $params[':search'] = "%$search%";
        }
        
        if (!empty($category)) {
            $conditions[] = "p.kategori = :category";
            $params[':category'] = $category;
        }
        
        if (!empty($skill)) {
            $conditions[] = "EXISTS (
                SELECT 1 FROM project_skill_requirement psr
                WHERE psr.project_id = p.project_id
                AND psr.skill_id = :skill
            )";
            $params[':skill'] = $skill;
        }
        
        if (!empty($conditions)) {
            $query .= " AND " . implode(" AND ", $conditions);
        }
        
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $projects = $stmt->fetchAll();
        
        echo json_encode($projects);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} 
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $judulProject = $data['judul_project'] ?? '';
    $deskripsi = $data['deskripsi_project'] ?? '';
    $maxMembers = $data['max_members'] ?? 5;
    $requiredSkills = $data['required_skills'] ?? [];
    
    try {
        if (empty($judulProject)) {
            throw new Exception('Judul proyek wajib diisi');
        }
        
        if (!is_numeric($maxMembers) || $maxMembers < 1 || $maxMembers > 20) {
            throw new Exception('Jumlah anggota harus antara 1-20');
        }
        
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("
            INSERT INTO project (creator_id, judul_project, deskripsi_project, max_members)
            VALUES (:creator_id, :judul_project, :deskripsi_project, :max_members)
        ");
        
        $stmt->execute([
            ':creator_id' => $user['pengguna_id'],
            ':judul_project' => $judulProject,
            ':deskripsi_project' => $deskripsi,
            ':max_members' => $maxMembers
        ]);
        
        $projectId = $pdo->lastInsertId();
        
        $memberStmt = $pdo->prepare("
            INSERT INTO project_member (project_id, pengguna_id, role)
            VALUES (:project_id, :pengguna_id, 'Pemimpin Proyek')
        ");
        $memberStmt->execute([
            ':project_id' => $projectId,
            ':pengguna_id' => $user['pengguna_id']
        ]);
        
        if (!empty($requiredSkills)) {
            $skillStmt = $pdo->prepare("
                INSERT INTO project_skill_requirement (project_id, skill_id)
                VALUES (:project_id, :skill_id)
            ");
            
            foreach ($requiredSkills as $skillId) {
                $skillStmt->execute([
                    ':project_id' => $projectId,
                    ':skill_id' => $skillId
                ]);
            }
        }
        
        $pdo->commit();
        
        echo json_encode([
            'success' => true,
            'project_id' => $projectId,
            'message' => 'Proyek berhasil dibuat'
        ]);
        
    } catch (Exception $e) {
        $pdo->rollBack();
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>