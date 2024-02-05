<nav>
    <ul class="menu">
        <li class="logo">Amministrazione</li>
        <li class="toggle"><a href="#" onclick="DOMManager.toggleMenu();return 0;">&#9776;</a></li>

        <li class="item"><a href="../index.php">Home</a></li>
        <li class="item <?php if($page_id == 1): ?>selected<?php endif ?>"><a <?php if($page_id !== 1): ?>href="index.php"<?php endif?>>Gestisci amministratori</a></li>
        <li class="item <?php if($page_id == 2): ?>selected<?php endif ?>"><a <?php if($page_id !== 2): ?>href="add.php"<?php endif?>>Inserisci Film</a></li>
        <li class="item <?php if($page_id == 3): ?>selected<?php endif ?>"><a <?php if($page_id !== 3): ?>href="edit.php"<?php endif?>>Gestisci Film</a></li>
        <li class="item"><a href="../logout.php">Logout</a></li>
    </ul>
</nav>