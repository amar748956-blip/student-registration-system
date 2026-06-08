<?php
/**
 * index.php — Student registration form (Create).
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$pageTitle  = 'Register Student';
$formAction = 'process.php';
$isEdit     = false;
$student    = [];

// Pull validation errors from a failed submission, if any.
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);

require __DIR__ . '/includes/header.php';
?>
<section class="card">
    <h2 class="card-title">Student Registration Form</h2>
    <p class="card-subtitle">Fields marked with <span class="req">*</span> are required.</p>
    <?php require __DIR__ . '/includes/form.php'; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
