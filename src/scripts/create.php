<?php

if (isset($_POST) && !empty($_POST) && isset($_FILES) && !empty($_FILES)) {
    require_once('../config.php');
    require_once(SRCDIR['CLASSES'] . 'Create.class.php');

    new Create($_POST, $_FILES);
}
