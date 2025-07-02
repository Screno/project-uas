<?php
// includes/auth.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db.php';

// Fungsi untuk memulai session jika belum aktif
function startSessionIfNotStarted() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// Fungsi untuk mengautentikasi user
function authenticateUser() {
    startSessionIfNotStarted();
    
    if (empty($_SESSION['user_id'])) {
        return null;
    }
    
    return [
        'pengguna_id' => $_SESSION['user_id'],
        'nama' => $_SESSION['nama'],
        'email' => $_SESSION['email']
    ];
}

// Fungsi untuk login user
function loginUser($email, $password) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM pengguna WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            startSessionIfNotStarted();
            
            // Simpan data user di session
            $_SESSION['user_id'] = $user['pengguna_id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['email'] = $user['email'];
            
            // Simpan juga seluruh data user untuk konsistensi
            $_SESSION['user'] = $user;
            
            return true;
        }
        
        return false;
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        return false;
    }
}

// Fungsi untuk registrasi user baru
function registerUser($name, $email, $password) {
    global $pdo;
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO pengguna (nama, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hashedPassword]);
        return true;
    } catch (PDOException $e) {
        error_log("Registration error: " . $e->getMessage());
        return false;
    }
}

// Fungsi untuk memastikan user sudah login
function requireAuth() {
    startSessionIfNotStarted();
    
    if (empty($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
}

// Fungsi untuk mendapatkan daftar keahlian
function getSkills() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("SELECT * FROM skills");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Skills error: " . $e->getMessage());
        return [];
    }
}

// Fungsi untuk mendapatkan daftar proyek
function getProjects() {
    global $pdo;
    
    try {
        $query = "SELECT p.*, u.nama AS user_name 
                FROM projects p 
                JOIN pengguna u ON p.user_id = u.pengguna_id";
        $stmt = $pdo->query($query);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Projects error: " . $e->getMessage());
        return [];
    }
}

// Fungsi untuk logout
function logoutUser() {
    startSessionIfNotStarted();
    
    // Hapus semua data session
    $_SESSION = array();
    
    // Hapus cookie session
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Hancurkan session
    session_destroy();
}
?>