
<div class="row form-group">
    <div class="col col-md-3">
        <label for="select" class=" form-control-label"><?php echo $title?></label>
    </div>
    <div class="col-12 col-md-9">
        <?php $first = 1;?>
        <select name="<?php echo $name ?>" id="select" class="form-control">
          <?php foreach($member as $value) {?>
            <option value="<?php echo $value['value']?>"
              <?php
                if(isset($_POST[$name])) echo ($_POST[$name]==$value['value']) ? 'selected="selected"' : '';
                else echo($first==1)?'selected="selected"' : '';
              ?>><?php echo $value['dsc']?></option>
          <?php $first+=1;}?>
        </select>
    </div>
</div>
