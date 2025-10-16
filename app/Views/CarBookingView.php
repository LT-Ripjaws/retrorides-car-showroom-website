<!-- Booking Hero Section -->
<section class="booking-hero">
    <div class="hero-content">
        <h1>Complete Your Purchase</h1>
        <p>You're one step away from owning this classic beauty</p>
    </div>
</section>

<!-- Main Booking Section -->
<section class="booking-section">
    <div class="container">
        
        <!-- Display Error Messages -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <span class="material-symbols-rounded">error</span>
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>

        <!-- Display Success Messages -->
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <span class="material-symbols-rounded">check_circle</span>
                <p><?php echo htmlspecialchars($success); ?></p>
            </div>
        <?php endif; ?>

        <div class="booking-grid">
            
            <!-- Left Column: Car Details -->
            <div class="car-details-section">
                <div class="car-card">
                    <h2>Your Selection</h2>
                    
                    <!-- Car Image -->
                    <div class="car-image-wrapper">
                        <img src="<?php echo $car['image_url']; ?>"
                             alt="<?php echo $car['title']; ?>"
                             loading="lazy"
                             style="object-fit:contain;">
                        <div class="car-badge">Available</div>
                    </div>

                    <!-- Car Information -->
                    <div class="car-info">
                        <h3 class="car-title"><?php echo $car['title']; ?></h3>
                        
                        <!-- Car Specifications -->
                        <div class="car-specs">
                            <div class="spec-item">
                                <span class="material-symbols-rounded">calendar_today</span>
                                <div class="spec-details">
                                    <span class="spec-label">Year</span>
                                    <span class="spec-value"><?php echo $car['year']; ?></span>
                                </div>
                            </div>
                            
                            <div class="spec-item">
                                <span class="material-symbols-rounded">directions_car</span>
                                <div class="spec-details">
                                    <span class="spec-label">Brand</span>
                                    <span class="spec-value"><?php echo $car['brand']; ?></span>
                                </div>
                            </div>
                            
                            <div class="spec-item">
                                <span class="material-symbols-rounded">settings</span>
                                <div class="spec-details">
                                    <span class="spec-label">Model</span>
                                    <span class="spec-value"><?php echo $car['model']; ?></span>
                                </div>
                            </div>
                            
                            <div class="spec-item">
                                <span class="material-symbols-rounded">tag</span>
                                <div class="spec-details">
                                    <span class="spec-label">VIN</span>
                                    <span class="spec-value"><?php echo $car['vin']; ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Car Description -->
                        <div class="car-description">
                            <h4>Description</h4>
                            <p><?php echo $car['description']; ?></p>
                        </div>

                        <!-- Price Display -->
                        <div class="car-price-box">
                            <span class="price-label">Total Price</span>
                            <span class="price-value"><?php echo $car['price']; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Purchase Information -->
                <div class="info-card">
                    <h3>What Happens Next?</h3>
                    <ul class="info-list">
                        <li>
                            <span class="material-symbols-rounded">done</span>
                            <span>We'll review your booking request</span>
                        </li>
                        <li>
                            <span class="material-symbols-rounded">phone</span>
                            <span>Our team will contact you within 24 hours</span>
                        </li>
                        <li>
                            <span class="material-symbols-rounded">payments</span>
                            <span>Discuss payment options and financing</span>
                        </li>
                        <li>
                            <span class="material-symbols-rounded">description</span>
                            <span>Complete paperwork and documentation</span>
                        </li>
                        <li>
                            <span class="material-symbols-rounded">key</span>
                            <span>Pick up your classic car!</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Right Column: Booking Form -->
            <div class="booking-form-section">
                <div class="form-card">
                    <h2>Your Contact Information</h2>
                    <p class="form-subtitle">Please provide your details so we can reach you</p>

                    <form method="POST" 
                          action="<?php echo $baseUrl; ?>/booking/create" 
                          class="booking-form"
                          id="bookingForm">
                        
                     
                        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                        
                        <!-- Car ID -->
                        <input type="hidden" name="car_id" value="<?php echo $car['car_id']; ?>">

                        <!-- Full Name Field -->
                        <div class="form-group">
                            <label for="name">
                                Full Name <span class="required">*</span>
                            </label>
                            <div class="input-wrapper">
                                <span class="material-symbols-rounded input-icon">person</span>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="<?php echo $this->old('name', $user['name']); ?>"
                                       placeholder="Enter your full name"
                                       required
                                       autocomplete="name">
                            </div>
                            <span class="field-hint">As it appears on official documents</span>
                        </div>

                        <!-- Email Field -->
                        <div class="form-group">
                            <label for="email">
                                Email Address <span class="required">*</span>
                            </label>
                            <div class="input-wrapper">
                                <span class="material-symbols-rounded input-icon">email</span>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="<?php echo $this->old('email'); ?>"
                                       placeholder="your@email.com"
                                       required
                                       autocomplete="email">
                            </div>
                            <span class="field-hint">We'll send confirmation to this email</span>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="form-group checkbox-group">
                            <input type="checkbox" 
                                   id="terms" 
                                   name="terms" 
                                   required>
                            <label for="terms">
                                I understand that this is a booking request and not a final purchase. 
                                RetroRides will contact me to confirm availability and discuss payment options. 
                                I agree to the <a href="#" target="_blank">terms and conditions</a>.
                                <span class="required">*</span>
                            </label>
                        </div>

                        <!-- Privacy Notice -->
                        <div class="privacy-notice">
                            <span class="material-symbols-rounded">lock</span>
                            <p>Your information is secure and will only be used to process your booking.</p>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn-submit" id="submitBtn">
                            <span class="material-symbols-rounded">shopping_cart</span>
                            <span>Confirm Booking Request</span>
                        </button>

                        <!-- Back Link -->
                        <a href="<?php echo $baseUrl; ?>/collection" class="btn-back">
                            <span class="material-symbols-rounded">arrow_back</span>
                            <span>Back to Collection</span>
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php 
$this->clearOldInput(); 
?>