<?php
class Registration_model extends CI_Model
{
    /**
     * This function is used to get the user id by username
     * @return array $result : This is result of the query
     */
    function getByUsername($username)
    {
        $this->db->select('id, username');
        $this->db->from('tank_users');
        $this->db->where('LOWER(username)=', strtolower($username));
        $query = $this->db->get();
        
        
		return $query->result_array();
		
        
    }
    /**
     * This function is used to get the registration information
     * @return array $result : This is result of the query
     */
    function getAllRegistration()
    {
        $this->db->select('registrationID, application_date, app_status');
        $this->db->from('pt_registration');
        //$this->db->where('roleId !=', 0);
        $query = $this->db->get();
        
        return $query->result_array();
    }

    /**
     * This function is used to get the registration client profile info
     * @return array $result : This is result of the query
     */
    function getAllRegistrationClient()
    {
        $this->db->select('id, user_id, country, website, first_name, last_name, work_phone, home_phone, work_email, home_email, birthday, gender, user_bio, user_profile_img');
        $this->db->from(' tank_user_profiles');
        //$this->db->where('roleId !=', 0);
        $query = $this->db->get();
        
        return $query->result_array();
    }

    /**
     * This function is used to get the registration date/time information
     * @return array $result : This is result of the query
     */
    function getAllRegistrationDate()
    {
        $this->db->select('registrationdateID, registrationID, registration_date, registration_timein, registration_timeout, registration_active');
        $this->db->from(' pt_registration_dates');
        //$this->db->where('roleId !=', 0);
        $query = $this->db->get();
        
        return $query->result_array();
    }

    /**
     * This function is used to get the registration users info
     * @return array $result : This is result of the query
     */
    function getAllRegistrationUser()
    {
        $this->db->select('registrationuserID, registrationID, userID');
        $this->db->from(' pt_registration_users');
        //$this->db->where('roleId !=', 0);
        $query = $this->db->get();
        
        return $query->result_array();
    }

    /**
     * This function is used to get the registration users info by user id
     * @return array $result : This is result of the query
     */
    function getRegistrationUserInfoById($userID)
    {
        $this->db->select('id, user_id, country, website, first_name, last_name, work_phone, home_phone, work_email, home_email, birthday, gender, user_bio, user_profile_img');
        $this->db->from(' tank_user_profiles');
        $this->db->where('id =', $userID);
        $query = $this->db->get();
        
        return $query->row();
    }
    /******************************************************************************************************************
    ** 
    * GET ALL BY IDs ^^
    **
    ******************************************************************************************************************/

    /**
     * This function is used to get the complete registration info by id
     * @return array $result : This is result of the query
     */
    function getRegistrationById($userID)
    {
        $this->db->select('id, user_id, country, website, first_name, last_name, work_phone, home_phone, work_email, home_email, birthday, gender, user_bio, user_profile_img');
        $this->db->from(' tank_user_profiles');
        $this->db->where('id =', $userID);
        $query = $this->db->get();
        
        return $query->row();
    }    

/**
     * This will create a new registration
     * @return array $result : This is result of the query
     */
    function createGroupRegistration($time_data, $group, $userid, $userid2 = '', $userid3 = '')
    {
        $data['application_date'] = date('Y-m-d H:i:s');
        $data['app_status'] = "Submitted";

        //$sql = $this->db->set($data)->get_compiled_insert('pt_registration');
        $this->db->insert('pt_registration', $data);
        
        //echo $sql;

        $regID = $this->db->insert_id();
        //echo $regID;
        //insert into pt registration dates TODO
        //if single
        $this->createRegistrationDates($regID, $time_data, 1);

        //if group
        

        //reg users
        $this->createRegistrationUsers($regID, $userid);
        if ( $userid2 != '' ) {
            $this->createRegistrationUsers($regID, $userid2);
        }
        
        if ( $userid3 != '' ) {
            $this->createRegistrationUsers($regID, $userid3);
        }

        // to test for sql query
        //$sql = $this->db->set($data)->get_compiled_insert('pt_registration');
        
        
        //echo $sql;
       
    }
    /**
     * This will create a new registration
     * @return array $result : This is result of the query
     */
    function createRegistration($userid, $time_data, $group)
    {
        $data['application_date'] = date('Y-m-d H:i:s');
        $data['app_status'] = "Submitted";

        //$sql = $this->db->set($data)->get_compiled_insert('pt_registration');
        $this->db->insert('pt_registration', $data);
        
        //echo $sql;

        $regID = $this->db->insert_id();
        //echo $regID;
        //insert into pt registration dates TODO
        //if single
        $this->createRegistrationDates($regID, $time_data, 0);

        //if group
        

        //reg users
        $this->createRegistrationUsers($regID, $userid);

        // to test for sql query
        //$sql = $this->db->set($data)->get_compiled_insert('pt_registration');
        
        
        //echo $sql;
       
    }
    /**
     * This will create a new registration DATES
     * @return array $result : This is result of the query
     */
    function createRegistrationDates($regID, $time_data, $group)
    {
     
        $data['registrationID'] = $regID;
        $data['registration_active'] = 1;

        //Rooms Available
        $rooms = 6;
        foreach($time_data as $key => $item){
            if( $item != null ){
                //echo "\nDate: $key";
                $data['registration_date'] = $key;
                foreach($item as $detail){
                    //echo "($detail)";
                    //the user selected times go here (one hour time slot)
                    $data['registration_timein'] = "$detail:00:00";
                    $data['registration_timeout'] = "$detail:59:59";
                    //send to database here the times.
                    for($room = 1; $room <= $rooms; $room++){
                        if($group == 1) {
                            if($room == 3) { //needs to upload into room 3
                                if( !$this->getRegistrationByDate($key, $room, "$detail:00:00", "$detail:59:59") ){ //find an open space
                                    $data['roomspotID'] = $room;
                                    //$sql = $this->db->set($data)->get_compiled_insert('pt_registration_dates');
                                    $this->db->insert('pt_registration_dates', $data);
                                    //echo $sql;
                                    $room = $rooms;
                                } else {
                                    //keep going
                                }
                            }
                        } else {
                            echo "else-";
                            if($room == 3) { //needs to upload into room 3
                                echo $room;    // do nothing
                            } else {
                                if( !$this->getRegistrationByDate($key, $room, "$detail:00:00", "$detail:59:59") ){ //find an open space
                                    $data['roomspotID'] = $room;
                                    //$sql = $this->db->set($data)->get_compiled_insert('pt_registration_dates');
                                    $this->db->insert('pt_registration_dates', $data);
                                    //echo $sql;
                                    $room = $rooms;
                                } else {
                                    //keep going
                                }
                            }
                        } //else end
                    } // end rooms loop

                } //item detail
            } //item != null
        }

    }


