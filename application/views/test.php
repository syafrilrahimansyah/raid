<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
          <!-- start -->
          <form action="<?php echo base_url('test/multiselect');?>" method="post" enctype="multipart/form-data" class="form-horizontal">
          <h1>Pre-selected-options</h1>
          <select id='pre-selected-options' multiple='multiple' name="sel[]">
            <option value="elem_1">elem 1</option>
            <option value="elem_2">elem 2</option>
            <option value="elem_3">elem 3</option>
            <option value="elem_4">elem 4</option>
            <option value="elem_100">elem 100</option>
          </select>
          <input type="submit" name="pos" value="nah">
        </form>

          <!-- ends -->
          <!-- jQuery -->
          <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
          <!-- Bootstrap JavaScript -->
          <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/js/bootstrap.min.js"></script>
          <script src="../assets/js/jquery.multi-select.js"></script>
          <script type="text/javascript">
          // run pre selected options
          $('#pre-selected-options').multiSelect();
          </script>
        </div>
    </div>
</div>
