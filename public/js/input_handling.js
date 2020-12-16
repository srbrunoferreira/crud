function validateEmail(email) {
  var match = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,7})+$/;
  return match.test(email) && isNotEmpty(email);
}

function validateAlphabeticString(string) {
  return /^[A-zÀ-ú ]+$/.test(string) && isNotEmpty(string);
}

function validateDate(date) {
  var year = parseInt(date.split('-')[0]);
  return /^\d{4}([-])\d{2}\1\d{2}$/.test(date) && isNotEmpty(date);
}

function isNotEmpty(data) {
  if (data.constructor === File) {
    return 'name' in data && data['name'].trim() != '';
  } else {
    return data.trim().length > 0 ? true : false;
  }
}

function getStringLength(string) {
  if (string.constructor === File) {
    return 'name' in string? string['name'].length: 0;
  } else {
    return string.trim().length;
  }
}

function getValidationFunc(dataName) {
  var func;
  switch (dataName) {
    case '_name':
      func = 'validateAlphabeticString';
      break;
    case 'email':
      func = 'validateEmail';
      break;
    case 'birth-date':
      func = 'validateDate';
      break;
    case 'occupation':
      func = 'validateAlphabeticString';
      break;
    case '_password':
      func = 'isNotEmpty';
      break;
    case 'img':
      func = 'isNotEmpty';
      break;
    case 'reg-date':
      func = 'validateDate';
  }
  return func;
}