    /**
     * This will create a new registration users (clients/students/instructors)
     * @return array $result : This is result of the query
     */
    function createRegistrationUsers($regID, $userid)
    {
        $data['registrationID'] = $regID;

        //get the user ids of whoever just registered.
        $data['userID'] = $userid; //user ids

        // to test for sql query
        //$sql = $this->db->set($data)->get_compiled_insert('pt_registration_users');
        $this->db->insert('pt_registration_users', $data);
        
        //echo $sql;
        
    }    
    /**
     * This will get available dates
     * @return array $result : This is result of the query
     */

    function getRegistrationByDateApplied($date){
        $this->db->from(' pt_registration');
        $this->db->where('pt_registration.application_date', $date);

        $query = $this->db->get();
        
        return $query->result_array();
    }

    function getRegistrationByDateUserID($date, $userid){
        $this->db->from(' pt_registration_dates');
        //$this->db->where('app_status !=' , 'Denied');
        $this->db->where('registration_date', $date);
        $this->db->where('userID', $userid);
        $this->db->join('pt_registration', 'pt_registration.registrationID = pt_registration_dates.registrationID');
        $this->db->join('pt_registration_users', 'pt_registration_users.registrationID = pt_registration_dates.registrationID');

        $query = $this->db->get();
        
        return $query->result_array();

    }
    function getRegistrationByDate($date,$roomspotID , $timeIn, $timeOut)
    {
        // $this->db->select('registrationdateID, registrationID, registration_date, registration_timein, registration_timeout, registration_active, roomspotID');
        $this->db->from(' pt_registration_dates');
        $this->db->where('app_status !=' , 'Denied');
        $this->db->where('registration_date', $date);
        $this->db->where('roomspotID', $roomspotID);
        $this->db->where('registration_timein >=', $timeIn);
        $this->db->where('registration_timeout <=', $timeOut);
        $this->db->join('pt_registration', 'pt_registration.registrationID = pt_registration_dates.registrationID');
        //echo $this->db->get_compiled_select(); // before $this->db->get();

        $query = $this->db->get();
        
        return $query->result_array();
    }   

    /**
     * This will get available times from a date
     * @return array $result : This is result of the query
     */
    function getRegistrationByTime($date , $timeIn, $timeOut)
    {
        // $this->db->select('registrationdateID, registrationID, registration_date, registration_timein, registration_timeout, registration_active, roomspotID');
        
        $this->db->where('registration_date', $date);
        $this->db->where('app_status !=' , 'Denied');
        $this->db->where('roomspotID !=' , 3);
        $this->db->where('registration_timein >=', $timeIn);
        $this->db->where('registration_timeout <=', $timeOut);
        $this->db->join('pt_registration', 'pt_registration.registrationID = pt_registration_dates.registrationID');
         
        
		
        
        $query = $this->db->get('pt_registration_dates');
        //echo $this->db->get_compiled_select('pt_registration_dates');
        return $query->result_array();
    }  
    // /////////////////////////////////////////////////////////////
    // /////////////////////////////////////////////////////////////
    // /////////////////////////////////////////////////////////////
    //  GET ALL REGISTRATION INFORMATION BY REGISTRATION ID
    // /////////////////////////////////////////////////////////////
    function getAllbyRegID($regID){
        
        $this->db->where('pt_registration.registrationID', $regID);
        $this->db->join('pt_registration_dates', 'pt_registration_dates.registrationID = pt_registration.registrationID');
        $this->db->join('pt_registration_users', 'pt_registration_users.registrationID = pt_registration.registrationID');
        $this->db->join('tank_users', 'tank_users.id = pt_registration_users.userID');
        $this->db->join('pt_room_spot_info', 'pt_room_spot_info.roomspotID = pt_registration_dates.roomspotID');
        
        //	THE SQL QUERY CODE HERE
		// $query = $this->db->get_compiled_select('pt_registration');
        // echo "$query";
        $query = $this->db->get('pt_registration');
        if ( $query->num_rows() > 0 ){
			return $query->result();
		}else{
			return FALSE;
		}
        //echo $this->db->get_compiled_select(); // before $this->db->get();
    }
    function getRegID($regID){
        $this->db->where('pt_registration.registrationID', $regID);
        $query = $this->db->get('pt_registration');
        if ( $query->num_rows() > 0 ){
			return $query->result();
		}else{
			return FALSE;
		}
    }
    // Edit Newsletter from the database
    function edit_registration($regID, $data){

        $this->db->where('registrationID', $regID);
        $this->db->update('pt_registration', $data);
    } // Close: Edit

}