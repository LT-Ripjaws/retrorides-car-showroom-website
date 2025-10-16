<?php
$baseUrl = getBasePath();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'Customer Dashboard'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($description ?? 'Customer dashboard and account management'); ?>">
    <link rel="icon" type="image/x-icon" href="<?php echo $baseUrl; ?>/favicon.ico">

    <!-- Core styles -->
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/base.css">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/components.css">

    <!-- Customer-specific styles -->
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/customer-sidebar.css">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/Customer/customer-base.css">

    <!-- Page-specific CSS -->
    <?php if (isset($pageCSS)): ?>
        <link rel="stylesheet" href="<?php echo $baseUrl . $pageCSS; ?>">
    <?php endif; ?>

    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" />
</head>
<body>
    <div class="full-container">
        <!-- Customer Sidebar -->
        <?php require BASE_PATH . '/app/Views/Layouts/customer-sidebar.php'; ?>

        <!-- Main customer content -->
        <main class="customer-main">
            <?php echo $content; ?>
        </main>
    </div>

    <!-- Expose base URL to JS -->
    <script>
        window.baseUrl = '<?php echo $baseUrl; ?>';
    </script>

    <!-- Shared Customer Scripts -->
    <script src="<?php echo $baseUrl; ?>/assets/js/customer-sidebar.js"></script>
    <script src="<?php echo $baseUrl; ?>/assets/js/Customer/customer-common.js"></script>
    <script src="<?php echo $baseUrl; ?>/assets/js/anim.js"></script>

    <!-- Page-specific JS -->
    <?php if (isset($pageJS)): ?>
        <script src="<?php echo $baseUrl . $pageJS; ?>"></script>
    <?php endif; ?>
</body>
</html>