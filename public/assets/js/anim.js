    //script for my animation
    document.addEventListener("DOMContentLoaded", () => {
    const observer = new IntersectionObserver(
        (entries, obs) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
            entry.target.classList.add("visible");
            obs.unobserve(entry.target); 
            }
        });
        },
        { threshold: 0.2 } 
    );

    document.querySelectorAll("[data-animate]").forEach(el => {
        observer.observe(el);
    });
    });

    // Animate stats on scroll
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -100px 0px'
    };

    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statNumbers = entry.target.querySelectorAll('.stat-number');
                statNumbers.forEach(stat => {
                    animateNumber(stat);
                });
                statsObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);

    function animateNumber(element) {
        const finalNumber = parseInt(element.textContent.replace(/[,+]/g, ''));
        const duration = 2000;
        const increment = finalNumber / (duration / 16);
        let current = 0;

        const timer = setInterval(() => {
            current += increment;
            if (current >= finalNumber) {
                element.textContent = element.textContent; // Reset to original format
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current).toLocaleString() + '+';
            }
        }, 16);
    }

    // Observe stats section
    const statsSection = document.querySelector('.story-stats');
    if (statsSection) {
        statsObserver.observe(statsSection);
    }

    // This script  handles slideahow for index page
    let slideIndex = 0;
    const slides = document.querySelector(".slides");
    const slideImages = document.querySelectorAll(".slides img");
    const totalSlides = slideImages.length;

    document.querySelector(".next").addEventListener("click", () => {
        slideIndex = (slideIndex + 1) % totalSlides;
        updateSlide();
    });

    document.querySelector(".prev").addEventListener("click", () => {
        slideIndex = (slideIndex - 1 + totalSlides) % totalSlides;
        updateSlide();
    });

    function updateSlide() {
        slides.style.transform = `translateX(-${slideIndex * 100}%)`;
    }


    setInterval(() => {
        slideIndex = (slideIndex + 1) % totalSlides;
        updateSlide();
    }, 30000);