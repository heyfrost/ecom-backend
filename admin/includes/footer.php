

    <footer class="text-center col-md-12" id="footer">  
      &copy; 2013-2019

    </footer>
<script>

  function updateSizes(){
    var sizeString='';
    for(i=1;i<=12;i++){
      if(jQuery('#size'+i).val()!= ''){
        sizeString+=jQuery('#size'+i).val()+':'+jQuery('#qty'+i).val()+',';
      }
    }
    jQuery('#sizes').val(sizeString);
  }
  function get_child_options(selected){
    if(typeof selected==='undefined'){
      var selected='';
    }
    var parentID=jQuery('#parent').val();
    jQuery.ajax({
      url: '/ecom/admin/parsers/child_categories.php',
      type: 'POST',
      data: {parentID : parentID,selected:selected},
      success: function(data){
        jQuery('#child').html(data);//places html code inside here #id
      },
      error: function(){
        alert("Something went wrong with child option")
      },
    });
  jQuery('select[name="parent"]').change(function(){
    get_child_options();
  });//listener

  }


</script>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->


    <script src="https://code.jquery.com/jquery-3.3.1.slim.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>