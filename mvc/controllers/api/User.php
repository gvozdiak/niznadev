<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

require_once APPPATH . '/libraries/JWT/JWT.php';

// use namespace
use Restserver\Libraries\REST_Controller;

use \Firebase\JWT\JWT;

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class User extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['login_post']['limit'] = 10000; // Default 500 requests per hour per user/key
        $this->methods['users_get']['limit'] = 1000; // Default 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 1000; // Default 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // Default 50 requests per hour per user/key
        $this->load->model('signin_m');
        $this->load->model('setting_m');
        $this->load->model('user_m');
    }

    public function index_get()
    {
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjEiLCJ1c2VybmFtZSI6ImFkbWluIiwiaWF0IjoxNDg4MTE0MzQwfQ.K9eF9rlTci0nUlyrNfTzr-xSTwAOwjG5z6ngT29vHcw';
        var_dump(JWT::decode($token, config_item('encryption_key'), array('HS256')));
    }

    public function login_post()
    {
        $username = $this->post('username');
        $password = $this->post('password');
        $invalidLogin = ['status' => False, 'message' => 'Unauthorized'];
        if(!$username || !$password) $this->response($invalidLogin, REST_Controller::HTTP_NOT_FOUND);
        $id = $this->signin($username,$password);
        if($id) {
            $token['id'] = $id;
            $token['username'] = $username;
            $date = new DateTime();
            $token['iat'] = $date->getTimestamp();
            // $token['exp'] = $date->getTimestamp() + 60*60*5;
            $output['id_token'] = JWT::encode($token, config_item('encryption_key'));
            $this->set_response($output, REST_Controller::HTTP_OK);
        }
        else {
            $this->set_response($invalidLogin, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function signin($username, $password) {
        $tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');

        $settings = $this->setting_m->get_setting(1);
        $lang = $settings->language;
        $defaultschoolyearID = $settings->school_year;
        $array = array();
        $i = 0;
        $password = $this->hash($password);
        $userdata = '';
        foreach ($tables as $table) {
            $user = $this->db->get_where($table, array("username" => $username, "password" => $password, 'active' => 1));
            $alluserdata = $user->row();
            if(count($alluserdata)) {
                $userdata = $alluserdata;
                $array['permition'][$i] = 'yes';
                $array['usercolname'] = $table.'ID';
            } else {
                $array['permition'][$i] = 'no';
            }
            $i++;

        }

        if(in_array('yes', $array['permition'])) {
            $usertype = $this->usertype_m->get_usertype($userdata->usertypeID);
            if(count($usertype)) {
                return $userdata->$array['usercolname'];
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function hash($string) {
		return hash("sha512", $string . config_item("encryption_key"));
	}


    public function users_get()
    {
        // Users from a data store e.g. database
        $users = $this->user_m->get_order_by_user(['active' => 1]);

        // dd($users);

        $id = $this->get('id');

        // If the id parameter doesn't exist return all the users

        if ($id === NULL)
        {
            // Check if the users data store contains users (in case the database result returns NULL)
            if ($users)
            {
                // Set the response and exit
                $this->response($users, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                // Set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'No users were found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }

        // Find and return a single record for a particular user.
        else {
            $id = (int) $id;

            // Validate the id.
            if ($id <= 0)
            {
                // Invalid id, set the response and exit.
                $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            }

            // Get the user from the array, using the id as key for retrieval.
            // Usually a model is to be used for this.

            $user = NULL;

            if (!empty($users))
            {
                foreach ($users as $key => $value)
                {
                    if (isset($value->userID) && $value->userID == $id)
                    {
                        $user = $value;
                    }
                }
            }

            if (!empty($user))
            {
                $this->set_response($user, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'User could not be found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }
    }

    public function users_post()
    {
        // $this->some_model->update_user( ... );
        $message = [
            'id' => 100, // Automatically generated by the model
            'name' => $this->post('name'),
            'email' => $this->post('email'),
            'message' => 'Added a resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }

    public function users_delete()
    {
        $id = (int) $this->get('id');

        // Validate the id.
        if ($id <= 0)
        {
            // Set the response and exit
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // $this->some_model->delete_something($id);
        $message = [
            'id' => $id,
            'message' => 'Deleted the resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
    }

}
