<?php
$baseUrl = getBasePath();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'RetroRides'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($description ?? 'Vintage car collection and restoration services'); ?>">
    <meta name="keywords" content="vintage cars, classic cars, retro rides, car restoration">
    <meta name="author" content="RetroRides Team">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- Base Assets -->
    <link rel="icon" type="image/x-icon" href="<?php echo $baseUrl; ?>/favicon.ico">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/base.css">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/components.css">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/header.css">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/animations.css">
    
    <!-- Page-specific CSS -->
    <?php if (isset($pageCSS)): ?>
        <link rel="stylesheet" href="<?php echo $baseUrl . $pageCSS; ?>">
    <?php endif; ?>
</head>
<body>
    <?php require BASE_PATH . '/app/Views/Layouts/header.php'; ?>
    
    <main>
        <?php echo $content; ?>
    </main>
    
    <?php require BASE_PATH . '/app/Views/Layouts/footer.php'; ?>
    
    <!-- Base JavaScript -->
    <script src="<?php echo $baseUrl; ?>/assets/js/anim.js"></script>
    
    <!-- Page-specific JS -->
    <?php if (isset($pageJS)): ?>
        <script src="<?php echo $baseUrl . $pageJS; ?>"></script>
    <?php endif; ?>
</body>
</html>