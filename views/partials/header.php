<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'FasiChat Classroom' ?></title>
    <link rel="stylesheet" href="/FasiChatClassroom/public/assets/css/style.css">
    <link rel="stylesheet" href="/FasiChatClassroom/public/assets/css/chat.css">
    <?= \Helpers\ViewHelper::csrfField() ?>
</head>
<body>