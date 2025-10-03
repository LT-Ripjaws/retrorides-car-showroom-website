<?php
$baseUrl = getBasePath();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'Admin Panel'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($description ?? 'Admin dashboard and management area'); ?>">
    <link rel="icon" type="image/x-icon" href="<?php echo $baseUrl; ?>/public/favicon.ico">

    <!-- Core styles -->
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/base.css">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/components.css">

    <!-- Admin-specific styles -->
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/sidebar.css">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/Admin/admin-base.css">

    <!-- Page-specific CSS -->
    <?php if (isset($pageCSS)): ?>
        <link rel="stylesheet" href="<?php echo $baseUrl . $pageCSS; ?>">
    <?php endif; ?>

    <!-- Material Icons (for them sidebarsz) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" />
</head>
<body>
    <div class="full-container">
        <!-- This is the sidebar -->
        <?php require BASE_PATH . '/app/Views/Layouts/sidebar.php'; ?>

        <!-- Our main admin content -->
        <main>
            <?php echo $content; ?>
        </main>
    </div>

    <!-- We eXpose base URL to JS -->
    <script>
        window.baseUrl = '<?php echo $baseUrl; ?>';
    </script>

    <!-- Shared Admin Scripts -->
    <script src="<?php echo $baseUrl; ?>/assets/js/sidebar.js"></script>
    <script src="<?php echo $baseUrl; ?>/assets/js/Admin/admin-common.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Page-specific JS -->
    <?php if (isset($pageJS)): ?>
        <script src="<?php echo $baseUrl . $pageJS; ?>"></script>
    <?php endif; ?>
</body>
</html>
