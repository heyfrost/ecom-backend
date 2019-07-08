<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/ecom/core/init.php';
if(!is_logged_in()){
    login_error_redirect();
}
include 'includes/head.php';
$hashed=$user_data['password'];
$old_password=((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
$old_password=trim($old_password);

$password=((isset($_POST['password']))?sanitize($_POST['password']):'');
$password=trim($password);

$confirm=((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
$confirm=trim($confirm);

$new_hashed=password_hash($password,PASSWORD_DEFAULT);
$user_id=$user_data['id'];

$errors=array();
?>
<div id="login-form">
    <div>
        <?php 
            if($_POST){
                // form validation
                if(empty($_POST['old_password'])||empty($_POST['password'])|| empty($_POST['confirm'])){
                    $errors[]='You must fill all fields password';

                }
            
                //password is more than 6 char
                if(strlen($password)<8){
                    $errors[]='Password too short';
                }
                // if pass
                if($password != $confirm){
                    $errors[]='Password does not match';
                }

                if(!password_verify($old_password,$hashed)){
                    $errors[]='old password incorrect';
                }
                //errors display
                if(!empty($errors)){
                  echo  display_errors($errors);
                }else{
                    //change password
                    $db->query("UPDATE users SET password='$new_hashed' WHERE id='$user_id' ");
                    $_SESSION['success_flash']="Your password is updated";
                    header('Location: index.php');
                }
            }


        ?>


    </div>
    <div>
        <h2 class="text-center">Change password</h2><hr>
        <form action="change_password.php" method="post">
            <div class="form-group">
                <label for="old_password">Old password:</label>
                <input type="password" name="old_password" id="old_password" calss="form-control" value="<?=$old_password;?>">
            </div>
            <div class="form-group">
                <label for="password">New Password:</label>
                <input type="password" name="password" id="password" calss="form-control" value="<?=$password;?>">
            </div>
            <div class="form-group">
                <label for="confirm">Confirm New Password:</label>
                <input type="password" name="confirm" id="confirm" calss="form-control" value="<?=$confirm;?>">
            </div>
            <div class="form-group">
                <a href="index.php" class="btn btn-default">Cancel</a>
                <input type="submit" value="Update" class="btn btn-primary">
            </div>
        </form>
        <p class="text-right"><a href="/ecom/index.php" alt="home">Visit Site</a></p>
    </div>
</div>


<?php include 'includes/footer.php' ?>