window.addEventListener('scroll', function() {
    const header = document.querySelector('.rectangle-header');
    const body = document.querySelector('body');

    // Add 'scrolled' class to body when scrolled down 100px or more
    if (window.scrollY > 100) {
        body.classList.add('scrolled');
    } else {
        body.classList.remove('scrolled');
    }
});