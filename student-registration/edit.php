<?php
/**
 * edit.php — Edit an existing student (Update form).
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$studentModel = new Student();
$student      = $studentModel->find($id);

if ($student === null) {
    setFlash('error', 'Student not found.');
    redirect('list.php');
}

$pageTitle  = 'Edit Student';
$formAction = 'process.php';
$isEdit     = true;

$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);

require __DIR__ . '/includes/header.php';
?>
<section class="card">
    <h2 class="card-title">Edit Student</h2>
    <p class="card-subtitle">Update the details below and save your changes.</p>
    <?php require __DIR__ . '/includes/form.php'; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
