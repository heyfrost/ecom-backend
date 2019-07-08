<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/ecom/core/init.php';
if(!is_logged_in()){
    login_error_redirect();
}
include 'includes/head.php';
include 'includes/navigation.php';
$dbpath='';
//Deleteproduct
if(isset($_GET['delete'])){
    $id=sanitize($_GET['delete']);
    $db->query("UPDATE products SET deleted =1 WHERE id='$id' ");
    header('Location: products.php');
}

if(isset($_GET['add'])|| isset($_GET['edit'])){
    
  $brandQuery=$db->query("SELECT * FROM brand ORDER BY brand");
  $parentQuery=$db->query("SELECT * FROM categories WHERE parent=0 ORDER BY category");
  $title=((isset($_POST['title']) && $_POST['title']!='')?sanitize($_POST['title']):'');
  $price=((isset($_POST['price']) && $_POST['price']!='')?sanitize($_POST['price']):'');
  $list_price=((isset($_POST['list_price']) && $_POST['list_price']!='')?sanitize($_POST['list_price']):'');
  $brand=((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):'');
  $parent=((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):'');
  $category=((isset($_POST['child']) && !empty($_POST['child']))?sanitize($_POST['child']):'');
  $description=((isset($_POST['description']) && $_POST['description']!='')?sanitize($_POST['description']):'');
  $sizes=((isset($_POST['sizes']) && $_POST['sizes']!='')?sanitize($_POST['sizes']):'');
  $saved_image='';
  

  if(isset($_GET['edit'])){
          $edit_id=(int)$_GET['edit'];
      $productresult=$db->query("SELECT * FROM products WHERE id='$edit_id'  ");
      $product=mysqli_fetch_assoc($productresult);
      if(isset($_GET['delete_image'])){
          $image_url=$_SERVER['DOCUMENT_ROOT'].$product['image'];
          unlink($image_url);
          $db->query("UPDATE products SET image ='' WHERE id='$edit_id'");
          header("Location: products.php?edit=".$edit_id);
      }
      $category=((isset($_POST['child']) && $_POST['child']!='')?sanitize($_POST['child']):$product['categories']);
      $title= ((isset($_POST['title']) && !empty($_POST['title']))?sanitize($_POST['title']):($product['title']))  ;
      $brand= ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):($product['brand']))  ;
      $parentQuer=$db->query("SELECT * FROM categories WHERE id='$category'");
      $parentResult=mysqli_fetch_assoc($parentQuer);
      $parent= ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):sanitize($parentResult['parent']))  ;
      $price= ((isset($_POST['price']) && !empty($_POST['price']))?sanitize($_POST['price']):($product['price']))  ;
      $description= ((isset($_POST['description']) && !empty($_POST['description']))?sanitize($_POST['description']):($product['description']))  ;
      $list_price= ((isset($_POST['list_price']) && !empty($_POST['list_price']))?sanitize($_POST['list_price']):($product['list_price']))  ;
      $sizes= ((isset($_POST['sizes']) && !empty($_POST['sizes']))?sanitize($_POST['sizes']):($product['sizes']))  ;
      $saved_image=(($product['image']!='')?$product['image']:'');
      $dbpath=$saved_image;
      if(!empty($sizes)){
        $sizeString=sanitize($sizes);
        $sizeString=rtrim($sizeString,", ");// , ke baad space jaruri hai warna galat result aa raha 
       // echo $sizeString;
        $sizesArray=explode(',',$sizeString);
        $sArray=array();
        $qArray=array();
        foreach($sizesArray as $ss){
         $s=explode(':',$ss);
        // print_r( $s);
         $sArray=$s[0];
         $qArray=$s[1];   
        }
    }
    else{$sizesArray=array();}

    }
  $sizesArray=array();
  if($_POST){
     
     // $dbpath='';
      $errors=array();
      
  $required=array('title','brand','price','parent','child','sizes');
  foreach($required as $field){
      if($_POST[$field]==''){
          $errors[]='All fields with * are required';
          break;
      }
  }
  //processing image 17 14:11
  if(!empty($_FILES)){
     // var_dump($_FILES);
      $photo=$_FILES['photo'];
      $name=$photo['name'];
      $nameArray=explode('.',$name);
      $fileName=$nameArray[0];
      $fileExt=$nameArray[1];
      $mime=explode('/',$photo['type']);
     // print_r($mime);
      $mimeType=$mime[0];
      $mimeExt=$mime[1];
      $tmpLoc=$photo['tmp_name'];
      $fileSize=$photo['size'];
      $allowed=array('png','jpg','jpeg','gif');
      $uploadName=md5(microtime()).'.'.$fileExt;
      $uploadPath=BASEURL.'images/products/'.$uploadName;
      $dbpath= '/ecom/images/products/'.$uploadName;

      if($mimeType!='image' || $mimeType==''){
          $errors[]='the file must be an image';
      }
      if(!in_array($fileExt,$allowed)){
          $errors[]='image format must be correct';
      }
      if($fileSize>25000000){
          $errors[]='file size must be under 25mb';
      }



  if(!empty($errors)){
      echo display_errors($errors);
  }else{
      if(!empty($_FILES))
      {
         move_uploaded_file($tmpLoc,$uploadPath);
      }
      $insertSql="INSERT INTO `products` (`title`,`price`,`list_price`,`brand`,`categories`,`sizes`,`image`,`description`) 
      VALUES ('$title','$price','$list_price','$brand','$category','$sizes','$dbpath','$description') "; 
      if(isset($_GET['edit'])){
        $insertSql="UPDATE products SET title='$title',price='$price',list_price='$list_price',brand='$brand',categories='$category',sizes='$sizes',image='$dbpath',description='$description'  WHERE id='$edit_id'";
        }
      $db->query($insertSql);
      header('Location: products.php');
  }
}
} 
?>
    <h2 class="text-center"><?=((isset($_GET['edit']))?'Edit':'Add A ');?>  Product</h2><hr>
    <form action="products.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1');?>" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="form-group col-md-3">
                <label for="title">Title*:</label>
                <input type="text" name="title" class="form-control" id="title" value="<?=$title;?>">
            </div>
            <div class="form-group col-md-3">
                <label for="brand">Brand*:</label>
                <select class="form-control" name="brand" id="brand">
                    <option value="<?=(($brand =='')?'selected':'');?> "></option>
                    <?php while($b=mysqli_fetch_assoc($brandQuery)): ?>
                    <option value="<?=$b['id'];?>"<?=(($brand ==$b['id'])?'selected ':'');?> ><?=$b['brand'];?></option>
                    <?php endwhile;?>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="parent">Parent Category</label>
                <select name="parent" id="parent" class="form-control">
                    <option value="<?=(($parent =='')?'selected ':'');?>">
                        <?php while($p=mysqli_fetch_assoc($parentQuery)): ?>
                            <option value="<?=$p['id'];?>" <?=(($parent==$p['id'])?'selected ':'');?> ><?=$p['category'];?></option>
                        <?php endwhile;?>
                    </option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="child">Child Category*:</label>
                <select name="child" class="form-control" id="child">

                </select>
            </div>
        </div>
        <hr>
       <div class="row"><div class="clearfix"></div>
       <div class="form-group col-md-3">
            <label for="price">Price*:</label>
            <input type="text" id="price" class="form-control" name="price" value="<?=$price;?>  ">
        </div>
        <div class="form-group col-md-3">
            <label for="list_price">List Price:</label>
            <input type="text" id="list_price" class="form-control" name="list_price" value="<?=$list_price;?>  ">
        </div>
        <div class="form-group col-md-3">
            <label for="">Quantity and Sizes*:</label>
            <button class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle');return false;">Quantity and Sizes</button>
        </div>
        <div class="form-group col-md-3">
            <label for="sizes">Sizes & Quantity Preview</label>
            <input class="form-control" type="text" name="sizes" id="sizes" value="<?=$sizes;?> " readonly >
        </div>
       </div><hr>
       <div class="row">
           <div class="col-md-6 form-group">
            <?php if($saved_image!=''): ?>
                <div class="saved-image"><img src="<?=$saved_image; ?>" alt="saved image"></div>
                <a href="products.php?delete_image=1&edit=<?=$edit_id;?>" class="text-danger">Delete image</a>
            <?php else:?>
               <label for="photo">Product Photo</label>
               <input type="file" name="photo" id="photo" class="form-control">
            <?php endif;?>
            </div>
           <div class="form-group col-md-6">
               <label for="description">Description:</label>
               <textarea name="description" id="description" class="form-control" cols="30" rows="6"><?=$description;?> </textarea>
           </div>
       </div>
       <input type="submit" value="<?=((isset($_GET['edit']))?'Edit':'Add A ');?> Product" class="btn btn-success pull-right " >
       <a href="products.php" class="btn btn-danger pull-right">Cancel</a>

    </form>
