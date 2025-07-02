<?php
session_start();
require_once '../includes/auth.php';

$user = authenticateUser();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skill-Connect - Platform Kolaborasi Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">
                <i class="fas fa-users me-2"></i>Skill-Connect
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="#">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#projects">Proyek</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Kontak</a></li>
                    <?php if ($user): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars($user['nama']) ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#profile">Profil</a></li>
                                <li><a class="dropdown-item" href="#" id="logout-btn">Keluar</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProjectModal">
                                <i class="fas fa-plus me-1"></i> Buat Proyek
                            </button>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="#login">Masuk</a></li>
                        <li class="nav-item ms-2"><a class="btn btn-primary" href="#register">Daftar</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <section class="hero-section bg-primary bg-opacity-10 rounded-4 p-5 mb-5">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="display-5 fw-bold mb-3">Temukan Kolaborator untuk Projek Kampus Anda</h1>
                    <p class="lead mb-4">Skill-Connect mempertemukan ide dengan eksekutor, dan keahlian dengan kesempatan di dalam ekosistem kampus.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="#projects" class="btn btn-primary btn-lg px-4">Lihat Projek</a>
                        <button class="btn btn-outline-primary btn-lg px-4" data-bs-toggle="modal" data-bs-target="#createProjectModal">Buat Proyek</button>
                    </div>
                </div>
                <div class="col-md-6 text-center">
                    <img src="https://via.placeholder.com/500x300" alt="Kolaborasi Mahasiswa" class="img-fluid rounded-3 shadow">
                </div>
            </div>
        </section>

        <section id="projects" class="mb-5">
            <h2 class="text-center mb-4 position-relative pb-3" style="border-bottom: 3px solid #2b6df6; display: inline-block;">
                Proyek Tersedia
            </h2>
            
            <div class="row mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="input-group">
                        <input type="text" id="project-search" class="form-control form-control-lg" placeholder="Cari proyek...">
                        <button class="btn btn-primary" type="button" id="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex flex-wrap gap-2">
                        <select class="form-select" id="category-filter">
                            <option value="">Semua Kategori</option>
                            <option>Hackathon</option>
                            <option>Business Case</option>
                            <option>Data Science</option>
                            <option>UI/UX Design</option>
                        </select>
                        <select class="form-select" id="skill-filter">
                            <option value="">Semua Keahlian</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row g-4" id="projects-container">
                <!-- Projects will be loaded here -->
                <div class="col-12 text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h3 class="mb-4"><i class="fas fa-users me-2"></i>Skill-Connect</h3>
                    <p>Platform kolaborasi untuk mahasiswa, mempertemukan ide dengan eksekutor, dan keahlian dengan kesempatan di dalam ekosistem kampus.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="mb-4">Tautan</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Beranda</a></li>
                        <li class="mb-2"><a href="#projects" class="text-white text-decoration-none">Proyek</a></li>
                        <li class="mb-2"><a href="#about" class="text-white text-decoration-none">Tentang Kami</a></li>
                        <li class="mb-2"><a href="#contact" class="text-white text-decoration-none">Kontak</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-4">Kontak</h5>
                    <p><i class="fas fa-map-marker-alt me-2"></i> Gedung Teknik Lt. 5, Universitas Teknologi Indonesia</p>
                    <p><i class="fas fa-envelope me-2"></i> info@skillconnect.ac.id</p>
                </div>
            </div>
            <div class="border-top mt-4 pt-4 text-center">
                <p>&copy; 2023 Skill-Connect. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- Create Project Modal -->
    <div class="modal fade" id="createProjectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Proyek Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="create-project-form">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Judul Proyek</label>
                            <input type="text" class="form-control" name="judul_project" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi Proyek</label>
                            <textarea class="form-control" name="deskripsi_project" rows="3" required></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Kategori</label>
                                <select class="form-select" name="kategori">
                                    <option value="">Pilih Kategori</option>
                                    <option>Hackathon</option>
                                    <option>Business Case</option>
                                    <option>Data Science</option>
                                    <option>UI/UX Design</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Maksimal Anggota</label>
                                <input type="number" class="form-control" name="max_members" min="1" max="20" value="5" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keahlian yang Dibutuhkan</label>
                            <div id="required-skills-container" class="d-flex flex-wrap gap-2 mb-2">
                                <!-- Skills will be added here -->
                            </div>
                            <div class="input-group">
                                <select class="form-select" id="skill-selector">
                                    <option value="">Pilih keahlian</option>
                                </select>
                                <button type="button" class="btn btn-outline-secondary" id="add-skill-btn">Tambah</button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gambar Proyek (Opsional)</label>
                            <input type="file" class="form-control" id="project-image" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Upload Proyek</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/app.js"></script>
</body>
</html>