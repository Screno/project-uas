document.addEventListener('DOMContentLoaded', () => {
    console.log("DOM fully loaded");
    
    // Load skills and projects
    loadSkills();
    loadProjects();
    
    // Handle logout button
    document.getElementById('logout-btn')?.addEventListener('click', logoutUser);
});

function loadSkills() {
    fetch('api/skills.php')
        .then(response => {
            if (!response.ok) throw new Error('Failed to load skills');
            return response.json();
        })
        .then(data => {
            console.log("Skills loaded:", data);
            renderSkills(data);
        })
        .catch(error => {
            console.error('Error loading skills:', error);
            document.getElementById('skills-container').innerHTML = `
                <div class="alert alert-danger">
                    Gagal memuat daftar keahlian: ${error.message}
                </div>
            `;
        });
}

function loadProjects() {
    fetch('api/projects.php')
        .then(response => {
            if (!response.ok) throw new Error('Failed to load projects');
            return response.json();
        })
        .then(data => {
            console.log("Projects loaded:", data);
            renderProjects(data);
        })
        .catch(error => {
            console.error('Error loading projects:', error);
            document.getElementById('projects-container').innerHTML = `
                <div class="alert alert-danger">
                    Gagal memuat proyek: ${error.message}
                </div>
            `;
        });
}

function renderSkills(skills) {
    const container = document.getElementById('skills-container');
    container.innerHTML = '';
    
    skills.forEach(skill => {
        const skillElement = document.createElement('div');
        skillElement.className = 'badge bg-primary me-2 mb-2';
        skillElement.textContent = skill.name;
        container.appendChild(skillElement);
    });
}

function renderProjects(projects) {
    const container = document.getElementById('projects-container');
    container.innerHTML = '';
    
    projects.forEach(project => {
        const projectElement = document.createElement('div');
        projectElement.className = 'card mb-3';
        projectElement.innerHTML = `
            <div class="card-body">
                <h5 class="card-title">${project.title}</h5>
                <p class="card-text">${project.description}</p>
                <p class="text-muted">Dibuat oleh: ${project.user_name}</p>
                <span class="badge bg-info">${project.status}</span>
            </div>
        `;
        container.appendChild(projectElement);
    });
}

function logoutUser(e) {
    e.preventDefault();
    fetch('api/logout.php')
        .then(response => {
            if (response.ok) {
                window.location.href = 'login.php';
            }
        });
}