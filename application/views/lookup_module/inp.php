<div class="row form-group">
    <div class="col col-md-3">
        <label for="text-input" class=" form-control-label"><?php echo $title?></label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="text-input" name="<?php echo $name ?>" placeholder="<?php echo $plc_hldr?>" class="form-control" value="<?php echo isset($_POST[$name]) ? $_POST[$name] : '' ?>">
        <small class="form-text text-muted"><?php echo $dsc?></small>
    </div>
</div>
