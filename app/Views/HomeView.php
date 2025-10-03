<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>Welcome to RetroRides</h1>
        <div class="hero-sub">
            <p>Discover timeless classics, beautifully preserved.</p>
            <div class="divider"></div>
            <a href="<?php echo $baseUrl; ?>/collection" class="btn-main">Explore Collection</a>
        </div>
    </div>
</section>

<!-- Featured Collection -->
<section class="featured">
    <h1 data-animate>Featured Classics</h1>
    <div class="featured-cars">
        <div class="glass"> 
            <?php foreach ($featuredCars as $car): ?>
                <div class="car-card" data-animate>
                    <img src="<?php echo $car['image_url']; ?>" 
                            alt="<?php echo htmlspecialchars($car['alt']); ?>">
                    <h3><?php echo htmlspecialchars($car['name']); ?></h3>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section> 

<!-- Showcase Section -->
<section class="showcase"> 
    <div class="showcase-content">
        <div class="showcase-img imageReveal">
            <img src="<?php echo $baseUrl; ?>/assets/images/Home/showcaseimg.png" alt="Showcase Image">
        </div>
        <h1 class="autoShowText">BEST CARS ALL AROUND</h1>
        <p class="autoShowText">Browse our collection to find a classic car that will reflect your unique style and desires. BE VINTAGE.</p>
    </div>

    <div class="border"></div>

    <div class="showcase-content2">
        <h2 class="autoShowText">Why Choose Us?</h2>
        <p>We offer a curated selection of classic cars, each with a unique story and heritage. Our team is passionate about vintage automobiles and is here to help you find the perfect ride.</p>
    </div>

    <div class="border"></div>

    <div class="showcase-content3">
        <div class="showcase-img3">
            <img src="<?php echo $baseUrl; ?>/assets/images/Home/showcaseimg3.jpg" alt="Showcase Image">
        </div>
        <div class="vertical-border"></div>
        <div class="showcase-butt-img4">
            <div class="tnb">
                <p>~Click here and enjoy the brands!~</p>
                <a href="<?php echo $baseUrl; ?>/collection" class="btn-main">Explore Collection</a>
            </div>  
            <img src="<?php echo $baseUrl; ?>/assets/images/Home/showcaseimg4.jpg" alt="Showcase Image">
        </div>
    </div>
</section>

<!-- Slideshow Section -->
<section class="slideshow">
    <h1 data-animate>Take a sneak peak at the collection</h1>
    <div class="glass-container">
        <div class="slider" data-animate>
            <div class="slides">
                <?php foreach ($slideshowImages as $slide): ?>
                    <img src="<?php echo $slide; ?>" 
                            alt="Classic Car Slide">
                <?php endforeach; ?>
            </div>
            <button class="prev">&#10094;</button>
            <button class="next">&#10095;</button>
        </div>
    </div>    
</section> 

<!-- About Section -->
<section class="about" >
    <div class="about-text" data-animate>
        <h2>About RetroRides</h2>
        <p>
            RetroRides is your hub for classic cars that defined generations. Since <?php echo $companyInfo['foundedYear']; ?>, 
            we have been dedicated to preserving and celebrating automotive history.
            From vintage American muscle to timeless European icons, our collection celebrates automotive history.  
            Browse, admire, and relive the golden era of cars to your heart's content.
        </p>
        <p>
            With over <?php echo $companyInfo['yearsInBusiness']; ?> years of experience, we've built a reputation for quality, 
            authenticity, and exceptional customer service. Each vehicle in our collection has been carefully selected and 
            meticulously maintained to preserve its original character and charm.
        </p>
    </div>
    <div class="about-img">
        <img src="<?php echo $baseUrl; ?>/assets/images/logo.png" alt="About RetroRides">
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials">
    <h1 class="autoShowText">What Our Customers Say</h1>
    <div class="testimonial-slider" data-animate>
        <div class="testimonial-track">
            <?php 
            // Duplicate testimonials for infinite scroll effect
            $allTestimonials = array_merge($testimonials, $testimonials);
            foreach ($allTestimonials as $testimonial): 
            ?>
                <div class="testimonial-card">
                    <p>"<?php echo htmlspecialchars($testimonial['quote']); ?>"</p>
                    <h4>- <?php echo htmlspecialchars($testimonial['author']); ?></h4>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Brands Section -->
<section class="brands">
    <h1 class="brands-title">
        Our Featured Brands
        <span class="line"></span>
    </h1>
    <div class="brands-logos" data-animate>
        <?php foreach ($brands as $brand): ?>
            <img src="<?php echo $brand['logo_url']; ?>" 
                    alt="<?php echo htmlspecialchars($brand['name']); ?> Logo">
        <?php endforeach; ?>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter">
    <div class="newsletter-content">
        <h2>Subscribe to Our Newsletter</h2>
        <p>Get the latest updates, exclusive offers, and news delivered straight to your inbox.</p>
        <form class="newsletter-form" action="#" method="post">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit" class="btn-main">Subscribe</button>
        </form>
    </div>
    <div class="newsletter-image">
        <img src="<?php echo $baseUrl; ?>/assets/images/Home/car-newsletter.png" alt="Car Image">
    </div>
</section>

<!-- Call to Action -->
<section class="cta">
    <h2>Join the RetroRides Community</h2>
    <p>Sign up today and explore our exclusive collection of timeless classics.</p>
    <a href="<?php echo $baseUrl; ?>/login" class="btn-main">Login / Register</a>
</section>

