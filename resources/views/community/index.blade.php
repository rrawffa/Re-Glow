@extends('layouts.app')

@section('title', 'Community Sharing')

@section('content')

<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Community Sharing</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
<style>
  .material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    cursor: pointer;
  }
  .material-symbols-outlined.filled {
    font-variation-settings: 'FILL' 1;
  }
  /* small helper for dropdown visibility */
  .hidden { display: none !important; }
</style>
<script id="tailwind-config">
  tailwind.config = {
    darkMode: "class",
    theme: {
      extend: {
        colors: {
          "primary": "#ef8fa7",
          "background-light": "#F9FAFB",
          "background-dark": "#211115",
          "text-light": "#1b0e11",
          "text-dark": "#f8f6f6",
          "secondary-light": "#f3e8ea",
          "secondary-dark": "#3a2a2e",
          "muted-light": "#955062",
          "muted-dark": "#a88d94",
        },
        fontFamily: {
          "display": ["Plus Jakarta Sans", "Noto Sans", "sans-serif"]
        },
        borderRadius: {"DEFAULT": "0.5rem", "lg": "0.75rem", "xl": "1rem", "full": "9999px"},
      },
    },
  }
</script>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark">
<div class="relative flex h-auto min-h-screen w-full flex-col overflow-x-hidden">

  <!-- HEADER (ke-include sudah di layout) -->
  <header class="sticky top-0 z-10 w-full bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-sm">
    {{-- navbar included above --}}
  </header>

  <main class="layout-container flex h-full grow flex-col">
    <div class="px-4 sm:px-6 lg:px-10 flex flex-1 justify-center py-5">
      <div class="layout-content-container flex flex-col w-full max-w-7xl flex-1 gap-8">

        <!-- HERO -->
        <div class="flex w-full flex-col items-center justify-center gap-4 rounded-xl bg-gradient-to-b from-[#F3F3F3] to-[#F3F3F3] via-pink-50/10 py-12 px-6 text-center">
          <div class="w-12 h-0.5 bg-[#F9B6C7] mb-2"></div>
          <h1 class="text-4xl font-black leading-tight tracking-[-0.033em] text-[#20413A]">Share Your Story, Inspire Others!</h1>
          <p class="max-w-2xl text-[#666666]">Join the conversation and share your sustainable beauty tips, DIY recipes, and favorite eco-friendly products with our amazing community.</p>
          <button id="openCreatePostBanner" class="mt-4 flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center rounded-lg h-10 px-5 bg-[#F9B6C7] text-white text-sm font-bold hover:opacity-90 transition-opacity">
            <span class="truncate">Create Post</span>
          </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
          <!-- FEED -->
          <div class="lg:col-span-2 flex flex-col gap-6" id="feedColumn">

            <!-- POST CARD 1 -->
            <div class="post-card flex flex-col w-full rounded-xl bg-white dark:bg-secondary-dark border border-secondary-light dark:border-secondary-dark overflow-hidden">
              <div class="flex w-full flex-row items-center justify-start gap-3 p-4">
                <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full w-10 shrink-0" style='background-image: url("https://i.pravatar.cc/50?u=emma");'></div>
                <div class="flex h-full flex-1 flex-col items-start justify-start">
                  <p class="text-sm font-bold">Emma Rodriguez</p>
                  <p class="text-muted-light dark:text-muted-dark text-xs">2 hours ago</p>
                </div>

                <!-- three dots → button triggers dropdown (click) -->
                <div class="relative">
                  <button class="more-btn text-muted-light dark:text-muted-dark p-2 rounded-full" aria-expanded="false">
                    <span class="material-symbols-outlined">more_vert</span>
                  </button>

                  <!-- dropdown (hidden by default, toggled by click) -->
                  <div class="report-dropdown hidden absolute right-0 mt-2 bg-white dark:bg-secondary-dark rounded-lg shadow-lg border border-secondary-light dark:border-zinc-700 z-10 w-40">
                    <button class="report-btn flex items-center gap-3 px-4 py-2 w-full text-left text-sm text-red-600 hover:bg-secondary-light dark:hover:bg-zinc-700">
                      <span class="material-symbols-outlined text-red-600">flag</span>
                      Report
                    </button>
                  </div>
                </div>
              </div>

              <div class="px-4 pb-4">
                <p class="text-sm">Just tried making my own coffee scrub and my skin feels amazing! #ZeroWaste #DIYSkincare</p>
              </div>

              <div class="w-full grow aspect-[3/2]">
                <div class="w-full h-full bg-center bg-no-repeat bg-cover" style='background-image: url("https://picsum.photos/900/600?random=1");'></div>
              </div>

              <div class="flex flex-wrap gap-4 px-4 py-2 border-t border-secondary-light dark:border-secondary-dark/50 items-center">
                <!-- like button (material symbol) -->
                <button class="like-btn flex items-center gap-2" aria-pressed="false">
                  <span class="material-symbols-outlined like-icon">favorite_border</span>
                  <span class="like-count text-[13px] font-bold">129 likes</span>
                </button>

                <!-- comments toggle -->
                <button class="comment-toggle-btn flex items-center gap-2 text-muted-light dark:text-muted-dark">
                  <span class="material-symbols-outlined">chat_bubble_outline</span>
                  <span class="text-[13px] font-bold">12 comments</span>
                </button>
              </div>

              <!-- comment area (initially hidden) -->
              <div class="comment-section hidden px-4 pb-4 border-t border-secondary-light dark:border-secondary-dark/40">
                <div class="comments-list space-y-3 mt-3">
                  <div class="flex items-start gap-3">
                    <img src="https://i.pravatar.cc/40?u=chloe" class="w-8 h-8 rounded-full">
                    <div>
                      <div class="text-sm font-bold">Chloe <span class="text-xs text-muted-light">· 1 hour ago</span></div>
                      <div class="text-sm">I need to try this! Do you add any essential oils?</div>
                    </div>
                  </div>
                </div>

                <div class="flex items-center gap-3 mt-3">
                  <input type="text" class="comment-input flex-1 rounded-lg border px-3 py-2 text-sm" placeholder="Write a comment...">
                  <button class="add-comment-btn px-3 py-2 rounded-lg bg-primary text-white text-sm font-semibold">Post</button>
                </div>
              </div>
            </div>

            <!-- POST CARD 2 (another example) -->
            <div class="post-card flex flex-col w-full rounded-xl bg-white dark:bg-secondary-dark border border-secondary-light dark:border-secondary-dark overflow-hidden">
              <div class="flex w-full flex-row items-center justify-start gap-3 p-4">
                <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full w-10 shrink-0" style='background-image: url("https://i.pravatar.cc/50?u=liam");'></div>
                <div class="flex h-full flex-1 flex-col items-start justify-start">
                  <p class="text-sm font-bold">Liam Chen</p>
                  <p class="text-muted-light dark:text-muted-dark text-xs">1 day ago</p>
                </div>

                <div class="relative">
                  <button class="more-btn text-muted-light dark:text-muted-dark p-2 rounded-full" aria-expanded="false">
                    <span class="material-symbols-outlined">more_vert</span>
                  </button>
                  <div class="report-dropdown hidden absolute right-0 mt-2 bg-white dark:bg-secondary-dark rounded-lg shadow-lg border border-secondary-light dark:border-zinc-700 z-10 w-40">
                    <button class="report-btn flex items-center gap-3 px-4 py-2 w-full text-left text-sm text-red-600 hover:bg-secondary-light dark:hover:bg-zinc-700">
                      <span class="material-symbols-outlined text-red-600">flag</span>
                      Report
                    </button>
                  </div>
                </div>
              </div>

              <div class="px-4 pb-4">
                <p class="text-sm">My favorite sustainable swap has been switching to shampoo bars. #CleanBeauty #SustainableSwaps</p>
              </div>

              <div class="w-full grow aspect-[3/2]">
                <div class="w-full h-full bg-center bg-no-repeat bg-cover" style='background-image: url("https://picsum.photos/900/600?random=2");'></div>
              </div>

              <div class="flex flex-wrap gap-4 px-4 py-2 border-t border-secondary-light dark:border-secondary-dark/50 items-center">
                <button class="like-btn flex items-center gap-2" aria-pressed="false">
                  <span class="material-symbols-outlined like-icon">favorite_border</span>
                  <span class="like-count text-[13px] font-bold">97 likes</span>
                </button>

                <button class="comment-toggle-btn flex items-center gap-2 text-muted-light dark:text-muted-dark">
                  <span class="material-symbols-outlined">chat_bubble_outline</span>
                  <span class="text-[13px] font-bold">8 comments</span>
                </button>
              </div>

              <div class="comment-section hidden px-4 pb-4 border-t border-secondary-light dark:border-secondary-dark/40">
                <div class="comments-list space-y-3 mt-3"></div>
                <div class="flex items-center gap-3 mt-3">
                  <input type="text" class="comment-input flex-1 rounded-lg border px-3 py-2 text-sm" placeholder="Write a comment...">
                  <button class="add-comment-btn px-3 py-2 rounded-lg bg-primary text-white text-sm font-semibold">Post</button>
                </div>
              </div>
            </div>

          </div>

          <!-- SIDEBAR: Create Post container -->
          <div class="lg:col-span-1 flex flex-col gap-8 sticky top-24">
            <div class="p-6 rounded-xl bg-white dark:bg-secondary-dark border border-secondary-light dark:border-secondary-dark">
              <h3 class="text-lg font-bold mb-4">Create a Post</h3>
              <div class="flex flex-col gap-4">
                <textarea id="openCreatePostInput" class="form-textarea w-full resize-none rounded-lg bg-secondary-light dark:bg-background-dark h-24 px-4 py-2 text-sm" placeholder="What's your sustainable beauty tip today?" readonly></textarea>
                <div class="flex items-center justify-between">
                  <button id="addPhotoBtn" class="flex items-center gap-2 text-muted-light dark:text-muted-dark">
                    <span class="material-symbols-outlined text-xl">add_photo_alternate</span>
                    <span class="text-sm font-medium">Add Image</span>
                  </button>
                  <button id="openCreatePostSidebar" class="flex min-w-[84px] items-center justify-center rounded-lg h-9 px-4 bg-primary text-text-light text-sm font-bold">
                    <span class="truncate">Post</span>
                  </button>
                </div>
              </div>
            </div>

            <div class="p-6 rounded-xl bg-white dark:bg-secondary-dark border border-secondary-light dark:border-secondary-dark">
              <h3 class="text-lg font-bold mb-4">Trending Topics</h3>
              <div class="flex flex-col gap-3">
                <a class="block text-sm font-medium text-muted-light hover:text-primary" href="#">#ZeroWaste</a>
                <a class="block text-sm font-medium text-muted-light hover:text-primary" href="#">#DIYSkincare</a>
                <a class="block text-sm font-medium text-muted-light hover:text-primary" href="#">#CleanBeauty</a>
                <a class="block text-sm font-medium text-muted-light hover:text-primary" href="#">#UpcycledPackaging</a>
                <a class="block text-sm font-medium text-muted-light hover:text-primary" href="#">#SustainableSwaps</a>
              </div>
            </div>
          </div>

        </div> <!-- grid -->

      </div>
    </div>
  </main>

  {{-- CREATE POST MODAL (INJECTED) --}}
  <div id="createModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-secondary-dark rounded-xl p-6 w-[90%] max-w-lg shadow-2xl border border-secondary-light dark:border-secondary-dark transition-all duration-300 transform scale-95 opacity-0">
      <h2 class="text-xl font-bold mb-4">Create New Post</h2>
      <textarea id="createBody" class="form-textarea w-full resize-none rounded-lg focus:ring-primary/50 border-secondary-light dark:border-secondary-dark/50 bg-secondary-light/50 dark:bg-background-dark/50 h-24 px-4 py-3 text-sm" placeholder="Share your sustainable beauty tip..."></textarea>
      <input type="file" id="createImage" accept="image/*" class="w-full text-sm mt-2 border border-secondary-light dark:border-secondary-dark rounded-lg p-2 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/20 file:text-primary hover:file:bg-primary/30"/>
      <div id="createError" class="text-red-600 text-sm mt-3 hidden">Please write something or upload an image.</div>
      <div class="flex justify-end gap-3 mt-4">
        <button id="cancelCreate" class="px-4 py-2 rounded-lg bg-secondary-light dark:bg-secondary-dark text-sm font-medium hover:opacity-80">Cancel</button>
        <button id="publishPost" class="px-4 py-2 rounded-lg bg-primary text-text-light dark:text-text-dark text-sm font-bold hover:opacity-90">Publish</button>
      </div>
    </div>
  </div>

  {{-- REPORT CONFIRMATION MODAL --}}
  <div id="reportModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-secondary-dark rounded-xl p-6 w-[90%] max-w-sm shadow-lg border border-secondary-light dark:border-secondary-dark">
      <h2 class="text-lg font-bold mb-3">Report Post?</h2>
      <p class="text-sm text-muted-light dark:text-muted-dark mb-6">Are you sure you want to report this post?</p>
      <div class="flex justify-end gap-3">
        <button id="cancelReport" class="px-4 py-2 rounded-lg bg-secondary-light dark:bg-secondary-dark text-sm font-medium hover:opacity-80">Cancel</button>
        <button id="confirmReport" class="px-4 py-2 rounded-lg bg-red-500 text-white text-sm font-bold hover:bg-red-600">Report</button>
      </div>
    </div>
  </div>

