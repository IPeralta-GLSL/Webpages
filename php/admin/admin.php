<?php
session_start();
if (empty($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Projects</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #0a0a0a;
            color: #fff;
            min-height: 100vh;
        }

        header {
            background: rgba(255,255,255,0.04);
            border-bottom: 1px solid rgba(255,255,255,0.08);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
        }

        header h1 { font-size: 1.2rem; font-weight: 600; color: #a78bfa; }

        .header-actions { display: flex; gap: 0.75rem; align-items: center; }

        .btn {
            padding: 0.5rem 1.2rem;
            border: none;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
        }

        .btn-primary { background: #7c3aed; color: #fff; }
        .btn-primary:hover { background: #6d28d9; }
        .btn-success { background: #059669; color: #fff; }
        .btn-success:hover { background: #047857; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-ghost {
            background: transparent;
            color: #9ca3af;
            border: 1px solid rgba(255,255,255,0.12);
        }
        .btn-ghost:hover { background: rgba(255,255,255,0.06); color: #fff; }

        .save-status {
            font-size: 0.8rem;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-weight: 500;
        }
        .save-status.saved { background: rgba(5,150,105,0.2); color: #34d399; }
        .save-status.unsaved { background: rgba(245,158,11,0.2); color: #fbbf24; }
        .save-status.saving { background: rgba(124,58,237,0.2); color: #a78bfa; }
        .save-status.error { background: rgba(220,38,38,0.2); color: #f87171; }

        main { max-width: 1100px; margin: 0 auto; padding: 2rem; }

        .projects-list { display: flex; flex-direction: column; gap: 0.75rem; }

        .project-row {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px;
            overflow: hidden;
            transition: border-color 0.2s;
        }

        .project-row.dragging {
            opacity: 0.5;
            border-color: #7c3aed;
        }

        .project-row.drag-over {
            border-color: #a78bfa;
            background: rgba(124,58,237,0.08);
        }

        .project-header {
            display: flex;
            align-items: center;
            padding: 0.875rem 1rem;
            gap: 0.875rem;
            cursor: pointer;
            user-select: none;
        }

        .drag-handle {
            color: #4b5563;
            cursor: grab;
            padding: 0.25rem;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .drag-handle:active { cursor: grabbing; }

        .project-thumb {
            width: 52px;
            height: 36px;
            border-radius: 6px;
            object-fit: cover;
            background: #1f2937;
            flex-shrink: 0;
        }

        .project-meta { flex: 1; min-width: 0; }
        .project-meta h3 { font-size: 0.9rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .project-meta p { font-size: 0.75rem; color: #6b7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .project-row-actions { display: flex; gap: 0.5rem; flex-shrink: 0; }

        .icon-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.4rem 0.5rem;
            border-radius: 6px;
            color: #6b7280;
            transition: all 0.15s;
            font-size: 0.85rem;
        }

        .icon-btn:hover { background: rgba(255,255,255,0.08); color: #fff; }
        .icon-btn.delete:hover { background: rgba(220,38,38,0.15); color: #f87171; }

        .project-form {
            display: none;
            padding: 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.06);
            background: rgba(0,0,0,0.2);
        }

        .project-form.open { display: block; }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

        .form-group { display: flex; flex-direction: column; gap: 0.35rem; }
        .form-group.full { grid-column: 1 / -1; }

        label { font-size: 0.75rem; font-weight: 500; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em; }

        input[type="text"],
        input[type="url"],
        textarea {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            padding: 0.6rem 0.75rem;
            color: #fff;
            font-family: inherit;
            font-size: 0.875rem;
            transition: border-color 0.2s;
            width: 100%;
        }

        input[type="text"]:focus,
        input[type="url"]:focus,
        textarea:focus {
            outline: none;
            border-color: #7c3aed;
        }

        textarea { resize: vertical; min-height: 100px; }

        .array-field { display: flex; flex-direction: column; gap: 0.4rem; }

        .array-item {
            display: flex;
            gap: 0.4rem;
            align-items: center;
        }

        .array-item input { flex: 1; }

        .remove-item-btn {
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
            padding: 0.35rem;
            border-radius: 4px;
            font-size: 0.8rem;
            flex-shrink: 0;
            transition: all 0.15s;
        }

        .remove-item-btn:hover { color: #f87171; background: rgba(220,38,38,0.1); }

        .add-item-btn {
            background: none;
            border: 1px dashed rgba(255,255,255,0.15);
            border-radius: 6px;
            color: #6b7280;
            cursor: pointer;
            padding: 0.4rem 0.75rem;
            font-size: 0.8rem;
            font-family: inherit;
            transition: all 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            margin-top: 0.2rem;
        }

        .add-item-btn:hover { color: #a78bfa; border-color: #7c3aed; background: rgba(124,58,237,0.06); }

        .link-pair { display: grid; grid-template-columns: 1fr 1fr auto; gap: 0.4rem; align-items: center; }
        .link-pair input { min-width: 0; }

        .order-badge {
            font-size: 0.7rem;
            background: rgba(124,58,237,0.2);
            color: #a78bfa;
            border-radius: 4px;
            padding: 0.1rem 0.4rem;
            font-weight: 600;
            flex-shrink: 0;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #4b5563;
        }

        .empty-state i { font-size: 2.5rem; margin-bottom: 1rem; }

        .toast {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            padding: 0.75rem 1.25rem;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 500;
            z-index: 9999;
            transition: opacity 0.3s, transform 0.3s;
            opacity: 0;
            transform: translateY(10px);
            pointer-events: none;
        }

        .toast.show { opacity: 1; transform: translateY(0); }
        .toast.success { background: #065f46; color: #6ee7b7; border: 1px solid #059669; }
        .toast.error { background: #7f1d1d; color: #fca5a5; border: 1px solid #dc2626; }

        @media (max-width: 700px) {
            .form-grid { grid-template-columns: 1fr; }
            .form-group.full { grid-column: 1; }
            main { padding: 1rem; }
        }
    </style>
</head>
<body>

<header>
    <h1><i class="fas fa-layer-group"></i> Projects Admin</h1>
    <div class="header-actions">
        <span class="save-status saved" id="saveStatus">Saved</span>
        <button class="btn btn-primary" id="addBtn"><i class="fas fa-plus"></i> Add Project</button>
        <button class="btn btn-success" id="saveBtn"><i class="fas fa-save"></i> Save Changes</button>
        <a href="logout.php" class="btn btn-ghost"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</header>

<main>
    <div class="projects-list" id="projectsList"></div>
    <div class="empty-state" id="emptyState" style="display:none">
        <i class="fas fa-folder-open"></i>
        <p>No projects yet. Click "Add Project" to create one.</p>
    </div>
</main>

<div class="toast" id="toast"></div>

<script>
let projects = [];
let dragSrc = null;

async function loadProjects() {
    const res = await fetch('../../projects.json?t=' + Date.now());
    projects = await res.json();
    renderAll();
}

function renderAll() {
    const list = document.getElementById('projectsList');
    const empty = document.getElementById('emptyState');
    list.innerHTML = '';
    if (projects.length === 0) { empty.style.display = 'block'; return; }
    empty.style.display = 'none';

    projects.forEach((p, i) => {
        list.appendChild(buildRow(p, i));
    });
}

function buildRow(p, index) {
    const row = document.createElement('div');
    row.className = 'project-row';
    row.dataset.index = index;
    row.draggable = true;

    const thumb = p.image ? `<img class="project-thumb" src="${escHtml(p.image)}" alt="">` : `<div class="project-thumb"></div>`;

    row.innerHTML = `
        <div class="project-header">
            <span class="drag-handle"><i class="fas fa-grip-vertical"></i></span>
            <span class="order-badge">#${index + 1}</span>
            ${thumb}
            <div class="project-meta">
                <h3>${escHtml(p.title || 'Untitled')}</h3>
                <p>${escHtml(p.subtitle || '')}</p>
            </div>
            <div class="project-row-actions">
                <button class="icon-btn toggle-btn" title="Edit"><i class="fas fa-chevron-down"></i></button>
                <button class="icon-btn delete" title="Delete"><i class="fas fa-trash"></i></button>
            </div>
        </div>
        <div class="project-form" id="form-${index}">
            ${buildForm(p, index)}
        </div>
    `;

    row.querySelector('.toggle-btn').addEventListener('click', (e) => {
        e.stopPropagation();
        toggleForm(index, row);
    });

    row.querySelector('.project-header').addEventListener('click', () => {
        toggleForm(index, row);
    });

    row.querySelector('.delete').addEventListener('click', (e) => {
        e.stopPropagation();
        deleteProject(index);
    });

    row.addEventListener('dragstart', onDragStart);
    row.addEventListener('dragover', onDragOver);
    row.addEventListener('dragleave', onDragLeave);
    row.addEventListener('drop', onDrop);
    row.addEventListener('dragend', onDragEnd);

    return row;
}

function buildForm(p, index) {
    const tagsVal = (p.tags || []).join(', ');
    const imagesHtml = (p.images || []).map((img, i) => imageItem(img, index, i)).join('');
    const linksHtml = (p.links || []).map((l, i) => linkItem(l.name, l.url, index, i)).join('');

    return `
        <div class="form-grid">
            <div class="form-group">
                <label>Title</label>
                <input type="text" data-field="title" value="${escAttr(p.title || '')}" oninput="updateField(${index},'title',this.value)">
            </div>
            <div class="form-group">
                <label>Subtitle</label>
                <input type="text" data-field="subtitle" value="${escAttr(p.subtitle || '')}" oninput="updateField(${index},'subtitle',this.value)">
            </div>
            <div class="form-group full">
                <label>Main Image Path</label>
                <input type="text" data-field="image" value="${escAttr(p.image || '')}" oninput="updateField(${index},'image',this.value)">
            </div>
            <div class="form-group full">
                <label>Tags (comma separated)</label>
                <input type="text" data-field="tags" value="${escAttr(tagsVal)}" oninput="updateTags(${index},this.value)">
            </div>
            <div class="form-group full">
                <label>Description</label>
                <textarea data-field="description" oninput="updateField(${index},'description',this.value)">${escHtml(p.description || '')}</textarea>
            </div>
            <div class="form-group full">
                <label>Video URL (optional)</label>
                <input type="url" data-field="video" value="${escAttr(p.video || '')}" oninput="updateField(${index},'video',this.value)">
            </div>
            <div class="form-group full">
                <label>Gallery Images</label>
                <div class="array-field" id="images-${index}">${imagesHtml}</div>
                <button class="add-item-btn" onclick="addImage(${index})"><i class="fas fa-plus"></i> Add Image</button>
            </div>
            <div class="form-group full">
                <label>Links</label>
                <div class="array-field" id="links-${index}">${linksHtml}</div>
                <button class="add-item-btn" onclick="addLink(${index})"><i class="fas fa-plus"></i> Add Link</button>
            </div>
        </div>
    `;
}

function imageItem(val, projIndex, imgIndex) {
    return `<div class="array-item" id="img-item-${projIndex}-${imgIndex}">
        <input type="text" value="${escAttr(val)}" oninput="updateImageItem(${projIndex},${imgIndex},this.value)" placeholder="Portfolio/folder/image.png">
        <button class="remove-item-btn" onclick="removeImage(${projIndex},${imgIndex})"><i class="fas fa-times"></i></button>
    </div>`;
}

function linkItem(name, url, projIndex, linkIndex) {
    return `<div class="link-pair array-item" id="link-item-${projIndex}-${linkIndex}">
        <input type="text" value="${escAttr(name)}" oninput="updateLinkItem(${projIndex},${linkIndex},'name',this.value)" placeholder="Label">
        <input type="text" value="${escAttr(url)}" oninput="updateLinkItem(${projIndex},${linkIndex},'url',this.value)" placeholder="URL">
        <button class="remove-item-btn" onclick="removeLink(${projIndex},${linkIndex})"><i class="fas fa-times"></i></button>
    </div>`;
}

function toggleForm(index, row) {
    const form = row.querySelector('.project-form');
    const icon = row.querySelector('.toggle-btn i');
    const isOpen = form.classList.contains('open');
    form.classList.toggle('open', !isOpen);
    icon.className = isOpen ? 'fas fa-chevron-down' : 'fas fa-chevron-up';
}

function updateField(index, field, value) {
    if (field === 'video' && value.trim() === '') {
        delete projects[index].video;
    } else {
        projects[index][field] = value;
    }
    if (field === 'title' || field === 'subtitle' || field === 'image') {
        const row = document.querySelector(`.project-row[data-index="${index}"]`);
        if (row) {
            if (field === 'title') row.querySelector('.project-meta h3').textContent = value || 'Untitled';
            if (field === 'subtitle') row.querySelector('.project-meta p').textContent = value;
            if (field === 'image') row.querySelector('.project-thumb').src = value;
        }
    }
    markUnsaved();
}

function updateTags(index, value) {
    projects[index].tags = value.split(',').map(t => t.trim()).filter(t => t.length > 0);
    markUnsaved();
}

function updateImageItem(projIndex, imgIndex, value) {
    projects[projIndex].images[imgIndex] = value;
    markUnsaved();
}

function updateLinkItem(projIndex, linkIndex, key, value) {
    projects[projIndex].links[linkIndex][key] = value;
    markUnsaved();
}

function addImage(projIndex) {
    if (!projects[projIndex].images) projects[projIndex].images = [];
    const imgIndex = projects[projIndex].images.length;
    projects[projIndex].images.push('');
    const container = document.getElementById(`images-${projIndex}`);
    container.insertAdjacentHTML('beforeend', imageItem('', projIndex, imgIndex));
    markUnsaved();
}

function removeImage(projIndex, imgIndex) {
    projects[projIndex].images.splice(imgIndex, 1);
    const container = document.getElementById(`images-${projIndex}`);
    container.innerHTML = projects[projIndex].images.map((img, i) => imageItem(img, projIndex, i)).join('');
    markUnsaved();
}

function addLink(projIndex) {
    if (!projects[projIndex].links) projects[projIndex].links = [];
    const linkIndex = projects[projIndex].links.length;
    projects[projIndex].links.push({ name: '', url: '' });
    const container = document.getElementById(`links-${projIndex}`);
    container.insertAdjacentHTML('beforeend', linkItem('', '', projIndex, linkIndex));
    markUnsaved();
}

function removeLink(projIndex, linkIndex) {
    projects[projIndex].links.splice(linkIndex, 1);
    const container = document.getElementById(`links-${projIndex}`);
    container.innerHTML = projects[projIndex].links.map((l, i) => linkItem(l.name, l.url, projIndex, i)).join('');
    markUnsaved();
}

function deleteProject(index) {
    if (!confirm(`Delete "${projects[index].title || 'this project'}"?`)) return;
    projects.splice(index, 1);
    renderAll();
    markUnsaved();
}

function addProject() {
    const maxId = projects.reduce((m, p) => Math.max(m, p.id || 0), 0);
    projects.push({
        id: maxId + 1,
        title: '',
        subtitle: '',
        image: '',
        tags: [],
        description: '',
        images: [],
        links: []
    });
    renderAll();
    const newRow = document.querySelector(`.project-row[data-index="${projects.length - 1}"]`);
    if (newRow) {
        toggleForm(projects.length - 1, newRow);
        newRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    markUnsaved();
}

async function saveProjects() {
    const btn = document.getElementById('saveBtn');
    btn.disabled = true;
    setStatus('saving', 'Saving…');

    const reindexed = projects.map((p, i) => ({ ...p, id: i + 1 }));

    try {
        const res = await fetch('save_projects.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(reindexed)
        });
        const data = await res.json();
        if (data.success) {
            projects = reindexed;
            renderAll();
            setStatus('saved', 'Saved');
            showToast('Projects saved successfully!', 'success');
        } else {
            setStatus('error', 'Error');
            showToast('Error: ' + data.message, 'error');
        }
    } catch (e) {
        setStatus('error', 'Error');
        showToast('Failed to save: ' + e.message, 'error');
    }

    btn.disabled = false;
}

function markUnsaved() {
    setStatus('unsaved', 'Unsaved changes');
}

function setStatus(cls, text) {
    const el = document.getElementById('saveStatus');
    el.className = 'save-status ' + cls;
    el.textContent = text;
}

function showToast(msg, type) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.className = 'toast ' + type + ' show';
    setTimeout(() => { t.className = 'toast ' + type; }, 3000);
}

function onDragStart(e) {
    dragSrc = this;
    this.classList.add('dragging');
    e.dataTransfer.effectAllowed = 'move';
}

function onDragOver(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
    if (this !== dragSrc) this.classList.add('drag-over');
}

function onDragLeave() {
    this.classList.remove('drag-over');
}

function onDrop(e) {
    e.preventDefault();
    this.classList.remove('drag-over');
    if (this === dragSrc) return;
    const fromIndex = parseInt(dragSrc.dataset.index);
    const toIndex = parseInt(this.dataset.index);
    const moved = projects.splice(fromIndex, 1)[0];
    projects.splice(toIndex, 0, moved);
    renderAll();
    markUnsaved();
}

function onDragEnd() {
    document.querySelectorAll('.project-row').forEach(r => {
        r.classList.remove('dragging', 'drag-over');
    });
}

function escHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function escAttr(str) {
    return String(str).replace(/"/g,'&quot;').replace(/'/g,'&#39;');
}

document.getElementById('addBtn').addEventListener('click', addProject);
document.getElementById('saveBtn').addEventListener('click', saveProjects);

loadProjects();
</script>
</body>
</html>
