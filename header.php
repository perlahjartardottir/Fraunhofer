<!-- The header file for almost all views. -->
<!-- This file includes all the .css and .js needed  -->
<!-- It also displays the top of the page and the user who is logged in -->
<link href='/css/header.css' rel='stylesheet'>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src='../js/app.js'></script>
<script src='../Tooling/js/bootstrap.js'></script>
<div class="navbar navbar-default navbar-static-top">
  <div class="container">
    <div class="navbar-header">
      <div class='navbar-brand'>
        <a href='menu.php'>Menu</a>
        <span class='username'><strong><?php echo $_SESSION["username"];?></strong></span>
        <a href='editProfile.php'>Edit profile</a>
      </div>
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navHeaderCollapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
          </button>
    </div>
    <div class="collapse navbar-collapse navHeaderCollapse">
        <ul class='navbar-right btn-group' role='group'>
          <a href='../Tooling/Views/feedback.php' class='btn btn-primary headerbutton active' role='button'>Comment</a>
          <a href='https://github.com/Freyr12/Fraunhofer' class='btn btn-primary headerbutton active' role='button'>?</a>
          <a onclick='logout()' class='btn btn-danger headerbutton active' role='button'>Logout</a>
        </ul>
    </div>
  </div>
</div>
