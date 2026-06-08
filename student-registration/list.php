<?php
/**
 * list.php — Display all students with search filter (Read) + CRUD links.
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Student List';
$search    = trim((string) ($_GET['search'] ?? ''));

$studentModel = new Student();
$students     = $studentModel->getAll($search);

require __DIR__ . '/includes/header.php';
?>
<section class="card">
    <div class="list-head">
        <h2 class="card-title">Registered Students</h2>
        <a href="index.php" class="btn btn-primary btn-sm">+ Add Student</a>
    </div>

    <form class="search-bar" action="list.php" method="GET">
        <input type="text" name="search" value="<?= e($search) ?>"
               placeholder="Search by name, email, phone or country...">
        <button type="submit" class="btn btn-primary">Search</button>
        <?php if ($search !== ''): ?>
            <a href="list.php" class="btn btn-secondary">Clear</a>
        <?php endif; ?>
    </form>

    <?php if (empty($students)): ?>
        <p class="empty-state">No student records found.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>DOB</th>
                        <th>Country</th>
                        <th>Skills</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $s): ?>
                        <tr>
                            <td>
                                <?php if (!empty($s['profile_image'])): ?>
                                    <img class="avatar" src="uploads/<?= e($s['profile_image']) ?>" alt="<?= e($s['full_name']) ?>">
                                <?php else: ?>
                                    <span class="avatar avatar-placeholder"><?= e(strtoupper(substr($s['full_name'], 0, 1))) ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= e($s['full_name']) ?></td>
                            <td><?= e($s['email']) ?></td>
                            <td><?= e($s['phone']) ?></td>
                            <td><?= e($s['gender']) ?></td>
                            <td><?= e($s['dob']) ?></td>
                            <td><?= e($s['country']) ?></td>
                            <td><?= e($s['skills']) ?></td>
                            <td class="actions">
                                <a href="edit.php?id=<?= (int) $s['id'] ?>" class="btn btn-edit btn-sm">Edit</a>
                                <a href="delete.php?id=<?= (int) $s['id'] ?>" class="btn btn-delete btn-sm"
                                   onclick="return confirm('Delete this student record?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
