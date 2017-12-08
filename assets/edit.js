function validateFormEdit() {
  var pass = document.forms["edit"]["password"].value;
  var cpass = document.forms["edit"]["cpassword"].value;
  var addr = document.forms["edit"]["addr"].value;

  var error = "";
  if (pass != '' && pass != cpass) {
    error += " password and confim password aren't the same.";
  }

  if (pass != '' && pass.length < 6) {
    error += " password must be at least 6 characters.";
  }

  if (addr != '' && addr.length < 1) {
    error += "must have an address. ";
  }

  if (addr == '' && pass == '') {
    error += "must update some information";
  }
  
  if (error != "") alert (error);
  return error === "" && (pass !== '' || addr != '');
}