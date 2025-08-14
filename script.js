// Project data will be loaded dynamically from projects.json
let projectsData = {};

// DOM Elements
const hamburger = document.querySelector('.hamburger');
const navMenu = document.querySelector('.nav-menu');
let projectCards = []; // Will be populated after loading projects
const modal = document.getElementById('projectModal');
const modalTitle = document.getElementById('modalTitle');
const modalMainImage = document.getElementById('modalMainImage');
const modalThumbnails = document.getElementById('modalThumbnails');
const modalDescription = document.getElementById('modalDescription');
const modalTech = document.getElementById('modalTech');
const modalLinks = document.getElementById('modalLinks');
const closeModal = document.querySelector('.close');

// Load projects from JSON and initialize the page
async function loadProjects() {
    try {
        const response = await fetch('projects.json');
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const projects = await response.json();
        
        // Convert array to object with IDs as keys for compatibility
        projects.forEach(project => {
            projectsData[project.id] = project;
        });
        
        renderProjects(projects);
        initializeProjectCards();
        console.log('Projects loaded successfully!');
    } catch (error) {
        console.error('Error loading projects:', error);
        // Fallback: show error message or load default projects
        showProjectsError();
    }
}

// Render projects to the DOM
function renderProjects(projects) {
    const projectsGrid = document.getElementById('projectsGrid');
    if (!projectsGrid) {
        console.error('Projects grid element not found');
        return;
    }
    
    projectsGrid.innerHTML = projects.map(project => `
        <div class="project-card" data-project="${project.id}">
            <div class="project-image">
                <img src="${project.image}" alt="${project.title}">
                <div class="project-overlay">
                    <i class="fas fa-eye"></i>
                    <span>View Details</span>
                </div>
            </div>
            <div class="project-info">
                <h3>${project.title}</h3>
                <p>${project.subtitle}</p>
                <div class="project-tags">
                    ${project.tags.map(tag => `<span class="tag">${tag}</span>`).join('')}
                </div>
            </div>
        </div>
    `).join('');
}

// Initialize project cards event listeners
function initializeProjectCards() {
    projectCards = document.querySelectorAll('.project-card');
    projectCards.forEach(card => {
        card.addEventListener('click', () => {
            const projectId = card.getAttribute('data-project');
            openProjectModal(projectId);
        });
    });
    
    // Re-observe elements for scroll animations
    projectCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
}

// Show error message if projects fail to load
function showProjectsError() {
    const projectsGrid = document.getElementById('projectsGrid');
    if (projectsGrid) {
        projectsGrid.innerHTML = `
            <div class="projects-error">
                <i class="fas fa-exclamation-triangle"></i>
                <h3>Unable to load projects</h3>
                <p>There was an error loading the projects. Please try again later.</p>
            </div>
        `;
    }
}

// Mobile Navigation Toggle
hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('active');
    navMenu.classList.toggle('active');
});

// Close mobile menu when clicking on a link
document.querySelectorAll('.nav-link').forEach(n => n.addEventListener('click', () => {
    hamburger.classList.remove('active');
    navMenu.classList.remove('active');
}));

// Smooth scrolling for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Function to open project modal
function openProjectModal(projectId) {
    const project = projectsData[projectId];
    if (!project) return;

    // Set modal content
    modalTitle.textContent = project.title;
    modalDescription.textContent = project.description;
    
    // Set main image
    modalMainImage.src = project.images[0];
    modalMainImage.alt = project.title;
    
    // Create thumbnails
    modalThumbnails.innerHTML = '';
    project.images.forEach((image, index) => {
        const thumbnail = document.createElement('img');
        thumbnail.src = image;
        thumbnail.alt = `${project.title} - Image ${index + 1}`;
        thumbnail.className = `thumbnail ${index === 0 ? 'active' : ''}`;
        thumbnail.addEventListener('click', () => {
            modalMainImage.src = image;
            document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
            thumbnail.classList.add('active');
        });
        modalThumbnails.appendChild(thumbnail);
    });
    
    // Set technologies (now using tags)
    modalTech.innerHTML = `
        <h4>Technologies & Tags:</h4>
        <div class="skill-tags">
            ${project.tags.map(tag => `<span class="skill-tag">${tag}</span>`).join('')}
        </div>
    `;
    
    // Set links
    modalLinks.innerHTML = project.links.map(link => 
        `<a href="${link.url}" target="_blank">${link.name}</a>`
    ).join('');
    
    // Show modal
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

// Close modal
closeModal.addEventListener('click', closeProjectModal);
modal.addEventListener('click', (e) => {
    if (e.target === modal) {
        closeProjectModal();
    }
});

function closeProjectModal() {
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Close modal with Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.style.display === 'block') {
        closeProjectModal();
    }
});

