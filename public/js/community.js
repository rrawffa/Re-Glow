// community-frontend.js
// Frontend-only logic for Community Sharing (client-side state + localStorage)
// Features: create post popup -> publish -> switch to Threads, tabs (threads/replies/media),
// context menu owner vs other (edit/delete vs report), edit window <=24h, delete confirm, report confirm.
// Uses window.App.currentUserId & window.App.currentUserName injected by Blade.

(function(){
  // -- state --
  const STORAGE_KEY = 'reglow_posts_v1';
  const currentUserId = Number(window.App && window.App.currentUserId) || 0;
  const currentUserName = window.App && window.App.currentUserName || 'You';
  const postsListEl = document.getElementById('posts-list');
  const mediaGridEl = document.getElementById('media-grid');
  const repliesListEl = document.getElementById('replies-list');

  // modals
  const overlays = {
    create: document.getElementById('create-overlay'),
    edit: document.getElementById('edit-overlay'),
    delete: document.getElementById('delete-overlay'),
    report: document.getElementById('report-overlay'),
    expired: document.getElementById('expired-overlay')
  };

  // inputs
  const createTitle = document.getElementById('create-title');
  const createBody  = document.getElementById('create-body');
  const createImage = document.getElementById('create-image');

  const editTitle = document.getElementById('edit-title');
  const editBody  = document.getElementById('edit-body');

  // buttons
  const openCreateBtns = [document.getElementById('open-create-btn'), document.getElementById('open-create-btn-2')];
  const createCancel = document.getElementById('create-cancel');
  const createClose2 = document.getElementById('create-close-2');
  const createPublish = document.getElementById('create-publish');

  const editCancel = document.getElementById('edit-cancel');
  const editClose = document.getElementById('edit-close');
  const editSave = document.getElementById('edit-save');

  const deleteCancel = document.getElementById('delete-cancel');
  const deleteConfirm = document.getElementById('delete-confirm');

  const reportCancel = document.getElementById('report-cancel');
  const reportConfirm = document.getElementById('report-confirm');

  const expiredOk = document.getElementById('expired-ok');

  // tabs
  const tabs = document.querySelectorAll('.tab');

  // internal
  let posts = loadPosts();
  let editingPostId = null;
  let deletingPostId = null;
  let reportingPostId = null;

  // --- helpers ---
  function nowMs(){ return Date.now(); }
  function uid(){ return 'p' + nowMs() + '_' + Math.floor(Math.random()*9999); }
  function savePosts(){ localStorage.setItem(STORAGE_KEY, JSON.stringify(posts)); }
  function loadPosts(){
    try{
      const raw = localStorage.getItem(STORAGE_KEY);
      if(!raw) {
        // seed sample posts
        const sample = [
          {
            id: 'p_sample_1',
            ownerId: 2,
            userName: 'Emma Rodriguez',
            title: '',
            body: 'Just tried making my own face mask with honey, oats, and turmeric. My skin feels amazing! 🌿✨',
            image: 'https://picsum.photos/1000/600?random=11',
            createdAt: nowMs() - (2*60*60*1000),
          },
          {
            id: 'p_sample_2',
            ownerId: 3,
            userName: 'Marcus Chen',
            title: '',
            body: 'Switched to a bamboo toothbrush and refillable deodorant this month. Small changes, big impact!',
            image: null,
            createdAt: nowMs() - (30*60*60*1000),
          },
          {
            id: 'p_sample_3',
            ownerId: currentUserId,
            userName: currentUserName,
            title: '',
            body: 'Found an amazing local store that sells package-free shampoo bars and body care products.',
            image: 'https://picsum.photos/1000/600?random=7',
            createdAt: nowMs() - (10*60*60*1000),
          }
        ];
        localStorage.setItem(STORAGE_KEY, JSON.stringify(sample));
        return sample;
      }
      return JSON.parse(raw);
    }catch(e){ console.error(e); return []; }
  }

  function timeAgo(ms){
    const s = Math.floor((Date.now()-ms)/1000);
    if(s < 60) return s + 's';
    if(s < 3600) return Math.floor(s/60) + 'm';
    if(s < 86400) return Math.floor(s/3600) + 'h';
    return Math.floor(s/86400) + 'd';
  }

  function escapeHtml(s){ if(!s) return ''; return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

  // --- renderers ---
  function renderPosts(){
    postsListEl.innerHTML = '';
    posts.slice().reverse().forEach(post => {
      const card = document.createElement('article');
      card.className = 'card';
      card.dataset.id = post.id;

      const avatar = `<img src="${post.image || 'https://via.placeholder.com/80'}" class="avatar" alt="">`;
      const meta = document.createElement('div'); meta.className = 'meta';
      meta.innerHTML = `<img src="${post.image ? post.image : 'https://via.placeholder.com/80'}" class="avatar" style="width:50px;height:50px;border-radius:10px;object-fit:cover"><div><div class="user">${escapeHtml(post.userName)}</div><div class="time">${timeAgo(post.createdAt)} • ${new Date(post.createdAt).toLocaleString()}</div></div>`;

      const content = document.createElement('div'); content.className = 'content';
      content.innerHTML = escapeHtml(post.body || '');

      card.appendChild(meta);
      card.appendChild(content);

      if(post.image){
        const img = document.createElement('img');
        img.className = 'post-image';
        img.src = post.image;
        card.appendChild(img);
      }

      const dots = document.createElement('div'); dots.className = 'three-dots';
      dots.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24"><circle cx="5" cy="12" r="2.2" fill="#666"/><circle cx="12" cy="12" r="2.2" fill="#666"/><circle cx="19" cy="12" r="2.2" fill="#666"/></svg>`;
      dots.addEventListener('click', (e)=> {
        e.stopPropagation();
        openContextMenu(card, post);
      });
      card.appendChild(dots);

      postsListEl.appendChild(card);
    });
  }

  function renderMedia(){
    mediaGridEl.innerHTML = '';
    posts.filter(p => p.image).slice().reverse().forEach(p=>{
      const mc = document.createElement('div');
      mc.className = 'media-card';
      mc.innerHTML = `<img src="${p.image}" style="width:100%;height:160px;object-fit:cover;display:block"><div style="padding:8px"><div style="font-weight:700">${escapeHtml(p.userName)}</div><div style="font-size:13px;color:#666;margin-top:6px">${timeAgo(p.createdAt)}</div></div>`;
      mediaGridEl.appendChild(mc);
    });
  }

  function renderReplies(){
    repliesListEl.innerHTML = '';
    // For demo: replies are synthetic from posts that have replies property if present.
    const userReplies = [];
    posts.forEach(p=>{
      if(p.replies && p.replies.length){
        p.replies.forEach(r => {
          if(r.userId === currentUserId) userReplies.push({postTitle: p.userName + ' • ' + (p.title || ''), body: r.body, createdAt: r.createdAt});
        });
      }
    });
    if(userReplies.length === 0){
      repliesListEl.innerHTML = '<div style="color:#666">No replies yet (demo)</div>';
      return;
    }
    userReplies.forEach(r=>{
      const el = document.createElement('div'); el.className='card';
      el.innerHTML = `<div style="font-weight:700">${escapeHtml(r.postTitle)}</div><div style="color:#444;margin-top:6px">${escapeHtml(r.body)}</div><div style="color:#888;font-size:13px;margin-top:8px">${timeAgo(r.createdAt)}</div>`;
      repliesListEl.appendChild(el);
    });
  }

  function reRenderAll(){ renderPosts(); renderMedia(); renderReplies(); }

  // --- context menu ---
  function openContextMenu(cardEl, post){
    // remove existing
    document.querySelectorAll('.ctx-menu').forEach(n=>n.remove());

    const menu = document.createElement('div'); menu.className = 'ctx-menu';
    const isOwner = Number(post.ownerId || post.ownerId) === Number(currentUserId);

    if(isOwner){
      menu.innerHTML = `<div class="ctx-item" data-action="edit"><span class="label">Edit</span></div>
                        <div class="ctx-item" data-action="delete" style="color:${getComputedStyle(document.documentElement).getPropertyValue('--danger') || '#d9534f'}"><span class="label">Delete</span></div>`;
      menu.querySelector('[data-action="edit"]').addEventListener('click', ()=>{ menu.remove(); openEdit(post.id); });
      menu.querySelector('[data-action="delete"]').addEventListener('click', ()=>{ menu.remove(); openDelete(post.id); });
    } else {
      menu.innerHTML = `<div class="ctx-item report" data-action="report" style="color:${getComputedStyle(document.documentElement).getPropertyValue('--danger') || '#d9534f'}"><span class="label">Report</span></div>`;
      menu.querySelector('[data-action="report"]').addEventListener('click', ()=>{ menu.remove(); openReport(post.id); });
    }

    cardEl.appendChild(menu);
    setTimeout(()=> menu.classList.add('show'), 10);

    // close when clicking outside
    const onDocClick = (ev) => { if(!menu.contains(ev.target) && !cardEl.contains(ev.target)){ menu.remove(); document.removeEventListener('click', onDocClick); } };
    document.addEventListener('click', onDocClick);
  }

  // --- modal controllers ---
  function showOverlay(name){ overlays[name].classList.add('show'); overlays[name].style.display='flex'; }
  function hideOverlay(name){ overlays[name].classList.remove('show'); overlays[name].style.display='none'; }

  // Create
  openCreateBtns.forEach(b=> b && b.addEventListener('click', ()=> showOverlay('create')));
  createCancel.addEventListener('click', ()=> hideOverlay('create'));
  createClose2.addEventListener('click', ()=> hideOverlay('create'));

  createPublish.addEventListener('click', async ()=>{
    const body = document.getElementById('create-body').value.trim();
    const title = document.getElementById('create-title').value.trim();
    const file = createImage.files[0];

    if(!body && !file){
      alert('Please write something or choose an image.');
      return;
    }

    // if image -> read as dataURL
    let imgData = null;
    if(file){
      imgData = await readFileAsDataURL(file);
    }

    const newPost = {
      id: uid(),
      ownerId: currentUserId,
      userName: currentUserName,
      title: title,
      body: body,
      image: imgData,
      createdAt: nowMs(),
      replies: []
    };
    posts.push(newPost);
    savePosts();
    hideOverlay('create');

    // reset form
    createTitle.value=''; createBody.value=''; createImage.value='';

    // switch to Threads tab
    document.querySelectorAll('.tab').forEach(t=>t.classList.remove('active'));
    document.querySelector('.tab[data-tab="threads"]').classList.add('active');
    document.querySelectorAll('.panel').forEach(p=>p.style.display='none');
    document.getElementById('threads').style.display='block';

    reRenderAll();
  });

  function readFileAsDataURL(file){
    return new Promise((res, rej)=>{
      const fr = new FileReader();
      fr.onload = ()=> res(fr.result);
      fr.onerror = ()=> rej();
      fr.readAsDataURL(file);
    });
  }

  // Edit
  function openEdit(postId){
    const post = posts.find(p=>p.id === postId);
    if(!post) return;
    const hours = (nowMs() - post.createdAt) / (1000*60*60);
    if(hours > 24){
      showOverlay('expired');
      return;
    }
    editingPostId = postId;
    editTitle.value = post.title || '';
    editBody.value = post.body || '';
    showOverlay('edit');
  }

  editCancel.addEventListener('click', ()=> hideOverlay('edit'));
  editClose.addEventListener('click', ()=> hideOverlay('edit'));
  editSave.addEventListener('click', ()=>{
    if(!editingPostId) return;
    const post = posts.find(p=>p.id===editingPostId);
    if(!post) return;
    const hours = (nowMs() - post.createdAt) / (1000*60*60);
    if(hours > 24){
      hideOverlay('edit');
      showOverlay('expired');
      return;
    }
    post.title = editTitle.value;
    post.body = editBody.value;
    savePosts();
    editingPostId = null;
    hideOverlay('edit');
    reRenderAll();
  });

  // Delete
  function openDelete(postId){
    deletingPostId = postId;
    showOverlay('delete');
  }
  deleteCancel.addEventListener('click', ()=> { deletingPostId = null; hideOverlay('delete'); });
  deleteConfirm.addEventListener('click', ()=>{
    if(!deletingPostId) return;
    posts = posts.filter(p=>p.id !== deletingPostId);
    savePosts();
    deletingPostId = null;
    hideOverlay('delete');
    reRenderAll();
  });

  // Report
  function openReport(postId){
    reportingPostId = postId;
    showOverlay('report');
  }
  reportCancel.addEventListener('click', ()=> { reportingPostId = null; hideOverlay('report'); });
  reportConfirm.addEventListener('click', ()=>{
    if(!reportingPostId) return;
    // remove post from view for this user (client-side)
    posts = posts.filter(p=>p.id !== reportingPostId);
    savePosts();
    reportingPostId = null;
    hideOverlay('report');
    reRenderAll();
  });

  // expired
  expiredOk.addEventListener('click', ()=> hideOverlay('expired'));

  // tabs
  tabs.forEach(t=>{
    t.addEventListener('click', ()=>{
      tabs.forEach(x=>x.classList.remove('active'));
      t.classList.add('active');
      const target = t.dataset.tab;
      document.querySelectorAll('.panel').forEach(p=>p.style.display='none');
      document.getElementById(target).style.display='block';
    });
  });

  // close any ctx menu when clicking anywhere
  document.addEventListener('click', ()=> { document.querySelectorAll('.ctx-menu').forEach(n=>n.remove()); });

  // initial render
  reRenderAll();

})();
