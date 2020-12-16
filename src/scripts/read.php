<?php

if (isset($_POST) && !empty($_POST)) {
    require_once('../config.php');
    require_once(SRCDIR['CLASSES'] . 'Read.class.php');

    new Read($_POST);
}
