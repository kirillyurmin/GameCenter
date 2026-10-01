const searchInput = document.getElementById('search');
const cards = document.querySelectorAll('.game-card');
const emptyMessage = document.getElementById('search-empty');

searchInput.addEventListener('input', () => {
    const query = searchInput.value.trim().toLowerCase();
    let visibleCount = 0;

    cards.forEach(card => {
        const title = card.querySelector('h3').textContent.toLowerCase();
        const matches = title.includes(query);

        card.style.display = matches ? '' : 'none';
        if (matches) visibleCount++;
    });
});