// Navbar background change on scroll
window.addEventListener('scroll', () => {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 100) {
        navbar.style.background = 'rgba(10, 10, 10, 0.98)';
    } else {
        navbar.style.background = 'rgba(10, 10, 10, 0.95)';
    }
});

// Intersection Observer for animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe elements for scroll animations
document.querySelectorAll('.project-card, .about-text, .skills-section, .profile-image, .skill-icon').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px)';
    el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(el);
});

// Typing effect for hero subtitle
function typeWriter(element, text, speed = 50) {
    let i = 0;
    element.textContent = '';
    
    function type() {
        if (i < text.length) {
            element.textContent += text.charAt(i);
            i++;
            setTimeout(type, speed);
        }
    }
    
    type();
}

// Start typing effect when page loads
window.addEventListener('load', () => {
    const subtitle = document.querySelector('.hero-subtitle');
    const originalText = subtitle.textContent;
    typeWriter(subtitle, originalText, 30);
});

// Parallax effect for hero section
window.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    const hero = document.querySelector('.hero');
    if (hero) {
        hero.style.transform = `translateY(${scrolled * 0.5}px)`;
    }
});

// Add loading animation
window.addEventListener('load', () => {
    const loadingScreen = document.createElement('div');
    loadingScreen.className = 'loading-screen';
    loadingScreen.innerHTML = `
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <p>Loading Portfolio...</p>
        </div>
    `;
    
    // Add loading screen styles
    const loadingStyles = document.createElement('style');
    loadingStyles.textContent = `
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #0a0a0a;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 1;
            transition: opacity 0.5s ease;
        }
        
        .loading-content {
            text-align: center;
            color: #4CAF50;
        }
        
        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(76, 175, 80, 0.3);
            border-top: 3px solid #4CAF50;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    `;
    
    document.head.appendChild(loadingStyles);
    document.body.appendChild(loadingScreen);
    
    // Hide loading screen after a short delay
    setTimeout(() => {
        loadingScreen.style.opacity = '0';
        setTimeout(() => {
            loadingScreen.remove();
            loadingStyles.remove();
        }, 500);
    }, 1500);
});

