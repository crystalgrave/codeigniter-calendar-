<div class="card text-dark">
<div class="card-header bg-dark text-white">
<h1>Event</h1>
</div>



<?php if(($results)) : ?>
	<?php foreach($results as $row): ?>	
	<div class="card-body">
		
			<h3><?php 
			$date	=	date("l - F d, Y", strtotime($row->registration_date));
			echo  $date;
			?></h3>
			<p><?php 
			$timein	=	date("g a", strtotime($row->registration_timein));
			echo  "Time In: " . $timein;
			?></p>

			<p><?php echo "Room: $row->room_spot_name" ?></p>
			

			<p>With: <?php echo "$row->first_name $row->last_name" ?></p>
			<p class="text-info">Application Status: <span class="badge badge-info"><?php echo "$row->app_status" ?></span></p>
<hr>
		<div class="card-body text-dark">
		<a href="/ci-test1/calendar" class="btn btn-secondary">Back</a>
		<?php if( $this->tank_auth->get_user_role() == 1 ) :?>
			<a href="/ci-test1/calendar/edit/<?php echo $row->registrationdateID ?>" class="btn btn-success">Edit</a>
		<?php endif; ?>
		
		</div>
	</div>
	<?php endforeach;?>
<?php else:?>
	<p>No results</p>
<?php endif; ?>
</div>