<?php
/**
 * Created by PhpStorm.
 * User: f-mkotsovoulou
 * Date: 3/12/2018
 * Time: 2:37 PM
 */?>

<!-- DESKTOP NAVIGATION -->
<!-- <div data-sticky-container>
 -->
<div style="background-color: #5740a0">
    <nav class="wrap top-bar nav-desktop">
        <div class="top-bar-left">
            <h2 class="site-logo">DGall</h2>
        </div>
        <div class="top-bar-right">
            <ul class="menu menu-desktop">
                <li><a href="index.php">Gallery</a></li>
                <li><a href="artists.php">Artists</a></li>
                <li><a href="paintings.php">Paintings</a></li>
                  <li><?php if (isset($_SESSION['username'])) { ?>
                        <a href="logout.php">Logout</a>
                        <?php } else {?>
                        <a href="login.php">Login</a></li>
                        <?php }
                        if (isset($_SESSION['level']) && $_SESSION['level']=='A') { ?>
                        <li><a href="adminPanel/web/">Admin Panel</a></li>
                        <?php } ?>
            </ul>
        </div>
    </nav>
</div>

