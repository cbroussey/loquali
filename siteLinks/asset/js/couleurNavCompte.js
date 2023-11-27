document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('mouseenter', () => {
        const color = item.dataset.color;
        item.style.backgroundColor = `var(--${color}HoverColor)`;
    });

    item.addEventListener('mouseleave', () => {
        item.style.backgroundColor = 'initial';
    });
});