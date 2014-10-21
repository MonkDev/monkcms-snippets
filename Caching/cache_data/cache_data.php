<?php


	function cache_data($options){

		// defaults
		$cache_dir = '/_cache/';
		$mode = (isset($options['mode']) ? $options['mode'] : 'read');
		$expire = (isset($options['expire']) ? $options['expire'] : 'nightly');
		$data = (isset($options['data']) ? $options['data'] : '');

		// create file tree
		$cache_path	= rtrim($_SERVER['DOCUMENT_ROOT'],'/') . '/' . trim($cache_dir,'/');
		if(isset($options['path'])){
			$path = $cache_path . '/' . trim($options['path'], '/');
		} else {
			$path = $cache_path;
		}
		if(!file_exists($path)){
			mkdir($path, 0775, true);
		}

		// create catalog file
		$catalog_filename = 'catalog.json';
		$catalog_filepath = $path . '/' . $catalog_filename;
		if(!file_exists($catalog_filepath)){
			file_put_contents($catalog_filepath, '');
		}

		// create data file
		$cache_filename = 'data.txt';
		$cache_filepath = $path . '/' . $cache_filename;
		if(!file_exists($cache_filepath)){
			file_put_contents($cache_filepath, '');
		}

		// set expiration date
		if($expire=='minute'){ $date = date('Y-m-d H:i:00'); }
		if($expire=='hourly'){ $date = date('Y-m-d H:00:00'); }
		if($expire=='nightly'){ $date = date('Y-m-d'); }
		if($expire=='weekly'){ $date = date('Y-m-d', strtotime('this week',time())); }
		if($expire=='monthly'){ $date = date('Y-m'); }

		// check if cache is expired
		$cache_expired = true;
		$catalog = json_decode(file_get_contents($catalog_filepath),true);
		if($catalog){
			if(strtotime($catalog['date']) >= strtotime($date) && $catalog['expire']==$expire){
				$cache_expired = false;
			}
		}

		// mode: read
		if($mode=='read'){
			if($cache_expired===false){
				$cache_data = file_get_contents($cache_filepath);
				return $cache_data;
			} else {
				return false;
			}
		}

		// mode: write
		if($mode=='write'){
			if($cache_expired===true){
				$catalog = json_encode(array(
					'expire' => $expire,
					'date' => $date,
					'last_cached' => date('r')
				));
				file_put_contents($catalog_filepath, $catalog);
				file_put_contents($cache_filepath, $data);
				return $data;
			}	else {
				return false;
			}
		}

	}

?>