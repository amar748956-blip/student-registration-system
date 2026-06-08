<?php /** @var string $pageTitle */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) : 'Student Registration' ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <h1 class="logo">Student<span>Reg</span></h1>
            <nav class="main-nav">
                <a href="index.php">Register</a>
                <a href="list.php">Student List</a>
            </nav>
        </div>
    </header>
    <main class="container">
        <?php if ($flash = getFlash()): ?>
            <div class="alert alert-<?= e($flash['type']) ?>">
                <?= e($flash['message']) ?>
            </div>
        <?php endif; ?>
