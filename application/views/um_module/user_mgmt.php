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
              <h3 class="title-1">User Management</h3>
              <button class="au-btn au-btn-icon au-btn--blue" data-toggle="modal" data-target="#new">
                  <i class="zmdi zmdi-plus"></i>add user
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
                          <th>Username</th>
                          <th>Password</th>
                          <th>Display Name</th>
                          <th>Role</th>
                          <th>Allowed</th>
                          <th>Secure Key</th>
                      </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($allUser as $user) {?>
                      <tr data-toggle="modal" data-target="#<?php echo $user->username?>" id="appadd">
                            <td><?php echo $user->username?></td>
                            <td>*****</td>
                            <td><?php echo $user->dsply_name?></td>
                            <td><?php echo $user->role?></td>
                            <td><?php echo $user->allowed?></td>
                            <td class="process"><?php echo $user->scr_key?></td>
                      </tr>
                    <?php }?>

                  </tbody>
              </table>
              <?php echo $pagination?>
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
          <strong>Profile Editor</strong>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
              <form action="<?php echo base_url('UM_Main/usr_mgmt')?>" method="post" enctype="multipart/form-data" class="form-horizontal">
                  <div class="row form-group">
                      <div class="col col-md-3">
                          <label for="text-input" class=" form-control-label">Username</label>
                      </div>
                      <div class="col-12 col-md-9">
                          <input type="text" name="username" class="form-control" value="" required>
                      </div>
                  </div>
                  <div class="row form-group">
                      <div class="col col-md-3">
                          <label for="password-input" class=" form-control-label">Password</label>
                      </div>
                      <div class="col-12 col-md-9">
                          <input type="password" name="password" class="form-control" value="" required>
                      </div>
                  </div>
                  <div class="row form-group">
                      <div class="col col-md-3">
                          <label for="text-input" class=" form-control-label">Display Name</label>
                      </div>
                      <div class="col-12 col-md-9">
                          <input type="text" id="text-input" name="dsply_name" class="form-control" required>
                      </div>
                  </div>
                  <div class="row form-group">
                      <div class="col col-md-3">
                          <label for="select" class=" form-control-label">Role</label>
                      </div>
                      <div class="col-12 col-md-9">
                          <select name="role" id="select" class="form-control">
                              <option value="sysadm">sysadm</option>
                              <option value="usr">usr</option>
                          </select>
                      </div>
                  </div>
                  <div class="row form-group">
                    <div class="col col-md-3">
                        <label for="multiple-select" class=" form-control-label">Allowed</label>
                    </div>
                    <div class="col col-md-9">
                        <select name="allowed[]" id="multiple-select" multiple="" class="form-control">
                          <?php foreach ($allAct as $act) {?>
                            <option value="<?php echo $act->act?>"><?php echo $act->act?></option>
                          <?php }?>
                        </select>
                    </div>
                  </div>
                  <div class="row form-group">
                      <div class="col col-md-3">
                          <label for="text-input" class=" form-control-label">Secure Key</label>
                      </div>
                      <div class="col-12 col-md-9">
                          <input type="text" id="text-input" placeholder="input random value" name="scr_key" class="form-control">
                      </div>
                  </div>
              </div>

        <div class="modal-footer">
          <button type="submit" name="submit" class="btn btn-primary btn-sm">
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
    <!-- Modal -->
    <?php foreach ($allUser as $user) {?>
    <div class="modal fade" id="<?php echo $user->username?>" role="dialog">
      <div class="modal-dialog" style="max-width:50%">
        <!-- Modal content-->
        <div class="modal-content" >
          <div class="modal-header">
            <strong>Profile Editor</strong>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
                <form action="<?php echo base_url('UM_Main/usr_mgmt')?>" method="post" enctype="multipart/form-data" class="form-horizontal">
                  <input type="text" id="text-input" name="username_id" class="form-control" value="<?php echo $user->username?>" required hidden>
                    <div class="row form-group">
                        <div class="col col-md-3">
                            <label for="text-input" class=" form-control-label">Username</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <input type="text" id="text-input" name="username" class="form-control" value="<?php echo $user->username?>" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col col-md-3">
                            <label for="password-input" class=" form-control-label">Password</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <input type="password" id="password-input" name="password" class="form-control" value="<?php echo $user->password?>" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col col-md-3">
                            <label for="text-input" class=" form-control-label">Display Name</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <input type="text" id="text-input" name="dsply_name" class="form-control" value="<?php echo $user->dsply_name?>" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col col-md-3">
                            <label for="select" class=" form-control-label">Role</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <select name="role" id="select" class="form-control">
                                <option value="sysadm" <?php echo ($user->role=='sysadm') ? 'selected':'' ?>>sysadm</option>
                                <option value="usr" <?php echo ($user->role=='usr') ? 'selected':'' ?>>usr</option>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                      <div class="col col-md-3">
                          <label for="multiple-select" class=" form-control-label">Allowed</label>
                      </div>
                      <div class="col col-md-9">
                          <select name="allowed[]" id="multiple-select" multiple="" class="form-control">
                            <?php
                              $allowed = explode('|',$user->allowed);
                            ?>
                            <?php foreach ($allAct as $act) {?>
                              <option value="<?php echo $act->act?>" <?php echo (in_array($act->act,$allowed))?'selected':''?>><?php echo $act->act?></option>
                            <?php }?>
                          </select>
                      </div>
                    </div>
                    <div class="row form-group">
                        <div class="col col-md-3">
                            <label for="text-input" class=" form-control-label">Secure Key</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <input type="text" id="text-input" placeholder="input random value" name="scr_key" class="form-control" value="<?php echo $user->scr_key?>">
                        </div>
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
            <button type="" class="btn btn-danger btn-sm" data-dismiss="modal" data-toggle="modal" data-target="#DEL<?php echo $user->username?>">
                <i class="fa fa-dot-circle-o"></i> Remove User
            </button>

          </div>

        </div>
      </div>
    </div>
  <?php }?>
  <?php foreach ($allUser as $user) {?>
  <div class="modal fade" id="DEL<?php echo $user->username?>" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Confirm</h4>
        </div>
        <form action="<?php echo base_url('UM_Main/usr_mgmt')?>" method="post" enctype="multipart/form-data" class="form-horizontal">
          <input type="text" id="text-input" name="username_id" class="form-control" value="<?php echo $user->username?>" required hidden>
        <div class="modal-body">
          <p>remove user : <?php echo $user->username?></p>


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
  <?php } ?>
</div>
