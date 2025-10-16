 <!-- Hero Section -->
<section class="collection-hero">
    <div class="hero-content">
        <h1>~Choose Your Ride~</h1>
        <p>Browse our carefully curated collection of timeless rides.</p>
        <?php if (!empty($filters['search'])): ?>
            <p class="search-info">Search results for: "<strong><?php echo htmlspecialchars($filters['search']); ?></strong>"</p>
        <?php endif; ?>
        <p class="car-count">Cars Available: '<?php echo $carCount; ?>'</p>
    </div>
</section>

<!-- Filters Section -->
<section class="filters-section">
    <div class="container" data-animate>
        <div class="filters-wrapper">
            <h2>Search for your car</h2>
            <form method="GET" action="<?php echo $baseUrl; ?>/collection" class="filters-form">
                <!-- Search -->
                <div class="filter-group search-group">
                    <label for="search">Search:</label>
                    <input type="text" 
                            id="search" 
                            name="search" 
                            placeholder="Search by brand, model, or description..." 
                            value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>">
                </div>

                <!-- Brand Filter -->
                <div class="filters-grid">
                    <div class="filter-group">
                        <label for="brand">Brand:</label>
                        <select id="brand" name="brand">
                            <option value="">All Brands</option>
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?php echo htmlspecialchars($brand); ?>" 
                                        <?php echo ($filters['brand'] ?? '') === $brand ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($brand); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div class="filter-group">
                        <label for="min_price">Min Price:</label>
                        <input type="number" 
                            id="min_price" 
                            name="min_price" 
                            min="<?php echo $priceRange['min']; ?>" 
                            max="<?php echo $priceRange['max']; ?>"
                            value="<?php echo $filters['min_price'] ?? ''; ?>"
                            placeholder="<?php echo number_format($priceRange['min']); ?>">
                    </div>

                    <div class="filter-group">
                        <label for="max_price">Max Price:</label>
                        <input type="number" 
                            id="max_price" 
                            name="max_price" 
                            min="<?php echo $priceRange['min']; ?>" 
                            max="<?php echo $priceRange['max']; ?>"
                            value="<?php echo $filters['max_price'] ?? ''; ?>"
                            placeholder="<?php echo number_format($priceRange['max']); ?>">
                    </div>
                </div>
                
                <!-- Sorting Option -->
                <div class="filter-group sort-group">
                    <label for="sort">Sort By:</label>
                    <select id="sort" name="sort">
                        <option value="year_asc" <?php echo ($filters['sort'] ?? '') === 'year_asc' ? 'selected' : ''; ?>>
                            Year (Oldest First)
                        </option>
                        <option value="year_desc" <?php echo ($filters['sort'] ?? '') === 'year_desc' ? 'selected' : ''; ?>>
                            Year (Newest First)
                        </option>
                        <option value="price_asc" <?php echo ($filters['sort'] ?? '') === 'price_asc' ? 'selected' : ''; ?>>
                            Price (Low to High)
                        </option>
                        <option value="price_desc" <?php echo ($filters['sort'] ?? '') === 'price_desc' ? 'selected' : ''; ?>>
                            Price (High to Low)
                        </option>
                        <option value="brand" <?php echo ($filters['sort'] ?? '') === 'brand' ? 'selected' : ''; ?>>
                            Brand (A–Z)
                        </option>
                    </select>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn-main sm" style="margin:3px;">Apply Filters</button>
                    <a href="<?php echo $baseUrl; ?>/collection" class="btn-main sm" style="margin:3px;">Clear Filters</a>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Car Collection -->
<section id="car-collection" class="car-collection">
    <div class="container" >
        <div class="car-grid">
            <?php if (!empty($cars)): ?>
                <?php foreach ($cars as $car): ?>
                    <div class="car-card">
                        <div class="car-img">
                            <img src="<?php echo $car['image_url']; ?>" 
                                    alt="<?php echo $car['brand'] . ' ' . $car['model']; ?>"
                                    loading="lazy">
                        </div>
                        <div class="car-details">
                            <h3><?php echo $car['title']; ?></h3>
                            <p class="car-desc"><?php echo $car['short_description']; ?></p>
                            <p class="car-price"><?php echo $car['formatted_price']; ?></p>
                            
                            <div class="car-actions">
                                <form action="<?php echo $baseUrl; ?>/booking" method="GET">
                                    <input type="hidden" name="car_id" value="<?php echo $car['car_id']; ?>">
                                    <button type="submit" class="btn-main sm">Order Now</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-cars">
                    <p>No cars available matching your criteria.</p>
                    <a href="<?php echo $baseUrl; ?>/collection" class="btn-main bm">View All Cars</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php if (!empty($hasFilters)): ?>
    <script>
        //auto scroll on filtration fr fr
    document.addEventListener("DOMContentLoaded", function() {
        const section = document.getElementById("car-collection");
        if (section) {
            section.scrollIntoView({ behavior: "smooth", block: "start" });
        }
    });
    </script>
<?php endif; ?>
