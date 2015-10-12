<!-- The header file for almost all views. -->
<!-- This file includes all the .css and .js needed  -->
<!-- It also displays the top of the page and the user who is logged in -->
<meta charset="UTF-8">
<meta name="google" content="notranslate">
<meta http-equiv="Content-Language" content="en">
<link href='../css/header.css' rel='stylesheet'>
<link href='../css/bootstrap.min.css' rel='stylesheet'>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src='../dest/fraunhofer.min.js'></script>
<div class="navbar navbar-default navbar-static-top">
  <div class="container">
    <div class="navbar-header">
      <div class='navbar-brand'>
        <a href='../../Views/menu.php'>Menu</a>
        <a href='../views/selection.php' style="margin-left:7px;">Home</a>
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
            <a href='../views/addNewPO.php'           class='btn btn-primary headerbutton active' role='button' type='button'>Add PO</a>
            <a href='../views/generateTrackSheet.php' class='btn btn-primary headerbutton active' role='button' type='button'>Track sheet</a>
            <a href='../views/filterPOS.php'          class='btn btn-primary headerbutton active' role='button' type='button'>PO search</a>
            <a href='../views/filterRuns.php'         class='btn btn-primary headerbutton active' role='button'>Run search</a>
            <a href='../Views/feedback.php'         class='btn btn-primary headerbutton active' role='button'>Comment</a>
            <a onclick='logout()' class='btn btn-danger headerbutton active' role='button'>Logout</a>
        </ul>
    </div>
  </div>
</div>
