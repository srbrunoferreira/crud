<?php

if (isset($_POST) && !empty($_POST)) {
    require_once('../config.php');
    require_once(SRCDIR['CLASSES'] . 'Update.class.php');
    $data = isset($_FILES) && !empty($_FILES)? [$_POST, $_FILES]: [$_POST];

    new Update($data);
}

// TODO:

// 1. Terminar a classe Update
// 2. Revisar todo o código
// 3. Enviar para o GitHub