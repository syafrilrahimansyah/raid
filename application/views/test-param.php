<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
          <div class="card">
                  <div class="card-header">
                      <strong>Curl</strong> Order Validation
                  </div>
                  <div class="card-body card-block">
                      <form action="<?php echo base_url('test/test_param');?>" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <input type="text" id="text-input" name="act_id" class="form-control" value="ov" hidden="">
                        <input type="text" id="text-input" name="param" class="form-control" value="1" hidden="">
						<input type="text" id="text-input" name="header" class="form-control" value="1" hidden="">
                        <input type="text" id="text-input" name="0#language" class="form-control" value="id" hidden="">
						<div class="row form-group">
                            <div class="col col-md-3">
                                <label class=" form-control-label">Environment</label>
                            </div>
                            <div class="col col-md-9">
                                <div class="form-check-inline form-check">
                                    <label for="inline-radio4" class="form-check-label " style="margin-right:10px">
                                        <input type="radio" id="inline-radio4" name="1#env" value="prod" class="form-check-input" <?php if(isset($_POST['1#env'])) echo $_POST['1#env']=='prod' ? 'checked="checked"' : '' ?> >Prod
                                    </label>
                                    <label for="inline-radio5" class="form-check-label " style="margin-right:10px">
                                        <input type="radio" id="inline-radio5" name="1#env" value="preprod" class="form-check-input" <?php if(isset($_POST['1#env'])) echo $_POST['1#env']=='preprod' ? 'checked="checked"' : '' ?> >Preprod
                                    </label>
                                </div>
                            </div>
                          </div>
                          <div class="row form-group">
                              <div class="col col-md-3">
                                  <label class=" form-control-label">Channel ID</label>
                              </div>
                              <div class="col col-md-9">
                                  <div class="form-check-inline form-check">
                                      <label for="inline-radio4" class="form-check-label " style="margin-right:10px">
                                          <input type="radio" id="inline-radio4" name="channel_id" value="CHANNELID: UX" class="form-check-input" <?php if(isset($_POST['channel_id'])) echo $_POST['channel_id']=='UX' ? 'checked="checked"' : '' ?> >UX
                                      </label>
                                      <label for="inline-radio5" class="form-check-label " style="margin-right:10px">
                                          <input type="radio" id="inline-radio5" name="channel_id" value="VMP" class="form-check-input" <?php if(isset($_POST['channel_id'])) echo $_POST['channel_id']=='VMP' ? 'checked="checked"' : '' ?>>VMP
                                      </label>
                                      <label for="inline-radio6" class="form-check-label " style="margin-right:10px">
                                          <input type="radio" id="inline-radio6" name="channel_id" value="WEB" class="form-check-input" <?php if(isset($_POST['channel_id'])) echo $_POST['channel_id']=='WEB' ? 'checked="checked"' : '' ?>>WEB
                                      </label>
                                      <label for="inline-radio7" class="form-check-label " style="margin-right:10px">
                                          <input type="radio" id="inline-radio7" name="channel_id" value="CMS" class="form-check-input" <?php if(isset($_POST['channel_id'])) echo $_POST['channel_id']=='CMS' ? 'checked="checked"' : '' ?>>CMS
                                      </label>
                                      <label for="inline-radio8" class="form-check-label " style="margin-right:10px">
                                          <input type="radio" id="inline-radio8" name="channel_id" value="DSC" class="form-check-input" <?php if(isset($_POST['channel_id'])) echo $_POST['channel_id']=='DSC' ? 'checked="checked"' : '' ?>>DSC
                                      </label>
                                      <label for="inline-radio9" class="form-check-label " style="margin-right:10px">
                                          <input type="radio" id="inline-radio9" name="channel_id" value="UMB" class="form-check-input" <?php if(isset($_POST['channel_id'])) echo $_POST['channel_id']=='UMB' ? 'checked="checked"' : '' ?>>UMB
                                      </label>
                                  </div>
                              </div>
                          </div>
                          <div class="row form-group">
                              <div class="col col-md-3">
                                  <label for="text-input" class=" form-control-label">Transaction ID</label>
                              </div>
                              <div class="col-12 col-md-9">
                                  <input type="text" id="text-input" name="3#transaction_id" placeholder="ex : test0001" class="form-control" value="<?php echo isset($_POST['3#transaction_id']) ? $_POST['3#transaction_id'] : '' ?>">
                                  <small class="form-text text-muted">please input unique trx_id to to ease checks on kibana</small>
                              </div>
                          </div>
                          <div class="row form-group">
                              <div class="col col-md-3">
                                  <label for="text-input" class=" form-control-label">MSISDN A</label>
                              </div>
                              <div class="col-12 col-md-9">
                                  <input type="text" id="text-input" name="service_id_a" placeholder="ex : 6281234567890" class="form-control" value="<?php echo isset($_POST['service_id_a']) ? $_POST['service_id_a'] : '' ?>">
                              </div>
                          </div>
						  <div class="row form-group">
                              <div class="col col-md-3">
                                  <label for="text-input" class=" form-control-label">MSISDN B (GIFT)</label>
                              </div>
                              <div class="col-12 col-md-9">
                                  <input type="text" id="text-input" name="3#service_id_b" placeholder="ex : 6281234567890" class="form-control" value="<?php echo isset($_POST['3#service_id_b']) ? $_POST['3#service_id_b'] : '' ?>">
                              </div>
                          </div>
                          <div class="row form-group">
                              <div class="col col-md-3">
                                  <label for="text-input" class=" form-control-label">Product ID</label>
                              </div>
                              <div class="col-12 col-md-9">
                                  <input type="text" id="text-input" name="3#product_id" placeholder="ex : 00008364" class="form-control" value="<?php echo isset($_POST['3#product_id']) ? $_POST['3#product_id'] : '' ?>">
                              </div>
                          </div>
                          <div class="row form-group">
                              <div class="col col-md-3">
                                  <label class=" form-control-label">Purchase Mode</label>
                              </div>
                              <div class="col col-md-9">
                                  <div class="form-check-inline form-check">
                                      <label for="inline-radio1" class="form-check-label " style="margin-right:10px">
                                          <input type="radio" id="inline-radio1" name="3#purchase_mode" value="SELF" class="form-check-input" <?php if(isset($_POST['3#purchase_mode'])) echo $_POST['3#purchase_mode']=='SELF' ? 'checked="checked"' : '' ?>>SELF
                                      </label>
                                      <label for="inline-radio2" class="form-check-label " style="margin-right:10px">
                                          <input type="radio" id="inline-radio2" name="3#purchase_mode" value="GIFT" class="form-check-input" <?php if(isset($_POST['3#purchase_mode'])) echo $_POST['3#purchase_mode']=='GIFT' ? 'checked="checked"' : '' ?>>GIFT
                                      </label>
                                      <label for="inline-radio3" class="form-check-label " style="margin-right:10px">
                                          <input type="radio" id="inline-radio3" name="3#purchase_mode" value="ASK" class="form-check-input" <?php if(isset($_POST['3#purchase_mode'])) echo $_POST['3#purchase_mode']=='ASK' ? 'checked="checked"' : '' ?>>ASK
                                      </label>
                                  </div>
                              </div>
                          </div>
                          <div class="row form-group">
                              <div class="col col-md-3">
                                  <label class=" form-control-label">Purchase Type</label>
                              </div>
                              <div class="col col-md-9">
                                  <div class="form-check-inline form-check">
                                      <label for="inline-radio4" class="form-check-label " style="margin-right:10px">
                                          <input type="radio" id="inline-radio4" name="3#is_subscription" value="0" class="form-check-input" <?php if(isset($_POST['3#is_subscription'])) echo $_POST['3#is_subscription']=='0' ? 'checked="checked"' : '' ?>>One Time
                                      </label>
                                      <label for="inline-radio5" class="form-check-label " style="margin-right:10px">
                                          <input type="radio" id="inline-radio5" name="3#is_subscription" value="1" class="form-check-input" <?php if(isset($_POST['3#is_subscription'])) echo $_POST['3#is_subscription']=='1' ? 'checked="checked"' : '' ?>>Subscribe
                                      </label>
                                  </div>
                              </div>
                          </div>
                          <div class="row form-group">
                              <div class="col col-md-3">
                                  <label class=" form-control-label">Order Type</label>
                              </div>
                              <div class="col col-md-9">
                                  <div class="form-check-inline form-check">
                                      <label for="inline-radio6" class="form-check-label " style="margin-right:10px">
                                          <input type="radio" id="inline-radio6" name="3#order_type" value="ACT" class="form-check-input" <?php if(isset($_POST['3#order_type'])) echo $_POST['3#order_type']=='ACT' ? 'checked="checked"' : '' ?>>Activation
                                      </label>
                                      <label for="inline-radio7" class="form-check-label " style="margin-right:10px">
                                          <input type="radio" id="inline-radio7" name="3#order_type" value="DEACT" class="form-check-input" <?php if(isset($_POST['3#order_type'])) echo $_POST['3#order_type']=='DEACT' ? 'checked="checked"' : '' ?>>Deactivation
                                      </label>
                                  </div>
                              </div>
                          </div>
                          <div class="row form-group">
                              <div class="col col-md-3">
                                  <label class=" form-control-label">Payment Method</label>
                              </div>
                              <div class="col col-md-9">
                                  <div class="form-check-inline form-check">
                                      <label for="inline-radio6" class="form-check-label " style="margin-right:10px">
                                          <input type="radio" id="inline-radio6" name="3#payment_method" value="BALANCE" class="form-check-input" <?php if(isset($_POST['3#payment_method'])) echo $_POST['3#payment_method']=='BALANCE' ? 'checked="checked"' : '' ?>>Balance
                                      </label>
                                      <label for="inline-radio7" class="form-check-label " style="margin-right:10px">
                                          <input type="radio" id="inline-radio7" name="3#payment_method" value="BILLING" class="form-check-input" <?php if(isset($_POST['3#payment_method'])) echo $_POST['3#payment_method']=='BILLING' ? 'checked="checked"' : '' ?>>Billing
                                      </label>
                                  </div>
                              </div>
                          </div>
                          <div class="row form-group">
                              <div class="col col-md-3">
                                  <label for="select" class=" form-control-label">Channel</label>
                              </div>
                              <div class="col-12 col-md-9">
                                  <select name="3#channel" id="select" class="form-control">
                                      <option value="i1">i1 - MyTsel</option>
                                      <option value="f0">f0 - Traditional</option>
                                      <option value="i5">i5 - VMP</option>
                                      <option value="a0">a0 - MyTsel Web</option>
                                      <option value="p0">p0 - Campaign</option>
                                      <option value="k4">k4 - DSC</option>
									  <option value="e0">e0 - MKIOS</option>
                                  </select>
                              </div>
                          </div>

                  </div>
                  <div class="card-footer">
                      <input type="submit" id="sendbutton" class="btn btn-primary btn-sm" name="submit" value="submit"></input>
                    	<button type="button" onclick="reset()" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-ban"></i> Clear Form</button>
                  </div>
                  </form>
              </div>
            </div>
            <?php if($x == 1)$this->load->view('module/curl_response')?>

    </div>




</div>
