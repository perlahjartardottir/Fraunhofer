function logout() {
  $.ajax({
    url: "../Login/logout.php",
    type: "POST"
  }).done(function() {
    // redirect the user to the login page
    // this is done so you loose access to the site you are at
    // when you log out.
    window.location = "../Login/login.php";
  });
}
function addEmployee() {
  var eName = $('#eName').val();
  var ePhoneNumber = $('#ePhoneNumber').val();
  var eEmail = $('#eEmail').val();
  var ePass = $('#ePass').val();
  var ePassAgain = $('#ePassAgain').val();
  var sec_lvl = $('#sec_lvl').val();
  if (!eName) {
    $("#invalidEmployee").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Employee name</div>");
    return 0;
  } else if (!sec_lvl) {
    $("#invalidEmployee").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Security level</div>");
    return 0;
  } else if (!ePass) {
    $("#invalidEmployee").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Password</div>");
    return 0;
  } else if (!ePassAgain) {
    $("#invalidEmployee").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>You must confirm the password</div>");
    return 0;
  } else if (!eEmail || !ePhoneNumber) {
    $("#invalidEmployee").html("<div class='alert alert-warning fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>There is missing information about this employee</br>you can add this info in the View All Employees page.</div>");
  }
  $.ajax({
    url: "../Tooling/InsertPHP/insertNewEmployee.php",
    type: "POST",
    data: {
      eName: eName,
      ePhoneNumber: ePhoneNumber,
      eEmail: eEmail,
      ePass: ePass,
      sec_lvl: sec_lvl,
      ePassAgain: ePassAgain
    },
    success: function(data, status, xhr) {
      //alert("Employee added");
      window.location.reload();
    }
  });
}

function changeEmployee() {
  var employee_ID = $('#input_employee_ID').val();
  var employee_name = $('#input_employee_name').val();
  var employee_email = $('#input_employee_email').val();
  var employee_phone = $('#input_employee_phone').val();
  var security_level = $('#input_security_level').val();
  $.ajax({
    url: "../Tooling/UpdatePHP/updateEmployee.php",
    type: "POST",
    data: {
      employee_ID: employee_ID,
      employee_name: employee_name,
      employee_email: employee_email,
      employee_phone: employee_phone,
      security_level: security_level,
    },
    success: function(data, status, xhr) {
      console.log(data);
      if (data.indexOf("invalid ID") > -1) {
        alert("Employee ID must be valid");
      }
      if (data.indexOf("invalid email") > -1) {
        alert("Invalid email");
      }
      if (data.indexOf("invalid phone number") > -1) {
        alert("Invalid phone number");
      }
      if (data.indexOf("invalid security level") > -1) {
        alert("Security level should be in the range 1-4");
      } else {
        window.location.reload(true);
      }
    }
  });
}

function deleteEmployee() {
  var employee_ID = $('#input_employee_ID').val();
  var r = confirm("Are you sure you want to delete employee with ID: " + employee_ID);
  if (r === true) {
    $.ajax({
      url: "../Tooling/DeletePHP/deleteEmployee.php",
      type: "POST",
      data: {
        employee_ID: employee_ID
      },
      success: function(data, status, xhr) {
        window.location.reload(true);
        $("#status_text").html(data);
        $("#input_employee_ID").val("");
      }
    });
  }
}

function checkPass() {
  //Store the password field objects into variables ...
  var pass1 = document.getElementById('ePass');
  var pass2 = document.getElementById('ePassAgain');
  //Store the Confimation Message Object ...
  var message = document.getElementById('confirmMessage');
  //Set the colors we will be using ...
  var goodColor = "#66cc66";
  var badColor = "#ff6666";
  //Compare the values in the password field
  //and the confirmation field
  if (pass1.value == pass2.value) {
    //The passwords match.
    //Set the color to the good color and informxw
    //the user that they have entered the correct password
    pass2.style.backgroundColor = goodColor;
    message.style.color = goodColor;
    message.innerHTML = "Passwords Match!";
  } else {
    //The passwords do not match.
    //Set the color to the bad color and
    //notify the user.
    pass2.style.backgroundColor = badColor;
    message.style.color = badColor;
    message.innerHTML = "Passwords Do Not Match!";
  }
}
