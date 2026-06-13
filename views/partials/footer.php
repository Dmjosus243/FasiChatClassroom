    <script src="<?= \Helpers\ViewHelper::asset('js/main.js') ?>"></script>
    <?php if (isset($extraJs)): ?>
        <?php foreach ($extraJs as $js): ?>
            <script src="<?= \Helpers\ViewHelper::asset('js/' . $js) ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>