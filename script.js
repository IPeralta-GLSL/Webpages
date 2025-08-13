// Project data with detailed information
const projectsData = {
    1: {
        title: "Custom 3D Renderer",
        description: `A high-performance 3D rendering engine built from scratch using modern OpenGL and C++. This project demonstrates advanced graphics programming techniques including:

• Physically Based Rendering (PBR) pipeline
• Real-time shadows with cascade shadow mapping
• Deferred rendering for optimal performance
• Post-processing effects (SSAO, Bloom, Tone mapping)
• Model loading with Assimp integration
• Custom shader management system
• Frustum culling and occlusion culling optimization

The renderer supports various lighting models, material systems, and provides a flexible architecture for creating complex 3D scenes. Performance optimizations include instanced rendering, GPU-driven rendering, and efficient memory management.`,
        technologies: ["C++", "OpenGL 4.6", "GLSL", "Assimp", "GLM", "GLFW"],
        images: [
            "https://via.placeholder.com/800x600/1a1a2e/ffffff?text=PBR+Rendering",
            "https://via.placeholder.com/800x600/1a1a2e/64ffda?text=Shadow+Mapping",
            "https://via.placeholder.com/800x600/1a1a2e/ffffff?text=Post+Processing",
            "https://via.placeholder.com/800x600/1a1a2e/64ffda?text=Material+System"
        ],
        links: [
            { name: "GitHub Repository", url: "#" },
            { name: "Live Demo", url: "#" },
            { name: "Documentation", url: "#" }
        ]
    },
    2: {
        title: "Physics Engine",
        description: `A comprehensive physics simulation engine designed for real-time applications. The engine implements advanced collision detection algorithms and supports various types of rigid body dynamics:

• Broad-phase collision detection using spatial partitioning
• Narrow-phase collision with SAT and GJK algorithms  
• Constraint-based physics solver with iterative methods
• CUDA acceleration for massive particle simulations
• Fluid dynamics simulation with SPH (Smoothed Particle Hydrodynamics)
• Soft body physics with mass-spring systems
• Integration with graphics pipeline for visualization

The engine is optimized for performance with multi-threading support and GPU acceleration where applicable. It includes debugging tools and visualization features to help developers understand and tune physics behaviors.`,
        technologies: ["C++", "CUDA", "OpenMP", "Eigen", "ImGui"],
        images: [
            "https://via.placeholder.com/800x600/16213e/ffffff?text=Collision+Detection",
            "https://via.placeholder.com/800x600/16213e/64ffda?text=Rigid+Body+Dynamics",
            "https://via.placeholder.com/800x600/16213e/ffffff?text=Fluid+Simulation",
            "https://via.placeholder.com/800x600/16213e/64ffda?text=Soft+Body+Physics"
        ],
        links: [
            { name: "GitHub Repository", url: "#" },
            { name: "Technical Paper", url: "#" },
            { name: "Performance Benchmarks", url: "#" }
        ]
    },
    3: {
        title: "Real-time Shader Editor",
        description: `An advanced shader development environment that provides real-time editing and preview capabilities. This tool is designed to streamline the shader development workflow:

• Live shader compilation and error reporting
• Node-based visual shader editor
• Real-time preview with customizable 3D scenes
• Hot-reload functionality for rapid iteration
• Support for multiple shading languages (HLSL, GLSL)
• Built-in library of common shader patterns
• Performance profiling and optimization suggestions
• Export functionality for various game engines

The editor features a modern interface built with C# and WPF, utilizing DirectX for rendering. It includes debugging tools, visual node graphs for complex shader logic, and integration with popular game engines.`,
        technologies: ["C#", "DirectX 11", "HLSL", "WPF", ".NET", "SharpDX"],
        images: [
            "https://via.placeholder.com/800x600/0f3460/ffffff?text=Shader+Editor+UI",
            "https://via.placeholder.com/800x600/0f3460/64ffda?text=Node+Graph+System",
            "https://via.placeholder.com/800x600/0f3460/ffffff?text=Real-time+Preview",
            "https://via.placeholder.com/800x600/0f3460/64ffda?text=Performance+Tools"
        ],
        links: [
            { name: "Download Tool", url: "#" },
            { name: "User Guide", url: "#" },
            { name: "Shader Library", url: "#" }
        ]
    },
    4: {
        title: "Indie Game Engine",
        description: `A complete game engine built specifically for indie developers, featuring a modern architecture and user-friendly editor. The engine focuses on ease of use while maintaining high performance:

• Vulkan-based rendering backend for maximum performance
• Component-based entity system (ECS architecture)
• Visual scripting system alongside C++ programming
• Cross-platform support (Windows, Linux, macOS)
• Built-in physics integration with custom collision system
• Audio system with 3D spatial audio support
• Asset pipeline with automatic optimization
• Integrated editor with scene management and debugging tools

The engine includes a comprehensive toolset for game development, from level design to asset management. It features hot-reloading capabilities, visual debugging tools, and export functionality for multiple platforms.`,
        technologies: ["C++", "Vulkan", "Qt", "Lua", "OpenAL", "Assimp"],
        images: [
            "https://via.placeholder.com/800x600/533483/ffffff?text=Engine+Editor",
            "https://via.placeholder.com/800x600/533483/64ffda?text=Scene+Management",
            "https://via.placeholder.com/800x600/533483/ffffff?text=Visual+Scripting",
            "https://via.placeholder.com/800x600/533483/64ffda?text=Asset+Pipeline"
        ],
        links: [
            { name: "Engine Download", url: "#" },
            { name: "Documentation", url: "#" },
            { name: "Sample Projects", url: "#" }
        ]
    },
    5: {
        title: "GPU Ray Tracer",
        description: `A high-performance ray tracing renderer leveraging GPU compute capabilities for photorealistic image generation. This project explores cutting-edge rendering techniques:

• Monte Carlo path tracing with importance sampling
• Bounding Volume Hierarchy (BVH) acceleration structures
• Multiple importance sampling for efficient light transport
• Support for complex materials (glass, metal, subsurface scattering)
• GPU-accelerated denoising algorithms
• Progressive rendering with adaptive sampling
• HDR environment lighting and tone mapping
• OpenCL compute shaders for maximum performance

The ray tracer is built in Rust for memory safety and performance, utilizing OpenCL for GPU acceleration. It supports various primitive types, complex lighting scenarios, and produces high-quality renders suitable for production use.`,
        technologies: ["Rust", "OpenCL", "GLSL", "OpenGL", "HDR", "OIDN"],
        images: [
            "https://via.placeholder.com/800x600/7209b7/ffffff?text=Ray+Traced+Scene",
            "https://via.placeholder.com/800x600/7209b7/64ffda?text=Material+Showcase",
            "https://via.placeholder.com/800x600/7209b7/ffffff?text=Lighting+Study",
            "https://via.placeholder.com/800x600/7209b7/64ffda?text=Performance+Metrics"
        ],
        links: [
            { name: "GitHub Repository", url: "#" },
            { name: "Render Gallery", url: "#" },
            { name: "Technical Blog", url: "#" }
        ]
    },
    6: {
        title: "VFX Processing Tool",
        description: `An innovative visual effects processing tool that combines traditional VFX techniques with modern machine learning approaches. This tool is designed for real-time visual effects processing:

• Real-time video processing pipeline with GPU acceleration
• Machine learning models for object detection and tracking
• Automated rotoscoping using semantic segmentation
• Style transfer and artistic effect filters
• Motion analysis and stabilization algorithms
• Green screen removal with edge refinement
• Temporal coherence for smooth video transitions
• Integration with popular video editing software

The tool utilizes Python with TensorFlow for machine learning components and OpenCV for traditional computer vision tasks. It features a user-friendly interface and supports batch processing for production workflows.`,
        technologies: ["Python", "TensorFlow", "OpenCV", "CUDA", "NumPy", "FFmpeg"],
        images: [
            "https://via.placeholder.com/800x600/a663cc/ffffff?text=VFX+Pipeline",
            "https://via.placeholder.com/800x600/a663cc/64ffda?text=ML+Object+Tracking",
            "https://via.placeholder.com/800x600/a663cc/ffffff?text=Style+Transfer",
            "https://via.placeholder.com/800x600/a663cc/64ffda?text=Real-time+Processing"
        ],
        links: [
            { name: "Tool Download", url: "#" },
            { name: "Tutorial Videos", url: "#" },
            { name: "Model Repository", url: "#" }
        ]
    }
};

// DOM Elements
const hamburger = document.querySelector('.hamburger');
const navMenu = document.querySelector('.nav-menu');
const projectCards = document.querySelectorAll('.project-card');
const modal = document.getElementById('projectModal');
const modalTitle = document.getElementById('modalTitle');
const modalMainImage = document.getElementById('modalMainImage');
const modalThumbnails = document.getElementById('modalThumbnails');
const modalDescription = document.getElementById('modalDescription');
const modalTech = document.getElementById('modalTech');
const modalLinks = document.getElementById('modalLinks');
const closeModal = document.querySelector('.close');

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

// Project card click handlers
projectCards.forEach(card => {
    card.addEventListener('click', () => {
        const projectId = card.getAttribute('data-project');
        openProjectModal(projectId);
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
    
    // Set technologies
    modalTech.innerHTML = `
        <h4>Technologies Used:</h4>
        <div class="skill-tags">
            ${project.technologies.map(tech => `<span class="skill-tag">${tech}</span>`).join('')}
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