</div>

<!-- INTERAKSI SCRIPT -->
<script>
document.addEventListener("DOMContentLoaded", function() {

  /* ------------------ HELPERS ------------------ */
  function escapeHtml(s) {
    return (s||'').replace(/[&<>"]/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]; });
  }

  function findLikeCountEl(post) {
    // try common selectors within a post-card
    return post.querySelector('.like-count') || post.querySelector('p:contains("likes")') || null;
  }

  /* ------------------ LIKE TOGGLE (supports both button & plain span versions) ------------------ */
  function setupLikeButtons() {
    document.querySelectorAll('.post-card').forEach(post => {
      // prefer button.like-btn
      let likeBtn = post.querySelector('.like-btn');
      let icon = likeBtn ? likeBtn.querySelector('.like-icon') : null;
      let countEl = likeBtn ? likeBtn.querySelector('.like-count') : null;

      // fallback: find any material-symbols inside the post with favorite text
      if(!likeBtn) {
        icon = post.querySelector('.material-symbols-outlined.like-icon') || Array.from(post.querySelectorAll('.material-symbols-outlined')).find(s => s.textContent.trim().includes('favorite'));
        // find parent clickable container for icon (if any)
        if(icon) {
          // attach click to icon's closest clickable ancestor, else icon itself
          likeBtn = icon.closest('button') || icon;
        }
        // find count near icon
        countEl = post.querySelector('.like-count') || Array.from(post.querySelectorAll('p,span')).find(el => /\d+\s+likes/.test((el.textContent||'')));
      }

      if(!likeBtn || !icon) return;

      // ensure icon has baseline text: 'favorite' or 'favorite_border'
      if(!icon.textContent.trim()) {
        icon.textContent = 'favorite_border';
      }

      // click handler
      const handler = (e) => {
        e.preventDefault();
        e.stopPropagation();
        const isFilled = icon.textContent.trim() === 'favorite';
        if(!isFilled) {
          icon.classList.add('filled');
          icon.textContent = 'favorite';
          icon.style.color = '#FF0000';
          likeBtn.setAttribute('aria-pressed', 'true');
        } else {
          icon.classList.remove('filled');
          icon.textContent = 'favorite_border';
          icon.style.color = '';
          likeBtn.setAttribute('aria-pressed', 'false');
        }
        // update count text if possible
        if(countEl) {
          // extract number
          const raw = (countEl.textContent||'').trim().split(' ')[0];
          const num = parseInt(raw) || 0;
          countEl.textContent = (isFilled ? (num - 1) : (num + 1)) + ' likes';
        }
      };

      // remove existing to avoid double-binding
      likeBtn.removeEventListener && likeBtn.removeEventListener('click', handler);
      likeBtn.addEventListener('click', handler);
      // also clickable on icon directly
      icon.addEventListener('click', handler);
    });
  }

  /* ------------------ REPORT DROPDOWN + CONFIRMATION ------------------ */
  const reportModalEl = document.getElementById('reportModal');
  let postToReport = null;

  // close dropdowns when clicking outside
  document.addEventListener('click', function(e){
    if(!e.target.closest('.more-btn') && !e.target.closest('.report-dropdown')) {
      document.querySelectorAll('.report-dropdown').forEach(dd => dd.classList.add('hidden'));
    }
  });

  // toggle dropdown on click of three-dots
  document.querySelectorAll('.more-btn').forEach(btn => {
    btn.addEventListener('click', (ev) => {
      ev.stopPropagation();
      const dd = btn.parentElement.querySelector('.report-dropdown') || btn.closest('.relative')?.querySelector('.report-dropdown');
      if(!dd) return;
      // toggle
      const isHidden = dd.classList.contains('hidden');
      // first close others
      document.querySelectorAll('.report-dropdown').forEach(d => d.classList.add('hidden'));
      if(isHidden) dd.classList.remove('hidden');
      else dd.classList.add('hidden');
    });
  });

  // Support older markup where report is an <a href="#">
  document.addEventListener('click', function(e){
    const a = e.target.closest('a[href="#"]');
    if(!a) return;
    if((a.textContent||'').toLowerCase().includes('report')) {
      e.preventDefault();
      const postCard = a.closest('.post-card') || a.closest('.flex.flex-col.w-full') || a.closest('[data-post-id]') || null;
      postToReport = postCard;
      if(reportModalEl) {
        reportModalEl.classList.remove('hidden');
        reportModalEl.classList.add('flex');
      } else {
        // fallback alert
        if(postCard && postCard.parentElement) postCard.remove();
        alert('✅ Post reported');
      }
    }
  });

  // click on report button inside dropdown
  document.querySelectorAll('.report-btn').forEach(btn => {
    btn.addEventListener('click', (ev) => {
      ev.preventDefault();
      ev.stopPropagation();
      postToReport = btn.closest('.post-card') || null;
      if(reportModalEl){
        reportModalEl.classList.remove('hidden');
        reportModalEl.classList.add('flex');
      } else {
        if(postToReport && postToReport.parentElement) postToReport.remove();
      }
      // hide dropdown
      const dd = btn.closest('.report-dropdown');
      if(dd) dd.classList.add('hidden');
    });
  });

  // cancel report
  const cancelReportBtn = document.getElementById('cancelReport');
  if(cancelReportBtn) cancelReportBtn.addEventListener('click', () => {
    if(reportModalEl){
      reportModalEl.classList.add('hidden');
      reportModalEl.classList.remove('flex');
    }
    postToReport = null;
  });

  // confirm report: remove from DOM
  const confirmReportBtn = document.getElementById('confirmReport');
  if(confirmReportBtn) confirmReportBtn.addEventListener('click', () => {
    if(postToReport && postToReport.parentElement){
      postToReport.remove();
    } else {
      // fallback: remove first post-card
      const fallback = document.querySelector('.post-card');
      if(fallback) fallback.remove();
    }
    if(reportModalEl){
      reportModalEl.classList.add('hidden');
      reportModalEl.classList.remove('flex');
    }
    postToReport = null;
    // feedback
    setTimeout(()=> alert('✅ Post reported and removed from your feed'), 80);
  });

  /* ------------------ COMMENTS: toggle & add ------------------ */
  function setupComments() {
    document.querySelectorAll('.post-card').forEach(post => {
      const toggleBtn = post.querySelector('.comment-toggle-btn');
      const section = post.querySelector('.comment-section');
      if(toggleBtn && section){
        toggleBtn.addEventListener('click', () => {
          section.classList.toggle('hidden');
        });
      }

      // add comment action
      const addBtn = post.querySelector('.add-comment-btn');
      if(addBtn){
        addBtn.addEventListener('click', () => {
          const input = addBtn.closest('.comment-section').querySelector('.comment-input');
          const list = addBtn.closest('.comment-section').querySelector('.comments-list');
          const text = input && input.value && input.value.trim();
          if(!text) return;
          const commentEl = document.createElement('div');
          commentEl.className = 'flex items-start gap-3';
          commentEl.innerHTML = `<img src="https://i.pravatar.cc/40?u=you" class="w-8 h-8 rounded-full"><div><div class="text-sm font-bold">You <span class="text-xs text-muted-light">· just now</span></div><div class="text-sm">${escapeHtml(text)}</div></div>`;
          list.appendChild(commentEl);
          input.value = '';

          // update comment count in toggle button (if exists)
          const toggleTextEl = post.querySelector('.comment-toggle-btn span:last-child');
          if(toggleTextEl){
            const cur = toggleTextEl.textContent.trim();
            const num = parseInt(cur) || 0;
            toggleTextEl.textContent = (num + 1) + ' comments';
          }
        });
      }
    });
  }

  /* ------------------ CREATE MODAL LOGIC ------------------ */
  const createModal = document.getElementById('createModal');
  const createBody = document.getElementById('createBody');
  const createImage = document.getElementById('createImage');
  const createError = document.getElementById('createError');
  const cancelCreate = document.getElementById('cancelCreate');
  const publishPost = document.getElementById('publishPost');

  function openModal(modal) {
    if(!modal) return;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    // small animation (if desired)
    const inner = modal.querySelector('div');
    if(inner){
      inner.style.opacity = "1";
      inner.style.transform = "scale(1)";
    }
  }
  function closeModal(modal) {
    if(!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    const inner = modal.querySelector('div');
    if(inner){
      inner.style.opacity = "";
      inner.style.transform = "";
    }
  }

  // triggers: banner, readonly input, sidebar button, add photo button
  const openCreatePostBanner = document.getElementById('openCreatePostBanner');
  const openCreatePostInput = document.getElementById('openCreatePostInput');
  const openCreatePostSidebar = document.getElementById('openCreatePostSidebar');
  const addPhotoBtn = document.getElementById('addPhotoBtn');

  [openCreatePostBanner, openCreatePostInput, openCreatePostSidebar, addPhotoBtn].forEach(el => {
    if(!el) return;
    el.addEventListener('click', (e) => {
      e.preventDefault();
      openModal(createModal);
    });
  });

  if(cancelCreate){
    cancelCreate.addEventListener('click', ()=> closeModal(createModal));
  }

  // close modal when clicking outside inner content
  if(createModal){
    createModal.addEventListener('click', (e) => {
      if(e.target === createModal) closeModal(createModal);
    });
  }

  // publish: validate, create new post in feed, rewire interactions, then redirect
  if(publishPost){
    publishPost.addEventListener('click', () => {
      const body = (createBody.value || '').trim();
      const file = createImage.files && createImage.files[0];
      if(!body && !file){
        if(createError) createError.classList.remove('hidden');
        return;
      }
      if(createError) createError.classList.add('hidden');

      const feed = document.getElementById('feedColumn');
      if(feed){
        const card = document.createElement('div');
        card.className = 'post-card flex flex-col w-full rounded-xl bg-white dark:bg-secondary-dark border border-secondary-light dark:border-secondary-dark overflow-hidden';
        const now = 'Just now';
        const safeBody = escapeHtml(body);
        card.innerHTML = `
          <div class="flex w-full flex-row items-center justify-start gap-3 p-4">
            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full w-10 shrink-0" style="background-image:url('https://i.pravatar.cc/50?u=you');"></div>
            <div class="flex h-full flex-1 flex-col items-start justify-start">
              <p class="text-sm font-bold">You</p>
              <p class="text-muted-light dark:text-muted-dark text-xs">${now}</p>
            </div>
            <div class="relative">
              <button class="more-btn text-muted-light dark:text-muted-dark p-2 rounded-full" aria-expanded="false">
                <span class="material-symbols-outlined">more_vert</span>
              </button>
              <div class="report-dropdown hidden absolute right-0 mt-2 bg-white dark:bg-secondary-dark rounded-lg shadow-lg border border-secondary-light dark:border-zinc-700 z-10 w-40">
                <button class="report-btn flex items-center gap-3 px-4 py-2 w-full text-left text-sm text-red-600 hover:bg-secondary-light dark:hover:bg-zinc-700">
                  <span class="material-symbols-outlined text-red-600">flag</span>
                  Report
                </button>
              </div>
            </div>
          </div>
          <div class="px-4 pb-4"><p class="text-sm">${safeBody}</p></div>
        `;

        // image preview if file
        if(file){
          const reader = new FileReader();
          reader.onload = function(e){
            const imgWrap = document.createElement('div');
            imgWrap.className = 'w-full grow aspect-[3/2]';
            imgWrap.innerHTML = `<div class="w-full h-full bg-center bg-no-repeat bg-cover" style="background-image:url('${e.target.result}');"></div>`;
            // insert after header
            const header = card.children[0];
            header.after(imgWrap);
          };
          reader.readAsDataURL(file);
        }

        // footer actions
        const footer = document.createElement('div');
        footer.className = 'flex flex-wrap gap-4 px-4 py-2 border-t border-secondary-light dark:border-secondary-dark/50 items-center';
        footer.innerHTML = `
          <button class="like-btn flex items-center gap-2" aria-pressed="false">
            <span class="material-symbols-outlined like-icon">favorite_border</span>
            <span class="like-count text-[13px] font-bold">0 likes</span>
          </button>
          <button class="comment-toggle-btn flex items-center gap-2 text-muted-light dark:text-muted-dark">
            <span class="material-symbols-outlined">chat_bubble_outline</span>
            <span class="text-[13px] font-bold">0 comments</span>
          </button>
        `;
        card.appendChild(footer);

        // comment section
        const commentSection = document.createElement('div');
        commentSection.className = 'comment-section hidden px-4 pb-4 border-t border-secondary-light dark:border-secondary-dark/40';
        commentSection.innerHTML = `
          <div class="comments-list space-y-3 mt-3"></div>
          <div class="flex items-center gap-3 mt-3">
            <input type="text" class="comment-input flex-1 rounded-lg border px-3 py-2 text-sm" placeholder="Write a comment...">
            <button class="add-comment-btn px-3 py-2 rounded-lg bg-primary text-white text-sm font-semibold">Post</button>
          </div>
        `;
        card.appendChild(commentSection);

        // prepend card
        feed.prepend(card);

        // re-setup interactions for new nodes
        setupLikeButtons();
        setupComments();

        // close modal
        closeModal(createModal);

        // redirect to profile after short delay
        setTimeout(()=> {
          window.location.href = "/community/profile";
        }, 450);
      }
    });
  }

  /* ------------------ INIT ------------------ */
  setupLikeButtons();
  setupComments();

});
</script>

</body>
</html>

@endsection
