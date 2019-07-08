<?php
  require_once 'core/init.php';
  include 'includes/head.php';
  include 'includes/navigation.php'; 
  //include 'includes/headerfull.php' ;
  if($cart_id!=''){
      $cartQ=$db->query("SELECT * FROM cart WHERE id='{$cart_id}' ");
      $result=mysqli_fetch_assoc($cartQ);
      $items=json_decode($result['items'],true);
      $i=1;
      $subtotal=0;
      $item_count=0;
  }
?>


<div class="row">
    <div class="col-md-12">
    <h2 class="text-center">
            MY SHOPPING CART
        </h2><hr>
        <?php if($cart_id==''):?>
            <div class="bg-danger">
                <p class="text-center text-danger">Your cart is empty</p>
            </div>
            <?php else :?> 
                <table class="table table-bordered table-condensed table-striped">
                    <thead>
                        <th>#</th>
                        <th>Item</th>
                        <th>Price</th>
                        <th>quantity</th>
                        <th>Size</th>
                        <th>SubTotal</th>
                    </thead>
                    <tbody>
                        <?php foreach($items as $item){
                            $product_id=$item['id'];
                            $productQ=$db->query("SELECT * FROM products WHERE id='{$product_id}' ");
                            $product=mysqli_fetch_assoc($productQ);
                            $sArray=explode(',',$product['sizes']);
                            foreach($sArray as $sizeString){
                                $s=explode(':',$sizeString);
                                if($s[0]== $item['size']){
                                    $available=$s[1];
                                }
                            }
                            ?>
                                <tr>
                                    <td><?=$i;?> </td>
                                    <td><?=$product['title'];?> </td>
                                    <td><?=money($product['price']);?></td>
                                    <td>
                                        <button class="btn btn-xs btn-default" onclick="update_cart('removeone','<?=$product['id'];?>','<?=$item['size'];?>');"> - </button>
                                        <?=$item['quantity'];?>
                                        <?php //if($item['quantity']< $available): ?>
                                        <button class="btn btn-xs btn-default" onclick="update_cart('addone','<?=$product['id'];?>','<?=$item['size'];?>');"> + </button>
                                        <?php// else: ?>
                                            <span class="text-danger"></span>
                                        <?php// endif;?>    
                                    </td>
                                    <td><?=$item['size'];?></td>
                                    <td><?=money($item['quantity']*$product['price']);?></td>
                                </tr>

                            <?php $i++;
                            $item_count+=$item['quantity'];
                            $subtotal+=($product['price']*$item['quantity']);
                        } 
                            $tax=TAXRATE*$subtotal;
                           // $tax= number_format($tax,2);
                            $grand_total=$tax+$subtotal;
                        ?>
                    </tbody>
                </table>
                <table class="table table-bordered table-condensed text-right">
                    <legend></legend><hr>
                    <thead>
                        <th>Total Items</th>
                        <th>Sub total</th>
                        <th>Tax</th>
                        <th>Grand total</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?=$item_count;?></td>
                            <td><?=money($subtotal);?></td>
                            <td><?=money($tax);?></td>
                            <td class="bg-success"><?=money($grand_total);?></td>

                        </tr>
                    </tbody>
                </table>

                <!-- Button trigger modal  Checkout-->
<button type="button" class="btn btn-primary btn-lg pull-right" data-toggle="modal" data-target="#checkoutModal">
  <span class="glyphicon glyphicon-shopping-cart">Checkout</span>
</button>

<!-- Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="checkoutModalLabel">Shipping Address</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

            <?php endif;?>
    </div>
</div>




<?php 
include 'includes/footer.php';
?>