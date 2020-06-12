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
          <div class="table-responsive m-b-40">
              <table class="table table-borderless table-data3">
                  <thead>
                      <tr>
                          <th>Date</th>
                          <th>Activity</th>
                          <th>User</th>
                          <th>Payload</th>
                          <th>Result</th>
                          <th>Http_Stat</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php foreach ($log_data as $rec) { ?>
                        <tr data-toggle="modal" data-target="#<?php echo $rec->log_id?>" id="appadd">
                            <td><?php echo $rec->date?></td>
                            <td><?php echo $rec->act?></td>
                            <td><?php echo $rec->username?></td>
                            <td><?php echo $rec->req?></td>
                            <td>[show response]</td>
                            <?php if($rec->stat == '200'){?>
                              <td class="process"><?php echo $rec->stat?></td>
                            <?php } else {?>
                            <td class="denied"><?php echo $rec->stat?></td>
                            <?php }?>
                        </tr>
                      <?php }?>
                  </tbody>
              </table>

          </div>
          <?php echo $pagination?>
        </div>
    </div>
    <?php foreach ($log_data as $mod) { ?>
    <!-- Modal -->
    <div class="modal fade" id="<?php echo $mod->log_id?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document" style="max-width: 60%;">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Activity Log ID #<?php echo $mod->log_id?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="card" style="border: none;">
              <div class="card-body">
                <div class="form-group">
                    <label for="inputText3" class="col-form-label"><h5>Date :</h5></label>
                    <p class="d-inline"><?php echo $mod->date?></p>
                </div>
                <div class="form-group">
                    <label for="inputText3" class="col-form-label"><h5>Activity :</h5></label>
                    <p class="d-inline"><?php echo $mod->act?></p>
                </div>
                <div class="form-group">
                    <label for="inputText3" class="col-form-label"><h5>User :</h5></label>
                    <p class="d-inline"><?php echo $mod->username?></p>
                </div>
                <div class="form-group">
                    <label for="inputText3" class="col-form-label"><h5>Request Payload :</h5></label>
                    <p class="d-inline"><?php echo $mod->req?></p>
                </div>
                <div class="form-group">
                    <label for="inputText3" class="col-form-label"><h5>Response Payload :</h5></label>
                    <p class="d-inline"><?php echo $mod->res?></p>
                </div>
                <div class="form-group">
                    <label for="inputText3" class="col-form-label"><h5>Status :</h5></label>
                    <p class="d-inline"><?php echo $mod->stat?></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php }?>

</div>
