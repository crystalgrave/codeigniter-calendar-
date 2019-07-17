<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
    //protected $table = 'tank_user_profiles';

    function __construct(){
        parent::__construct();
    }

    // Check Newsletter Author from the database
    function get_userprofile_detail($id){
        $this->db->where('tank_user_profiles.user_id', $id);
        $this->db->join('tank_users', 'tank_user_profiles.user_id = tank_users.id');
        $query=$this->db->get('tank_user_profiles');

        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }

    // Edit profile from the database
    function edit_profile($data,$id){

        $this->db->where('user_id', $id);
        $this->db->update('tank_user_profiles', $data);
    } // Close: Ed

    // Edit Avatar from the database
    function edit_profileavatar($data,$id){

        $this->db->where('user_id', $id);
        $this->db->update('tank_user_profiles', $data);
    } // Close: Ed
}