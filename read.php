<?php
require_once('src/config.php');
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>

  <title>Read - CRUD</title>
  <?php require_once(PUBLICDIR['INCLUDES'] . 'main_head.php'); ?>
  <?php require_once(PUBLICDIR['INCLUDES'] . 'pages.php'); ?>
  <link rel="stylesheet" href="<?php echo PUBLICDIR['CSS'] . 'response_div.css'; ?>">

</head>

<body>

  <header id="main-header">
    <?php require_once(PUBLICDIR['INCLUDES'] . 'main_header.php'); ?>
  </header>

  <main id="main-div">

    <div class="page-title-container">
      <h1>Buscar por</h1>
    </div>

    <div class="form-container">

      <form id="read-form" class="form" name="read" method="POST">

        <fieldset class="form-inputs-container">
          <legend>Filtrar por</legend>

          <div class="input-container name-container">
            <label for="name">Nome</label>
            <input id="name" name="_name" type="text" maxlength="255" placeholder="Nome do funcionário" autofocus data-name="Nome">
          </div>

          <div class="input-container name-container">
            <label for="email">E-mail</label>
            <input id="email" name="email" type="email" maxlength="255" placeholder="E-mail do funcionário" data-name="E-mail">
          </div>

          <div class="input-container name-container">
            <label for="birth-date">Data de aniversário</label>
            <input id="birth-date" name="birth-date" type="date" data-name="Data de aniversário">
          </div>

          <div class="input-container name-container">
            <label for="occupation">Profissão</label>
            <input id="occupation" name="occupation" type="text" maxlength="255" placeholder="Profissão do funcionário" data-name="Profissão">
          </div>

          <div class="input-container name-container">
            <label for="reg-date">Data de registro</label>
            <input id="reg-date" name="reg-date" type="date" data-name="Data de registro">
          </div>

          <div class="form-button-container">
            <button class='action-button read-action-button' type="button" title="Enviar" data-action="read">Enviar</button>
            <button class='action-button refresh-action-button' type="button" title="Enviar" data-action="read">Atualizar</button>
            <button type="reset" title="Limpar">Limpar</button>
          </div>
        </fieldset>

      </form>

    </div>

    <form id="update-form" class="form" name="create" method="POST" enctype="multipart/form-data">

      <fieldset class="form-inputs-container">
        <legend>Atualizar dados</legend>

        <div class="input-container img-container">
          <label for="update-img">Foto do funcionário</label>
          <input id="update-img" name="img" type="file" accept=".png, .jpg, .jpeg" data-name="Foto do funcionário" required>
        </div>

        <div class="input-container name-container">
          <label for="update-name">Nome</label>
          <input id="update-name" name="_name" type="text" maxlength="255" placeholder="Digite seu nome aqui" autofocus data-name="Nome" required autofocus>
        </div>

        <div class="input-container email-container">
          <label for="update-email">E-mail</label>
          <input id="update-email" name="email" type="email" maxlength="255" placeholder="Digite seu e-mail aqui" data-name="E-mail" required>
        </div>

        <div class="input-container birth-container">
          <label for="update-birth-date">Data de aniversário</label>
          <input id="update-birth-date" name="birth-date" type="date" data-name="Data de aniversário" required>
        </div>

        <div class="input-container occupation-container">
          <label for="update-occupation">Profissão</label>
          <input id="update-occupation" name="occupation" type="text" maxlength="255" placeholder="Digite sua profissão aqui" data-name="Profissão" required>
        </div>

        <div class="input-container password-container">
          <label for="update-password">Senha</label>
          <input id="update-password" name="_password" type="password" maxlength="255" placeholder="Digite sua senha aqui" data-name="Senha" required>
        </div>

        <input id="update-user-id" type="hidden" name="user-id">

        <div class="form-button-container">
          <button class='action-button update-action-button' type="button" title="Enviar" data-action="update">Enviar</button>
          <button class="update-btn-cancel" type="button" title="Cancelar">Fechar</button>
          <button type="reset" title="Limpar">Limpar</button>
        </div>

      </fieldset>

    </form>

    <div id="response-container"></div>

    <div class="progress-effect"></div>

  </main>

  <footer id="main-footer">
    <?php require_once(PUBLICDIR['INCLUDES'] . 'main_footer.html'); ?>
  </footer>

  <?php require_once(PUBLICDIR['INCLUDES'] . 'request_warning.html'); ?>

  <script src="<?php echo PUBLICDIR['JS']; ?>submit_data.js"></script>
  <script src="<?php echo PUBLICDIR['JS']; ?>input_handling.js"></script>
  <script src="<?php echo PUBLICDIR['JS']; ?>dispatch.js"></script>

</body>

</html>