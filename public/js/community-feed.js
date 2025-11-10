/* community-feed.js
   Frontend-only feed logic:
   - sample posts (multi-user)
   - create post (dataURL)
   - tabs: threads/replies/media
   - context menu on posts: REPORT only (red)
   - report confirmation modal removes post from view for this reporter (persist via localStorage)
*/

(() => {
  // config
  const STORAGE_KEY = 'reglow_feed_posts_v1';
  const REPORTED_KEY = 'reglow_reported_by_user_v1';
  const currentUserId = window.App && window.App.currentUserId ? Number(window.App.currentUserId) : 1;
  const currentUserName = window.App && window.App.currentUserName ? window.App.currentUserName : 'username_';

  // elements
  const postsList = document.getElementById('posts-list');
  const repliesList = document.getElementById('replies-list');
  const mediaGrid = document.getElementById('media-grid');
  const tabs = document.querySelectorAll('.tab');

  // modals
  const modalCreate = document.getElementById('modal-create');
  const modalReport = document.getElementById('modal-report');

  // create modal controls
  const openCreateInput = document.getElementById('open-create-input');
  const openCreateBtn = document.getElementById('open-create-btn');
  const createCancel = document.getElementById('create-cancel');
  const createClose = document.getElementById('create-close');
  const createPublish = document.getElementById('create-publish');

  // report controls
  const reportCancel = document.getElementById('report-cancel');
  const reportConfirm = document.getElementById('report-confirm');

  // internal state
  let posts = loadPosts();
  let reportedByUser = loadReported();
  let postToReport = null;

  // --- helpers ---
  function nowMs(){ return Date.now(); }
  function savePosts(){ localStorage.setItem(STORAGE_KEY, JSON.stringify(posts)); }
  function saveReported(){ localStorage.setItem(REPORTED_KEY, JSON.stringify(reportedByUser)); }
  function loadPosts(){
    const raw = localStorage.getItem(STORAGE_KEY);
    if(raw) return JSON.parse(raw);
    // seed sample multi-user posts
    const seed = [
      { id:'u1-'+nowMs(), user:{id:2,name:'Emma Rodriguez', avatar:'https://i.pravatar.cc/80?img=12'}, title:'', body:'Just tried making my own face mask with honey, oats, and turmeric. My skin feels amazing!', image:'https://picsum.photos/900/600?random=11', createdAt: nowMs() - (2*60*60*1000) },
      { id:'u2-'+(nowMs()+1), user:{id:3,name:'Marcus Chen', avatar:'https://i.pravatar.cc/80?img=8'}, title:'', body:'Switched to a bamboo toothbrush and refillable deodorant this month. Small changes, big impact!', image:null, createdAt: nowMs() - (30*60*60*1000) },
      { id:'u3-'+(nowMs()+2), user:{id:4,name:'Sarah Johnson', avatar:'https://i.pravatar.cc/80?img=5'}, title:'', body:'Found this local store selling shampoo bars and package-free products.', image:'https://picsum.photos/900/600?random=7', createdAt: nowMs() - (10*60*60*1000) },
      // include one by current user too to show
      { id:'me-'+(nowMs()+3), user:{id:currentUserId,name:currentUserName, avatar:'https://via.placeholder.com/80'}, title:'', body:'Sharing my sustainable swap list!', image:null, createdAt: nowMs() - (6*60*60*1000) }
    ];
    localStorage.setItem(STORAGE_KEY, JSON.stringify(seed));
    return seed;
  }
  function loadReported(){
    const raw = localStorage.getItem(REPORTED_KEY);
    if(raw) return JSON.parse(raw);
    return {}; // map: userId -> [postId,...]
  }
  function timeAgo(ts){
    const s = Math.floor((Date.now() - ts)/1000);
    if(s<60) return s+'s';
    if(s<3600) return Math.floor(s/60)+'m';
    if(s<86400) return Math.floor(s/3600)+'h';
    return Math.floor(s/86400)+'d';
  }
  function escapeHtml(s){ if(!s) return ''; return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

  // --- rendering ---
  function renderPosts(){
    postsList.innerHTML = '';
    // filter out posts that current user reported
    const hidden = (reportedByUser[currentUserId] || []);
    posts.slice().reverse().forEach(p=>{
      if(hidden.includes(p.id)) return;
      const card = document.createElement('article');
      card.className = 'card';
      // meta
      const meta = document.createElement('div'); meta.className='meta';
      const avatar = document.createElement('img'); avatar.className='avatar'; avatar.src = p.user.avatar || 'https://via.placeholder.com/80';
      const info = document.createElement('div');
      info.innerHTML = `<div class="name">${escapeHtml(p.user.name)}</div><div class="time">${timeAgo(p.createdAt)}</div>`;
      meta.appendChild(avatar); meta.appendChild(info);
      card.appendChild(meta);
      // content
      const content = document.createElement('div'); content.className='content'; content.innerText = p.body || '';
      card.appendChild(content);
      // image
      if(p.image){
        const img = document.createElement('img'); img.className='post-image'; img.src = p.image;
        card.appendChild(img);
      }
      // three-dots (report-only)
      const dots = document.createElement('div'); dots.className='three-dots';
      dots.innerHTML = `<svg width="20" height="20" viewBox="0 0 24 24"><path d="M3 6h18v2H3zM3 11h18v2H3zM3 16h18v2H3z" fill="#666"/></svg>`;
      dots.addEventListener('click', (ev) => {
        ev.stopPropagation();
        openReportMenu(card, p);
      });
      card.appendChild(dots);
      postsList.appendChild(card);
    });
  }

  function renderMedia(){
    mediaGrid.innerHTML = '';
    const hidden = (reportedByUser[currentUserId] || []);
    posts.filter(p=>p.image).slice().reverse().forEach(p=>{
      if(hidden.includes(p.id)) return;
      const mc = document.createElement('div'); mc.className='media-card';
      mc.innerHTML = `<img src="${p.image}" alt=""><div style="padding:10px"><div style="font-weight:700">${escapeHtml(p.user.name)}</div><div style="font-size:13px;color:#666">${timeAgo(p.createdAt)}</div></div>`;
      mediaGrid.appendChild(mc);
    });
  }

  function renderReplies(){
    repliesList.innerHTML = '';
    // Simple demo: list replies by current user if any; otherwise placeholder
    const demoReplies = [
      {id:'r1', body:'Great tip!', to:'Sarah Johnson', createdAt: nowMs() - (6*60*60*1000)},
      {id:'r2', body:'Will try this', to:'Emma Rodriguez', createdAt: nowMs() - (20*60*60*1000)}
    ];
    demoReplies.forEach(r=>{
      const c = document.createElement('div'); c.className='card';
      c.innerHTML = `<div style="font-weight:700">${escapeHtml(r.to)}</div><div class="content" style="margin-top:8px">${escapeHtml(r.body)}</div><div style="color:#777;font-size:13px;margin-top:8px">${timeAgo(r.createdAt)}</div>`;
      repliesList.appendChild(c);
    });
  }

  function renderAll(){ renderPosts(); renderMedia(); renderReplies(); }

  // --- report menu + actions ---
  function openReportMenu(cardEl, post){
    // close any existing menu
    document.querySelectorAll('.ctx-menu').forEach(n=>n.remove());
    const menu = document.createElement('div'); menu.className='ctx-menu';
    menu.innerHTML = `
      <div class="ctx-item report" data-act="report">
        <svg width="16" height="16" viewBox="0 0 24 24" style="margin-right:8px"><path d="M3 6h18v2H3zM3 11h18v2H3zM3 16h18v2H3z" fill="${'#e44'}"/></svg>
        <div style="flex:1;color:#e44;font-weight:700">Report</div>
      </div>
    `;
    menu.querySelector('[data-act="report"]').addEventListener('click', ()=> {
      menu.remove();
      // open confirm modal
      postToReport = post;
      modalReport.classList.add('show');
      modalReport.style.display = 'flex';
    });
    cardEl.appendChild(menu);
    setTimeout(()=> menu.classList.add('show'), 10);

    // close on outside click
    const onDocClick = (ev)=>{
      if(!menu.contains(ev.target) && !cardEl.contains(ev.target)){
        menu.remove();
        document.removeEventListener('click', onDocClick);
      }
    };
    document.addEventListener('click', onDocClick);
  }

  reportCancel.addEventListener('click', ()=> {
    postToReport = null;
    modalReport.classList.remove('show'); modalReport.style.display='none';
  });

  reportConfirm.addEventListener('click', ()=> {
    if(!postToReport) return;
    // mark reported for this user
    reportedByUser[currentUserId] = reportedByUser[currentUserId] || [];
    if(!reportedByUser[currentUserId].includes(postToReport.id)){
      reportedByUser[currentUserId].push(postToReport.id);
    }
    saveReported();
    // hide modal
    modalReport.classList.remove('show'); modalReport.style.display='none';
    postToReport = null;
    // re-render
    renderAll();
  });

  // --- create post modal ---
  openCreateInput && openCreateInput.addEventListener('click', openCreateModal);
  openCreateBtn && openCreateBtn.addEventListener('click', openCreateModal);
  createCancel && createCancel.addEventListener('click', closeCreateModal);
  createClose && createClose.addEventListener('click', closeCreateModal);

  function openCreateModal(){
    modalCreate.classList.add('show'); modalCreate.style.display='flex';
    // clear fields
    document.getElementById('create-title').value = '';
    document.getElementById('create-body').value = '';
    document.getElementById('create-image').value = '';
  }
  function closeCreateModal(){
    modalCreate.classList.remove('show'); modalCreate.style.display='none';
  }

  createPublish.addEventListener('click', async ()=> {
    const title = document.getElementById('create-title').value.trim();
    const body = document.getElementById('create-body').value.trim();
    const file = document.getElementById('create-image').files[0];
    if(!body && !file) return alert('Please write something or choose an image.');

    let imageData = null;
    if(file){
      imageData = await fileToDataURL(file);
    }

    // create post as currentUser
    const newPost = {
      id: 'post-' + nowMs(),
      user: { id: currentUserId, name: currentUserName, avatar: 'https://via.placeholder.com/80' },
      title: title,
      body: body,
      image: imageData,
      createdAt: nowMs()
    };
    posts.push(newPost);
    savePosts();
    closeCreateModal();
    // switch to threads tab
    document.querySelectorAll('.tab').forEach(t=>t.classList.remove('active'));
    document.querySelector('.tab[data-tab="threads"]').classList.add('active');
    document.getElementById('threads').style.display = 'block';
    document.getElementById('replies').style.display = 'none';
    document.getElementById('media').style.display = 'none';
    renderAll();
  });

  // helper read file
  function fileToDataURL(file){
    return new Promise((res, rej) => {
      const fr = new FileReader();
      fr.onload = ()=> res(fr.result);
      fr.onerror = rej;
      fr.readAsDataURL(file);
    });
  }

  // --- tabs wiring ---
  tabs.forEach(t=>{
    t.addEventListener('click', ()=>{
      tabs.forEach(x=>x.classList.remove('active'));
      t.classList.add('active');
      const target = t.dataset.tab;
      document.getElementById('threads').style.display = (target === 'threads') ? 'block' : 'none';
      document.getElementById('replies').style.display = (target === 'replies') ? 'block' : 'none';
      document.getElementById('media').style.display = (target === 'media') ? 'block' : 'none';
    });
  });

  // init
  renderAll();

})();