// Add particle background effect
function createParticles() {
    const particlesContainer = document.createElement('div');
    particlesContainer.className = 'particles-container';
    particlesContainer.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 1;
        overflow: hidden;
    `;
    
    for (let i = 0; i < 50; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.cssText = `
            position: absolute;
            width: 2px;
            height: 2px;
            background: rgba(76, 175, 80, 0.3);
            border-radius: 50%;
            animation: float-particle ${5 + Math.random() * 10}s linear infinite;
            left: ${Math.random() * 100}%;
            top: ${Math.random() * 100}%;
            animation-delay: ${Math.random() * 10}s;
        `;
        particlesContainer.appendChild(particle);
    }
    
    // Add particle animation styles
    const particleStyles = document.createElement('style');
    particleStyles.textContent = `
        @keyframes float-particle {
            0% {
                transform: translateY(0px) translateX(0px);
                opacity: 0;
            }
            10% {
                opacity: 0.3;
            }
            90% {
                opacity: 0.3;
            }
            100% {
                transform: translateY(-100vh) translateX(${Math.random() * 200 - 100}px);
                opacity: 0;
            }
        }
    `;
    
    document.head.appendChild(particleStyles);
    document.body.appendChild(particlesContainer);
}

// Initialize particles
createParticles();

// WebGL Cosmic Background Shader
class CosmicShader {
    constructor() {
        this.canvas = document.getElementById('shader-canvas');
        this.gl = null;
        this.program = null;
        this.startTime = Date.now();
        this.init();
    }

    async init() {
        this.gl = this.canvas.getContext('webgl') || this.canvas.getContext('experimental-webgl');
        if (!this.gl) {
            console.warn('WebGL not supported, falling back to CSS background');
            return;
        }

        this.resizeCanvas();
        window.addEventListener('resize', () => this.resizeCanvas());

        await this.setupShaders();
        this.render();
    }

    resizeCanvas() {
        const rect = this.canvas.parentElement.getBoundingClientRect();
        this.canvas.width = rect.width;
        this.canvas.height = rect.height;
        if (this.gl) {
            this.gl.viewport(0, 0, this.canvas.width, this.canvas.height);
        }
    }

    async setupShaders() {
        try {
            // Load shader files
            const vertexShaderSource = await this.loadShaderFile('shaders/vertex.glsl');
            const fragmentShaderSource = await this.loadShaderFile('shaders/fragment.glsl');

            const vertexShader = this.createShader(this.gl.VERTEX_SHADER, vertexShaderSource);
            const fragmentShader = this.createShader(this.gl.FRAGMENT_SHADER, fragmentShaderSource);

            this.program = this.createProgram(vertexShader, fragmentShader);
            this.gl.useProgram(this.program);

            // Create vertex buffer
            const positions = new Float32Array([
                -1, -1,
                 1, -1,
                -1,  1,
                 1,  1,
            ]);

            const positionBuffer = this.gl.createBuffer();
            this.gl.bindBuffer(this.gl.ARRAY_BUFFER, positionBuffer);
            this.gl.bufferData(this.gl.ARRAY_BUFFER, positions, this.gl.STATIC_DRAW);

            const positionAttribute = this.gl.getAttribLocation(this.program, 'position');
            this.gl.enableVertexAttribArray(positionAttribute);
            this.gl.vertexAttribPointer(positionAttribute, 2, this.gl.FLOAT, false, 0, 0);

            // Get uniform locations
            this.timeUniform = this.gl.getUniformLocation(this.program, 'time');
            this.resolutionUniform = this.gl.getUniformLocation(this.program, 'resolution');
        } catch (error) {
            console.error('Error loading shaders:', error);
        }
    }

    async loadShaderFile(url) {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Failed to load shader: ${url}`);
        }
        return await response.text();
    }

    createShader(type, source) {
        const shader = this.gl.createShader(type);
        this.gl.shaderSource(shader, source);
        this.gl.compileShader(shader);

        if (!this.gl.getShaderParameter(shader, this.gl.COMPILE_STATUS)) {
            console.error('Shader compilation error:', this.gl.getShaderInfoLog(shader));
            this.gl.deleteShader(shader);
            return null;
        }

        return shader;
    }

    createProgram(vertexShader, fragmentShader) {
        const program = this.gl.createProgram();
        this.gl.attachShader(program, vertexShader);
        this.gl.attachShader(program, fragmentShader);
        this.gl.linkProgram(program);

        if (!this.gl.getProgramParameter(program, this.gl.LINK_STATUS)) {
            console.error('Program linking error:', this.gl.getProgramInfoLog(program));
            this.gl.deleteProgram(program);
            return null;
        }

        return program;
    }

    render() {
        if (!this.gl || !this.program) return;

        const currentTime = (Date.now() - this.startTime) / 1000;

        this.gl.uniform1f(this.timeUniform, currentTime);
        this.gl.uniform2f(this.resolutionUniform, this.canvas.width, this.canvas.height);

        this.gl.clearColor(0.02, 0.02, 0.08, 1.0);
        this.gl.clear(this.gl.COLOR_BUFFER_BIT);

        this.gl.drawArrays(this.gl.TRIANGLE_STRIP, 0, 4);

        requestAnimationFrame(() => this.render());
    }
}

// Initialize shader when page loads
document.addEventListener('DOMContentLoaded', () => {
    loadProjects(); // Load projects dynamically
    new CosmicShader();
    loadFragmentShaderCode();
    initAudioControl();
});

