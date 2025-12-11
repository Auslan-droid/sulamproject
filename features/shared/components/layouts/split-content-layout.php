<?php
/**
 * Shared Split Content Layout Partial
 * 
 * Usage:
 * $splitLayoutLeft = "Content for left column";
 * $splitLayoutRight = "Content for right column";
 * include 'path/to/split-content-layout.php';
 * 
 * Variables:
 * - $splitLayoutLeft (HTML string)
 * - $splitLayoutRight (HTML string)
 * - $splitLayoutClasses (string, optional) - Extra classes for the container
 */
?>

<div class="split-layout-grid <?php echo $splitLayoutClasses ?? ''; ?>">
    <!-- Main Content / Left Column -->
    <div class="split-layout-left">
        <?php echo $splitLayoutLeft ?? ''; ?>
    </div>

    <!-- Sidebar / Right Column -->
    <div class="split-layout-right">
        <?php echo $splitLayoutRight ?? ''; ?>
    </div>
</div>
