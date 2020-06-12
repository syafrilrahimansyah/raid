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
        <h3 class="title-1">[<?php echo $payload_id?>] Group</h3>
        <button class="au-btn au-btn-icon au-btn--blue" data-toggle="modal" data-target="#new-inp">
        <i class="zmdi zmdi-plus"></i>add input
        </button>
        <button class="au-btn au-btn-icon au-btn--blue" data-toggle="modal" data-target="#new-look">
        <i class="zmdi zmdi-plus"></i>add lookup
        </button>
        <button class="au-btn au-btn-icon au-btn--blue" data-toggle="modal" data-target="#gen-log">
        <i class="zmdi zmdi-plus"></i>generate log
        </button>
      </div>
      <br>
      <div class="table-responsive m-b-40">
        <table class="table table-borderless table-data3">
          <thead>
            <tr>
              <th>ID</th>
              <th>Type</th>
              <th>Group Name</th>
              <th>Title</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if($allValue!=['']){
            foreach ($allValue as $value) {?>
                <tr data-toggle="modal" data-target="#<?php echo $value[2]?>" id="appadd">
                  <td><?php echo (isset($value[1]->id))?$value[1]->id:''?></td>

                  <?php
                    if(isset($value[0])){
                      if($value[0]=='i'){
                        echo '<td>Input</td>';
                      }
                      elseif ($value[0]=='h') {
                        echo '<td>Hidden Input</td>';
                      }
                      else{
                        echo '<td>Lookup</td>';
                      }
                    }
                  ?>
                  <td><?php echo (isset($value[1]->name))?$value[1]->name:''?></td>
                  <td><?php echo (isset($value[1]->title))?$value[1]->title:''?></td>
                </tr>
            <?php }}?>
          </tbody>
        </table>
        <?php //echo $pagination?>
      </div>
    </div>
  </div>
</div>
<!-- ######################### Modal NEW-->
<!-- Modal -->
<div class="modal fade" id="gen-log" role="dialog">
  <div class="modal-dialog" style="max-width:50%">
  <!-- Modal content-->
    <div class="modal-content" >
      <div class="modal-header">
        <strong>Value Editor</strong>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form action="<?php echo base_url('PM_Main/req_payload_group/').$payload_id?>" method="post" enctype="multipart/form-data" class="form-horizontal">
          <input type="text" name="act_id" class="form-control" value="<?php echo $act_id?>" hidden required>
          <p>Generate log for Payload ID : <?php echo $act_id?></p>
          <div class="modal-footer">
            <button type="submit" name="genlog" class="btn btn-primary btn-sm">
            <i class="fa fa-dot-circle-o"></i> confirm
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="new-inp" role="dialog">
  <div class="modal-dialog" style="max-width:50%">
  <!-- Modal content-->
    <div class="modal-content" >
      <div class="modal-header">
        <strong>Value Editor</strong>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form action="<?php echo base_url('PM_Main/req_payload_group/').$payload_id?>" method="post" enctype="multipart/form-data" class="form-horizontal">
          <input type="text" name="act_id" class="form-control" value="<?php echo $act_id?>" hidden required>
          <div class="row form-group">
              <div class="col col-md-3">
                  <label for="text-input" class=" form-control-label">Assign as</label>
              </div>
              <div class="col-12 col-md-9">
                  <select name="group[]" id="select" class="form-control">
                      <option value="i">Input</option>
                      <option value="h">Hidden Input</option>
                  </select>
              </div>
          </div>
          <div class="row form-group">
              <div class="col col-md-3">
                  <label for="text-input" class=" form-control-label">Select Group ID</label>
              </div>
              <div class="col-12 col-md-9">
                  <select name="group[]" id="select" class="form-control">
                    <?php foreach ($InpGroup as $group) {?>

                      <option value="<?php echo $group->id?>"><?php echo $group->id.' - '.$group->name?></option>
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
<div class="modal fade" id="new-look" role="dialog">
  <div class="modal-dialog" style="max-width:50%">
  <!-- Modal content-->
    <div class="modal-content" >
      <div class="modal-header">
        <strong>Value Editor</strong>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form action="<?php echo base_url('PM_Main/req_payload_group/').$payload_id?>" method="post" enctype="multipart/form-data" class="form-horizontal">
          <input type="text" name="act_id" class="form-control" value="<?php echo $act_id?>" hidden required>
          <input type="text" name="group[]" class="form-control" value="l" hidden required>
          <div class="row form-group">
              <div class="col col-md-3">
                  <label for="text-input" class=" form-control-label">Select Group ID</label>
              </div>
              <div class="col-12 col-md-9">
                  <select name="group[]" id="select" class="form-control">
                    <?php foreach ($LookGroup as $group) {?>
                      <option value="<?php echo $group->id?>"><?php echo $group->id.' - '.$group->name?></option>
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
<?php foreach($allValue as $modal) {?>
  <div class="modal fade" id="<?php echo $modal[2]?>" role="dialog">
    <div class="modal-dialog" style="max-width:50%">
    <!-- Modal content-->
      <div class="modal-content" >
        <div class="modal-header">
          <strong>Value Editor</strong>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form action="<?php echo base_url('PM_Main/req_payload_group/').$payload_id?>" method="post" enctype="multipart/form-data" class="form-horizontal">
            <input type="text" name="act_id" class="form-control" value="<?php echo $act_id?>" hidden required>
            <div class="row form-group">
                <div class="col col-md-3">
                  <label for="text-input" class=" form-control-label">Group ID</label>
                </div>
                  <p><?php echo $modal[1]->id?></p>
            </div>
            <div class="modal-footer">
              <button type="" class="btn btn-danger btn-sm" data-dismiss="modal" data-toggle="modal" data-target="#DEL<?php echo $modal[2]?>">
              <i class="fa fa-dot-circle-o"></i> Remove Value
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
<?php }?>
<?php foreach($allValue as $del) {?>
  <div class="modal fade" id="DEL<?php echo $del[2]?>" role="dialog">
    <div class="modal-dialog">

    <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Confirm</h4>
        </div>
        <form action="<?php echo base_url('PM_Main/req_payload_group/').$payload_id?>" method="post" enctype="multipart/form-data" class="form-horizontal">
          <input type="text" name="act_id" class="form-control" value="<?php echo $act_id?>" required hidden="">
          <input type="text" name="id" class="form-control" value="<?php echo $del[2]?>" required hidden="">
          <div class="modal-body">
            <p>remove value id : <?php echo $del[1]->id?></p>
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
