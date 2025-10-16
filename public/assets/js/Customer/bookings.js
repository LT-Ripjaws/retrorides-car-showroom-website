// Customer Bookings Page JavaScript

 // Toggle booking details panel
function toggleBookingDetails(bookingId) {
    const detailsPanel = document.getElementById(`details-${bookingId}`);
    
    if (detailsPanel) {
        detailsPanel.classList.toggle('active');
        
        // Close other open panels
        document.querySelectorAll('.booking-details-panel').forEach(panel => {
            if (panel.id !== `details-${bookingId}`) {
                panel.classList.remove('active');
            }
        });
    }
}


 // Open cancel booking modal
function confirmCancelBooking(bookingId) {
    const modal = document.getElementById('cancelModal');
    const bookingIdInput = document.getElementById('cancelBookingId');
    
    if (modal && bookingIdInput) {
        bookingIdInput.value = bookingId;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}


 // Close cancel booking modal
function closeCancelModal() {
    const modal = document.getElementById('cancelModal');
    
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('cancelModal');
    
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeCancelModal();
            }
        });
    }

    // Handle cancel form submission
    const cancelForm = document.getElementById('cancelForm');
    if (cancelForm) {
        cancelForm.addEventListener('submit', (e) => {
            // Show loading state
            const submitBtn = cancelForm.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="material-symbols-rounded">hourglass_empty</span> Cancelling...';
            }
        });
    }

    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    // Animate booking cards on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.booking-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(card);
    });
});

// Close modal with Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeCancelModal();
    }
});

// Handle filter tabs active state
const filterTabs = document.querySelectorAll('.filter-tab');
filterTabs.forEach(tab => {
    tab.addEventListener('click', function() {
        // Remove active class from all tabs
        filterTabs.forEach(t => t.classList.remove('active'));
        // Add active class to clicked tab
        this.classList.add('active');
    });
});