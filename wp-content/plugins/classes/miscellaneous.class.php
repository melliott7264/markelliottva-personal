<?php
/*
 * This class exists on Central Server too
 */
if (!class_exists('WPPostsRateKeys_Miscellaneous')) {
	class WPPostsRateKeys_Miscellaneous
	{
		static function endsWith($haystack, $needle) {
		    $length = strlen($needle);
		    $start  = $length * -1; //negative
		    return (substr($haystack, $start) === $needle);
		}
		
		static function dir_tree($dir) {
			   $path = '';
			   $stack[] = $dir;
			   while ($stack) {
			       $thisdir = array_pop($stack);
			       if ($dircont = scandir($thisdir)) {
			           $i=0;
			           while (isset($dircont[$i])) {
			               if ($dircont[$i] !== '.' && $dircont[$i] !== '..') {
			                   $current_file = "{$thisdir}/{$dircont[$i]}";
			                   if (is_file($current_file)) {
			                       $path[] = "{$thisdir}/{$dircont[$i]}";
			                   } elseif (is_dir($current_file)) {
			                        $path[] = "{$thisdir}/{$dircont[$i]}";
			                       $stack[] = $current_file;
			                   }
			               }
			               $i++;
			           }
			       }
			   }
			   return $path;
			}
		
		/**
         * Return all the parameters by GEt in current page URL
         *
         * @static
         * @param 	string	$param_except	is defined, exclude this parameter
         * @return 	array
         */
		static function get_parameters_by_get($param_except='')
		{
			$query_parameters = array();
			
			if ($_SERVER['QUERY_STRING']!='') {
				$query_arr = explode('&',$_SERVER['QUERY_STRING']);
				foreach ($query_arr as $query_item) {
					$query_item_arr = explode('=',$query_item);
					$query_item_name = $query_item_arr[0];
					if ($query_item_name!='') {			
						if (isset($query_item_arr[1]))
							$query_item_value = $query_item_arr[1];
						else 
							$query_item_value = '';
							
						if ($param_except!=$query_item_name)
							$query_parameters[$query_item_name] = $query_item_value;
					}
				}
			}
			return $query_parameters;
		}
		
		/**
         * Return the current page URL with query string
         *
         * @static
         * @param 	array	$param_except	is defined, exclude the parameters form the query string
         * @return 	string
         */
		static function get_current_url($param_except=array())
		{
			$to_return = 'http';
			
			if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")
				$to_return .= "s";
			
			$to_return .= '://' . $_SERVER["SERVER_NAME"];
			
			if ($_SERVER["SERVER_PORT"] != "80")
				$to_return .=  ':' . $_SERVER["SERVER_PORT"];
			
			if (substr_count($_SERVER["REQUEST_URI"],'?')>0)
				$to_return .= substr($_SERVER["REQUEST_URI"], 0, strpos($_SERVER["REQUEST_URI"], '?'));
			else 
				$to_return .= $_SERVER["REQUEST_URI"];
			
			if (count($param_except)>0) {
				$query_parameters = array();
				$query_arr = explode('&',$_SERVER['QUERY_STRING']);
				foreach ($query_arr as $query_item) {
					$query_item_arr = explode('=',$query_item);
					$query_item_name = $query_item_arr[0];
					
					if (!in_array($query_item_name,$param_except))
						$query_parameters[] = join('=',$query_item_arr);
				}
				$query_parameters_str = join('&',$query_parameters);
			}
			else 
				$query_parameters_str = $_SERVER['QUERY_STRING'];
			
			if ($query_parameters_str!='')
				$to_return .= '?' . $query_parameters_str;
			
			return $to_return;
		}
		
        /**
         * Function to sort Array data
         *
         * @static 
         * @param array $array to be sorted
         * @param string $by key of the values to be to sort (the associative array name that is one level deep)
         * @param string $order can be "ASC" or "DESC"
         * @param string $type can be "num" or "str"
         * @return array
         */
        static function sortmddata($array, $by, $order, $type) {
			if (is_array($array)) {

				$sortby = "sort$by"; //This sets up what you are sorting by
				
				$firstval = current($array); //Pulls over the first array
				if (is_array($firstval)) {
					$vals = array_keys($firstval); //Grabs the associate Arrays
					
					foreach ($vals as $init) {
					   $keyname = "sort$init";
					   $$keyname = array();
					}
					//This was strange because I had problems adding
					//Multiple arrays into a variable variable
					//I got it to work by initializing the variable variables as arrays
					//Before I went any further
					foreach ($array as $key => $row) {
						foreach ($vals as $names) {
						   $keyname = "sort$names";
						   $test = array();
						   $test[$key] = @strtolower($row[$names]); // @ is for the case the value is an array, in this case we will ignore this
						   $$keyname = array_merge($$keyname,$test);
						   
						}
					}
			
					//This will create dynamic mini arrays so that I can perform
					//the array multisort with no problem
					//Notice the temp array... I had to do that because I 
					//cannot assign additional array elements to a 
					//varaiable variable            
					
					if ($order == "DESC") {  
						if ($type == "num"){
							array_multisort($$sortby,SORT_DESC, SORT_NUMERIC,$array);
						} 
						else {
							array_multisort($$sortby,SORT_DESC, SORT_STRING,$array);
						}
					}
					else {
						if ($type == "num"){
							array_multisort($$sortby,SORT_ASC, SORT_NUMERIC,$array);
						} 
						else {
							if ($$sortby!=NULL)
								array_multisort($$sortby,SORT_ASC, SORT_STRING,$array);
						}
					}
			
					//This just goed through and asks the additional arguments
					//What they are doing and are doing variations of
					//the multisort
					return $array;
				}
			}
			return array();
		}
	}
}