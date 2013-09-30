<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Example
*
* This is an example of a few basic user interaction methods you could use
* all done with a hardcoded array.
*
* @package CodeIgniter
* @subpackage Rest Server
* @category Controller
* @author Phil Sturgeon
* @link http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';
require APPPATH.'/models/db_functions.php';

class Api extends REST_Controller
{
	function user_get()
	    {
	        /*
	    	if(!$this->get('id'))
	        {
	         $this->response(NULL, 400);
	        } 
			*/
	        // $user = $this->some_model->getSomething( $this->get('id') );
	        
	        // connect to DB
	        $con = connect_db('central_db');
	        
	        
	        //var_dump($this->get('status'));
	        
	        // define query
	        $query_string = "SELECT * FROM users WHERE username = '".$this->get('username')."'";
	        //var_dump($query_string);
	        // execute query
	        $result = $con->query($query_string);
	        //var_dump($result);
	        
	        // fetch results (one or none entry)
	        while ($row = $result->fetch_array())
	        {
	        	
	        	$id = intval($row['user_id']);
	        	
	        	$users[$id] = array(
						           'id' => $id,
						           'user_team' => intval($row['user_team']),
						           'name_user' => $row['name_user'],
						           'surname_user' => $row['surname_user'],
						           'username' => $row['username'],
						           'password' => $row['password'],
						           'email' => $row['email'],
						           'amka' => $row['amka'],
						           'status' => $row['status'],
						           'department' => $row['department']
	        					   
	        	);
	        	
	        }
	        
	        
	        // increase number of queries (tracking pusposes)
	        //$query_string = "TRUNCATE `stat_activity`;";
	        //$con->query($query_string);
	        $query_string = "UPDATE `stat_activity` SET `num_of_queries`=`num_of_queries`+1, `last_happened_on`=CURRENT_TIMESTAMP;";
	        $con->query($query_string);
	        
	        // this array is the JSON root element
	        /*
		     $users = array(
				1 => array('id' => 1, 'name' => $username[1], 'email' => 'example1@example.com', 'fact' => 'Loves swimming'),
				2 => array('id' => 2, 'name' => $username[2], 'email' => 'example2@example.com', 'fact' => 'Has a huge face'),
				3 => array('id' => 3, 'name' => $username[3], 'email' => 'example3@example.com', 'fact' => 'Is a Scott!', array('hobbies' => array('jogging', 'bikes'))),
			);
			*/
			
			

		    
		    
		     $user = @$users[$id];
	    
	        if($user)
	        {
	        	
	        	$query_string = "INSERT INTO users_logged_in VALUES( ".$id." , 1 )";
	        	$query_string .= " ON DUPLICATE KEY UPDATE logged = 1";
	        	$con->query($query_string);
	        	
	            $this->response($user, 200); // 200 being the HTTP response code
	        }
	
	        else
	        {
	            $this->response(array('error' => 'User could not be found'), 404);
	        }
	    }
	    
	    
	    
	    
	    function user_post()
	    {
	        
	    	
	    	$username = $this->get('username');
			$password = $this->post('password');
			$email = $this->post('email');
			$userteam = $this->post('user_team');
			$name = $this->post('name_user');
			$surname = $this->post('surname_user');
			$amka = $this->post('amka');
			$status = $this->post('status');
			$department = $this->post('department');
			
			
	    	
	    	$con = connect_db('central_db');
	    	// main query
	    	$query_string = "UPDATE `users` SET `password`='".$password."', `email`='".$email."', `user_team`='".$userteam."', `name_user`='".$name."', `surname_user`='".$surname."', `amka`='".$amka."', `status`='".$status."', `department`='".$department."' WHERE `username`='".$username."';";
	    	$con->query($query_string);
	    	
	        // increase queries (testing purposes)
	        $query_string = "UPDATE `stat_activity` SET `num_of_queries`=`num_of_queries`+1, `last_happened_on`=CURRENT_TIMESTAMP;";
	        $con->query($query_string);
	        
	        //$this->response(array('message'=>'Updated'), 200); // 200 being the HTTP response code
	        
	        
	        // retreive updated
	        
	    	// define query
	        $query_string = "SELECT * FROM users WHERE username = '".$username."'";
	        //var_dump($query_string);
	        // execute query
	        $result = $con->query($query_string);
	        //var_dump($result);
	        
	        // fetch results (one or none entry)
	        while ($row = $result->fetch_array())
	        {
	        	
	        	$id = intval($row['user_id']);
	        	
	        	$users[$id] = array(
						           'id' => $id,
						           'user_team' => intval($row['user_team']),
						           'name_user' => $row['name_user'],
						           'surname_user' => $row['surname_user'],
						           'username' => $row['username'],
						           'password' => $row['password'],
						           'email' => $row['email'],
						           'amka' => $row['amka'],
						           'status' => $row['status'],
						           'department' => $row['department'],
	        						'message' => 'Updated'
	        					   
	        	);
	        	
	        }
	        
	        $this->response($users[$id], 200);
	        
	    }
	    
	    
	    
	    
	    
