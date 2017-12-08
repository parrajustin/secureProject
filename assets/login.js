function validateFormLogin() {
  var user = document.forms["login"]["uname"].value;
  var pass = document.forms["login"]["password"].value;

  var error = "";
  if (user.length < 6) {
      error += ("username must be at least 6 characters.");
  }
  if (pass.length < 6) {
    error += (" password must be at least 6 characters.");
  }
  if (error != "") alert (error);
  return error === "";
}

function validateFormRegister() {
  var user = document.forms["register"]["uname"].value;
  var pass = document.forms["register"]["password"].value;
  var cpass = document.forms["register"]["cpassword"].value;
  var addr = document.forms["register"]["addr"].value;

  var error = "";
  if (user.length < 6) {
    error += "username must be at least 6 characters.";
  }

  if (pass != cpass) {
    error += " password and confim password aren't the same.";
  }

  if (pass.length < 6) {
    error += " password must be at least 6 characters.";
  }

  if (addr.length < 1) {
    error += "must have an address. ";
  }
  
  if (error != "") alert (error);
  return error === "";
}