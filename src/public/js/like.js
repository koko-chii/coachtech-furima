document.addEventListener('DOMContentLoaded', function () {
    const likeBtn = document.getElementById('like-btn');
    const heartImg = document.getElementById('heart-img');
    const likeCount = document.getElementById('like-count');

    if (likeBtn) {
        likeBtn.addEventListener('click', function () {
            const itemId = this.dataset.itemId;

            fetch(`/like/${itemId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.status === 401) {
                    alert('いいねするにはログインが必要です');
                    return;
                }
                return response.json();
            })
            .then(data => {
                if (data) {
                    // 画像の切り替え
                    heartImg.src = data.is_liked ? '/img/liked.png' : '/img/HeartLogo.png';
                    // カウントの更新
                    likeCount.textContent = data.like_count;
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
});
