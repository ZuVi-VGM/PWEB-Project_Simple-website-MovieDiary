
<nav>
    <ul class="menu">
        <li class="logo">MovieDiary</li>
        <li class="toggle"><a href="#" onclick="DOMManager.toggleMenu();return 0;">&#9776;</a></li>

        <li class="item <?php if($page_id == 1): ?>selected<?php endif ?>"><a <?php if($page_id !== 1): ?>href="home.php"<?php endif?>>Home</a></li>
        <li class="item <?php if($page_id == 2): ?>selected<?php endif ?>"><a <?php if($page_id !== 2): ?>href="explore.php"<?php endif?>>Esplora</a></li>
        <li class="item <?php if($page_id == 3): ?>selected<?php endif ?>"><a <?php if($page_id !== 3): ?>href="settings.php"<?php endif?>>Impostazioni</a></li>
        <?php if($_SESSION['is_admin'] == 1): ?><li class="item"><a href="admin">Amministrazione</a></li><?php endif;?>
        <li class="item"><a href="logout.php">Logout</a></li>

    </ul>
</nav>
