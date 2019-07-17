<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calendar extends CI_Controller {
	public function __construct(){
        parent:: __construct();
		$this->load->model('Newsletter_model');
        $this->load->model('Dashboard_model');
    }
	
	public function index()
	{
		 /*$data=array('year'=>$this->uri->segment(3),
             'month'=>$this->uri->segment(4));*/
		
		$prefs = array(
        'show_next_prev'  => TRUE,
        'next_prev_url'   => base_url() .'calendar/index/'
		);

		$prefs['template'] = '

        {table_open}<table border="0" cellpadding="1" cellspacing="1" id="calendar">{/table_open}

		{heading_row_start}<tr class="row text-center d-flex justify-content-around">{/heading_row_start}
		
		{heading_previous_cell}
			<th class="" style="border: none !important;"><a href="{previous_url}" class="btn btn-primary">&lt;&lt;</a></th>
		{/heading_previous_cell}

		{heading_title_cell}
			<th colspan="{colspan}" class="" style="border: none !important;"><h4 class="text-center text-primary">{heading}</h4></th>
		{/heading_title_cell}
		
		{heading_next_cell}
			<th class="" style="border: none !important;"><a href="{next_url}" class="btn btn-primary pull-right">&gt;&gt;</a></th>
		{/heading_next_cell}
		
        {heading_row_end}</tr>{/heading_row_end}


        {week_row_start}<tr class="weekdays">{/week_row_start}
		{week_day_cell}
			<td scope="col">
				<h4 class="text-white">{week_day}</h4>
			</td>
		{/week_day_cell}
        {week_row_end}</tr>{/week_row_end}

        {cal_row_start}<tr>{/cal_row_start}
        {cal_cell_start}<td>{/cal_cell_start}
        {cal_cell_start_today}<td class="day bg-light text-primary accordion">{/cal_cell_start_today}
        {cal_cell_start_other}<td class="day other-month bg-dark text-primary accordion">{/cal_cell_start_other}

		{cal_cell_content}
		<div class="accordion text-primary day" id="accordionExample">
			{day}<br>
			
			<span id="heading{day}" class="text-primary btn btn-link" type="button" data-toggle="collapse" data-target="#day{day}" aria-expanded="true" aria-controls="{day}">Meeting List</span><br>

			<div id="day{day}" class="collapse hide" aria-labelledby="heading{day}" data-parent="#accordionExample">
				{content}
			</div>
		</div>
		{/cal_cell_content}
		
		{cal_cell_content_today}
		<div class="accordion text-primary day" id="accordionExample">
			{day} <span class="text-primary">Today</span><br>

			<span class="text-primary btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Meeting List</span><br>

			<div id="collapseOne" class="collapse hide" aria-labelledby="headingOne" data-parent="#accordionExample">
				{content}
			</div>
		</div>
		{/cal_cell_content_today}
		
        {cal_cell_no_content}{day}{/cal_cell_no_content}
		{cal_cell_no_content_today}
			<div class="accordion text-primary" id="accordionExample">
				{day} <span class="badge badge-dark text-primary" type="button" data-toggle="collapse" data-target="#collapseOne" 	aria-expanded="true" aria-controls="collapseOne">Today</span>
			</div>
		{/cal_cell_no_content_today}




        {cal_cell_blank}&nbsp;{/cal_cell_blank}

        {cal_cell_other}{day}{/cal_cel_other}

        {cal_cell_end}</td>{/cal_cell_end}
        {cal_cell_end_today}</td>{/cal_cell_end_today}
        {cal_cell_end_other}</td>{/cal_cell_end_other}
        {cal_row_end}</tr>{/cal_row_end}

        {table_close}</table>{/table_close}';

        $this->load->library('calendar',$prefs);

        //{cal_cell_content}<a href="{content}">{day}</a>{/cal_cell_content}
		// moving data from controller to view

		$data['heading'] = "Calendar";
		


		

		
		//exit();

		$data['year'] = $this->uri->segment(3);
		$data['month'] = $this->uri->segment(4);

		if(!is_numeric($data['year'])){
			$data['year'] = date('Y');;
		}
		if(!is_numeric($data['month'])){
			$data['month'] = date('n');
		}


		$this->load->model('calendar_model');
		

		//user id 1 and 7 have registrations
		//$user_id = 7;

		//gets user id
		$user_id  = $this->tank_auth->get_user_id();
		$roleid  = $this->tank_auth->get_user_role();
		//////////////////////////////////////////////////////////////////////
		///////////                                              /////////////
		///////////              DASHBOARD CARD?                 /////////////
		///////////                                              /////////////
		//////////////////////////////////////////////////////////////////////
		if($roleid == 1){	//admin
			$admin_info 	=	"";
			$this->load->model('Registration_model');
			$yesterday = date("Y-m-d", strtotime('yesterday'));
			$today = date("Y-m-d", strtotime('today'));
			

			$yesterday	=	$this->Registration_model->getRegistrationByDateApplied($yesterday);
			$today	=	$this->Registration_model->getRegistrationByDateApplied($today);
			

		$tmp_data = array();
		if($yesterday){
			
			foreach ($yesterday as $row)
			{
			   	$key = date('d', strtotime($row['application_date']));
			    //$tmp_data[$key] = "#";

				$link = base_url(). "calendar/status/" .$row['registrationID']; 
			   	if (array_key_exists ($key, $tmp_data)) { // if multiple events for one date !
				    $tmp_data[$key] .= "<br><a class=\"btn btn-dark\" href=\"$link\">Registration " . $row['registrationID'] . "</a>"; // then append to this array item
				 
				} else{
					$tmp_data[$key] = "<a class=\"btn btn-dark\" href=\"$link\">Registration " . $row['registrationID'] . "</a>"; // then append to this 
				
				}
   
			}
					
		}
		$data['yesterday_dates'] = '';
		$data['yesterday_dates'] = $tmp_data;

		$tmp_data = array();

		if($today){
			
			foreach ($today as $row)
			{
			   	$key = date('d', strtotime($row['application_date']));
			    //$tmp_data[$key] = "#";

				$link = base_url(). "calendar/status/" .$row['registrationID']; 
			   	if (array_key_exists ($key, $tmp_data)) { // if multiple events for one date !
				    $tmp_data[$key] .= "<br><a class=\"btn btn-dark\" href=\"$link\">Registration " . $row['registrationID'] . "</a>"; // then append to this array item
				 
				} else{
					$tmp_data[$key] = "<a class=\"btn btn-dark\" href=\"$link\">Registration " . $row['registrationID'] . "</a>"; // then append to this 
				
				}
   
			}
					
		}

		$data['today_dates'] = '';
		$data['today_dates'] = $tmp_data;
		

		$results = $this->calendar_model->get_all_events($data['year'],$data['month']);
		} else {
			$results = $this->calendar_model->getevents($data['year'],$data['month'], $user_id);
		}
		//////////////////////////////////////////////////////////////////////
		///////////                                              /////////////
		///////////              DASHBOARD CARD?                 /////////////
		///////////                                              /////////////
		//////////////////////////////////////////////////////////////////////


		
		
		
		$data['dates'] = "";
		
		$tmp = array();
		if($results){
			
			foreach ($results as $row)
			{
			   	$key = date('d', strtotime($row->registration_date));
			    //$tmp[$key] = "#";

				$link = base_url(). "calendar/status/" .$row->registrationID; 
			   	if (array_key_exists ($key, $tmp)) { // if multiple events for one date !
				    //$tmp[$key] .= "<br><a class=\"badge badge-light\" href=\"$link\">$row->registration_timein</a>"; // then append to this array item
					if($row->app_status == 'Denied'){
						$tmp[$key] .= "<br><a class=\"badge badge-danger\" href=\"$link\">$row->registration_timein</a>";
					}
					if( $row->app_status == 'Submitted' || $row->app_status == 'submitted' ){ //lowercase for testing
						$tmp[$key] .= "<br><a class=\"badge badge-info\" href=\"$link\">$row->registration_timein</a>";
					}
					if($row->app_status == 'Approved'){
						$tmp[$key] .= "<br><a class=\"badge badge-success\" href=\"$link\">$row->registration_timein</a>";
					}
				} else{
					if($row->app_status == 'Denied'){
						$tmp[$key] = "<a class=\"badge badge-danger\" href=\"$link\">$row->registration_timein</a>";
					}
					if( $row->app_status == 'Submitted' || $row->app_status == 'submitted' ){ //lowercase for testing
						$tmp[$key] = "<a class=\"badge badge-info\" href=\"$link\">$row->registration_timein</a>";
					}
					if($row->app_status == 'Approved'){
						$tmp[$key] = "<a class=\"badge badge-success\" href=\"$link\">$row->registration_timein</a>";
					}
				}
   
			}
			$data['dates'] = $tmp;

		}
		

		
		if ($this->tank_auth->is_logged_in()) {									// logged in
			$data['user_id'] = $this->tank_auth->get_user_id();
			$data['profile'] = $this->Dashboard_model->get_userprofile_detail($data['user_id']);
		}
		$this->load->view('includes/header', $data);
		$this->load->view('calendar_view', $data);
		$this->load->view('includes/footer');


		/////////////

		


	} // \ index


	public function status($regID){

			if(!is_numeric($regID)){ /* if this parameter is missing, or wrong format...*/
			/* best to just redirect*/
			redirect('/', 'location');
			exit(); // do no more
			}


			$this->load->model('registration_model');
			$data['results'] = $this->registration_model->getAllbyRegID($regID);
			$data['application_status'] = $this->registration_model->getRegID($regID);
			

			if ($this->tank_auth->is_logged_in()) {									// logged in
			$data['user_id'] = $this->tank_auth->get_user_id();
			$data['profile'] = $this->Dashboard_model->get_userprofile_detail($data['user_id']);
		}
			$this->load->view('includes/header', $data);
			$this->load->view('calendar/calendar_status_edit_view', $data);
			$this->load->view('includes/footer');

	}

	public function approve($regID){
		if( $this->tank_auth->get_user_role() != 1 ){ //if not admin
			redirect(''); // will redirect
		} else{
			if(!is_numeric($regID)){ /* if this parameter is missing, or wrong format...*/
			/* best to just redirect*/
			redirect('/', 'location');
			exit(); // do no more
			}
		
		$this->load->model('registration_model');
		$item['app_status'] = "Approved";
		$this->registration_model->edit_registration($regID, $item);
		redirect('calendar', 'location');
		}
	
	}
	public function deny($regID){
		if( $this->tank_auth->get_user_role() != 1 ){ //if not admin
			redirect(''); // will redirect
		} else{
			if(!is_numeric($regID)){ /* if this parameter is missing, or wrong format...*/
			/* best to just redirect*/
			redirect('/', 'location');
			exit(); // do no more
			}
		
		$this->load->model('registration_model');
		$item['app_status'] = "Denied";
		$this->registration_model->edit_registration($regID, $item);
		redirect('calendar', 'location');
		}
	
	}

}
