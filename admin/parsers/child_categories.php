<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/ecom/core/init.php';
    $parentID=(int)$_POST['parentID'];
    $selected=sanitize($_POST['selected']);
    $childQuery=$db->query("SELECT * FROM categories WHERE parent='$parentID' ORDER BY category");
    ob_start(); //pre built php function that start buffering
?>
<option value=""></option>
<?php while($child=mysqli_fetch_assoc($childQuery)):?>
<option value="<?=$child['id'];?>" <?=(($selected==$child['id'])?' selected':'');?> ><?=$child['category'];?> </option>

<?php endwhile;?>
<?php echo ob_get_clean(); ?>