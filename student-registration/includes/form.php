<?php
/**
 * Reusable student form partial (used for both Create and Edit).
 *
 * Expects:
 *   $student   array|null  Existing record when editing.
 *   $errors    array       Validation errors (field => message).
 *   $formAction string     Form submit URL.
 *   $isEdit    bool        Whether this is an edit operation.
 */

$student = $student ?? [];
$errors  = $errors ?? [];
$old     = $_SESSION['old'] ?? [];
unset($_SESSION['old']);

/** Pre-fill helper: prefer old input, then existing record. */
$val = static function (string $key) use ($old, $student) {
    return e((string) ($old[$key] ?? $student[$key] ?? ''));
};

$selectedSkills = [];
if (!empty($old['skills'])) {
    $selectedSkills = is_array($old['skills']) ? $old['skills'] : explode(', ', (string) $old['skills']);
} elseif (!empty($student['skills'])) {
    $selectedSkills = explode(', ', (string) $student['skills']);
}
?>
<form id="studentForm" action="<?= e($formAction) ?>" method="POST" enctype="multipart/form-data" novalidate>
    <?php if (!empty($isEdit)): ?>
        <input type="hidden" name="id" value="<?= e((string) $student['id']) ?>">
    <?php endif; ?>

    <div class="form-grid">
        <!-- Full Name -->
        <div class="form-group">
            <label for="full_name">Full Name <span class="req">*</span></label>
            <input type="text" id="full_name" name="full_name" value="<?= $val('full_name') ?>">
            <small class="error" data-error="full_name"><?= e($errors['full_name'] ?? '') ?></small>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email">Email Address <span class="req">*</span></label>
            <input type="email" id="email" name="email" value="<?= $val('email') ?>">
            <small class="error" data-error="email"><?= e($errors['email'] ?? '') ?></small>
        </div>

        <!-- Phone -->
        <div class="form-group">
            <label for="phone">Phone Number <span class="req">*</span></label>
            <input type="number" id="phone" name="phone" value="<?= $val('phone') ?>" placeholder="10 digit number">
            <small class="error" data-error="phone"><?= e($errors['phone'] ?? '') ?></small>
        </div>

        <!-- Date of Birth -->
        <div class="form-group">
            <label for="dob">Date of Birth <span class="req">*</span></label>
            <input type="date" id="dob" name="dob" value="<?= $val('dob') ?>">
            <small class="error" data-error="dob"><?= e($errors['dob'] ?? '') ?></small>
        </div>

        <!-- Gender -->
        <div class="form-group">
            <label>Gender <span class="req">*</span></label>
            <?php
            $genderVal = $old['gender'] ?? $student['gender'] ?? '';
            foreach (['Male', 'Female', 'Other'] as $g):
            ?>
                <label class="radio-inline">
                    <input type="radio" name="gender" value="<?= $g ?>" <?= $genderVal === $g ? 'checked' : '' ?>>
                    <?= $g ?>
                </label>
            <?php endforeach; ?>
            <small class="error" data-error="gender"><?= e($errors['gender'] ?? '') ?></small>
        </div>

        <!-- Country -->
        <div class="form-group">
            <label for="country">Country <span class="req">*</span></label>
            <select id="country" name="country">
                <option value="">-- Select Country --</option>
                <?php
                $countryVal = $old['country'] ?? $student['country'] ?? '';
                foreach (countries() as $c):
                ?>
                    <option value="<?= e($c) ?>" <?= $countryVal === $c ? 'selected' : '' ?>><?= e($c) ?></option>
                <?php endforeach; ?>
            </select>
            <small class="error" data-error="country"><?= e($errors['country'] ?? '') ?></small>
        </div>

        <!-- Skills -->
        <div class="form-group full-width">
            <label>Skills</label>
            <div class="checkbox-row">
                <?php foreach (['PHP', 'MySQL', 'JavaScript', 'HTML', 'CSS', 'React'] as $skill): ?>
                    <label class="checkbox-inline">
                        <input type="checkbox" name="skills[]" value="<?= $skill ?>"
                            <?= in_array($skill, $selectedSkills, true) ? 'checked' : '' ?>>
                        <?= $skill ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Address -->
        <div class="form-group full-width">
            <label for="address">Address</label>
            <textarea id="address" name="address" rows="3"><?= $val('address') ?></textarea>
        </div>

        <!-- Profile Image -->
        <div class="form-group full-width">
            <label for="profile_image">Profile Image</label>
            <input type="file" id="profile_image" name="profile_image" accept="image/*">
            <?php if (!empty($student['profile_image'])): ?>
                <div class="current-image">
                    <span>Current:</span>
                    <img src="uploads/<?= e($student['profile_image']) ?>" alt="Current profile image">
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <?= !empty($isEdit) ? 'Update Student' : 'Register Student' ?>
        </button>
        <a href="list.php" class="btn btn-secondary">View List</a>
    </div>
</form>
