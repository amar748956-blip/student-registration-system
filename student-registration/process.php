<?php
/**
 * Form processor — handles both Create and Update (no duplicated logic).
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$id     = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$isEdit = $id > 0;

// Collect & normalise input.
$input = [
    'full_name' => trim((string) ($_POST['full_name'] ?? '')),
    'email'     => trim((string) ($_POST['email'] ?? '')),
    'phone'     => trim((string) ($_POST['phone'] ?? '')),
    'gender'    => $_POST['gender'] ?? '',
    'dob'       => $_POST['dob'] ?? '',
    'country'   => $_POST['country'] ?? '',
    'skills'    => isset($_POST['skills']) && is_array($_POST['skills'])
        ? implode(', ', $_POST['skills'])
        : '',
    'address'   => trim((string) ($_POST['address'] ?? '')),
];

$student   = new Student();
$validator = new Validator();
$errors    = $validator->validateStudent($input);

// Unique-email check.
if (empty($errors['email']) && $student->emailExists($input['email'], $id)) {
    $errors['email'] = 'This email is already registered';
}

// Handle image upload.
$uploader = new FileUploader();
$uploadedImage = null;

if (!empty($_FILES['profile_image']['name'])) {
    $uploadedImage = $uploader->upload($_FILES['profile_image']);
    if ($uploader->hasError()) {
        $errors['profile_image'] = $uploader->getError();
    }
}

// If validation fails, send user back with old input + errors.
if (!empty($errors)) {
    $_SESSION['old']    = $_POST;
    $_SESSION['errors'] = $errors;
    redirect($isEdit ? "edit.php?id={$id}" : 'index.php');
}

// Determine the final image value.
if ($isEdit) {
    $existing = $student->find($id);
    if ($existing === null) {
        setFlash('error', 'Student not found.');
        redirect('list.php');
    }
    $input['profile_image'] = $uploadedImage ?? $existing['profile_image'];

    if ($student->update($id, $input)) {
        setFlash('success', 'Student record updated successfully.');
    } else {
        setFlash('error', 'Failed to update student record.');
    }
} else {
    $input['profile_image'] = $uploadedImage;

    if ($student->create($input)) {
        setFlash('success', 'Student registered successfully.');
    } else {
        setFlash('error', 'Failed to register student.');
    }
}

redirect('list.php');
