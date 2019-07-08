<div class="container-fluid">
<nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
     <div class="container">
      <div class="text-center">
      <a href="index.php" class="nav navbar navbar-brand">ECOM PROJECT ADMIN PANEL</a>
      </div>   
    </div>
        <div class="container-fluid">
        <ul class="nav navbar-nav">
            <li><a href="brands.php">Brand</a></li>
            <li><a href="categories.php">Categories</a></li>
            <li><a href="products.php">Products</a></li>
            <? if(has_permission('admin')):?>
            <li><a href="users.php">Users</a></li>
<?endif;?>
<li class="dropdown">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Hello <?=$user_data['first'];?>!  </a>
  <ul class="dropdown-menu" role="menu">
    <li><a href="change_password.php">Change Password</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
        </div>
 
</li>
</div>
<hr>
           
         <hr>
       
        </ul>
      </div>
    </nav>
    <hr>