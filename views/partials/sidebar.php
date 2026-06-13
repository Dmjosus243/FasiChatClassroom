<aside class="sidebar">
    <ul class="sidebar-menu">
        <li><a href="<?= \Helpers\ViewHelper::url('dashboard/' . $_SESSION['user_role']) ?>">Dashboard</a></li>
        <li><a href="<?= \Helpers\ViewHelper::url('messages') ?>">Messages</a></li>
        <?php if ($_SESSION['user_role'] === 'apparitaire'): ?>
            <li><a href="<?= \Helpers\ViewHelper::url('valve') ?>">Valve</a></li>
        <?php endif; ?>
        <?php if (in_array($_SESSION['user_role'], ['doyen', 'vice-doyen'])): ?>
            <li><a href="<?= \Helpers\ViewHelper::url('convocations') ?>">Convocations</a></li>
        <?php endif; ?>
    </ul>
</aside>