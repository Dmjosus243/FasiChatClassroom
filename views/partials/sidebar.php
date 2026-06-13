<aside class="sidebar">
    <ul>
        <li><a href="/FasiChatClassroom/public/dashboard/<?= $_SESSION['user_role'] ?>">Dashboard</a></li>
        <?php if ($_SESSION['user_role'] === 'apparitaire'): ?>
            <li><a href="/FasiChatClassroom/public/valve">Valve</a></li>
        <?php endif; ?>
    </ul>
</aside>