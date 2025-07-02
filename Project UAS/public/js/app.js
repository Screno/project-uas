document.addEventListener('DOMContentLoaded', () => {
    initApp();
});

async function initApp() {
    // Load skills for dropdown
    await loadSkills();
    
    // Load initial projects
    await loadProjects();
    
    // Setup event listeners
    setupEventListeners();
}

async function loadSkills() {
    try {
        const response = await fetch('/api/skills');
        if (!response.ok) throw new Error('Failed to load skills');
        
        const skills = await response.json();
        const skillSelector = document.getElementById('skill-selector');
        
        // Clear existing options except the first one
        while (skillSelector.options.length > 1) {
            skillSelector.remove(1);
        }
        
        // Add skills to selector
        skills.forEach(skill => {
            const option = document.createElement('option');
            option.value = skill.skill_id;
            option.textContent = skill.nama_skill;
            skillSelector.appendChild(option);
        });
    } catch (error) {
        showError('Gagal memuat daftar keahlian: ' + error.message);
    }
}

async function loadProjects(searchTerm = '', category = '', skill = '') {
    const container = document.getElementById('projects-container');
    container.innerHTML = `
        <div class="col-12 text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    
    try {
        const params = new URLSearchParams();
        if (searchTerm) params.append('search', searchTerm);
        if (category) params.append('category', category);
        if (skill) params.append('skill', skill);
        
        const response = await fetch(`/api/projects?${params.toString()}`);
        if (!response.ok) throw new Error('Failed to load projects');
        
        const projects = await response.json();
        renderProjects(projects);
    } catch (error) {
        container.innerHTML = `
            <div class="col-12">
                <div class="alert alert-danger">
                    Gagal memuat proyek: ${error.message}
                </div>
            </div>
        `;
    }
}

function renderProjects(projects) {
    const container = document.getElementById('projects-container');
    
    if (!projects || projects.length === 0) {
        container.innerHTML = `
            <div class="col-12">
                <div class="alert alert-info">
                    Tidak ada proyek yang tersedia saat ini.
                </div>
            </div>
        `;
        return;
    }
    
    let html = '';
    
    projects.forEach(project => {
        const availableSlots = project.available_slots;
        const isRecruiting = project.status === 'Merekrut' && availableSlots > 0;
        const badgeClass = isRecruiting ? 'bg-success' : 'bg-secondary';
        const badgeText = isRecruiting ? `Mencari ${availableSlots} Anggota` : 'Penuh';
        
        html += `
        <div class="col-lg-4 col-md-6">
            <div class="card project-card h-100">
                <span class="badge ${badgeClass} badge-status">${badgeText}</span>
                
                ${project.image_path ? 
                    `<img src="${project.image_path}" class="card-img-top project-image" alt="${project.judul_project}">` : 
                    `<div class="card-img-top project-image bg-light d-flex align-items-center justify-content-center">
                        <i class="fas fa-project-diagram fa-3x text-muted"></i>
                    </div>`
                }
                
                <div class="card-body">
                    <h5 class="card-title">${project.judul_project}</h5>
                    <p class="card-text">${truncateText(project.deskripsi_project || 'Tidak ada deskripsi', 100)}</p>
                    
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <div>
                            <span class="member-count">
                                <i class="fas fa-users me-1"></i>
                                ${project.current_members}/${project.max_members} Anggota
                            </span>
                        </div>
                        <div>
                            <small class="text-muted">${formatDate(project.tgl_dibuat)}</small>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <button class="btn ${isRecruiting ? 'btn-primary' : 'btn-outline-secondary'} w-100" 
                            ${isRecruiting ? '' : 'disabled'}>
                        ${isRecruiting ? 'Lihat Detail' : 'Projek Penuh'}
                    </button>
                </div>
            </div>
        </div>
        `;
    });
    
    container.innerHTML = html;
}

function setupEventListeners() {
    // Search button
    document.getElementById('search-btn').addEventListener('click', () => {
        const searchTerm = document.getElementById('project-search').value;
        const category = document.getElementById('category-filter').value;
        const skill = document.getElementById('skill-filter').value;
        loadProjects(searchTerm, category, skill);
    });
    
    // Add skill to project
    document.getElementById('add-skill-btn').addEventListener('click', () => {
        const skillSelector = document.getElementById('skill-selector');
        const selectedOption = skillSelector.options[skillSelector.selectedIndex];
        
        if (selectedOption.value) {
            const skillId = selectedOption.value;
            const skillName = selectedOption.text;
            
            const skillBadge = document.createElement('div');
            skillBadge.className = 'skill-badge d-flex align-items-center';
            skillBadge.innerHTML = `
                ${skillName}
                <input type="hidden" name="required_skills[]" value="${skillId}">
                <button type="button" class="btn-close ms-2 remove-skill"></button>
            `;
            
            document.getElementById('required-skills-container').appendChild(skillBadge);
            
            // Reset selector
            skillSelector.selectedIndex = 0;
            
            // Add remove event
            skillBadge.querySelector('.remove-skill').addEventListener('click', function() {
                skillBadge.remove();
            });
        }
    });
    
    // Create project form
    document.getElementById('create-project-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const form = e.target;
        const formData = new FormData(form);
        
        // Handle file upload
        const fileInput = document.getElementById('project-image');
        if (fileInput.files.length > 0) {
            try {
                const uploadResponse = await uploadFile(fileInput.files[0]);
                if (uploadResponse.path) {
                    formData.append('image_path', uploadResponse.path);
                }
            } catch (error) {
                showError('Gagal mengunggah gambar: ' + error.message);
                return;
            }
        }
        
        // Convert to JSON
        const jsonData = {};
        for (const [key, value] of formData.entries()) {
            if (key === 'required_skills[]') {
                if (!jsonData.required_skills) jsonData.required_skills = [];
                jsonData.required_skills.push(value);
            } else {
                jsonData[key] = value;
            }
        }
        
        try {
            const response = await fetch('/api/projects', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(jsonData)
            });
            
            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.error || 'Gagal membuat proyek');
            }
            
            const result = await response.json();
            showSuccess('Proyek berhasil dibuat!');
            
            // Reset form
            form.reset();
            document.getElementById('required-skills-container').innerHTML = '';
            
            // Close modal
            bootstrap.Modal.getInstance(document.getElementById('createProjectModal')).hide();
            
            // Reload projects
            await loadProjects();
            
        } catch (error) {
            showError('Gagal membuat proyek: ' + error.message);
        }
    });
}

async function uploadFile(file) {
    const formData = new FormData();
    formData.append('file', file);
    
    const response = await fetch('/api/upload', {
        method: 'POST',
        body: formData
    });
    
    if (!response.ok) {
        const error = await response.json();
        throw new Error(error.error || 'Upload failed');
    }
    
    return await response.json();
}

// Helper functions
function truncateText(text, maxLength) {
    return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
}

function showError(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3';
    alertDiv.style.zIndex = '1100';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        bootstrap.Alert.getInstance(alertDiv)?.close();
    }, 5000);
}

function showSuccess(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
    alertDiv.style.zIndex = '1100';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto-dismiss after 3 seconds
    setTimeout(() => {
        bootstrap.Alert.getInstance(alertDiv)?.close();
    }, 3000);
}