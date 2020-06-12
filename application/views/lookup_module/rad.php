<div class="row form-group">
    <div class="col col-md-3">
        <label class=" form-control-label"><?php echo $title?></label>
    </div>
    <div class="col col-md-9">
        <div class="form-check-inline form-check">
          <?php $first = 1;?>
          <?php foreach($member as $value) {?>
            <label for="inline-radio6" class="form-check-label " style="margin-right:10px">
                <input type="radio" id="inline-radio6" name="<?php echo $name ?>" value="<?php echo $value['value']?>" class="form-check-input" <?php if(isset($_POST[$name])) echo ($_POST[$name]==$value['value']) ? 'checked="checked"' : '';
                            else echo($first==1)?'checked="checked"' : '';
                  ?>> <?php echo $value['dsc']?>
            </label>
          <?php $first+=1;}?>

        </div>
    </div>
</div>
