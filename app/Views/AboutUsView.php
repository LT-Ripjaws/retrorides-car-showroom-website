<!-- Hero Section -->
<section class="about-hero " >
    <div class="hero-content" data-animate>
        <h1>About RetroRides</h1>
        <p class="hero-subtitle">Preserving automotive history, one classic at a time</p>
    </div>
    <div class="hero-image" data-animate>
        <img src="<?php echo $heroImage ?>" 
                alt="Vintage car restoration garage" 
                loading="lazy">
    </div>
</section>

<!-- Story Section -->
<section class="our-story">
    <div class="container">
        <div class="story-content" data-animate>
            <div class="story-text">
                <h2>Our Story</h2>
                <p>Founded in <?php echo $foundedYear; ?>, RetroRides was born from a simple passion: the love of vintage automobiles and their timeless beauty. What started as a small garage restoration project has grown into one of the most trusted names in classic car sales and restoration.</p>
                
                <p>For over <?php echo $yearsInBusiness; ?> years, we've been dedicated to preserving automotive history by carefully restoring, maintaining, and finding new homes for classic vehicles. Each car that passes through our doors tells a story, and we're honored to be part of that narrative.</p>
                
                <p>Our philosophy is simple: treat every vintage automobile with the respect and care it deserves. Whether it's a 1960s muscle car or a elegant 1940s cruiser, we approach each restoration with meticulous attention to detail and historical accuracy.</p>
            </div>
            
            <div class="story-stats" data-animate>
                <div class="stat-item">
                    <div class="stat-number"><?php echo number_format($carsRestored); ?>+</div>
                    <div class="stat-label">Cars Restored</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo number_format($happyCustomers); ?>+</div>
                    <div class="stat-label">Happy Customers</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $yearsInBusiness; ?>+</div>
                    <div class="stat-label">Years of Excellence</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Values Section -->
<section class="mission-values">
    <div class="container" data-animate>
        <h2>Our Mission & Values</h2>
        <div class="values-grid data-animate">
            <div class="value-card">
                <div class="value-icon">
                    <img src="<?php echo $baseUrl; ?>/assets/images/about/authenticity-icon.svg" 
                            alt="Authenticity icon">
                </div>
                <h3>Authenticity</h3>
                <p>We believe in preserving the original character and integrity of every vintage vehicle. Our restorations maintain historical accuracy while ensuring modern reliability.</p>
            </div>
            
            <div class="value-card">
                <div class="value-icon">
                    <img src="<?php echo $baseUrl; ?>/assets/images/about/quality-icon.svg" 
                            alt="Quality icon">
                </div>
                <h3>Quality Craftsmanship</h3>
                <p>Every restoration project receives the highest level of craftsmanship. We use period-correct parts and techniques to ensure lasting quality and value.</p>
            </div>
            
            <div class="value-card">
                <div class="value-icon">
                    <img src="<?php echo $baseUrl; ?>/assets/images/about/passion-icon.svg" 
                            alt="Passion icon">
                </div>
                <h3>Passion</h3>
                <p>Our love for vintage automobiles drives everything we do. This passion is evident in every restored vehicle and every customer interaction.</p>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="our-team">
    <div class="container" data-animate>
        <h2>Meet Our Team</h2>
        <p class="section-intro">The passionate professionals behind RetroRides</p>
        
        <div class="team-grid" data-animate>
            <?php foreach ($teamMembers as $member): ?>
                <div class="team-member">
                    <div class="member-image">
                        <img src="<?php echo $member['image']; ?>" 
                                alt="<?php echo htmlspecialchars($member['name']); ?>"
                                loading="lazy">
                    </div>
                    <div class="member-info">
                        <h3><?php echo htmlspecialchars($member['name']); ?></h3>
                        <p class="member-position"><?php echo htmlspecialchars($member['position']); ?></p>
                        <p class="member-bio"><?php echo htmlspecialchars($member['bio']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Services Overview -->
<section class="services-overview">
    <div class="container" data-animate>
        <h2>What We Offer</h2>
        <div class="services-grid">
            <div class="service-item">
                <h3>Classic Car Sales</h3>
                <p>Carefully curated collection of vintage automobiles, each with detailed history and documentation.</p>
            </div>
            
            <div class="service-item">
                <h3>Full Restoration</h3>
                <p>Complete ground-up restoration services bringing classic cars back to their original glory.</p>
            </div>
            
            <div class="service-item">
                <h3>Maintenance & Repair</h3>
                <p>Specialized maintenance services for vintage vehicles using period-appropriate techniques and parts.</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="cta-section">
    <div class="container" data-animate>
        <div class="cta-content">
            <h2>Ready to Find Your Dream Classic?</h2>
            <p>Explore our curated collection of vintage automobiles or get in touch to discuss your restoration project.</p>
            <div class="cta-buttons">
                <a href="<?php echo $baseUrl; ?>/collection" class="btn-main">View Our Collection</a>
                <a href="<?php echo $baseUrl; ?>/contact" class="btn-main">Contact Us</a>
            </div>
        </div>
    </div>
</section>
