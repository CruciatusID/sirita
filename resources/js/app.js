import './bootstrap';

document.querySelectorAll('[data-mobile-menu-button]').forEach((button) => {
    const menu = document.querySelector('[data-mobile-menu]');

    if (! menu) {
        return;
    }

    button.addEventListener('click', () => {
        const isExpanded = button.getAttribute('aria-expanded') === 'true';

        button.setAttribute('aria-expanded', String(! isExpanded));
        menu.classList.toggle('hidden', isExpanded);
    });
});
