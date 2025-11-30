@extends('layouts.app')

@section('title', 'Community Sharing-profile')

@section('content')

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Community — Profile</title>

    <!-- Tailwind for Edit Profile Modal Only -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

    <style>
        /* ensure hidden works reliably */
        .hidden { display: none !important; }

        body {
            font-family: "DM Sans", Inter, Arial, sans-serif;
        }

        /* ROOT COLORS (as requested) */
        :root {
            --color-pink-soft: #F9B6C7; /* Main Pink Accent (Badge BG) */
            --color-dark-text: #20413A; /* Dark Text/Accent (Teal-ish) */
            --color-hero-bg: #F9FAFB; /* Off-White Background */
            --color-love-red: #FF0000; /* Red color for filled heart icon */

            /* kept old variable names for compatibility */
            --primary: var(--color-pink-soft);
            --accent: #ff9eaa;
            --text: var(--color-dark-text);
            --muted: #888;
            --bg: var(--color-hero-bg);
            --card: #ffffff;
            --overlay: rgba(0, 0, 0, 0.35);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: var(--bg);
            padding: 25px;
            color: var(--text);
        }

        /* Slim container: make the surrounding white card more "slim" even on wide screens */
        .container {
            max-width: 920px;
            margin: auto;
            padding-left: 18px;
            padding-right: 18px;
        }

        /* PROFILE WRAPPER */
        .profile-wrapper {
            background: white;
            border-radius: 20px;
            padding: 25px 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .05);
            margin: 35px auto;
            max-width: 700px;
            width: 90%;
        }

        .profile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .profile-info {
            max-width: 68%;
        }

        .profile-username {
            font-size: 36px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--text);
        }

        .profile-desc {
            margin-top: 8px;
            font-size: 15px;
            line-height: 1.45;
            color: #444;
        }

        /* profile-photo will now support svg-data avatars too */
        .profile-photo {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
            border: 6px solid #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            background-color: #f4f7f6;
            cursor: pointer;
        }

        .edit-profile-btn {
            margin: 22px 0;
            width: 220px;
            padding: 12px 18px;
            font-size: 18px;
            border-radius: 12px;
            border: none;
            background: var(--primary);
            font-weight: 700;
            cursor: pointer;
        }

        /* TABS */
        .tabs {
            display: flex;
            gap: 25px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 18px;
            justify-content: center;
        }

        .tab {
            cursor: pointer;
            font-size: 20px;
            color: #777;
            transition: .16s;
        }

        .tab:hover {
            transform: translateY(-2px);
            color: var(--text);
        }

        .tab.active {
            color: var(--text);
            border-bottom: 4px solid var(--primary);
            padding-bottom: 6px;
        }

        /* CREATE ROW */
        .create-row {
            display: flex;
            align-items: center;
            background: white;
            padding: 14px;
            border-radius: 16px;
            gap: 12px;
            margin-bottom: 18px;
            box-shadow: 0 5px 12px rgba(0, 0, 0, .04);
        }

        .create-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
        }

        .create-input {
            flex: 1;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #eee;
            background: #fafafa;
        }

        .create-btn {
            padding: 10px 16px;
            background: var(--primary);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
        }

        /* POSTS */
        .posts {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .card {
            background: white;
            padding: 16px;
            border-radius: 14px;
            box-shadow: 0 5px 12px rgba(0, 0, 0, .04);
            position: relative;
        }

        /* Post meta */
        .meta {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .meta img.avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .meta .info {
            display: flex;
            flex-direction: column;
        }

        .meta .row {
            display:flex;
            gap:10px;
            align-items:center;
        }

        .meta .name {
            font-weight:700;
        }

        .meta .date {
            font-size:13px;
            color:var(--muted);
        }

        .meta .time-ago {
            font-size:13px;
            color:var(--muted);
        }

        .content {
            margin-top: 10px;
            font-size: 16px;
            color:#222;
        }

        .post-image {
            width: 100%;
            height: 260px;
            border-radius: 12px;
            object-fit: cover;
            margin-top: 12px;
        }

        .three-dots {
            position: absolute;
            right: 12px;
            top: 12px;
            cursor: pointer;
            padding: 6px;
        }

        /* actions area (likes + comments) - styled like example */
        .post-actions {
            display:flex;
            gap:18px;
            align-items:center;
            margin-top:12px;
            border-top:1px solid #f2f2f2;
            padding-top:12px;
        }

        .post-actions button {
            background: none;
            border: none;
            display:flex;
            gap:8px;
            align-items:center;
            cursor:pointer;
            font-weight:700;
            font-size:14px;
            color: #3b3b3b;
            padding: 4px 6px;
            border-radius: 8px;
        }

        .post-actions button:hover {
            background: #faf6f7;
        }

        .post-actions button .icon {
            width:20px;
            height:20px;
            display:block;
        }

        .post-actions .comment-count {
            color: #8a5a66;
        }

        /* ensure heart icons hide/show predictably */
        .heart-solid { fill: var(--color-love-red); }
        .heart-solid.hidden { display: none !important; }
        .heart-outline.hidden { display: none !important; }

        /* comment section (hidden by default) */
        .comment-section {
            margin-top:12px;
            border-top:1px solid #f0f0f0;
            padding-top:10px;
        }

        .comment-item { padding:6px 0; border-bottom:1px dashed #f2f2f2; font-size:14px; color:#333; }

        /* Replies specific styles: merged layout (NO vertical pink line as requested) */
        .reply-block {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .reply-card {
            position: relative;
            overflow: visible;
            padding-left: 12px;
        }

        .reply-card .meta {
            align-items: flex-start;
            gap: 12px;
        }

        .replying-to {
            font-size: 13px;
            color: #9aa5a1;
            margin-top: 2px;
        }

        .replying-to span {
            color: var(--primary);
            font-weight: 600;
        }

        .original-thread-compact {
            background: #fbfbfb;
            padding: 12px;
            border-radius: 10px;
            margin-top: 10px;
            box-shadow: none;
            border: 1px solid #f2f2f2;
        }

        .original-thread-compact .like-count,
        .original-thread-compact .comment-count {
            font-weight:700;
            color:#3b3b3b;
        }

        .ctx-item.disabled {
            color:#bdbdbd;
            cursor:not-allowed;
        }

        /* other UI */
        .ctx-menu {
            position: absolute;
            top: 52px;
            right: 18px;
            background: white;
            padding: 8px;
            width: 160px;
            border-radius: 10px;
            display: none;
            box-shadow: 0 8px 20px rgba(0, 0, 0, .12);
        }

        .ctx-menu.show {
            display: flex;
            flex-direction: column;
            animation: popup .2s ease-out;
        }

        @keyframes popup {
            from {
                opacity: 0;
                transform: scale(.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .ctx-item {
            padding: 8px 10px;
            display: flex;
            gap: 8px;
            cursor: pointer;
            border-radius: 6px;
        }

        .ctx-item:hover:not(.disabled) {
            background: #f6f6f6;
        }

        .ctx-item.delete {
            color: #d33;
            font-weight: 600;
        }

        /* MEDIA GRID */
        .media-grid {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
        }

        .media-card {
            background: white;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, .06);
        }

        .media-card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
        }

        /* MODALS */
        .overlay {
            position: fixed;
            inset: 0;
            display: none;
            background: var(--overlay);
            backdrop-filter: blur(3px);
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .overlay.show {
            display: flex;
        }

        .modal {
            background: white;
            width: 420px;
            padding: 25px;
            border-radius: 18px;
            animation: popup .2s ease-out;
        }

        .modal.small {
            width: 330px;
        }

        .modal-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .modal input,
        .modal textarea {
            width: 100%;
            border: 1px solid #ececec;
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 12px;
        }

        .btn-row {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn-ghost {
            padding: 8px 14px;
            border: 1px solid #ddd;
            border-radius: 12px;
            background: white;
            cursor: pointer;
        }

        .btn-ok {
            padding: 9px 18px;
            border-radius: 12px;
            background: var(--primary);
            border: none;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-danger {
            padding: 9px 18px;
            background: #e55;
            color: white;
            border: none;
            border-radius: 12px;
            cursor: pointer;
        }

        .notice {
            background: #fff4f4;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #ffdede;
            color: #b33;
            font-size: 14px;
        }

        /* Responsive tweaks */
        @media (max-width: 720px) {
            .container { max-width: 100%; padding-left: 12px; padding-right: 12px; }
            .profile-wrapper { padding: 18px; border-radius: 12px; }
            .profile-username { font-size: 26px; }
            .profile-photo { width: 88px; height: 88px; }
            .reply-card { padding-left: 12px; }
            .original-thread-compact { margin-left: 0; }
            .edit-profile-btn { width: 160px; font-size: 15px; padding: 10px; }
            .post-image { height: 180px; }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="profile-wrapper">

        <div class="profile-header">
            <div class="profile-info">
                <div class="profile-username">username_</div>
                <div class="profile-desc">
                    Hi! I love sharing tips about reusing everyday items and reducing cosmetic waste. Let's learn together ♻️💚
                </div>
            </div>

            <img src="https://via.placeholder.com/120" class="profile-photo" id="profile-photo-el" alt="avatar" title="Click to change (in Edit Profile)">
        </div>

        <button class="edit-profile-btn" id="edit-profile-open">Edit Profile</button>
        <!-- Hidden file input untuk upload foto profil (dipakai saat ubah lewat modal) -->
        <input id="profile-photo-file" type="file" accept="image/*" style="display:none" />

        <div class="tabs">
            <div class="tab active" data-tab="threads">Threads</div>
            <div class="tab" data-tab="replies">Replies</div>
            <div class="tab" data-tab="media">Media</div>
        </div>

        <div class="create-row">
            <img src="https://via.placeholder.com/80" class="create-avatar" id="create-avatar-el">
            <input class="create-input" placeholder="Share your story..." readonly id="open-create">
            <button class="create-btn" id="open-create-btn">Post</button>
        </div>

        <section id="threads">
            <div class="posts" id="posts-list"></div>
        </section>

        <section id="replies" style="display:none">
            <div class="posts" id="replies-list"></div>
        </section>

        <section id="media" style="display:none">
            <div class="media-grid" id="media-grid"></div>
        </section>

    </div>
</div>


<!-- CREATE POST MODAL -->
<div id="create-overlay" class="overlay">
    <div class="modal" id="create-modal">
        <div class="modal-title">Create Post</div>

        <input id="create-title" placeholder="Title (optional)">
        <textarea id="create-body" rows="4" placeholder="Write something..."></textarea>
        <input id="create-image" type="file" accept="image/*">

        <div class="btn-row">
            <button class="btn-ghost" id="create-cancel">Cancel</button>
            <button class="btn-ok" id="create-publish">Publish</button>
        </div>
    </div>
</div>

<!-- EDIT POST MODAL -->
<div id="edit-overlay" class="overlay">
    <div class="modal small" id="edit-modal">
        <div class="modal-title">Edit Post</div>

        <input id="edit-title">
        <textarea id="edit-body" rows="4"></textarea>

        <div id="edit-expired" class="notice" style="display:none">
            This post is older than 24 hours. Editing disabled.
        </div>

        <div class="btn-row">
            <button class="btn-ghost" id="edit-cancel">Cancel</button>
            <button class="btn-ok" id="edit-save">Save</button>
        </div>
    </div>
</div>

<!-- DELETE CONFIRMATION -->
<div id="delete-overlay" class="overlay">
    <div class="modal small" id="delete-modal">
        <div class="modal-title">Delete Post?</div>
        <p style="margin-bottom:12px;color:#666">Are you sure you want to delete this post?</p>

        <div class="btn-row">
            <button class="btn-ghost" id="delete-cancel">Cancel</button>
            <button class="btn-danger" id="delete-confirm">Delete</button>
        </div>
    </div>
</div>

<!-- EDIT PROFILE MODAL (TAILWIND) -->
<div id="edit-profile-overlay" class="fixed inset-0 hidden 
    items-center justify-center bg-black/40 backdrop-blur-sm z-[9999]">

    <div class="relative w-full max-w-lg rounded-xl bg-white p-6 sm:p-8 shadow-2xl flex flex-col gap-6">

        <!-- HEADER -->
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">Edit Profile</h2>

            <button id="edit-profile-close"
                class="flex h-8 w-8 items-center justify-center rounded-full hover:bg-gray-200">
                <span class="material-symbols-outlined text-gray-700">close</span>
            </button>
        </div>

        <!-- AVATAR -->
        <div class="flex flex-col items-center gap-4">
            <div class="group relative">
                <div id="modal-avatar-div" class="h-32 w-32 rounded-full bg-cover bg-center"
                    style="background-image:url('https://via.placeholder.com/150')"></div>

                <div class="absolute inset-0 flex cursor-pointer flex-col items-center justify-center 
                    rounded-full bg-black/50 opacity-0 group-hover:opacity-100 transition" id="modal-avatar-click">
                    <span class="material-symbols-outlined text-white text-3xl">photo_camera</span>
                    <span class="text-sm text-white">Change</span>
                </div>
            </div>
        </div>

        <!-- FORM -->
        <div class="flex flex-col gap-4">

            <label class="flex flex-col">
                <span class="font-semibold mb-1">Username</span>
                <input id="edit-username"
                    class="rounded-lg border p-3"
                    value="username_">
            </label>

            <label class="flex flex-col">
                <span class="font-semibold mb-1">Bio</span>
                <textarea id="edit-bio" class="rounded-lg border p-3 min-h-32">Hi! I love sharing eco-friendly tips and reducing waste.</textarea>
            </label>

        </div>

        <!-- BUTTONS -->
        <div class="flex justify-end gap-3">
            <button id="edit-profile-cancel"
                class="px-4 py-2 rounded-lg bg-gray-200 font-semibold">
                Cancel
            </button>

            <button id="edit-profile-save"
                class="px-4 py-2 rounded-lg bg-[#f4a9b7] text-white font-semibold">
                Save Changes
            </button>
        </div>

    </div>
</div>


<!-- =========================== JAVASCRIPT ============================= -->
<script>
/* ---------- INIT CURRENT USER (load from storage if exists) ---------- */
const storedUser = JSON.parse(localStorage.getItem('userData') || 'null');
const currentUser = {
    id: 'me',
    name: storedUser && storedUser.name ? storedUser.name : 'username_',
    avatar: storedUser && storedUser.avatar ? storedUser.avatar : null,
    bio: storedUser && storedUser.bio ? storedUser.bio : ''
};

/* ---------- SAMPLE DATA (unchanged structure) ---------- */
let posts = [
    {
        id: "p1",
        title: "",
        body: "Just tried making my own face mask with honey, oats, and turmeric ✨",
        image: "https://picsum.photos/900/600?random=11",
        createdAt: Date.now() - 2 * 60 * 60 * 1000,
        author: currentUser.name,
        likes: 97,
        comments: 8
    },
    {
        id: "p2",
        title: "",
        body: "Switched to a bamboo toothbrush and refillable deodorant this month. Small changes, big impact!",
        image: null,
        createdAt: Date.now() - 48 * 60 * 60 * 1000,
        author: "Marcus Chen",
        likes: 42,
        comments: 15
    },
    {
        id: "p3",
        title: "",
        body: "I donated old clothes today. It's small, but it felt good. ♻️",
        image: null,
        createdAt: Date.now() - 30 * 60 * 60 * 1000,
        author: currentUser.name,
        likes: 8,
        comments: 3
    },
    {
        id: "p4",
        title: "",
        body: "Made a compost bin from an old plastic container — works surprisingly well!",
        image: "https://picsum.photos/900/600?random=22",
        createdAt: Date.now() - 6 * 60 * 60 * 1000,
        author: currentUser.name,
        likes: 2,
        comments: 1
    },
    {
        id: "p5",
        title: "",
        body: "Trying out zero-waste shampoo bars — any brand recs?",
        image: null,
        createdAt: Date.now() - 10 * 60 * 60 * 1000,
        author: currentUser.name,
        likes: 0,
        comments: 0
    }
];

let userReplies = [
    {
        id: "r1",
        name: currentUser.name,
        avatar: null,
        body: "That's so cool!!! I want to try composting next week.",
        createdAt: Date.now() - 5 * 60 * 60 * 1000,
        replyTo: "p2",
        replyToName: "Marcus Chen",
        likes: 2,
        comments: 0
    },
    {
        id: "r2",
        name: currentUser.name,
        avatar: null,
        body: "Love this idea — bookmarked!",
        createdAt: Date.now() - 20 * 60 * 60 * 1000,
        replyTo: "p2",
        replyToName: "Marcus Chen",
        likes: 1,
        comments: 1
    }
];

/* ---------- ELEMENT REFERENCES ---------- */
const tabs = document.querySelectorAll(".tab");
const postsList = document.getElementById("posts-list");
const repliesList = document.getElementById("replies-list");
const mediaGrid = document.getElementById("media-grid");

const profilePhotoEl = document.getElementById("profile-photo-el");
const profileInputEl = document.getElementById("profile-photo-file");
const modalAvatarDiv = document.getElementById("modal-avatar-div");
const createAvatarEl = document.getElementById("create-avatar-el");

/* ---------- HELPERS ---------- */
function pad(n){ return n<10? '0'+n: n; }
function formatDate(ms){
    const d=new Date(ms);
    return `${pad(d.getDate())}/${pad(d.getMonth()+1)}/${d.getFullYear()}`;
}
function timeAgoVerbose(ms){
    const s=Math.floor((Date.now()-ms)/1000);
    if(s<60) return s + (s===1? " second ago":" seconds ago");
    const m=Math.floor(s/60);
    if(m<60) return m + (m===1? " minute ago":" minutes ago");
    const h=Math.floor(m/60);
    if(h<24) return h + (h===1? " hour ago":" hours ago");
    const d=Math.floor(h/24);
    return d + (d===1? " day ago":" days ago");
}
function escapeHTML(str){
    return String(str||"").replace(/[&<>]/g,m=>({"&":"&amp;","<":"&lt;",">":"&gt;"}[m]));
}
function parseCountFromText(t){
    if(!t) return 0;
    const n = (t+"").replace(/\D/g,'');
    return parseInt(n) || 0;
}

/* ---------- USER DATA PERSISTENCE ---------- */
function saveUserData(){
    const data = { name: currentUser.name, avatar: currentUser.avatar, bio: currentUser.bio };
    try{ localStorage.setItem('userData', JSON.stringify(data)); } catch(e){ console.warn('ls save failed',e); }
}

/* ---------- AVATAR + USER LOAD ---------- */
function generateInitialsDataUrl(name = '', size = 160, bg = '#E8F6F1', fg = '#20413a') {
    const initials = (name||'U').trim().split(/\s+/).slice(0,2).map(n=>n[0].toUpperCase()).join('');
    const fontSize = Math.floor(size * 0.45);
    const svg = `<svg xmlns='http://www.w3.org/2000/svg' width='${size}' height='${size}'><rect width='100%' height='100%' fill='${bg}' rx='16' /><text x='50%' y='50%' dy='0.35em' text-anchor='middle' fill='${fg}' font-family='Plus Jakarta Sans, Poppins, sans-serif' font-size='${fontSize}' font-weight='700'>${initials}</text></svg>`;
    return `data:image/svg+xml;charset=utf-8,${encodeURIComponent(svg)}`;
}

function getAvatarForName(name){
    const saved = JSON.parse(localStorage.getItem('userData') || 'null')?.avatar;
    if(name === currentUser.name){
        if(saved) return saved;
        return generateInitialsDataUrl(currentUser.name);
    } else {
        return generateInitialsDataUrl(name || 'U', 120, '#FBF7F8', '#2F3E35');
    }
}

function applyAvatarToUI(dataUrl){
    if(profilePhotoEl && profilePhotoEl.tagName.toLowerCase()==='img') profilePhotoEl.src = dataUrl;
    if(createAvatarEl && createAvatarEl.tagName.toLowerCase()==='img') createAvatarEl.src = dataUrl;
    if(modalAvatarDiv) modalAvatarDiv.style.backgroundImage = `url('${dataUrl}')`;
    try{
        const ud = JSON.parse(localStorage.getItem('userData')||'null') || {};
        ud.avatar = dataUrl;
        localStorage.setItem('userData', JSON.stringify(ud));
    }catch(e){ console.warn('ls save failed',e); }
}

function loadInitialAvatarAndName(){
    const stored = JSON.parse(localStorage.getItem('userData') || 'null');
    if(stored){
        if(profilePhotoEl && stored.avatar) profilePhotoEl.src = stored.avatar;
        if(createAvatarEl && stored.avatar) createAvatarEl.src = stored.avatar;
        if(modalAvatarDiv && stored.avatar) modalAvatarDiv.style.backgroundImage = `url('${stored.avatar}')`;
        if(stored.name) {
            currentUser.name = stored.name;
            document.querySelector('.profile-username').textContent = currentUser.name;
        }
        if(stored.bio){
            currentUser.bio = stored.bio;
            document.querySelector('.profile-desc').textContent = currentUser.bio;
        }
    } else {
        const dataUrl = generateInitialsDataUrl(currentUser.name);
        applyAvatarToUI(dataUrl);
    }
}

loadInitialAvatarAndName();

/* ---------- RENDER FUNCTIONS ---------- */
function renderPosts(){
    postsList.innerHTML = "";

    // only show current user's posts in the Threads tab
    const myPosts = posts.filter(p => p.author === currentUser.name)
                         .sort((a,b)=>b.createdAt - a.createdAt);

    if(myPosts.length === 0){
        postsList.innerHTML = `<div class="card"><div class="content">No threads yet.</div></div>`;
        return;
    }

    myPosts.forEach(post=>{
        const card=document.createElement('div');
        card.className='card post-card';

        const authorAvatar = getAvatarForName(currentUser.name);

        // content
        card.innerHTML = `
            <div class="meta">
                <img src="${escapeHTML(authorAvatar)}" class="avatar">
                <div class="info">
                    <div class="row"><div class="name">${escapeHTML(post.author||'You')}</div><div class="date">${formatDate(post.createdAt)}</div></div>
                    <div class="time-ago">${timeAgoVerbose(post.createdAt)}</div>
                </div>
            </div>

            <div class="content">${escapeHTML(post.body)}</div>
        `;

        if(post.image){
            const img=document.createElement('img');
            img.src=post.image;
            img.className='post-image';
            card.appendChild(img);
        }

        // actions (like + comments) - styled as "NN likes" & "NN comments"
        const actions = document.createElement('div');
        actions.className = 'post-actions';
        actions.innerHTML = `
            <button class="like-btn" aria-pressed="false" title="Like">
                <svg class="icon heart-outline" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#333">
                  <path d="M12.1 21.35l-1.1-.9C5.14 15.24 2 12.39 2 8.99 2 6.24 4.24 4 7 4c1.54 0 3.04.99 3.57 2.36h1.87C13.96 4.99 15.46 4 17 4c2.76 0 5 2.24 5 4.99 0 3.4-3.14 6.25-8.99 11.46l-1.9 1.9z" stroke-width="1.1"/>
                </svg>
                <svg class="icon heart-solid hidden" width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="${getComputedStyle(document.documentElement).getPropertyValue('--color-love-red') || '#e2556b'}">
                  <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                </svg>
                <span class="like-count">${post.likes || 0} likes</span>
            </button>

            <button class="comment-toggle-btn">
                <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#b76e79">
                  <path d="M21 12c0 4.418-4.03 8-9 8a9.81 9.81 0 01-4.39-1.02L3 20l1.05-3.33A7.99 7.99 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" stroke-width="1.2"/>
                </svg>
                <span class="comment-count" style="color:#8a5a66">${post.comments || 0} comments</span>
            </button>
        `;
        card.appendChild(actions);

        // comment section (hidden initially)
        const commentSection = document.createElement('div');
        commentSection.className = 'comment-section hidden';
        commentSection.innerHTML = `
            <div class="comments-list"></div>
            <div style="display:flex; gap:8px; margin-top:8px;">
              <input type="text" class="comment-input" placeholder="Write a comment..." style="flex:1; padding:8px; border-radius:8px; border:1px solid #eee;">
              <button class="add-comment-btn btn-ghost">Post</button>
            </div>
        `;
        card.appendChild(commentSection);

        const dots=document.createElement('div');
        dots.className='three-dots';
        dots.innerHTML=`<svg width="22" height="22" viewBox="0 0 24 24"><circle cx="5" cy="12" r="2.2" fill="#666"/><circle cx="12" cy="12" r="2.2" fill="#666"/><circle cx="19" cy="12" r="2.2" fill="#666"/></svg>`;

        dots.onclick = ()=> showContextMenu(card, post);
        card.appendChild(dots);

        postsList.appendChild(card);
    });
}

function renderReplies(){
    repliesList.innerHTML = "";

    if(userReplies.length===0){
        repliesList.innerHTML = `<div class="card"><div class="content">No replies yet.</div></div>`;
        return;
    }

    // For each reply, build a single card containing the reply + the original thread (merged)
    userReplies.forEach(reply=>{
        const card = document.createElement('div');
        card.className = 'card reply-card';

        const avatarSrc = reply.avatar ? reply.avatar : getAvatarForName(reply.name);

        // reply header + content
        card.innerHTML = `
            <div class="meta">
                <img src="${escapeHTML(avatarSrc)}" class="avatar">
                <div class="info">
                    <div class="row"><div class="name">${escapeHTML(reply.name)}</div><div class="date">${formatDate(reply.createdAt)}</div></div>
                    <div class="replying-to">Replying to <span>@${escapeHTML(reply.replyToName||'')}</span> · <span class="time-ago">${timeAgoVerbose(reply.createdAt)}</span></div>
                </div>
            </div>

            <div class="content">${escapeHTML(reply.body)}</div>
        `;

        // show original thread inside the same card (compact)
        const original = posts.find(p=>p.id === reply.replyTo);
        if(original){
            const origDiv = document.createElement('div');
            origDiv.className = 'original-thread-compact';
            const origAvatar = original.author === currentUser.name ? getAvatarForName(currentUser.name) : getAvatarForName(original.author);
            origDiv.innerHTML = `
                <div style="display:flex; gap:10px; align-items:flex-start;">
                    <img src="${escapeHTML(origAvatar)}" class="avatar" style="width:40px;height:40px;">
                    <div style="flex:1">
                        <div style="display:flex; gap:8px; align-items:center;">
                            <div style="font-weight:700">${escapeHTML(original.author || 'User')}</div>
                            <div style="font-size:13px; color:var(--muted)">${formatDate(original.createdAt)}</div>
                        </div>

                        <div style="margin-top:6px; font-size:14px; color:#333">${escapeHTML(original.body)}</div>

                        <div style="margin-top:8px; display:flex; gap:12px; align-items:center;">
                          <button class="like-btn small" aria-pressed="false" title="Like (orig)">
                              <svg class="icon heart-outline" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#333">
                                <path d="M12.1 21.35l-1.1-.9C5.14 15.24 2 12.39 2 8.99 2 6.24 4.24 4 7 4c1.54 0 3.04.99 3.57 2.36h1.87C13.96 4.99 15.46 4 17 4c2.76 0 5 2.24 5 4.99 0 3.4-3.14 6.25-8.99 11.46l-1.9 1.9z" stroke-width="1.1"/>
                              </svg>
                              <svg class="icon heart-solid hidden" width="16" height="16" viewBox="0 0 20 20" fill="${getComputedStyle(document.documentElement).getPropertyValue('--color-love-red') || '#e2556b'}">
                                <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                              </svg>
                              <span class="like-count">${original.likes || 0} likes</span>
                          </button>

                          <button class="comment-toggle-btn small">
                              <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#b76e79">
                                  <path d="M21 12c0 4.418-4.03 8-9 8a9.81 9.81 0 01-4.39-1.02L3 20l1.05-3.33A7.99 7.99 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" stroke-width="1.2"/>
                              </svg>
                              <span class="comment-count" style="color:#8a5a66">${original.comments || 0} comments</span>
                          </button>
                        </div>

                    </div>
                </div>
            `;
            card.appendChild(origDiv);
        }

        repliesList.appendChild(card);
    });
}

function renderMedia(){
    mediaGrid.innerHTML='';
    posts.filter(p=>p.image && p.author === currentUser.name).forEach(p=>{
        const m=document.createElement('div');
        m.className='media-card';
        m.innerHTML=`
            <img src="${p.image}">
            <div style="padding:10px">
                <div style="font-weight:700">${escapeHTML(p.author||currentUser.name)}</div>
                <div style="font-size:13px;color:#666">${timeAgoVerbose(p.createdAt)}</div>
            </div>
        `;
        mediaGrid.appendChild(m);
    });
}

/* ---------- CONTEXT MENU: EDIT + DELETE (edit disabled if >24h) ---------- */
function showContextMenu(card, post){
    closeAllContextMenus();

    const menu=document.createElement('div');
    menu.className='ctx-menu';

    // check age
    const hours=(Date.now()-post.createdAt)/(1000*60*60);
    const canEdit = hours <= 24;

    menu.innerHTML = `
        <div class="ctx-item edit ${canEdit? '' : 'disabled'}" data-act="edit">✏️ Edit</div>
        <div class="ctx-item delete" data-act="delete">🗑 Delete</div>
    `;

    const editItem = menu.querySelector('.ctx-item.edit');
    const deleteItem = menu.querySelector('.ctx-item.delete');

    if(canEdit){
        editItem.onclick = ()=> {
            editingPost = post.id;
            document.getElementById("edit-title").value = post.title || "";
            document.getElementById("edit-body").value = post.body || "";
            document.getElementById("edit-expired").style.display = "none";
            document.getElementById("edit-overlay").classList.add("show");
        };
    } else {
        editItem.onclick = ()=> { showToast("Editing disabled for posts older than 24 hours."); };
    }

    deleteItem.onclick = ()=> {
        deletingPost = post.id;
        document.getElementById("delete-overlay").classList.add("show");
    };

    card.appendChild(menu);
    setTimeout(()=>menu.classList.add('show'),8);
}

function closeAllContextMenus(){
    document.querySelectorAll('.ctx-menu').forEach(m=>m.remove());
}

/* small toast for feedback */
function showToast(msg, duration=2600){
    const existing = document.getElementById('__small_toast');
    if(existing) existing.remove();
    const div=document.createElement('div');
    div.id='__small_toast';
    div.style.position='fixed';
    div.style.right='16px';
    div.style.bottom='24px';
    div.style.background='#2F3E35';
    div.style.color='#fff';
    div.style.padding='10px 14px';
    div.style.borderRadius='10px';
    div.style.boxShadow='0 8px 24px rgba(0,0,0,0.12)';
    div.style.zIndex=99999;
    div.textContent=msg;
    document.body.appendChild(div);
    setTimeout(()=>{ div.style.opacity='0'; div.style.transform='translateY(8px)'; }, duration);
    setTimeout(()=>div.remove(), duration+400);
}

/* ---------- CREATE POST ---------- */
document.getElementById("open-create").onclick =
document.getElementById("open-create-btn").onclick = ()=> {
    document.getElementById("create-overlay").classList.add("show");
};

document.getElementById("create-cancel").onclick = ()=> document.getElementById("create-overlay").classList.remove("show");

document.getElementById("create-publish").onclick = async () => {
    const t = document.getElementById("create-title").value.trim();
    const b = document.getElementById("create-body").value.trim();
    const f = document.getElementById("create-image").files[0];

    if(!b && !f) return alert("Write something first!");

    let img=null;
    if(f) img = await fileToDataURL(f);

    posts.unshift({
        id: "p"+Date.now(),
        title: t,
        body: b,
        image: img,
        createdAt: Date.now(),
        author: currentUser.name,
        likes: 0,
        comments: 0
    });

    document.getElementById("create-overlay").classList.remove("show");
    document.getElementById("create-title").value = "";
    document.getElementById("create-body").value = "";
    renderAll();
};

/* ---------- EDIT POST ---------- */
let editingPost = null;
document.getElementById("edit-cancel").onclick = ()=> document.getElementById("edit-overlay").classList.remove("show");

document.getElementById("edit-save").onclick = ()=> {
    if(!editingPost) return;
    const p = posts.find(x => x.id === editingPost);
    if(!p) return;
    const hours=(Date.now()-p.createdAt)/(1000*60*60);
    if(hours>24){
        document.getElementById("edit-expired").style.display = "block";
        return;
    }
    p.title = document.getElementById("edit-title").value;
    p.body = document.getElementById("edit-body").value;
    document.getElementById("edit-overlay").classList.remove("show");
    renderAll();
};

/* ---------- DELETE POST -------- */
let deletingPost = null;
document.getElementById("delete-cancel").onclick = ()=> document.getElementById("delete-overlay").classList.remove("show");
document.getElementById("delete-confirm").onclick = ()=> {
    posts = posts.filter(p=>p.id !== deletingPost);
    document.getElementById("delete-overlay").classList.remove("show");
    renderAll();
};

/* ---------- EDIT PROFILE MODAL ---------- */
const epOpen = document.getElementById("edit-profile-open");
const epOverlay = document.getElementById("edit-profile-overlay");
const epClose = document.getElementById("edit-profile-close");
const epCancel = document.getElementById("edit-profile-cancel");
const epSave = document.getElementById("edit-profile-save");
const modalAvatarClick = document.getElementById("modal-avatar-click");

epOpen.addEventListener("click", ()=>{
    epOverlay.classList.remove("hidden");
    epOverlay.classList.add("flex");
    // set modal avatar + fields
    const saved = JSON.parse(localStorage.getItem('userData') || 'null');
    if(saved && saved.avatar) modalAvatarDiv.style.backgroundImage = `url('${saved.avatar}')`;
    else modalAvatarDiv.style.backgroundImage = `url('${generateInitialsDataUrl(currentUser.name,150)}')`;
    document.getElementById("edit-username").value = currentUser.name;
    document.getElementById("edit-bio").value = currentUser.bio || document.querySelector(".profile-desc").textContent;
});

function closeEditProfile(){
    epOverlay.classList.add("hidden");
    epOverlay.classList.remove("flex");
}

epClose.onclick = closeEditProfile;
epCancel.onclick = closeEditProfile;

/* When saving profile: update currentUser, update posts & replies authored by old name, persist */
epSave.onclick = ()=> {
    const oldName = currentUser.name;
    const newName = document.getElementById("edit-username").value.trim();
    const newBio = document.getElementById("edit-bio").value.trim();

    if(newName && newName !== oldName){
        // update posts authored by oldName to newName
        posts.forEach(p=>{
            if(p.author === oldName) p.author = newName;
        });
        // update replies author names
        userReplies.forEach(r=>{
            if(r.name === oldName) r.name = newName;
            if(r.replyToName === oldName) r.replyToName = newName;
        });

        currentUser.name = newName;
        document.querySelector(".profile-username").textContent = newName;
    }

    if(newBio){
        currentUser.bio = newBio;
        document.querySelector(".profile-desc").textContent = newBio;
    }

    // persist userData (avatar already handled when uploading)
    saveUserData();

    closeEditProfile();
    renderAll();
};

/* ---------- FILE READER HELPER (user avatar) ---------- */
function fileToDataURL(file){
    return new Promise((res,rej)=>{
        const fr=new FileReader();
        fr.onload=()=>res(fr.result);
        fr.onerror=rej;
        fr.readAsDataURL(file);
    });
}

/* ---------- PROFILE PHOTO PICKER (modal & hidden input) ---------- */
// clicking profile pic triggers hidden input
if(profilePhotoEl && profileInputEl){
    profilePhotoEl.addEventListener('click', ()=> profileInputEl.click());
}
if(modalAvatarClick && profileInputEl){
    modalAvatarClick.addEventListener('click', ()=> profileInputEl.click());
}

if(profileInputEl){
    profileInputEl.addEventListener('change', async ()=>{
        const file = profileInputEl.files && profileInputEl.files[0];
        if(!file) return;
        if(!file.type || !file.type.startsWith('image/')){
            alert('Pilih file gambar.');
            profileInputEl.value='';
            return;
        }
        try{
            const dataUrl = await fileToDataURL(file);
            currentUser.avatar = dataUrl;
            applyAvatarToUI(dataUrl);
            saveUserData(); // save avatar to userData
            renderAll();
        }catch(e){ console.error(e); alert('Gagal membaca file.'); }
        finally{ profileInputEl.value=''; }
    });
}

/* ---------- TAB SWITCHING ---------- */
tabs.forEach(tab=>{
    tab.addEventListener('click', ()=>{
        tabs.forEach(t=>t.classList.remove('active'));
        tab.classList.add('active');
        document.getElementById("threads").style.display = "none";
        document.getElementById("replies").style.display = "none";
        document.getElementById("media").style.display = "none";
        document.getElementById(tab.dataset.tab).style.display = "block";
    });
});

/* ---------- COMMENTS + LIKE BUTTONS (behaviour) ---------- */
function setupComments() {
  document.querySelectorAll('.post-card').forEach(post => {

    const toggleBtn = post.querySelector('.comment-toggle-btn');
    const section   = post.querySelector('.comment-section');
    const addBtn    = post.querySelector('.add-comment-btn');
    const list      = post.querySelector('.comments-list');
    const input     = post.querySelector('.comment-input');

    // Toggle comments
    if (toggleBtn && section) {
      toggleBtn.addEventListener('click', () => {
        section.classList.toggle('hidden');
      });
    }

    // Add comment
    if (addBtn) {
      addBtn.addEventListener('click', () => {
        const text = input.value.trim();
        if (!text) return;

        const commentEl = document.createElement('div');
        commentEl.className = "comment-item";
        commentEl.innerHTML = `<p><strong>You</strong>: ${escapeHTML(text)}</p>`;

        list.appendChild(commentEl);
        input.value = "";

        // update counter (safely)
        if(toggleBtn){
          const current = parseCountFromText(toggleBtn.textContent);
          const countSpan = toggleBtn.querySelector('.comment-count');
          if(countSpan){
            countSpan.textContent = `${current + 1} comments`;
          } else {
            toggleBtn.textContent = `${current + 1} comments`;
          }
        }
      });
    }

  });

  // Also wire comment toggle inside original-thread-compact blocks
  document.querySelectorAll('.original-thread-compact').forEach(block=>{
      const toggle = block.querySelector('.comment-toggle-btn');
      if(toggle){
          toggle.addEventListener('click', ()=>{
              const parentCard = block.closest('.reply-card');
              let cs = parentCard.querySelector('.comment-section');
              if(!cs){
                  cs = document.createElement('div');
                  cs.className = 'comment-section hidden';
                  cs.innerHTML = `<div class="comments-list"></div>
                    <div style="display:flex; gap:8px; margin-top:8px;">
                      <input type="text" class="comment-input" placeholder="Write a comment..." style="flex:1; padding:8px; border-radius:8px; border:1px solid #eee;">
                      <button class="add-comment-btn btn-ghost">Post</button>
                    </div>`;
                  block.appendChild(cs);
                  setupComments(); // attach handlers to the newly created comment form
              }
              cs.classList.toggle('hidden');
          });
      }
  });
}

function setupLikeButtons() {
  document.querySelectorAll('.like-btn').forEach(btn => {
    const outline = btn.querySelector('.heart-outline');
    const solid   = btn.querySelector('.heart-solid');
    const counter = btn.querySelector('.like-count');

    // Ensure initial state: outline visible, solid hidden (in case)
    if(solid) solid.classList.add('hidden');
    if(outline) outline.classList.remove('hidden');

    btn.addEventListener('click', () => {
      const isLiked = !solid.classList.contains('hidden');
      let count = parseCountFromText(counter.textContent);

      if (isLiked) {
        // UNLIKE
        if(solid) solid.classList.add('hidden');
        if(outline) outline.classList.remove('hidden');
        btn.setAttribute('aria-pressed', 'false');
        counter.textContent = `${Math.max(0, count - 1)} likes`;
      } else {
        // LIKE
        if(solid) solid.classList.remove('hidden');
        if(outline) outline.classList.add('hidden');
        btn.setAttribute('aria-pressed', 'true');
        counter.textContent = `${count + 1} likes`;
      }
    });
  });
}

/* ---------- INIT RENDER ---------- */
function renderAll(){
    // Ensure profile header shows current user info
    document.querySelector(".profile-username").textContent = currentUser.name;
    if(currentUser.bio) document.querySelector(".profile-desc").textContent = currentUser.bio;
    if(currentUser.avatar){
        applyAvatarToUI(currentUser.avatar);
    } else {
        // ensure initials shown
        const dataUrl = generateInitialsDataUrl(currentUser.name);
        applyAvatarToUI(dataUrl);
    }

    renderPosts();
    renderReplies();
    renderMedia();
    // wire up comment and like handlers after DOM is updated
    setupComments();
    setupLikeButtons();
}
renderAll();

/* close ctx when clicking outside */
document.addEventListener('click', e=>{
    if(!e.target.closest('.three-dots') && !e.target.closest('.ctx-menu')){
        closeAllContextMenus();
    }
});
</script>

</body>
</html>

@endsection
