<h1>Event</h1>


<?php if(($results)) : ?>
	<?php foreach($results as $row): ?>	
<div class="card text-dark">
    <div class="form-group">
        <label for="date">Date:</label>
        <input class="form-control" type="date" name="date" id="" value="<?php echo $row->registration_date ?>">
    </div>
    <div class="form-group">
		<label for="timein">Time In</label>
        <input class="form-control" type="time" name="timein" id="" value="<?php echo $row->registration_timein ?>">
    </div>
    <div class="form-group">
        <label for="timeout">Time Out</label>
        <input class="form-control" type="time" name="timeout" id="" value="<?php echo $row->registration_timeout ?>">
    </div>
    <div class="form-group">
        <label for="roomspot">Room Spot</label>
        
        <select name="roomspot" id="" class="form-control">
        <?php if($row->room_spot_name){ //if one exists, then please display the current room name
            echo "<option>$row->room_spot_name (current)</option>";
        }?>
        <option value="">J112 One</option>
        <option value="">J112 Two</option>
        <option value="">J112 Three</option>
        <option value="">S113 One</option>
        <option value="">S113 Two</option>
        <option value="">S113 Three</option>
        </select>
    </div>
</div>
	<?php endforeach;?>
<?php else:?>
	<p>No results</p>
<?php endif; ?>    
	<div class="card">
		<div class="card-body text-dark">
		<a href="/ci-test1/calendar" class="btn btn-secondary">Cancel & Go Back</a>
		<a href="" class="btn btn-success">Update Booking</a>
        <a href="" class="btn btn-outline-danger">Delete Booking</a>
		</div>
	</div>

