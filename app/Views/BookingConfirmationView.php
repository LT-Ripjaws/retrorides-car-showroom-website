<!-- Confirmation Hero -->
<section class="confirmation-hero">
    <div class="hero-content">
        <div class="success-icon">
            <span class="material-symbols-rounded">check_circle</span>
        </div>
        <h1>Booking Confirmed!</h1>
        <p>Thank you for your interest in our classic car</p>
    </div>
</section>

<!-- Confirmation Details -->
<section class="confirmation-section">
    <div class="container">
        <div class="confirmation-card">
            <div class="confirmation-header">
                <h2>Your Booking Request Has Been Received</h2>
                <p class="booking-ref">Booking Reference: <strong>#<?php echo htmlspecialchars($bookingId ?? 'N/A'); ?></strong></p>
            </div>

            <div class="confirmation-content">
                <div class="info-box">
                    <span class="material-symbols-rounded">info</span>
                    <div>
                        <h3>What's Next?</h3>
                        <p>Our sales team will review your booking request and contact you within <strong>24 hours</strong> via email to:</p>
                    </div>
                </div>

                <ul class="next-steps">
                    <li>
                        <span class="step-number">1</span>
                        <div>
                            <h4>Confirm Availability</h4>
                            <p>Verify the car is still available for purchase</p>
                        </div>
                    </li>
                    <li>
                        <span class="step-number">2</span>
                        <div>
                            <h4>Discuss Details</h4>
                            <p>Go over pricing, payment options, and financing</p>
                        </div>
                    </li>
                    <li>
                        <span class="step-number">3</span>
                        <div>
                            <h4>Schedule Viewing</h4>
                            <p>Arrange a time to see the car in person</p>
                        </div>
                    </li>
                    <li>
                        <span class="step-number">4</span>
                        <div>
                            <h4>Complete Purchase</h4>
                            <p>Finalize paperwork and take your car home!</p>
                        </div>
                    </li>
                </ul>

                <div class="contact-box">
                    <h3>Need Immediate Assistance?</h3>
                    <p>If you have any questions or concerns, feel free to reach out:</p>
                    <div class="contact-methods">
                        <a href="mailto:sales@retrorides.com" class="contact-method">
                            <span class="material-symbols-rounded">email</span>
                            <span>sales@retrorides.com</span>
                        </a>
                        <a href="#" class="contact-method">
                            <span class="material-symbols-rounded">phone</span>
                            <span>+1 (xxx) xxx-xxx</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="confirmation-actions">
                <a href="<?php echo $baseUrl; ?>/collection" class="btn-main">
                    <span class="material-symbols-rounded">arrow_back</span>
                    <span>Browse More Cars</span>
                </a>
                <a href="<?php echo $baseUrl; ?>/" class="btn-secondary">
                    <span class="material-symbols-rounded">home</span>
                    <span>Back to Home</span>
                </a>
            </div>
        </div>
    </div>
</section>

<style>
/* Confirmation Page Styles */
.confirmation-hero {
    background: var(--orange-grad);
    padding: 4rem 2rem 3rem;
    text-align: center;
    color: #fff;
}

.success-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 100px;
    height: 100px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    margin-bottom: 1.5rem;
    animation: scaleIn 0.5s ease;
}

@keyframes scaleIn {
    from {
        transform: scale(0);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

.success-icon .material-symbols-rounded {
    font-size: 4rem;
}

.confirmation-hero h1 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.confirmation-hero p {
    font-size: 1.2rem;
    opacity: 0.95;
}

.confirmation-section {
    padding: 4rem 2rem;
    background: var(--background);
    min-height: 60vh;
}

.confirmation-card {
    max-width: 900px;
    margin: 0 auto;
    background: var(--panel);
    border-radius: 12px;
    padding: 3rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.confirmation-header {
    text-align: center;
    margin-bottom: 3rem;
    padding-bottom: 2rem;
    border-bottom: 2px solid var(--border-input);
}

.confirmation-header h2 {
    font-size: 2rem;
    color: var(--primary);
    margin-bottom: 1rem;
}

.booking-ref {
    font-size: 1.1rem;
    color: var(--secondary);
}

.booking-ref strong {
    color: var(--accent);
    font-size: 1.3rem;
}

.info-box {
    display: flex;
    gap: 1rem;
    padding: 1.5rem;
    background: rgba(52, 152, 219, 0.1);
    border-left: 4px solid #3498db;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.info-box .material-symbols-rounded {
    color: #3498db;
    font-size: 2rem;
    flex-shrink: 0;
}

.info-box h3 {
    font-size: 1.3rem;
    color: var(--primary);
    margin-bottom: 0.5rem;
}

.info-box p {
    color: var(--secondary);
    line-height: 1.6;
    margin: 0;
}

.next-steps {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.next-steps li {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
}

.step-number {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: var(--accent);
    color: #fff;
    border-radius: 50%;
    font-weight: 700;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.next-steps h4 {
    font-size: 1.1rem;
    color: var(--primary);
    margin-bottom: 0.25rem;
}

.next-steps p {
    color: var(--secondary);
    font-size: 0.95rem;
    line-height: 1.5;
    margin: 0;
}

.contact-box {
    padding: 2rem;
    background: var(--background);
    border-radius: 8px;
    margin-bottom: 2rem;
}

.contact-box h3 {
    font-size: 1.3rem;
    color: var(--primary);
    margin-bottom: 0.5rem;
}

.contact-box > p {
    color: var(--secondary);
    margin-bottom: 1.5rem;
}

.contact-methods {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.contact-method {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    background: var(--panel);
    border: 2px solid var(--border-input);
    border-radius: 8px;
    color: var(--accent);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.contact-method:hover {
    border-color: var(--accent);
    background: rgba(252, 151, 19, 0.05);
    transform: translateY(-2px);
}

.contact-method .material-symbols-rounded {
    font-size: 1.3rem;
}

.confirmation-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    padding-top: 2rem;
    border-top: 2px solid var(--border-input);
}

.confirmation-actions .btn-main,
.confirmation-actions .btn-secondary {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.875rem 1.5rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.confirmation-actions .btn-main {
    background: var(--orange-grad);
    color: #fff;
}

.confirmation-actions .btn-secondary {
    background: transparent;
    border: 2px solid var(--border-input);
    color: var(--secondary);
}

.confirmation-actions .btn-main:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(252, 151, 19, 0.3);
}

.confirmation-actions .btn-secondary:hover {
    border-color: var(--accent);
    color: var(--accent);
}

/* Responsive */
@media (max-width: 768px) {
    .confirmation-hero h1 {
        font-size: 2rem;
    }

    .confirmation-card {
        padding: 2rem 1.5rem;
    }

    .confirmation-actions {
        flex-direction: column;
    }

    .confirmation-actions .btn-main,
    .confirmation-actions .btn-secondary {
        width: 100%;
        justify-content: center;
    }

    .contact-methods {
        flex-direction: column;
    }

    .contact-method {
        justify-content: center;
    }
}
</style>