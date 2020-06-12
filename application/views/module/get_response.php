<div class="card">
    <div class="card-header">
        <strong class="card-title">JSON Response
            <small>
                <?php
                  if($stat!='success')
                    $badge = 'danger';
                  else
                    $badge = 'success'
                ?>
                <span class="badge badge-<?php echo $badge?> float-right mt-1" style="font-size:15px"><?php print_r($test_out);?></span>
            </small>
        </strong>
    </div>
    <div class="card-body">
		<p class="card-text">
		<?php
			//echo http_response_code($test_out);
			//print_r($test_outin);
			$tab = "  "; 
			$new_json = ""; 
			$indent_level = 0; 
			$in_string = false; 

			$curl_obj = json_decode($curl); 

			if($curl_obj === false) 
				return false; 

			$curl = json_encode($curl_obj); 
			$len = strlen($curl); 

			for($c = 0; $c < $len; $c++) 
			{ 
				$char = $curl[$c]; 
				switch($char) 
				{ 
					case '{': 
					case '[': 
						if(!$in_string) 
						{ 
							$new_json .= $char . "<br>" . str_repeat($tab, $indent_level+1); 
							$indent_level++; 
						} 
						else 
						{ 
							$new_json .= $char; 
						} 
						break; 
					case '}': 
					case ']': 
						if(!$in_string) 
						{ 
							$indent_level--; 
							$new_json .= "<br>" . str_repeat($tab, $indent_level) . $char; 
						} 
						else 
						{ 
							$new_json .= $char; 
						} 
						break; 
					case ',': 
						if(!$in_string) 
						{ 
							$new_json .= ",<br>" . str_repeat($tab, $indent_level); 
						} 
						else 
						{ 
							$new_json .= $char; 
						} 
						break;
					case ':': 
						if(!$in_string) 
						{ 
							$new_json .= ": "; 
						} 
						else 
						{ 
							$new_json .= $char; 
						} 
						break; 
					case '"': 
						if($c > 0 && $curl[$c-1] != '\\') 
						{ 
							$in_string = !$in_string; 
						} 
					default: 
						$new_json .= $char; 
						break;                    
				} 
			} 

			$json_out = str_replace("\/","/",$new_json);
			if($json_out == 'null')
				echo $raw_curl;
			else
				echo $json_out;
		
		?> 
		
		</p>
    </div>
</div>
<br>
