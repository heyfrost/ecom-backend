<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/ecom/core/init.php';
include 'includes/head.php';
$password='password';
$hash=password_hash($password,PASSWORD_DEFAULT);
echo $hash;
$email=((isset($_POST['email']))?sanitize($_POST['email']):'');
$email=trim($email);
$password=((isset($_POST['password']))?sanitize($_POST['password']):'');
//$hashed=password_hash($password,PASSWORD_DEFAULT);
$errors=array();
?>
<style>
    background-image:url("/ecom/src/img/login.jpg");
</style>
<div id="login-form">
    <div>
        <?php 
            if($_POST){
                // form validation
                if(empty($_POST['email'])||empty($_POST['password'])){
                    $errors[]='You must insert email and password';

                }
                //validate email
                if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                    $errors[]='enter valit email';
                }
                //password is more than 6 char
                if(strlen($password)<8){
                    $errors[]='Password too short';
                }


                // check if email exist in db
                $query=$db->query("SELECT * FROM users WHERE email='$email'");
                $user=mysqli_fetch_assoc($query);
                $userCount=mysqli_num_rows($query);// echo $userCount;
                if($userCount<1){
                    $errors[]='Email doesn\'t exist';
                }
                if(!password_verify($password,$user['password'])){
                    $errors[]='password incorrect';
                }
                //errors display
                if(!empty($errors)){
                  echo  display_errors($errors);
                }else{
                    // log user in
                    $user_id=$user['id'];
                    echo $user_id;
                    login($user_id);
                }
            }


        ?>


    </div>
    <div>
        <h2 class="text-center">Login</h2><hr>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" calss="form-control" value="<?=$email;?>">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" calss="form-control" value="<?=$password;?>">
            </div>
            <div class="form-group">
                <input type="submit" value="Login" class="btn btn-primary">
            </div>
        </form>
        <p class="text-right"><a href="/ecom/index.php" alt="home">Visit Site</a></p>
    </div>
</div>


<?php include 'includes/footer.php' ?>