document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('search');
    
    // Если поля поиска нет на странице, ничего не делаем
    if (!searchInput) return;

    const cards = document.querySelectorAll('.game-card');

    searchInput.addEventListener('input', () => {
        const query = searchInput.value.trim().toLowerCase();

        cards.forEach(card => {
            // Читаем данные из data-search (там название и жанр)
            const searchData = (card.getAttribute('data-search') || '').toLowerCase();
            
            // Если введенный текст есть в строке — показываем, иначе скрываем
            if (searchData.includes(query)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
});