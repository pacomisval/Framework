<div id="header">
    <a href="<?= $config['site']['root']?>"><div class="option">Inicio</div></a>
    <a href="<?=$config['site']['root']?>/historia"><div class="option">Historia</div></a>
    <a href="<?=$config['site']['root']?>/jugadores"><div class="option">Jugadores</div></a>
    <?php
        use core\auth\Auth;
        if (!auth::check()) {
    ?>
    <a href="<?=$config['site']['root']?>/registro"><div class="option right">Registro</div></a>
    <a href="<?=$config['site']['root']?>/login"><div class="option right">Login</div></a>
    <?php
        } else {
    ?>
    <a href="<?=$config['site']['root']?>/logout"><div class="option right">Logout</div></a>
    <div class="option right"><?= $_SESSION['userName']?></div>
    <?php
        }
    ?>
</div>