<?php
require_once '../core/init.php';
if(!is_logged_in()){
    login_error_redirect();
}
include 'includes/head.php';
include 'includes/navigation.php';
// get brand from database
$sql="SELECT * FROM brand ORDER BY  brand";
$results=$db->query($sql);
// if add form is submitted
$errors=array();
//edit brand
if(isset($_GET['edit']) && !empty($_GET['edit'])){
    $edit_id=(int)$_GET['edit'];
    $edit_id=sanitize($edit_id);
    $sql2="SELECT * FROM brand WHERE id='$edit_id'";
    $edit_result=$db->query($sql2);
    $eBrand=mysqli_fetch_assoc($edit_result);
}

//delete brand
if(isset($_GET['delete']) && !empty($_GET['delete'])){
    $delete_id=(int)$_GET['delete'];
    $delete_id=sanitize($delete_id);
    //echo $delete_id;
    $sql="DELETE FROM brand WHERE id='$delete_id' ";
    $db->query($sql);
    header('Location: brands.php');
}


if(isset($_POST['add_submit'])){
    $brand=sanitize($_POST['brand']);
    //if brand is blank
    if($_POST['brand']==''){
        $errors[].='must enter a brand';
    }
    // check if already exist
    $sql="SELECT * FROM brand WHERE brand='$brand'";
    if(isset($_GET['edit'])){
        $sql="SELECT * FROM brand WHERE brand='$brand' AND id!='$edit_id'";
    }
    $result=$db->query($sql);
    $count=mysqli_num_rows($result);
    //echo $count;
    if($count>0){
        $errors[].=$brand.' already exist please choose another...';
    }
    if(!empty($errors)){
        echo display_errors($errors);
    }else{
        //add brand to database
        $sql="INSERT INTO brand (brand) VALUES ('$brand')";
        if(isset($_GET['edit'])){
            $sql="UPDATE brand SET brand='$brand' WHERE id='$edit_id'";
            

        }
        $db->query($sql);
        header('Location: brands.php');
    }
}
?>


<h2 class="text-center">BRANDS</h2><hr>

<!-- form -->

<div class="text-center">
    <form class="form-inline" action="brands.php <?=((isset($_GET['edit']))?'?edit='.$edit_id:''); ?> " method="post">
        <div class="form-group">
            <?php 
                $brand_value='';
                if(isset($_GET['edit'])){
                    $brand_value=$eBrand['brand'];
                }else{
                    if(isset($_POST['brand'])){
                        $brand_value=sanitize($_POST['brand']);
                    }
                }
            ?>
            <label for="brand"><?=((isset($_GET['edit']))?'Edit':'Add a'); ?> Brand</label>
            <input type="text" name="brand" id="brand" class="form-control" value="<?= $brand_value; ?>">
            <?php if(isset($_GET['edit'])): ?>
                <a href="brands.php" class="btn btn-default">Cancel</a>
            <?php endif;?>
            <input type="submit" name="add_submit" value="<?=((isset($_GET['edit']))?'Edit':'Add'); ?> Brand" class="btn btn-success">
        </div>
    </form>
</div><hr>

<table class="table table-bordered table-striped table-auto table-condensed">
    <thead>
        <th></th>
        <th>Brand</th>
        <th></th>
    </thead>
    <tbody>
        <?php while($brand=mysqli_fetch_assoc($results)): ?>
        <tr>
            <th><a href="brands.php?edit=<?=$brand['id']?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a></th>
            <th><?= $brand['brand'];?> </th>
            <th><a href="brands.php?delete=<?=$brand['id']?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a></th>
        </tr>
        <?php endwhile;?>
    </tbody>
</table>
<?php include 'includes/footer.php'; ?>