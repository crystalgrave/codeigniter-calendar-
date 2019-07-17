<?php
class Calendar_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	function get_all_events($year,$month){
		$this->db->where('MONTH(registration_date)', $month , FALSE);
		$this->db->where('YEAR(registration_date)', $year , FALSE);
		$this->db->join('pt_room_spot_info', 'pt_room_spot_info.roomspotID = pt_registration_dates.roomspotID');
		$this->db->join('pt_registration_users', 'pt_registration_users.registrationID = pt_registration_dates.registrationID');
		$this->db->join('pt_registration', 'pt_registration.registrationID = pt_registration_dates.registrationID');

		$query = $this->db->get('pt_registration_dates');
		
		
		if ( $query->num_rows() > 0 ){
			return $query->result();
		}else{
			return FALSE;
		}
	
	}
	
	
	function getevents($year,$month,$user){
		$this->db->where('MONTH(registration_date)', $month , FALSE);
		$this->db->where('YEAR(registration_date)', $year , FALSE);
		$this->db->where('pt_registration_users.userID', $user);
		$this->db->join('pt_room_spot_info', 'pt_room_spot_info.roomspotID = pt_registration_dates.roomspotID');
		$this->db->join('pt_registration_users', 'pt_registration_users.registrationID = pt_registration_dates.registrationID');
		$this->db->join('pt_registration', 'pt_registration.registrationID = pt_registration_dates.registrationID');

		$query = $this->db->get('pt_registration_dates');
		
		if ( $query->num_rows() > 0 ){
			return $query->result();
		}else{
			return FALSE;
		}
	
	}	


	function get_single_event($id){
		
		$this->db->where('registrationdateID', $id);
		$this->db->join('pt_room_spot_info', 'pt_room_spot_info.roomspotID = pt_registration_dates.roomspotID');
		$this->db->join('pt_registration_users', 'pt_registration_dates.registrationID=pt_registration_users.registrationID');
		$this->db->join('pt_registration', 'pt_registration_users.registrationID=pt_registration.registrationID');
		$this->db->join('tank_user_profiles', 'tank_user_profiles.user_id=pt_registration_users.userID');
		
		$query = $this->db->get('pt_registration_dates');
		//	THE SQL QUERY CODE HERE
		// $query = $this->db->get_compiled_select('pt_registration_dates');
		// echo "$query";
		if ( $query->num_rows() > 0 ){
			return $query->result();
		}else{
			return FALSE;
		}

	}

	function get_all_bookings(){

		$this->db->select('pt_registration_dates.registrationID, pt_registration_dates.registration_date, pt_registration_dates.registration_timein, pt_registration_dates.registration_timeout, pt_registration_dates.roomspotID, pt_registration_dates.registration_active,
				pt_room_spot_info.room_spot_name');

        $this->db->from('pt_registration_dates');
        $this->db->join('pt_room_spot_info', 'pt_room_spot_info.roomspotID = pt_registration_dates.roomspotID');
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ){
			return $query->result();
		}else{
			return FALSE;
		}
	}
	

}// end class