		function address_get()
	    {
	        /*
	    	if(!$this->get('id'))
	        {
	         $this->response(NULL, 400);
	        } 
			*/
	        // $user = $this->some_model->getSomething( $this->get('id') );
	        
	        // connect to DB
	        $con = connect_db('central_db');
	        
	        
	        //var_dump($this->get('status'));
	        
	        // define query
	        $query_string = "SELECT * FROM address AS a INNER JOIN users AS u ON a.user_id=u.user_id WHERE u.username = '".$this->get('username')."'";
	        //var_dump($query_string);
	        // execute query
	        $result = $con->query($query_string);
	        //var_dump($result);
	        
	        // fetch results (one or none entry)
	        while ($row = $result->fetch_array())
	        {
	        	
	        	$id = intval($row['user_id']);
	        	
	        	$address[$id] = array(
						           'id' => $id,
						           'nomos' => $row['nomos'],
						           'dimos' => $row['dimos'],
						           'city' => $row['city'],
						           'address' => $row['address'],
						           'postal_code' => $row['tk'],
						           'area' => $row['perioxi'],
						           'country' => $row['xwra']
	        					   
	        	);
	        	
	        }
	        
	        
	        // increase number of queries (tracking pusposes)
	        //$query_string = "TRUNCATE `stat_activity`;";
	        //$con->query($query_string);
	        $query_string = "UPDATE `stat_activity` SET `num_of_queries`=`num_of_queries`+1, `last_happened_on`=CURRENT_TIMESTAMP;";
	        $con->query($query_string);
	        
	        // this array is the JSON root element
	        /*
		     $users = array(
				1 => array('id' => 1, 'name' => $username[1], 'email' => 'example1@example.com', 'fact' => 'Loves swimming'),
				2 => array('id' => 2, 'name' => $username[2], 'email' => 'example2@example.com', 'fact' => 'Has a huge face'),
				3 => array('id' => 3, 'name' => $username[3], 'email' => 'example3@example.com', 'fact' => 'Is a Scott!', array('hobbies' => array('jogging', 'bikes'))),
			);
			*/
			
			

		    
		    
		     $addr = @$address[$id];
	    
	        if($addr)
	        {
	            $this->response($addr, 200); // 200 being the HTTP response code
	        }
	
	        else
	        {
	            $this->response(array('error' => 'User could not be found'), 404);
	        }
	    }
	    
	    
	    
	    
	    function address_post()
	    {
	        
	    	
	    	$username = $this->get('username');
			$nomos = $this->post('nomos');
			$dimos = $this->post('dimos');
			$city = $this->post('city');
			$address = $this->post('address');
			$postal_code = $this->post('postal_code');
			$area = $this->post('area');
			$country = $this->post('country');
			
	    	
			$con = connect_db('central_db');
			
			$query_string = "SELECT * FROM users WHERE username = '".$this->get('username')."'";
	    	$result = $con->query($query_string);
	    	
	    	$row = $result->fetch_array();
	    	$user_id = intval($row['user_id']);
			
	    	// main query
	    	$query_string  = "UPDATE `address` SET `nomos`='".$nomos."', `dimos`='".$dimos."', `city`='".$city."', `address`='".$address."',";
	    	$query_string .= " `tk`='".$postal_code."', `perioxi`='".$area."', `xwra`='".$country."' WHERE `user_id`='".$user_id."';";
	    	$con->query($query_string);
	    	
	        // increase queries (testing purposes)
	        $query_string = "UPDATE `stat_activity` SET `num_of_queries`=`num_of_queries`+1, `last_happened_on`=CURRENT_TIMESTAMP;";
	        $con->query($query_string);
	        
	        $this->response(array('message'=>'Updated'), 200); // 200 being the HTTP response code
	    }
	    
	    
	    
	    
	    
	    
	    
	    
	    
