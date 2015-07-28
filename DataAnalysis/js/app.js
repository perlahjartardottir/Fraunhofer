function logout() {
  $.ajax({
    url: "../../Login/logout.php",
    type: "POST"
  }).done(function() {
    // redirect the user to the login page
    // this is done so you loose access to the site you are at
    // when you log out.
    window.location = "../../Login/login.php";
  });
}
