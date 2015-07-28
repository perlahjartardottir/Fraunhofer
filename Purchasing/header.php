<!-- The header file for almost all views. -->
<!-- This file includes all the .css and .js needed  -->
<!-- It also displays the top of the page and the user who is logged in -->
<link href='/css/header.css' rel='stylesheet'>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src='../js/app.js'></script>
<div class="navbar navbar-default navbar-static-top">
  <div class="container">
    <div class="navbar-header">
      <div class='navbar-brand'>
        <a href='../views/purchasing.php'>Home</a>
        <span class='username'><strong><?php echo $_SESSION["username"];?></strong></span>
      </div>
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navHeaderCollapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
          </button>
    </div>
    <div class="collapse navbar-collapse navHeaderCollapse">
        <ul class='navbar-right btn-group' role='group'>
            <a onclick='logout()' class='btn btn-danger headerbutton active' role='button'>Logout</a>
        </ul>
    </div>
  </div>
</div>
