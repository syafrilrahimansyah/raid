<style type="text/css">
  #appadd {
    white-space: nowrap;
    overflow: hidden;
    width: 10px;
    height: 10px;
    text-overflow: ellipsis;
  }
</style>

<div class="main-content">
  <div class="section__content section__content--p30">
    <div class="container-fluid">
      <div class="overview-wrap">
        <h3 class="title-1">Filter Key</h3>
        <button class="au-btn au-btn-icon au-btn--blue" data-toggle="modal" data-target="#new">
        <i class="zmdi zmdi-plus"></i>set key
        </button>
      </div>
      <br>
  <?php if($alert==null){}
    else{
  ?>
  <div class="alert alert-<?php echo $alert[0]?>" role="alert">
    <?php echo $alert[1]?>
  </div>
  <?php } ?>
      <div class="table-responsive m-b-40">
        <table class="table table-borderless table-data3">
          <thead>
            <tr>
              <th>ID</th>
              <th>Payload ID</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($allCombine as $value) {?>
              <tr data-toggle="modal" data-target="#<?php echo $value['id']?>" id="appadd">
                <td><?php echo $value['payload_id'].' - '.$value['rp_id']?></td>
                <td><?php echo $value['key']?></td>
              </tr>
            <?php }?>
          </tbody>
        </table>
        <?php echo $pagination?>
      </div>
    </div>
  </div>
</div>
<!-- ######################### Modal NEW-->
<!-- Modal -->
<div class="modal fade" id="new" role="dialog">
  <div class="modal-dialog" style="max-width:50%">
  <!-- Modal content-->
    <div class="modal-content" >
      <div class="modal-header">
        <strong>Value Editor</strong>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form action="<?php echo base_url('RM_Main/fltr')?>" method="post" enctype="multipart/form-data" class="form-horizontal">
          <div class="row form-group">
              <div class="col col-md-3">
                  <label for="select" class=" form-control-label">Payload ID</label>
              </div>
              <div class="col-12 col-md-9">
                  <select name="payload_id" id="select" class="form-control">
                    <?php foreach ($allPayload as $payload) { ?>
                      <option value="<?php echo $payload->act_id?>" ><?php echo $payload->act_id.' - '.$payload->id?></option>
                    <?php }?>
                  </select>
              </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="new" class="btn btn-primary btn-sm">
            <i class="fa fa-dot-circle-o"></i> Submit
            </button>
            <button type="reset" class="btn btn-danger btn-sm">
            <i class="fa fa-ban"></i> Reset
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<?php foreach($allCombine as $modal) {?>
  <div class="modal fade" id="<?php echo $modal['id']?>" role="dialog">
    <div class="modal-dialog" style="max-width:50%">
    <!-- Modal content-->
      <div class="modal-content" >
        <div class="modal-header">
          <strong>Value Editor</strong>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form action="<?php echo base_url('RM_Main/fltr')?>" method="post" enctype="multipart/form-data" class="form-horizontal">
            <input type="text" name="id" class="form-control" value="<?php echo $modal['id']?>" required hidden="">
            <input type="text" name="act_id" class="form-control" value="<?php echo $modal['rp_act_id']?>" required hidden="">
            <div class="row form-group">
                <div class="col col-md-3">
                    <label for="text-input" class=" form-control-label">Payload ID</label>
                </div>
                <div class="col-12 col-md-9">
                    <p><?php echo $modal['payload_id'].' - '.$modal['rp_id']?></p>
                </div>
            </div>
            <div class="row form-group">
                <div class="col col-md-3">
                    <label for="select" class=" form-control-label">Group ID</label>
                </div>
                <div class="col-12 col-md-9">
                  <?php if($modal['rp_group']!=[]){?>
                    <select name="group" id="select" class="form-control">
                      <?php foreach ($modal['rp_group'] as $rpg) { ?>
                        <option value="<?php echo $rpg[1]?>" <?php echo ($rpg[1]==$modal['key'])?'selected':'' ?> ><?php echo $rpg[1]?></option>
                      <?php }?>
                    </select>
                  <?php }else{?>
                    <p>no group assigned</p>
                    <?php }?>
                </div>
            </div>
            <div class="modal-footer">
            <button type="submit" class="btn btn-primary btn-sm" name="update">
              <i class="fa fa-dot-circle-o"></i> Update
            </button>
            <button type="reset" class="btn btn-warning btn-sm">
              <i class="fa fa-ban"></i> Reset Input
            </button>
          </form>
            <button type="" class="btn btn-danger btn-sm" data-dismiss="modal" data-toggle="modal" data-target="#DEL<?php echo $modal['id']?>">
            <i class="fa fa-dot-circle-o"></i> Remove Value
            </button>

            </div>

        </div>
      </div>
    </div>
  </div>
<?php }?>
<?php foreach($allCombine as $del) {?>
  <div class="modal fade" id="DEL<?php echo $del['id']?>" role="dialog">
    <div class="modal-dialog">

    <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Confirm</h4>
        </div>
        <form action="<?php echo base_url('RM_Main/fltr')?>" method="post" enctype="multipart/form-data" class="form-horizontal">
          <input type="text" name="id" class="form-control" value="<?php echo $del['id']?>" required hidden="">
          <div class="modal-body">
            <p>remove payload id : <?php echo $del['payload_id']?></p>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary btn-sm" name="remove">
            <i class="fa fa-dot-circle-o"></i> Yes
            </button>
            <button type="reset" class="btn btn-danger btn-sm" data-dismiss="modal">
            <i class="fa fa-ban"></i> No
            </button>
        </form>
          </div>
      </div>

    </div>
  </div>
<?php }?>
