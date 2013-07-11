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
						           'user_team' => $row['user_team'],
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
	            $this->response($user, 200); // 200 being the HTTP response code
	        }
	
	        else
	        {
	            $this->response(array('error' => 'User could not be found'), 404);
	        }
	    }
	    
	    
	    
	    
	    function user_post()
	    {
	        
	    	/*
	    	$json_from_phone = json_decode($this->request->body);
	    	
	    	$username = $json_from_phone->username;
	    	$password = $json_from_phone->password;
	    	$email = $json_from_phone->email;
	    	$userteam = $json_from_phone->userteam;
	    	$name = $json_from_phone->name;
	    	$surname = $json_from_phone->surname;
	    	$amka = $json_from_phone->amka;
	    	$status = $json_from_phone->status;
	    	$department = $json_from_phone->department;
	    	*/
	    	
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
	        
	        $this->response(array('message'=>'Updated'), 200); // 200 being the HTTP response code
	    }
	    
	    
	    
	    
	    
	    
	    
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
		
}