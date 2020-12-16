function submitData(data, target) {
  var req = new XMLHttpRequest();
  const URL = 'src/scripts/' + target + '.php';

  req.open('POST', URL, true);

  req.onreadystatechange = function () {
    if (req.readyState == 4 && req.status == 200) {
      showResponse(req.responseText);
      addUpdateDeleteBtnListener();
      document.querySelector('.progress-effect').style.display = 'none';
    }
  };

  req.addEventListener('loadstart', function () {});

  req.send(data);
}
