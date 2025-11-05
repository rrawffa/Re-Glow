console.log('Reaction script loaded');

document.addEventListener('DOMContentLoaded', function() {
    const reactionButtons = document.querySelectorAll('.reaction-btn');
    const toast = document.getElementById('notificationToast');
    const toastEmoji = document.getElementById('toastEmoji');
    const toastMessage = document.getElementById('toastMessage');

    console.log('Found reaction buttons:', reactionButtons.length);

    // Emoji mapping
    const emojiMap = {
        'suka': '❤️',
        'membantu': '👍',
        'menarik': '🔥',
        'inspiratif': '✨'
    };

    function showToast(emoji, message, type = 'success') {
        toastEmoji.textContent = emoji;
        toastMessage.textContent = message;
        toast.className = `notification-toast ${type} show`;
        
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    reactionButtons.forEach(button => {
        button.addEventListener('click', async function() {
            const reactionType = this.dataset.reaction;
            const kontenId = this.dataset.konten;

            console.log('Button clicked:', {
                reactionType,
                kontenId
            });

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                
                if (!csrfToken) {
                    console.error('CSRF token not found!');
                    showToast('❌', 'Error: CSRF token tidak ditemukan', 'error');
                    return;
                }

                console.log('Sending request with CSRF:', csrfToken);

                const response = await fetch(`/education/${kontenId}/reaction`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        tipe_reaksi: reactionType
                    })
                });

                console.log('Response status:', response.status);
                const data = await response.json();
                console.log('Response data:', data);

                if (data.success) {
                    // Update UI based on action
                    if (data.action === 'removed') {
                        this.classList.remove('active');
                        showToast(emojiMap[reactionType], 'Reaksi dihapus', 'success');
                    } else if (data.action === 'added') {
                        reactionButtons.forEach(btn => btn.classList.remove('active'));
                        this.classList.add('active');
                        showToast(emojiMap[reactionType], 'Terima kasih atas reaksinya!', 'success');
                    } else if (data.action === 'updated') {
                        reactionButtons.forEach(btn => btn.classList.remove('active'));
                        this.classList.add('active');
                        showToast(emojiMap[reactionType], 'Reaksi diperbarui', 'success');
                    }

                    // Update counts
                    if (data.counts) {
                        console.log('Updating counts:', data.counts);
                        
                        // Update individual counts
                        document.querySelectorAll('.reaction-count-display').forEach(el => {
                            const type = el.dataset.type;
                            if (data.counts[type] !== undefined) {
                                el.textContent = data.counts[type];
                            }
                        });

                        // Update total
                        const totalEl = document.getElementById('totalReactions');
                        if (totalEl && data.counts.total !== undefined) {
                            totalEl.textContent = data.counts.total;
                        }
                    }
                } else {
                    showToast('❌', data.message || 'Terjadi kesalahan', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('❌', 'Terjadi kesalahan saat mengirim reaksi', 'error');
            }
        });
    });
});