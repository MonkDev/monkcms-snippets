<?php

	function cache_data($options){

		// defaults
		$cache_dir	= '/_cache/';
		$mode				= (isset($options['mode']) ? $options['mode'] : 'read');
		$expire			= (isset($options['expire']) ? $options['expire'] : 'weekly');
		$data				= (isset($options['data']) ? $options['data'] : '');

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

		// create catalog
		$cat_filename = 'catalog.json';
		$cat_filepath = $path . '/' . $cat_filename;
		if(!file_exists($cat_filepath)){
			file_put_contents($cat_filepath, '');
		}

		// set expiration date
		if(	$expire=='hourly'	)	{ $expire_date = date('Y-m-d H:00:00'); }
		if(	$expire=='nightly')	{ $expire_date = date('Y-m-d'); }
		if(	$expire=='weekly'	)	{ $expire_date = date('Y-m-d', strtotime('this week',time())); }
		if(	$expire=='monthly')	{ $expire_date = date('Y-m'); }

		// check expiration date
		$cache_expired = true;
		$cache_dates = file_get_contents($cat_filepath);
		$cache_dates = json_decode($cache_dates, true);
		if($cache_dates && isset($cache_dates['last_cached'])){
			if(strtotime($cache_dates['last_cached']) >= strtotime($expire_date)){
				$cache_expired = false;
			}
		}

		// store data
		$cache_filename = 'data.txt';
		$cache_filepath = $path . '/' . $cache_filename;
		if(!file_exists($cache_filepath)){
			file_put_contents($cache_filepath, '');
		}

		// mode: read
		if($mode=='read'){
			if(!$cache_expired){
				$cache_data = file_get_contents($cache_filepath);
				return $cache_data;
			} else {
				return false;
			}
		}

		// mode: write
		if($mode=='write'){
			if($cache_expired){
				file_put_contents($cache_filepath, $data);
				$dates['last_cached'] = $expire_date;
				$dates = json_encode($dates);
				file_put_contents($cat_filepath, $dates);
				return $data;
			}	else {
				return false;
			}
		}

	}

?>