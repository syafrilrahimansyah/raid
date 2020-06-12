<div class="card">
    <div class="card-header">
        <strong class="card-title">JSON Response
            <small>
				<?php
                  if($stub!='success')
                    $badge = 'danger';
                  else
                    $badge = 'success'
                ?>
                <span class="badge badge-<?php echo $badge?> float-right mt-1"><?php echo $test_out?></span>
            </small>
        </strong>
    </div>
    <div class="card-body">
        <p class="card-text">
			<?php
				print_r($raw_curl);
				//print_r($test_outin);
			?>
        </p>
    </div>
</div>
<br>
