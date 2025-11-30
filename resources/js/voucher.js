document.addEventListener('DOMContentLoaded', function() {

  // Tabs switch
  const tabs = document.querySelectorAll('.tab-btn');
  const panels = document.querySelectorAll('.tab-panel');

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');

      panels.forEach(p => p.style.display = 'none');
      document.getElementById('tab-' + tab.dataset.tab).style.display = 'grid';
    });
  });

  // Favorite toggle
  const favButtons = document.querySelectorAll('.fav-toggle');
  favButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      btn.textContent = btn.textContent === '♡' ? '♥' : '♡';
      // Bisa ditambahkan AJAX untuk update DB favorite
    });
  });

  // Load More Button
  const loadMoreBtn = document.getElementById('loadMoreBtn');
  if (loadMoreBtn) {
    loadMoreBtn.addEventListener('click', () => {
      alert('Load more vouchers logic goes here.');
    });
  }

});
