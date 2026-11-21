// Scroll Reveal Observer
function initScrollReveal() {
    const reveals = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
    
    if (reveals.length === 0) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                // Once element is shown, we can unobserve it so it stays visible
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.05,
        rootMargin: '0px 0px -40px 0px' // Trigger slightly before it fully rolls into the viewport
    });

    reveals.forEach(el => {
        // If element is already in viewport, activate immediately
        const rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight && rect.bottom > 0) {
            el.classList.add('active');
        } else {
            observer.observe(el);
        }
    });
}

// Support standard page load and Livewire's wire:navigate SPA style transition
document.addEventListener('DOMContentLoaded', initScrollReveal);
document.addEventListener('livewire:navigated', initScrollReveal);

// Re-observe and activate reveal elements after Livewire DOM morphs/updates
document.addEventListener('livewire:init', () => {
    if (window.Livewire) {
        window.Livewire.hook('commit', ({ succeed }) => {
            succeed(() => {
                queueMicrotask(initScrollReveal);
            });
        });
        window.Livewire.hook('morph.updated', () => {
            queueMicrotask(initScrollReveal);
        });
    }
});
