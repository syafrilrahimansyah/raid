<div class="main-content">
  <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card">
                <div class="card-header">
                    <strong>Curl</strong> <?php echo $rp_conf['title']?>
                </div>
                <div class="card-body card-block">
                    <form action="<?php echo base_url('main/c_flex_post/'.$rp_conf['id']);?>" method="post" enctype="multipart/form-data" class="form-horizontal">
                      <input type="text" id="text-input" name="act_id" class="form-control" value="<?php echo $rp_conf['act_id']?>" hidden="">
                      <input type="text" id="text-input" name="param" class="form-control" value="<?php echo $rp_conf['param']?>" hidden="">
					            <input type="text" id="text-input" name="header" class="form-control" value="<?php echo $rp_conf['header']?>" hidden="">
                      <input type="text" id="text-input" name="0#language" class="form-control" value="id" hidden="">
                      <input type="text" id="text-input" name="url_add_path" class="form-control" value="<?php echo $rp_conf['url_add_path']?>" hidden="">

                        <?php
                        foreach ($rp as $val) {
                          if($val['exst']){
                            $metadata = [
                              'title' => $val['title'],
                              'name' => $val['name'],
                              'member' => $val['member'],
                              'plc_hldr' => $val['plc_hldr'],
                              'dsc' => $val['dsc'],
                              'value' => $val['value']
                            ];
                            $this->load->view('lookup_module/'.$val['dsply_tp'],$metadata);
                          }
                        }
                        ?>
                </div>
                <div class="card-footer">
                    <input type="submit" id="sendbutton" class="btn btn-primary btn-sm" name="submit" value="submit"></input>
                  	<button type="button" onclick="reset()" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-ban"></i> Clear Form</button>
                </div>
                </form>
            </div>
          </div>
          <?php if($x == 1)$this->load->view('module/put_response')?>
  </div>
</div>
