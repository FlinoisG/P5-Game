
<?= implode(" ", $_SESSION); ?>
<?php
if (file_exists('../deposit/User_Avatar/'.$_SESSION['auth'].'.png')){
    echo '<img src="../deposit/User_Avatar/'.$_SESSION['auth'].'.png" class="panelUserAvatar" alt="User Avatar">';
} else {
    echo '<img src="../deposit/User_Avatar/'.$_SESSION['auth'].'.jpg" class="panelUserAvatar" alt="User Avatar">';
}
?>
