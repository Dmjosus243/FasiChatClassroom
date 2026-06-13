<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'FasiChat Classroom' ?></title>
    <link rel="stylesheet" href="<?= \Helpers\ViewHelper::asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= \Helpers\ViewHelper::asset('css/chat.css') ?>">
    <?php if (isset($extraCss)): ?>
        <?php foreach ($extraCss as $css): ?>
            <link rel="stylesheet" href="<?= \Helpers\ViewHelper::asset('css/' . $css) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>