<!-- Modal -->
<div class="modal fade bs-example-modal-lg" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="sizesModalLabel">Sizes and Quantity</h4>
      </div>
      <div class="modal-body bs-example-modal-lg">
        <?php for($i=1;$i<=12;$i++):?>
            <div class="clear-fix">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="size<?=$i;?>">Size:</label>
                        <input type="text" name="size<?=$i;?>" id="size<?=$i;?>" value="<?=((!empty($sArray[$i-1]))?$sArray[$i-1]:'');?>" class="form-control">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="qty<?=$i;?>">Quantity:</label>
                        <input type="number" name="qty<?=$i;?>" id="qty<?=$i;?>" value="<?=((!empty($qArray[$i-1]))?$qArray[$i-1]:'');?>" min="0" class="form-control">
                    </div>
                </div>
            </div>    
        <?php endfor;?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updateSizes();jQuery('#sizesModal').modal('toggle');return false;">Save changes</button>
      </div>
    </div>
  </div>
</div>
<?php }else{

$sql="SELECT * FROM products WHERE deleted!=1";
$presults=$db->query($sql);
if(isset($_GET['featured'])){
    $id=(int)$_GET['id'];
    $featured=(int)$_GET['featured'];
    $featuredsql="UPDATE products SET featured='$featured' WHERE id='$id' ";
    $db->query($featuredsql);
    header('Location: products.php');
}
?>

<h2 class="text-center">Products</h2>
<a href="products.php?add=1" class="btn btn-success pull-right  " id="add-product-btn ">Add Product</a><div class="clearfix"></div>
<hr>
<table class="table table-bordered table-condensed table-striped">
    <thead>
        <th></th>
        <th>Products</th>
        <th>Price</th>
        <th>Categories</th>
        <th>Feature</th>
        <th>Sold</th>
    </thead>
    <tbody>
        <?php while($product=mysqli_fetch_assoc($presults)): 
            // setting category of child element
            $childID=$product['categories'];
            $catSql="SELECT * FROM categories WHERE id='$childID'";
            $result=$db->query($catSql);
            $child=mysqli_fetch_assoc($result);
            // getting parent
            $parentID=$child['parent'];
            $pSql="SELECT * FROM categories WHERE id='$parentID' ";
            $presult=$db->query($pSql);
            $parent=mysqli_fetch_assoc($presult);
            $category=$parent['category'].'~'.$child['category'];
            ?>
            <tr>
                <td>
                    <a href="products.php?edit=<?=$product['id'];?> " class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
                    <a href="products.php?delete=<?=$product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>

                </td>
                <td><?=$product['title'];?></td>
                <td><?=money($product['price']);?></td>
                <td><?=$category;?></td>
                <td><a href="products.php?featured=<?=(($product['featured']==0)?'1':'0'); ?>&id=<?=$product['id'];?> " class=" btn btn-xs btn-default ">
                <span class="glyphicon glyphicon-<?=(($product['featured']==1)?'minus':'plus');?>  "></span>
                </a>&nbsp <?=(($product['featured']==1)?'Featured Product':'');?></td>
                <td>0</td>
            </tr>

        <?php endwhile; ?>
    </tbody>
</table>



        <?php }include 'includes/footer.php'; ?>
        <script>
            jQuery('document').ready(function(){
                get_child_options('<?=$category;?>');
            });
        </script>