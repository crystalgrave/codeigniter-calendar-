<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">
body {
	font-family: Tahoma;
}



/* declare a 7 column grid on the table */
#calendar {
	width: 100%;
  display: grid;
  grid-template-columns: repeat(7, 1fr);
}

#calendar tr, #calendar tbody {
  grid-column: 1 / -1;
  display: grid;
  grid-template-columns: repeat(7, 1fr);
 width: 100%;
}

caption {
	text-align: center;
  grid-column: 1 / -1;
  font-size: 130%;
  font-weight: bold;
  padding: 10px 0;
}

#calendar a {
	color: white;
	text-decoration: none;
}

#calendar td, #calendar th {
	padding: 5px;
	box-sizing:border-box;
	border: 1px solid #ccc;
}

#calendar .weekdays {
	background: #2E2D2D;  
}


#calendar .weekdays th {
	text-align: center;
	text-transform: uppercase;
	line-height: 20px;
	border: none !important;
	padding: 10px 6px;
	color: #fff;
	font-size: 13px;
}

#calendar td {
	min-height: 90px;
  display: flex;
  flex-direction: column;
}

#calendar .days li:hover {
	background: #d3d3d3;
}

#calendar .date {
	text-align: center;
	margin-bottom: 5px;
	padding: 4px;
	background: #333;
	color: #fff;
	width: 20px;
	border-radius: 50%;
  flex: 0 0 auto;
  align-self: flex-end;
}

#calendar .event {
  flex: 0 0 auto;
	font-size: 13px;
	border-radius: 4px;
	padding: 5px;
	margin-bottom: 5px;
	line-height: 14px;
	background: #e4f2f2;
	border: 1px solid #b5dbdc;
	color: #009aaf;
	text-decoration: none;
}

#calendar .event-desc {
	color: #666;
	margin: 3px 0 7px 0;
	text-decoration: none;	
}

#calendar .other-month {
	background: grey;
	color: #666;
}

/* ============================
				Mobile Responsiveness
   ============================*/


@media(max-width: 768px) {

	#calendar .weekdays, #calendar .other-month {
		display: none;
	}

	#calendar li {
		height: auto !important;
		border: 1px solid #ededed;
		width: 100%;
		padding: 10px;
		margin-bottom: -1px;
	}
  
  #calendar, #calendar tr, #calendar tbody {
    grid-template-columns: 1fr;
  }
  
  #calendar  tr {
    grid-column: 1 / 2;
  }

	#calendar .date {
		align-self: flex-start;
	}
}
</style>

<div class="row text-dark mt-5 mb-5">
	<div class="col">
		<?php 		$roleid  = $this->tank_auth->get_user_role();
		if($roleid == 1): ?>
		<!-- ADMIN -->
		<div class="card">
			<div class="card-header bg-dark">
				<h2 class="text-center text-primary">New Registrations</h2>
			</div>
			
			<div class="card-body d-flex justify-content-between">
				<div>
					<h5>Registrations From Today</h5>
					<?php foreach($today_dates as $item): ?>
					<?php echo $item ?>
					
					<?php endforeach;?>
				</div>
				<div>
					<h5>Registrations From Yesterday</h5>
					<?php foreach($yesterday_dates as $item): ?>
					<?php echo $item ?>
					
					<?php endforeach;?>
				</div>
			</div>
		</div>
	<!-- ADMIN -->
		<?php endif;?>

		<div class="card text-black">
			<div class="card-header bg-dark">
				<h2 class="text-center text-primary">Calendar</h2>
			</div>
			<div class="card-body">
				<a class="btn btn-dark btn-lg my-2" href="<?php base_url();?>signup">Signup</a>
				<a class="btn btn-dark btn-lg my-2" href="<?php base_url();?>signup/group">Group Signup</a>
				<div>
					<h5>Status Colours</h5>
					<span class="badge badge-pill badge-info">Submitted</span>
					<span class="badge badge-pill badge-success">Approved</span>
					<span class="badge badge-pill badge-danger">Denied</span>
				</div>
				<div class="row text-center">
					<?php echo $this->calendar->generate($year,$month,$dates); ?>
				</div>
				
			</div>
		</div>
	</div>

</div>




