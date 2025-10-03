<!-- Hero Section -->
<section class="contact-hero">
    <div class="hero-content" data-animate>
        <h1>Contact RetroRides</h1>
        <p class="hero-subtitle">Ready to find your dream classic car or discuss restoration services?</p>
        <p class="hero-description">Get in touch with our passionate team of vintage automobile experts</p>
    </div>
</section>

<!-- Contact Form & Info Section -->
<section class="contact-main">
    <div class="container">
        <div class="contact-grid">
            <!-- Contact Form -->
            <div class="contact-form-section" data-animate>
                <h2>Send Us a Message</h2>
                
                <form id="contactForm" class="contact-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone">
                        </div>
                        
                        <div class="form-group">
                            <label for="department">Department</label>
                            <select id="department" name="department">
                                <option value="general">General Inquiry</option>
                                <option value="sales">Sales</option>
                                <option value="restoration">Restoration Services</option>
                                <option value="parts">Parts & Accessories</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <input type="text" id="subject" name="subject" required>
                    </div>

                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" rows="6" required 
                                    placeholder="Tell us about your vintage car needs, restoration project, or any questions you have..."></textarea>
                    </div>

                    <button type="submit" class="btn-main submit-btn">Send Message</button>
                </form>
            </div>

            <!-- Contact Information -->
            <div class="contact-info-section" data-animate>
                <div class="contact-info-card">
                    <h3>Get In Touch</h3>
                    <div class="contact-details">
                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                                </svg>
                            </div>
                            <div class="contact-text">
                                <h4>Phone</h4>
                                <p><a href="tel:<?php echo $contactInfo['phone']; ?>"><?php echo $contactInfo['phone']; ?></a></p>
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                                </svg>
                            </div>
                            <div class="contact-text">
                                <h4>Email</h4>
                                <p><a href="mailto:<?php echo $contactInfo['email']; ?>"><?php echo $contactInfo['email']; ?></a></p>
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                </svg>
                            </div>
                            <div class="contact-text">
                                <h4>Address</h4>
                                <p>
                                    <?php echo htmlspecialchars($contactInfo['address']['street']); ?><br>
                                    <?php echo htmlspecialchars($contactInfo['address']['city']); ?>, 
                                    <?php echo htmlspecialchars($contactInfo['address']['state']); ?> 
                                    <?php echo htmlspecialchars($contactInfo['address']['zip']); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Business Hours -->
                <div class="business-hours-card" data-animate>
                    <h3>Business Hours</h3>
                    <div class="hours-list">
                        <?php foreach ($businessHours as $day => $hours): ?>
                            <div class="hours-item">
                                <span class="day"><?php echo ucfirst($day); ?></span>
                                <span class="time"><?php echo $hours; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Departments Section -->
<section class="departments-section">
    <div class="container">
        <h2 data-animate>Contact Our Departments</h2>
        <p class="section-intro" data-animate>Reach out to the right team for faster, more specialized assistance</p>
        
        <div class="departments-grid">
            <?php foreach ($departments as $dept): ?>
                <div class="department-card" data-animate>
                    <h3><?php echo htmlspecialchars($dept['name']); ?></h3>
                    <p><?php echo htmlspecialchars($dept['description']); ?></p>
                    <div class="department-contact">
                        <a href="mailto:<?php echo $dept['email']; ?>" class="dept-email">
                            <?php echo $dept['email']; ?>
                        </a>
                        <a href="tel:<?php echo $dept['phone']; ?>" class="dept-phone">
                            <?php echo $dept['phone']; ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="map-section">
    <div class="container" data-animate>
        <h2>Visit Our Showroom</h2>
        <div class="map-container">
            <p>(this is a section for google maps integration)</p>
            <div class="map-placeholder">
                <div class="map-content">
                    <h3>RetroRides Vintage Automobiles</h3>
                    <p><?php echo htmlspecialchars($contactInfo['address']['street']); ?></p>
                    <p>
                        <?php echo htmlspecialchars($contactInfo['address']['city']); ?>, 
                        <?php echo htmlspecialchars($contactInfo['address']['state']); ?> 
                        <?php echo htmlspecialchars($contactInfo['address']['zip']); ?>
                    </p>
                    <br>
                    <a href="https://maps.google.com/?q=<?php echo urlencode($contactInfo['address']['street'] . ', ' . $contactInfo['address']['city'] . ', ' . $contactInfo['address']['state']); ?>" 
                        target="_blank" class="btn-main small">Get Directions</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section">
    <div class="container" data-animate>
        <h2>Frequently Asked Questions</h2>
        <div class="faq-grid">
            <div class="faq-item">
                <h3>How long does a full restoration take?</h3>
                <p>Restoration timelines vary depending on the vehicle's condition and scope of work. Typically, full restorations take 6-18 months. We'll provide a detailed timeline after our initial assessment.</p>
            </div>
            
            <div class="faq-item">
                <h3>Do you offer financing options?</h3>
                <p>Yes, we work with several classic car financing specialists to help make your dream car purchase possible. Contact our sales team for more information about available options.</p>
            </div>
            
            <div class="faq-item">
                <h3>Can you help me find a specific vintage car?</h3>
                <p>Absolutely! We have an extensive network of collectors and dealers. If you're looking for a specific make, model, or year, we can help locate it for you.</p>
            </div>
            
            <div class="faq-item">
                <h3>Do you provide appraisal services?</h3>
                <p>Yes, we offer professional appraisal services for insurance, estate, or sale purposes. Our certified appraisers have decades of experience with vintage automobiles.</p>
            </div>
            
            <div class="faq-item">
                <h3>Do you source authentic parts for restorations?</h3>
                <p>Yes, we specialize in sourcing rare and authentic parts for vintage cars. Our global network of suppliers ensures your restoration maintains originality and value.</p>
            </div>

            <div class="faq-item">
                <h3>Can I schedule a private viewing of a vehicle?</h3>
                <p>Of course. We offer private showroom appointments where you can inspect vehicles in detail and speak directly with our experts. Contact us to arrange a suitable time.</p>
            </div>
    </div>
</section>
    
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const contactForm = document.getElementById('contactForm');
        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                e.preventDefault();
                alert('Thank you for your interest! This form is currently non-functional. xD' );
            });
        }
    });
</script>