// Audio control functionality
function initAudioControl() {
    const audioToggle = document.getElementById('audioToggle');
    const audioIcon = document.getElementById('audioIcon');
    const bgMusic = document.getElementById('bgMusic');
    const volumeSlider = document.getElementById('volumeSlider');
    
    if (!audioToggle || !audioIcon || !bgMusic || !volumeSlider) return;

    let isPlaying = false;

    // Set initial volume
    bgMusic.volume = 0.2;

    // Function to update volume icon based on volume level
    function updateVolumeIcon(volume) {
        if (volume === 0 || !isPlaying) {
            audioIcon.className = 'fas fa-volume-mute';
        } else if (volume < 0.3) {
            audioIcon.className = 'fas fa-volume-down';
        } else if (volume < 0.7) {
            audioIcon.className = 'fas fa-volume-up';
        } else {
            audioIcon.className = 'fas fa-volume-up';
        }
    }

    // Function to start audio with multiple attempts
    function startAudio() {
        if (isPlaying) return;
        
        bgMusic.currentTime = 0; // Start from beginning
        
        const playPromise = bgMusic.play();
        
        if (playPromise !== undefined) {
            playPromise.then(() => {
                isPlaying = true;
                audioToggle.classList.remove('muted');
                audioToggle.title = 'Pause ambient music';
                updateVolumeIcon(bgMusic.volume);
                console.log('Audio started successfully');
            }).catch(error => {
                console.log('Auto-play attempt failed:', error);
                isPlaying = false;
                audioIcon.className = 'fas fa-volume-mute';
                audioToggle.classList.add('muted');
                audioToggle.title = 'Play ambient music';
            });
        }
    }

    // Function to toggle audio
    function toggleAudio() {
        if (isPlaying) {
            bgMusic.pause();
            isPlaying = false;
            audioIcon.className = 'fas fa-volume-mute';
            audioToggle.classList.add('muted');
            audioToggle.title = 'Play ambient music';
        } else {
            startAudio();
        }
    }

    // Volume slider event listener
    volumeSlider.addEventListener('input', (e) => {
        const volume = e.target.value / 100;
        bgMusic.volume = volume;
        updateVolumeIcon(volume);
        
        // If volume is 0, show muted state but don't stop playback
        if (volume === 0) {
            audioToggle.classList.add('muted');
        } else {
            audioToggle.classList.remove('muted');
        }
    });

    // Add click event listener
    audioToggle.addEventListener('click', toggleAudio);

    // Initialize volume icon
    updateVolumeIcon(bgMusic.volume);
}

// Load and display fragment shader code
async function loadFragmentShaderCode() {
    try {
        const response = await fetch('shaders/fragment.glsl');
        const shaderCode = await response.text();
        displayShaderCode(shaderCode);
    } catch (error) {
        console.warn('Could not load fragment shader file:', error);
    }
}

function displayShaderCode(code) {
    const codeAnimation = document.querySelector('.code-animation');
    if (!codeAnimation) return;

    // Clear existing content
    codeAnimation.innerHTML = '';

    // Split code into lines
    const lines = code.split('\n');
    
    lines.forEach((line, index) => {
        const codeLine = document.createElement('div');
        codeLine.className = 'code-line';
        if (line.startsWith('    ') || line.startsWith('\t')) {
            codeLine.classList.add('indent');
        }
        
        // Add line number
        const lineNumber = document.createElement('span');
        lineNumber.className = 'line-number';
        lineNumber.textContent = (index + 1).toString();
        codeLine.appendChild(lineNumber);

        // Add syntax highlighting
        const syntaxHighlighted = highlightGLSL(line);
        const codeContent = document.createElement('span');
        codeContent.innerHTML = syntaxHighlighted; // This should render the HTML
        codeLine.appendChild(codeContent);

        // Add animation delay
        codeLine.style.animationDelay = `${index * 0.05}s`;
        
        codeAnimation.appendChild(codeLine);
    });
}

function highlightGLSL(line) {
    // Escape HTML characters first
    let highlighted = line
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
    
    // Comments (do this first to avoid highlighting inside comments)
    highlighted = highlighted.replace(/(\/\/.*$)/g, '<span class="code-comment">$1</span>');

    // Only highlight if not inside a comment
    if (!highlighted.includes('code-comment')) {
        // Keywords
        highlighted = highlighted.replace(/\b(precision|uniform|float|vec2|vec3|vec4|int|bool|void|return|if|else|for|while|mediump)\b/g, '<span class="code-keyword">$1</span>');
        
        // Functions (words followed by parentheses)
        highlighted = highlighted.replace(/\b(fract|sin|cos|dot|mix|smoothstep|distance|pow|floor|main|noise|smoothNoise|fractalNoise|cosmicBackground)(?=\s*\()/g, '<span class="code-function">$1</span>');
        
        // Numbers
        highlighted = highlighted.replace(/\b\d+\.?\d*\b/g, '<span class="code-constant">$&</span>');
        
        // Variables
        highlighted = highlighted.replace(/\b(time|resolution|uv|color|nebula1|nebula2|nebula3|flow1|flow2|glow|dist|vignette|value|amplitude)\b/g, '<span class="code-variable">$1</span>');
    }

    return highlighted;
}
