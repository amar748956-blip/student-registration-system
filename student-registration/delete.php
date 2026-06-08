<?php
/**
 * delete.php — Delete a student record (Delete).
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    setFlash('error', 'Invalid student ID.');
    redirect('list.php');
}

$studentModel = new Student();
$student      = $studentModel->find($id);

if ($student === null) {
    setFlash('error', 'Student not found.');
    redirect('list.php');
}

// Remove the associated image file if present.
if (!empty($student['profile_image'])) {
    $imagePath = __DIR__ . '/uploads/' . $student['profile_image'];
    if (is_file($imagePath)) {
        @unlink($imagePath);
    }
}

if ($studentModel->delete($id)) {
    setFlash('success', 'Student record deleted successfully.');
} else {
    setFlash('error', 'Failed to delete student record.');
}

redirect('list.php');
