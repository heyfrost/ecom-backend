
  </div>


    <footer class="text-center" id="footer">  
      &copy; 2013-20123

    </footer>


    <!--details modal-->
  <script>
    jQuery(window).scroll(function(){
      var vscroll=jQuery(this).scrollTop();
      console.log(vscroll);
      jQuery('#logotext').css({
        "transform":"translate(0px, "+vscroll/2+"px)"
      });


      var vscroll=jQuery(this).scrollTop();
      console.log(vscroll);
      jQuery('#fore-flower').css({
        "transform":"translate(0px, -"+vscroll/12+"px)"
      });

    });



    function detailsmodal(id){
      // using ajax
      var data={"id":id};
      jQuery.ajax({
        url: '/ecom/includes/detailsmodal.php',
        method: "post",
        data: data,
        success : function(data){
          jQuery('body').append(data);
          jQuery('#details-modal').modal('toggle');
        },
        error: function(){
          alert("Something went Wrong");
        }
      });
    }
    function update_cart(mode,edit_id,edit_size){
      alert("function");
      var data={"mode":mode,"edit_id":edit_id,"edit_size":edit_size};
      jQuery.ajax(){
        url:'/ecom/admin/parsers/update_cart.php',
        method:"post",
        data:data,
        success:function(){location.reload();},
        error :function(){alert("something went wrong");},

      };

    }

    function add_to_cart(){
      jQuery('#modal_errors').html("");
      var size=jQuery('#size').val();
      var quantity=jQuery('#quantity').val();
      var available=jQuery('#available').val();
      var error='';
      var data=jQuery('#add_product_form').serialize();
      console.log(size);
      console.log(quantity);
      console.log(available);
      if(size=='' || quantity=='' || quantity== 0){
        console.log("if me hai");
        error+='<p class="text-danger text-center>YOu must choose a size and quantity</p>';
        jQuery('#modal_errors').html(error);
          alert(' if working still working');
       
        return;
      }
      else if(quantity>available){
        console.log("else if me hai");
        error+=' <p class="text-danger text-center>There are only'+available+'available </p>';
        jQuery('#modal_errors').html(error);
        return;
      }else{
        console.log("Else me hai");
        jQuery.ajax({
          url: '/ecom/admin/parsers/add_cart.php',
          method :'post',
          data: data,
          success:function(){
            location.reload();
          },//reloding page
          error :function(){alert("Something went Wrong");}
        });
      }
    }

    $(document).ready(function() {
      console.log($.ajax);
    });
  </script>
  <!-- jQuery library -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
 

</body>
</html>