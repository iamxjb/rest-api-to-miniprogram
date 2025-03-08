class Gallery {
    constructor(container, options = {}) {
        this.container = container;
        this.options = {
            autoplay: true,
            interval: 5000,
            ...options
        };
        this.slidesContainer = container.querySelector('.slides-container');
        this.slides = Array.from(container.querySelectorAll('.slide'));
        this.currentIndex = 0;
        this.touchStartX = 0;
        this.isDragging = false;
        this.autoPlayTimer = null;
        
        this.init();
    }

    init() {
        this.createNavigationDots();
        this.setupEventListeners();
        this.goTo(0);
        this.lazyLoad = new LazyLoader();
        this.lazyLoad.observe(this.slides);
        
        if (this.options.autoplay) {
            this.startAutoPlay();
        }
    }

    createNavigationDots() {
        const dotsContainer = this.container.querySelector('.navigation-dots');
        this.dots = this.slides.map((_, index) => {
            const dot = document.createElement('div');
            dot.className = 'dot';
            dot.addEventListener('click', () => this.goTo(index));
            dotsContainer.appendChild(dot);
            return dot;
        });
    }

    updateNavigation() {
        this.dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === this.currentIndex);
        });
    }

    goTo(index) {
        if (index < 0 || index >= this.slides.length) return;
        this.currentIndex = index;
        this.slidesContainer.style.transform = `translateX(-${index * 100}%)`;
        this.updateNavigation();
    }

    next() {
        const newIndex = (this.currentIndex + 1) % this.slides.length;
        this.goTo(newIndex);
    }

    prev() {
        const newIndex = (this.currentIndex - 1 + this.slides.length) % this.slides.length;
        this.goTo(newIndex);
    }

    handleTouchStart(e) {
        this.touchStartX = e.touches[0].clientX;
        this.isDragging = true;
    }

    handleTouchMove(e) {
        if (!this.isDragging) return;
        const touchEndX = e.touches[0].clientX;
        const diff = this.touchStartX - touchEndX;
        
        if (Math.abs(diff) > 50) {
            this.isDragging = false;
            diff > 0 ? this.next() : this.prev();
        }
    }

    setupEventListeners() {
        this.container.querySelector('.prev').addEventListener('click', () => this.prev());
        this.container.querySelector('.next').addEventListener('click', () => this.next());
        
        // 触摸事件
        this.slidesContainer.addEventListener('touchstart', e => this.handleTouchStart(e));
        this.slidesContainer.addEventListener('touchmove', e => this.handleTouchMove(e));
        
        // 窗口尺寸变化重置位置
        window.addEventListener('resize', () => this.goTo(this.currentIndex));
    }

    startAutoPlay() {
        this.autoPlayTimer = setInterval(() => this.next(), this.options.interval);
        
        // 鼠标悬停时暂停
        this.container.addEventListener('mouseenter', () => clearInterval(this.autoPlayTimer));
        this.container.addEventListener('mouseleave', () => this.startAutoPlay());
    }
}

class LazyLoader {
    constructor() {
        this.observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.loadImage(entry.target);
                    this.observer.unobserve(entry.target);
                }
            });
        }, {
            rootMargin: '200px',
            threshold: 0.01
        });
    }

    observe(slides) {
        slides.forEach(slide => {
            this.observer.observe(slide);
        });
    }

    loadImage(slide) {
        const img = slide.querySelector('img');
        if (!img.dataset.src) return;
        img.src = img.dataset.src;
        delete img.dataset.src;
    }
}

// 初始化画廊
document.addEventListener('DOMContentLoaded', () => {
    const gallery = new Gallery(document.querySelector('.gallery-container'), {
        autoplay: true,
        interval: 5000
    });
});