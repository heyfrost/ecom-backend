<?php 
  require_once 'core/init.php';
  include 'includes/head.php';
  include 'includes/navigation.php'; 
  include 'includes/headerfull.php' ;
  include 'includes/leftbar.php' ;

  $sql="SELECT * FROM products WHERE featured=1";
  $featured=$db->query($sql);
?>

      <!--main content -->
      <div class="col-lg-8 col-md-8">
      <h2 class="text-cente">Featured Products</h2><br>
        <div class="row">
          
        <?php while($product=mysqli_fetch_assoc($featured)): ?>
        <?php //var_dump($product); ?>
          <div class="col-md-3 col-sm-6">
            <h4><?php echo $product['title'] ?></h4>
            <img src="<?php echo $product['image'] ?>" alt="<?php echo $product['title'] ?>" class="img-thumb">
            <div class="list-price text-danger">List Price : <s><?php echo $product['list_price'] ?></s></div>
            <p class="price">Our Price :<?php echo $product['price'] ?></p>
            <button type="button" class=" btn btn-sm btn-success" onclick="detailsmodal(<?= $product['id']; ?>)" >Details</button>
          </div>
        <?php endwhile; ?>
          
        </div>
      </div>
<?php 
include 'includes/rightbar.php';
include 'includes/footer.php';
?>
    
   