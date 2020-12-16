<?php
require_once('src/config.php');
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>

  <title>Create - CRUD</title>
  <?php require_once(PUBLICDIR['INCLUDES'] . 'main_head.php'); ?>
  <?php require_once(PUBLICDIR['INCLUDES'] . 'pages.php'); ?>

</head>

<body>

  <header id="main-header">
    <?php require_once(PUBLICDIR['INCLUDES'] . 'main_header.php'); ?>
  </header>

  <main id="main-div">

    <div class="page-title-container">
      <h1>Cadastrar funcionário</h1>
    </div>

    <div class="form-container">

      <form id="create-form" class="form" name="create" method="POST" enctype="multipart/form-data">

        <fieldset class="form-inputs-container">
          <legend>Formulário de cadastro</legend>

          <div class="input-container name-container">
            <label for="name">Nome</label>
            <input id="name" name="_name" type="text" maxlength="255" placeholder="Digite seu nome aqui" autofocus data-name="Nome" required autofocus>
          </div>

          <div class="input-container email-container">
            <label for="email">E-mail</label>
            <input id="email" name="email" type="email" maxlength="255" placeholder="Digite seu e-mail aqui" data-name="E-mail" required>
          </div>

          <div class="input-container birth-container">
            <label for="birth-date">Data de aniversário</label>
            <input id="birth-date" name="birth-date" type="date" data-name="Data de aniversário" required>
          </div>

          <div class="input-container occupation-container">
            <label for="occupation">Profissão</label>
            <input id="occupation" name="occupation" type="text" maxlength="255" placeholder="Digite sua profissão aqui" data-name="Profissão" required>
          </div>

          <div class="input-container password-container">
            <label for="password">Senha</label>
            <input id="password" name="_password" type="password" maxlength="255" placeholder="Digite sua senha aqui" data-name="Senha" required>
          </div>

          <div class="input-container img-container">
            <label for="img">Foto do funcionário</label>
            <input id="img" name="img" type="file" accept=".png, .jpg, .jpeg" data-name="Foto do funcionário" required>
          </div>

          <div class="form-button-container">
            <button class='action-button' type="button" title="Enviar" data-action="create">Enviar</button>
            <button type="reset" title="Limpar">Limpar</button>
          </div>

        </fieldset>

      </form>

    </div>

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