		function phones_get()
	    {
	        
	        // connect to DB
	        $con = connect_db('central_db');
	        
	        
	        //var_dump($this->get('status'));
	        
	        // define query
	        $query_string = "SELECT * FROM phone_numbers AS p INNER JOIN users AS u ON p.user_id=u.user_id WHERE u.username = '".$this->get('username')."'";
	        //var_dump($query_string);
	        // execute query
	        $result = $con->query($query_string);
	        //var_dump($result);
	        
	        // fetch results (one or none entry)
	        while ($row = $result->fetch_array())
	        {
	        	
	        	$id = intval($row['user_id']);
	        	
	        	$phones[$id] = array(
						           'id' => $id,
						           'phone' => $row['telephone'],
						           'mobile' => $row['mobile'],
						           'fax' => $row['fax']
	        	);
	        	
	        }
	        
	        
	        // increase number of queries (tracking pusposes)
	        //$query_string = "TRUNCATE `stat_activity`;";
	        //$con->query($query_string);
	        $query_string = "UPDATE `stat_activity` SET `num_of_queries`=`num_of_queries`+1, `last_happened_on`=CURRENT_TIMESTAMP;";
	        $con->query($query_string);
	        
	        // this array is the JSON root element
	        /*
		     $users = array(
				1 => array('id' => 1, 'name' => $username[1], 'email' => 'example1@example.com', 'fact' => 'Loves swimming'),
				2 => array('id' => 2, 'name' => $username[2], 'email' => 'example2@example.com', 'fact' => 'Has a huge face'),
				3 => array('id' => 3, 'name' => $username[3], 'email' => 'example3@example.com', 'fact' => 'Is a Scott!', array('hobbies' => array('jogging', 'bikes'))),
			);
			*/
			
			

		    
		    
		     $ph = @$phones[$id];
	    
	        if($ph)
	        {
	            $this->response($ph, 200); // 200 being the HTTP response code
	        }
	
	        else
	        {
	            $this->response(array('error' => 'User could not be found'), 404);
	        }
	    }
	    
	    
	    
	    
	    
	    
	    function phones_post()
	    {
	        
	    	
	    	$username = $this->get('username');
			$phone = $this->post('phone');
			$mobile = $this->post('mobile');
			$fax = $this->post('fax');
			
	    	
			$con = connect_db('central_db');
			
			$query_string = "SELECT * FROM users WHERE username = '".$this->get('username')."'";
	    	$result = $con->query($query_string);
	    	
	    	$row = $result->fetch_array();
	    	$user_id = intval($row['user_id']);
			
	    	// main query
	    	$query_string  = "UPDATE `phone_numbers` SET `telephone`='".$phone."', `mobile`='".$mobile."', `fax`='".$fax."' WHERE `user_id`='".$user_id."';";
	    	$con->query($query_string);
	    	
	        // increase queries (testing purposes)
	        $query_string = "UPDATE `stat_activity` SET `num_of_queries`=`num_of_queries`+1, `last_happened_on`=CURRENT_TIMESTAMP;";
	        $con->query($query_string);
	        
	        $this->response(array('message'=>'Updated'), 200); // 200 being the HTTP response code
	    } 
	    
	    
	    
	    
	    
	    
		function program_get()
	    {
	        /*
	    	if(!$this->get('id'))
	        {
	         $this->response(NULL, 400);
	        } 
			*/
	        // $user = $this->some_model->getSomething( $this->get('id') );
	        
	        // connect to DB
	        $con = connect_db('central_db');
	        
	        
	        //var_dump($this->get('status'));
	        
	        // define query
	        $query_string  = "SELECT * FROM program AS p INNER JOIN users AS u ON p.user_id=u.user_id WHERE u.username = '".$this->get('username')."'";
	        $query_string .= " AND p.date >= CURRENT_DATE";
	        $query_string .= " ORDER BY p.date DESC";
	        //var_dump($query_string);
	        // execute query
	        $result = $con->query($query_string);
	        //var_dump($result);
	        
	        $prog = array();
	        
	        // fetch results (one or none entry)
	        while ($row = $result->fetch_array())
	        {
	        	
	        	$id = intval($row['program_id']);
	        	
	        	$program[$id] = array(
						           'id' => $id,
						           'date' => $row['date'],
						           'duty_type' => $row['duty_type'],
						           'duty_start_time' => $row['duty_start_time'],
						           'duty_end_time' => $row['duty_end_time'],
						           'location' => $row['location'],
						           'user_id' => intval($row['user_id']),
						           'program_name' => $row['program_name']
	        					   
	        	);
	        	
	        	$prog["programs"][] = @$program[$id];
	        	
	        }
	        
	        
	        // increase number of queries (tracking pusposes)
	        //$query_string = "TRUNCATE `stat_activity`;";
	        //$con->query($query_string);
	        $query_string = "UPDATE `stat_activity` SET `num_of_queries`=`num_of_queries`+1, `last_happened_on`=CURRENT_TIMESTAMP;";
	        $con->query($query_string);
	        
	        
	    
	        if($prog)
	        {
	            $this->response($prog, 200); // 200 being the HTTP response code
	        }
	
	        else
	        {
	            $this->response(array('error' => 'No program for this user.'), 404);
	        }
	    }
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    function change_post()
	    {
	        
	    	
	    	$id = intval($this->get('id'));
			$user_id = intval($this->post('user_id'));
			$request_date = $this->post('request_date');
			$request_start_time = $this->post('request_start_time');
			
	    	
			$con = connect_db('central_db');
			
	    	// main query
	    	if ($request_start_time == 'Any')
	    	{
	    		$query_string  = "INSERT INTO `change_list` (`id`, `user_id`, `request_date`, `request_start_time`) VALUES(".$id.", ".$user_id.", '".$request_date."', NULL)";
	    		$query_string .= " ON DUPLICATE KEY UPDATE `request_date`='".$request_date."', `request_start_time`=NULL;";
	    	}
	    	else
	    	{
	    		$query_string  = "INSERT INTO `change_list` (`id`, `user_id`, `request_date`, `request_start_time`) VALUES(".$id.", ".$user_id.", '".$request_date."', '".$request_start_time."')";
	    		$query_string .= " ON DUPLICATE KEY UPDATE `request_date`='".$request_date."', `request_start_time`='".$request_start_time."';";
	    	}
	    	
	    	var_dump($request_start_time);
	    	
	    	$con->query($query_string);
	    	
	        // increase queries (testing purposes)
	        $query_string = "UPDATE `stat_activity` SET `num_of_queries`=`num_of_queries`+1, `last_happened_on`=CURRENT_TIMESTAMP;";
	        $con->query($query_string);
	        
	        
	    	// define query
	        $query_string = "SELECT * FROM change_list WHERE id = '".$id."'";
	        //var_dump($query_string);
	        // execute query
	        $result = $con->query($query_string);
	        //var_dump($result);
	        
	        $prog = array();
	        
	        // fetch results (one or none entry)
	        while ($row = $result->fetch_array())
	        {
	        	
	        	$id = intval($row['id']);
	        	
	        	$change_list[$id] = array(
						           'id' => $id,
						           'user_id' => $row['user_id'],
						           'request_date' => $row['request_date'],
						           'request_start_time' => $row['request_start_time']
	        					   
	        	);
	        	
	        	
	        	
	        }
	        
	        
	        // increase number of queries (tracking pusposes)
	        //$query_string = "TRUNCATE `stat_activity`;";
	        //$con->query($query_string);
	        $query_string = "UPDATE `stat_activity` SET `num_of_queries`=`num_of_queries`+1, `last_happened_on`=CURRENT_TIMESTAMP;";
	        $con->query($query_string);
	        
	        
	        $list = @$change_list[$id];
	        
	    
	        if($list)
	        {
	        	$list['message'] = 'InsertedOrUpdated'; 
	            $this->response($list, 200); // 200 being the HTTP response code
	        }
	
	        else
	        {
	            $this->response(array('error' => 'No program for this user.'), 404);
	        }
	        
	        
	        
	    } 
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    function changes_post()
	    {
	        
	    	$changes = $this->post('changes');
	    	$size = intval($this->post('size'));
	    	
	    	$id = array($size);
	    	$user_id = array($size);
	    	$request_date = array($size);
	    	$request_date = array($size);
	    	
	    	for ($i=0; $i<$size; $i++)
	    	{
	    		$id[$i] = $changes[$i]['id'];
	    		//var_dump($id[$i]);
				$user_id[$i] = $changes[$i]['user_id'];
				//var_dump($user_id[$i]);
				$request_date[$i] = $changes[$i]['request_date'];
				//var_dump($request_date[$i]);
				$request_start_time[$i] = $changes[$i]['request_start_time'];
				//var_dump($request_start_time[$i]);
	    	}
	    	
			
	    	
			$con = connect_db('central_db');
			
			
			for ($i=0; $i<$size; $i++)
			{
		    	
				if ($request_start_time[$i] == 'Any')
				{
					$query_string  = "INSERT INTO `change_list` VALUES(".$id[$i].", ".$user_id[$i].", '".$request_date[$i]."', NULL)";
		    		$query_string .= "ON DUPLICATE KEY UPDATE `request_date`='".$request_date[$i]."', `request_start_time`=NULL";
				}
				else
				{
					$query_string  = "INSERT INTO `change_list` VALUES(".$id[$i].", ".$user_id[$i].", '".$request_date[$i]."', '".$request_start_time[$i]."')";
		    		$query_string .= "ON DUPLICATE KEY UPDATE `request_date`='".$request_date[$i]."', `request_start_time`='".$request_start_time[$i]."'";
				}
		    	
		    	$con->query($query_string);
		    	
		        // increase queries (testing purposes)
		        $query_string = "UPDATE `stat_activity` SET `num_of_queries`=`num_of_queries`+1, `last_happened_on`=CURRENT_TIMESTAMP;";
		        $con->query($query_string);
		        
			}
	        
	        
	        
			$this->response(array('message' => 'InsertedOrUpdated'), 200);
	        
	        
	    } 
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    function search_post()
	    {
	    	
	    	
	    	$id = $this->get('id');
	    	$date = $this->post('date');
	    	$duty_type = $this->post('type');
	    	$location = $this->post('location');
	    	$program_name = $this->post('progname');
	    	
	    	
	    	$con = connect_db('central_db');
	    	
	    	$query_string  = "SELECT * FROM program ";
	    	
	    	if ($date == 'Any')
	    	{
	    		$date = date("Y-m-d");
	    		$query_string .= " WHERE date >= '".$date."'";
	    		
	    		if ($duty_type != 'All') $query_string .= " AND duty_type = '".$duty_type."'";
	    		if ($location != 'All') $query_string .= " AND location = '".$location."'";
	    		if ($program_name != 'All') $query_string .= " AND program_name = '".$program_name."'";
	    	}
	    	else
	    	{
	    		$query_string .= " WHERE date = '".$date."'";
	    		
	    		if ($duty_type != 'All') $query_string .= " AND duty_type = '".$duty_type."'";
	    		if ($location != 'All') $query_string .= " AND location = '".$location."'";
	    		if ($program_name != 'All') $query_string .= " AND program_name = '".$program_name."'";
	    	}
	    	
	    	$query_string .= " AND user_id = ".$id;
	    	$query_string .= " ORDER BY date ASC";
	    	
	    	//var_dump($query_string);
	    	
	    	// perform query
	    	$result = $con->query($query_string);
	    	
	    	$prog = array();
	        
	        // fetch results (one or none entry)
	        while ($row = $result->fetch_array())
	        {
	        	
	        	$id = intval($row['program_id']);
	        	
	        	$program[$id] = array(
						           'id' => $id,
						           'date' => $row['date'],
						           'duty_type' => $row['duty_type'],
						           'duty_start_time' => $row['duty_start_time'],
						           'duty_end_time' => $row['duty_end_time'],
						           'location' => $row['location'],
						           'user_id' => intval($row['user_id']),
						           'program_name' => $row['program_name']
	        					   
	        	);
	        	
	        	$prog["programs"][] = @$program[$id];
	        	
	        }
	        
	        
	        // increase number of queries (tracking pusposes)
	        //$query_string = "TRUNCATE `stat_activity`;";
	        //$con->query($query_string);
	        $query_string = "UPDATE `stat_activity` SET `num_of_queries`=`num_of_queries`+1, `last_happened_on`=CURRENT_TIMESTAMP;";
	        $con->query($query_string);
	        
	        
	    
	        if($prog)
	        {
	            $this->response($prog, 200); // 200 being the HTTP response code
	        }
	
	        else
	        {
	            $this->response(array('error' => 'No program for this user.'), 404);
	        }
	    	
	    }
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    function populatesearch_get()
	    {
	    	
	    	
	    	
	    	$id = $this->get('id');
	    	
	    	
	    	$con = connect_db('central_db');
	    	
	    	
	    	
	        
	        
	        // ------- DUTY TYPES -------
	        
	        $query_string = "SELECT * FROM declared_duties WHERE user_id = ".$id;
	    	//var_dump($query_string);
	    	
	    	
	    	
	    	// perform query
	    	$result = $con->query($query_string);
	    	
	    	$duty_types = array();
	        
	        // fetch results (one or none entry)
	        while ($row = $result->fetch_array())
	        {
	        	
	        	$duty_types[] = array( 'user_id' => $row['user_id'], 'duty_type' => $row['duty_type']);
	        	
	        	
	        }
	        //var_dump($duty_types);
	        
	        // increase number of queries (tracking pusposes)
	        //$query_string = "TRUNCATE `stat_activity`;";
	        //$con->query($query_string);
	        $query_string = "UPDATE `stat_activity` SET `num_of_queries`=`num_of_queries`+1, `last_happened_on`=CURRENT_TIMESTAMP;";
	        $con->query($query_string);
	        
	        
	        
	        
	        // ------- LOCATIONS -------
	        
	        $query_string = "SELECT * FROM declared_locations WHERE user_id = ".$id;
	    	
	    	
	    	//var_dump($query_string);
	    	
	    	// perform query
	    	$result = $con->query($query_string);
	    	
	    	$locations = array();
	        
	        // fetch results (one or none entry)
	        while ($row = $result->fetch_array())
	        {
	        	
	        	$locations[] = array( 'user_id' => $row['user_id'], 'location' => $row['location']);
	        	
	        	
	        }
	        //var_dump($locations);
	        
	        // increase number of queries (tracking pusposes)
	        //$query_string = "TRUNCATE `stat_activity`;";
	        //$con->query($query_string);
	        $query_string = "UPDATE `stat_activity` SET `num_of_queries`=`num_of_queries`+1, `last_happened_on`=CURRENT_TIMESTAMP;";
	        $con->query($query_string);
	        
	        
	        
	        // ------- PROG NAMES -------
	    	
	    	$query_string  = "SELECT DISTINCT program_name FROM program WHERE date >= NOW() AND user_id = ".$id;
	    	
	    	
	    	//var_dump($query_string);
	    	
	    	// perform query
	    	$result = $con->query($query_string);
	    	
	    	$prognames = array();
	        
	        // fetch results (one or none entry)
	        while ($row = $result->fetch_array())
	        {
	        	
	        	$prognames[] = $row['program_name'];
	        	
	        }
	        //var_dump($prognames);
	        
	        // increase number of queries (tracking pusposes)
	        //$query_string = "TRUNCATE `stat_activity`;";
	        //$con->query($query_string);
	        $query_string = "UPDATE `stat_activity` SET `num_of_queries`=`num_of_queries`+1, `last_happened_on`=CURRENT_TIMESTAMP;";
	        $con->query($query_string);
	        
	        
	        
	        
	    	$this->response(array( 'duty_types' => $duty_types, 'locations' => $locations, 'program_names' => $prognames ), 200); // 200 being the HTTP response code
	        
	    	
	    	
	    	
	    	
	    }
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    function populatesettings_get()
	    {
	    	
	    	
	    	
	    	$con = connect_db('central_db');
	    	
	    	
	    	
	        
	        
	        // ------- DEPARTMENTS -------
	        
	        $query_string = "SELECT * FROM departments";;
	    	//var_dump($query_string);
	    	
	    	
	    	
	    	// perform query
	    	$result = $con->query($query_string);
	    	
	    	$departments = array();
	        
	        // fetch results (one or none entry)
	        while ($row = $result->fetch_array())
	        {
	        	
	        	$departments[] = array( 'department_name' => $row['department_name']);
	        	
	        	
	        }
	        //var_dump($duty_types);
	        
	        // increase number of queries (tracking pusposes)
	        //$query_string = "TRUNCATE `stat_activity`;";
	        //$con->query($query_string);
	        $query_string = "UPDATE `stat_activity` SET `num_of_queries`=`num_of_queries`+1, `last_happened_on`=CURRENT_TIMESTAMP;";
	        $con->query($query_string);
	        
	        
	        
	        
	        // ------- DUTY_TYPES -------
	        
	        $query_string = "SELECT * FROM duties";
	    	
	    	
	    	//var_dump($query_string);
	    	
	    	// perform query
	    	$result = $con->query($query_string);
	    	
	    	$locations = array();
	        
	        // fetch results (one or none entry)
	        while ($row = $result->fetch_array())
	        {
	        	
	        	$duties[] = array( 'duty_name' => $row['duty_name']);
	        	
	        	
	        }
	        //var_dump($locations);
	        
	        // increase number of queries (tracking pusposes)
	        //$query_string = "TRUNCATE `stat_activity`;";
	        //$con->query($query_string);
	        $query_string = "UPDATE `stat_activity` SET `num_of_queries`=`num_of_queries`+1, `last_happened_on`=CURRENT_TIMESTAMP;";
	        $con->query($query_string);
	        
	    	$this->response(array( 'departments' => $departments, 'duties' => $duties ), 200); // 200 being the HTTP response code
	        
	    	
	    }
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    function populatedeclared_get()
	    {
	    	
	    	
	    	$id = $this->get('id');
	    	
	    	$con = connect_db('central_db');
	    	
	    	
	        $query_string  = "SELECT p.program_id, u.name_user, u.surname_user, p.duty_type, u.department, p.date, p.duty_start_time, p.duty_end_time, cl.request_date, cl.request_start_time";
	        $query_string .= " FROM change_list AS cl";
	        $query_string .= " INNER JOIN users AS u ON u.user_id=cl.user_id";
	        $query_string .= " INNER JOIN program AS p ON p.program_id=cl.id";
	        $query_string .= " WHERE u.user_id!=".$id;
	    	
	    	
	    	// perform query
	    	$result = $con->query($query_string);
	    	
	    	$duties = array();
	        
	        // fetch results (one or none entry)
	        while ($row = $result->fetch_array())
	        {
	        	
	        	$duties[] = array(  'name' => $row['name_user'],
	        						'surname' => $row['surname_user'],
	        						'program_id' => $row['program_id'],
	        						'type' => $row['duty_type'],
		        					'department' => $row['department'],
	        						'date' => $row['date'],
	        						'start' => $row['duty_start_time'],
	        						'end' => $row['duty_end_time'],
	        						'req_date' => $row['request_date'],
	        						'req_time' => $row['request_start_time']
	        	);
	        	
	        	
	        }
	        //var_dump($duty_types);
	        
	        
	        
	        // increase number of queries (tracking pusposes)
	        //$query_string = "TRUNCATE `stat_activity`;";
	        //$con->query($query_string);
	        $query_string = "UPDATE `stat_activity` SET `num_of_queries`=`num_of_queries`+1, `last_happened_on`=CURRENT_TIMESTAMP;";
	        $con->query($query_string);
	        
	    	$this->response( array('duties' => $duties), 200); // 200 being the HTTP response code
	        
	    	
	    }
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    function populatedutiesavail_get()
	    {
	    	
	    		    	
	    	$i=0;
	    	$con = connect_db('central_db');
	    	
	    	
	        $query_string  = "SELECT cl.id, cl.request_date, cl.request_start_time, p.duty_type, p.location FROM change_list AS cl";
			$query_string .= " INNER JOIN program AS p ON p.program_id=cl.id";
			$query_string .= " WHERE cl.id=".$this->get('dutyid');
			$result = $con->query($query_string);
	    	
			while ($change_list = $result->fetch_array())
			{			
				
				
				$query_string  = "SELECT u.name_user, u.surname_user, u.department, p.location, p.duty_type, p.date, p.duty_start_time, p.duty_end_time, p.program_id FROM program AS p";
				$query_string .= " INNER JOIN users AS u ON u.user_id=p.user_id";
				$query_string .= " WHERE p.date='".$change_list['request_date']."'";
				$query_string .= " AND p.duty_type='".$change_list['duty_type']."'";
				$query_string .= " AND p.location='".$change_list['location']."'";
				if ($change_list['request_start_time'] != NULL)
				{
					$query_string .= " AND duty_start_time='".$change_list['request_start_time']."'";
				}
				$result2 = $con->query($query_string);
				
				
				while ($program = $result2->fetch_array())
				{
		        	
		        	$duties[] = array(  'program_id' => intval($program['program_id']),
		        						'type' => $program['duty_type'],
		        						'date' => $program['date'],
		        						'start' => $program['duty_start_time'],
		        						'end' => $program['duty_end_time']
		        	);
		        	
		        	$i++;
		        }
		        
			}
	        //var_dump($duty_types);
	        
	        
	        
	        // increase number of queries (tracking pusposes)
	        //$query_string = "TRUNCATE `stat_activity`;";
	        //$con->query($query_string);
	        $query_string = "UPDATE `stat_activity` SET `num_of_queries`=`num_of_queries`+1, `last_happened_on`=CURRENT_TIMESTAMP;";
	        $con->query($query_string);
	        
	    	if ($i) $this->response( array('duties' => $duties), 200); // 200 being the HTTP response code
	    	else $this->response( array('duties' => array ( array(  'program_id' => 0) ) ), 200); // 200 being the HTTP response code
	        
	    	
	    }
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    function exchangeduties_get()
	    {
	    	
	    	
	    	$con = connect_db('central_db');
	    	
	    	
	        // ********************* BEFORE *********************** //
			
			$query_string  = "SELECT date, duty_start_time, duty_end_time, user_id FROM program";
			$query_string .= " WHERE program_id=".$this->get('from');
			$result = $con->query($query_string);
			
			while ($before = $result->fetch_array())
			{
				$before_date = $before['date'];
				$before_start = $before['duty_start_time'];
				$before_end = $before['duty_end_time'];
				$before_user_id = $before['user_id'];
			}
			
			
			// ********************* AFTER *********************** //
			
			$query_string  = "SELECT date, duty_start_time, duty_end_time, user_id FROM program";
			$query_string .= " WHERE program_id=".$this->get('to');
			$result2 = $con->query($query_string);
			
			while ($after = $result2->fetch_array())
			{
				$after_date = $after['date'];
				$after_start = $after['duty_start_time'];
				$after_end = $after['duty_end_time'];
				$after_user_id = $after['user_id'];
			}
			
			
			
			
			
			
			// ******************* EXCHANGE ******************** //
			
			$query_string  = "UPDATE program SET date='".$after_date."', duty_start_time='".$after_start."', duty_end_time='".$after_end."'";
			$query_string .= " WHERE program_id=".$this->get('from');
			$con->query($query_string);
			
			$query_string  = "UPDATE program SET date='".$before_date."', duty_start_time='".$before_start."', duty_end_time='".$before_end."'";
			$query_string .= " WHERE program_id=".$this->get('to');
			$con->query($query_string);
			
			
			$query_string  = "DELETE FROM change_list";
			$query_string .= " WHERE id=".$this->get('from');
			$con->query($query_string);
			
			
			
			
			
			// ******************** NOTIFY *********************** //
			
			$query_string  = "INSERT INTO notify_user VALUES (NULL, ".$before_user_id.", ".$this->get('from').", 0, 'Request fulfilled and duty exchanged with another user's.')";
			$con->query($query_string);
	        
	        
	        
	        // increase number of queries (tracking pusposes)
	        //$query_string = "TRUNCATE `stat_activity`;";
	        //$con->query($query_string);
	        $query_string = "UPDATE `stat_activity` SET `num_of_queries`=`num_of_queries`+1, `last_happened_on`=CURRENT_TIMESTAMP;";
	        $con->query($query_string);
	        
	    	$this->response( array( 'error' => 'none' ), 200); // 200 being the HTTP response code
	        
	    	
	    }
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    function notifications_get()
	    {
	    	
	    	
	    	$con = connect_db('central_db');
	    	$id = $this->get('id');
	    	
	    	/*
	    	$username = $this->get('username');
	    	
	    	
			$query_string = "SELECT user_id FROM users WHERE username='".$username."'";
			$result = $con->query($query_string);
	    	
	    	while ($row = $result->fetch_array())
			{
				$id = $row['user_id'];
			}
	    	*/
			
			$query_string  = "SELECT * FROM notify_user WHERE user_id=".$id;
			$result = $con->query($query_string);
			
			
			while ($notifications = $result->fetch_array())
			{
				$prog_id[] = intval($notifications['program_id']);
				$isSecretary[] = intval($notifications['isSecretary']);
				$description[] = $notifications['description'];
			}
			
			
			
	        $query_string = "UPDATE `stat_activity` SET `num_of_queries`=`num_of_queries`+1, `last_happened_on`=CURRENT_TIMESTAMP;";
	        $con->query($query_string);
	        
	        $query_string  = "DELETE FROM notify_user WHERE user_id=".$id;
			$con->query($query_string);
	    	
			
			
			
			
	        if (isset($prog_id))
	        {
	        	
		        for ($i=0; $i<count($prog_id); $i++)
				{
			    	$query_string  = "SELECT * FROM program WHERE program_id=".$prog_id[$i];
					$result = $con->query($query_string);
					
					
					while ($dates_and_times = $result->fetch_array())
					{
						$date[] = $dates_and_times['date'];
						$start_time[] = $dates_and_times['duty_start_time'];
						$end_time[] = $dates_and_times['duty_end_time'];
					}
				}
				
				
	        	$this->response( array( 'program_id' => $prog_id,
	        							'isSecretary' => $isSecretary,
	        							'description' => $description,
	        							'date' => $date,
	        							'start_time' => $start_time,
	        							'end_time' => $end_time,
	        							'error' => "" )
	        					, 200); // 200 being the HTTP response code
	        }
	        else
	        {
	        	$this->response( array( 'error' => "No notifications for this user" ), 200); // 200 being the HTTP response code
	        }
	        
	    	
	    }
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    function notificationsnodelete_get()
	    {
	    	
	    	
	    	$con = connect_db('central_db');
	    	$username = $this->get('username');
	    	
			$query_string = "SELECT user_id FROM users WHERE username='".$username."'";
			$result = $con->query($query_string);
	    	
	    	while ($row = $result->fetch_array())
			{
				$id = $row['user_id'];
			}
	    	
			
			$query_string  = "SELECT * FROM notify_user WHERE user_id=".$id;
			$result = $con->query($query_string);
			
			
			while ($notifications = $result->fetch_array())
			{
				$prog_id[] = intval($notifications['program_id']);
				$isSecretary[] = intval($notifications['isSecretary']);
				$description[] = $notifications['description'];
			}
			
			
			
	        $query_string = "UPDATE `stat_activity` SET `num_of_queries`=`num_of_queries`+1, `last_happened_on`=CURRENT_TIMESTAMP;";
	        $con->query($query_string);
	        
	        
	        // DO NOT DELETE //
	        //$query_string  = "DELETE FROM notify_user WHERE user_id=".$id;
			//$con->query($query_string);
	    	
			
			
			
			
	        if (isset($prog_id))
	        {
	        	
		        for ($i=0; $i<count($prog_id); $i++)
				{
			    	$query_string  = "SELECT * FROM program WHERE program_id=".$prog_id[$i];
					$result = $con->query($query_string);
					
					
					while ($dates_and_times = $result->fetch_array())
					{
						$date[] = $dates_and_times['date'];
						$start_time[] = $dates_and_times['duty_start_time'];
						$end_time[] = $dates_and_times['duty_end_time'];
					}
				}
				
				
	        	$this->response( array( 'program_id' => $prog_id,
	        							'isSecretary' => $isSecretary,
	        							'description' => $description,
	        							'date' => $date,
	        							'start_time' => $start_time,
	        							'end_time' => $end_time,
	        							'error' => "" )
	        					, 200); // 200 being the HTTP response code
	        }
	        else
	        {
	        	$this->response( array( 'error' => "No notifications for this user" ), 200); // 200 being the HTTP response code
	        }
	        
	    	
	    }
	    
	    
	    
	    
	    
	    
	    
	    
	    
		function logout_get()
	    {
	        
	        $id = $this->get('id');
	        
	        $con = connect_db('central_db');
	        $query_string = "INSERT INTO users_logged_in VALUES( ".$id." , 0 )";
	        $query_string .= " ON DUPLICATE KEY UPDATE logged = 0";
	        $con->query($query_string);
	        
	        $this->response(array('message' => 'OK'), 404);
	        
	    }
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    /*
	    function users_get()
	    {
	        //$users = $this->some_model->getSomething( $this->get('limit') );
	        $users = array(
				array('id' => 1, 'name' => 'Some Guy', 'email' => 'example1@example.com'),
				array('id' => 2, 'name' => 'Person Face', 'email' => 'example2@example.com'),
				3 => array('id' => 3, 'name' => 'Scotty', 'email' => 'example3@example.com', 'fact' => array('hobbies' => array('fartings', 'bikes'))),
			);
	        
	        if($users)
	        {
	            $this->response($users, 200); // 200 being the HTTP response code
	        }
	
	        else
	        {
	            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
	        }
	    }
	
	
		public function send_post()
		{
			var_dump($this->request->body);
		}
		
		
		public function send_put()
		{
			var_dump($this->put('foo'));
		}
		*/
	    
	    
	    
	    
}