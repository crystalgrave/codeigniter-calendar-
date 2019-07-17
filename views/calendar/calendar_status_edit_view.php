<?php if( is_array($results) ) : ?>



<div class="row bg-dark mt-5">

    <div class="col-md-12 mt-4">
        <h2 class="text-center text-primary">Registration Status</h2>
    </div>

    <div class="card-body">
        <div class="form-group">
            <label for="date text-center">Date/Time Selected: </label>
            
            <ul class="list-group">
            <?php $old = '';
                foreach($results as $row): ?>	

                    <li class="list-group-item text-dark">
                    <?php  echo date("l, F d, Y", strtotime($row->registration_date) ) ?>
                    <dl class="row">
                        <dt class="col-sm-3">Time In: </dt>
                        <dd class="col-sm-9"><?php  echo  date("g a", strtotime($row->registration_timein) ) ?></dd>

                        <dt class="col-sm-3">Room: </dt>
                        <dd class="col-sm-9"><?php  echo $row->room_spot_name ?></dd>

                        

                    </dl>
                    </li>
                    <?php $old = $row->registration_date ?>
            <?php endforeach;?>
            </ul>
            
            <dl class="">
                <dt class="col-sm-3">With: </dt>
                <dd class="col-sm-9">
                <?php $old = ''; 
                $array = array();
                foreach ($results as $row):?>
                    <?php if($old == $row->email):?>
                    <!-- do nothing -->
                    <?php else:?>
                    <?php $array[]  =   $row->email; $old = $row->email; ?>
                    <?php endif; ?>
                <?php  endforeach ; 
                $new = array_unique($array);
                ?>
                <?php foreach($new as $item) :?>
                <a href="mailto:<?php echo $item ?>"><?php  echo $item ?></a>
                <?php  endforeach ; ?>
                </dd>
            </dl>
        </div>
        <div class="form-group">
    		<label for="timein">Status</label>
            <ul class="list-group">
            <?php foreach($application_status as $row): ?>	
                <?php 
                if($row->app_status == 'Denied'){
                    $appstatus = $row->app_status; echo "<span class=\"text-danger\">" . $appstatus . "</span>"; 
                } 
                elseif($row->app_status == 'Approved'){
                    $appstatus = $row->app_status; echo "<span class=\"text-success\">" . $appstatus . "</span>";
                } else {
                    $appstatus = $row->app_status; echo "<span class=\"text-info\">" . $appstatus . "</span>";
                }
                ?>
            <?php endforeach;?>
            </ul>
        </div>
    </div>    
</div>
	
<?php else:?>
	<p>No results</p>
<?php endif; ?>    
	<div class="col-md-12">
		<div class="card-body text-dark">
		<a href="/ci-test1/calendar" class="btn btn-secondary">Cancel & Go Back</a>
        <?php $roleid  = $this->tank_auth->get_user_role();
		if($roleid == 1): ?>
                <?php if($appstatus == 'Submitted'): ?>
                    <?php foreach($application_status as $row): ?>	
                    <a onclick="return confirm('Are You Sure? This action cannot be changed after.')" href="<?php base_url();?>/ci-test1/calendar/approve/<?php echo $row->registrationID;?>" class="btn btn-success">Approve Registration</a>
                    <?php endforeach;?>

                    <?php foreach($application_status as $row): ?>	
                    <a onclick="return confirm('Are You Sure? This action cannot be changed after.')" href="<?php base_url();?>/ci-test1/calendar/deny/<?php echo $row->registrationID;?>" class="btn btn-outline-danger">Deny Registration</a>
                    <?php endforeach;?>
                <?php endif;?>
        <?php endif;?>
		</div>
</div>




