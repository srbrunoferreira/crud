document.querySelectorAll('.action-button').forEach((btn) => {
  btn.addEventListener('click', (event) => {
    var action = btn.getAttribute('data-action');
    var formElement = document.querySelector('#' + action + '-form');
    var formData = new FormData(formElement);
    var thereAreInvalidInputs = false;
    var data = {};

    for (var input of formData.entries()) {
      var inputName = input[0];
      var inputValue = input[1];
      var funcName = getValidationFunc(inputName);
      var isValidInput = inputName != 'user-id'? window[funcName](inputValue): Number.isInteger(Number.parseInt(inputValue));

      if (isValidInput) {
        data[inputName] = inputValue;
      } else if (action == 'create' || getStringLength(inputValue) > 0) {
        thereAreInvalidInputs = true;
      }
    }

    var maxNecessaryInputs = action == 'update'? 1: 0; // It is because update form already has a hidden input

    if (!thereAreInvalidInputs && Object.keys(data).length > maxNecessaryInputs) {
      var readyData = generateReadyData(data);
      submitData(readyData, action);
      document.querySelector('.progress-effect').style.display = 'block';
    } else {
      alert('EXISTEM INFORMAÇÕES INVÁLIDAS NOS CAMPOS DO FORMULÁRIO.');
    }
  });
});

function generateReadyData(data) {
  var readyData = new FormData();

  for (var key in data) {
    readyData.set(key, data[key]);
  }

  return readyData;
}

var updateForm = document.querySelector('#update-form');
var idInHiddenInput = document.querySelector('#update-user-id');

const RESPONSE_TYPES = {
  SUCCESS: 'Operação realizada com sucesso!',
  INTERNAL_ERROR: 'Ocorreu um erro interno.\nContate a um administrador.',
  INVALID_INPUT:
    'Os dados inseridos não são válidos\nOu o e-mail já está registrado.',
  NOTHING_FOUND:
    'Nenhuma correspondência foi encontrada.\nTente mudar os filtros.',
};

function displayEditPanel(rowId) {
  var rowElement = document.querySelector('#row-' + rowId);

  updateForm.style.display = 'flex';
  rowElement.classList.add('show-like-edit');
  idInHiddenInput.value = rowId;
}

function removeEditPanel() {
  var rowElement = document.querySelector('#row-' + idInHiddenInput.value);

  updateForm.style.display = 'none';
  rowElement.classList.remove('show-like-edit');
}

function addUpdateDeleteBtnListener() {
  document.querySelectorAll('.update-btn').forEach((btn) => {
    btn.addEventListener('click', () => {
      displayEditPanel(btn.getAttribute('data-id'));

      document.querySelectorAll('.update-btn-cancel').forEach((btn) => {
        btn.addEventListener('click', () => {
          var rowEdited = document.querySelector('#row-' + idInHiddenInput.getAttribute('value'));
          rowEdited.classList.remove('show-like-edit');
          updateForm.style.display = 'none';
        });
      });

      document.querySelector('.update-action-button').addEventListener('click', (btn) => {
        removeEditPanel();
      });
    });
  });

  document.querySelectorAll('.delete-btn').forEach((btn) => {
    btn.addEventListener('click', () => {
      var data = {};
      userPictureTag = btn.parentNode.parentNode.childNodes[0].childNodes[0];
      data['userPictureFilename'] = userPictureTag.getAttribute('src').split('/')[5];
      data['rowId'] = btn.getAttribute('data-id');
      data = generateReadyData(data);
      submitData(data, 'delete');
    });
  });
}

function showResponse(response) {
  if (!(response in RESPONSE_TYPES)) {
    document.querySelector('#response-container').innerHTML = response;
  } else {
    alert(RESPONSE_TYPES[response]);
  }
}
