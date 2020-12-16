<?php

if (isset($_POST) && !empty($_POST)) {
    require_once('../config.php');
    require_once(SRCDIR['CLASSES'] . 'Delete.class.php');

    new Delete($_POST);
}
