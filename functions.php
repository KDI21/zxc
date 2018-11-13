<?php
function is_allowed($ownerId) {
    if ($_SESSION['rights'] == 'adm') {
        return true;
    }

    if ($_SESSION['id'] == $ownerId) {
        return true;
    }

    return false;
}
