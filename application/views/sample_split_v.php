<html lang ="en">
	
<legend><a href="<?php echo site_url()."sample_issue/listing"; ?>" >Samples Listing</a>
&nbsp;&rarr;&nbsp;Sample Split & / Issue&nbsp;&rarr;&nbsp;<?php $reqid = $this -> uri -> segment(3); echo $reqid; ?></legend>

<hr />
<!-- If sample had already been issued show info about to whom had issued -->
<?php if(!empty($assignment)){ ?>
  <fieldset>
    <legend>Previous Assignments</legend>
    <table id = "assignment_table" >
      <thead>
        <tr>
          <th>Name</th>
          <th>Department</th>
          <th>Quantity</th>
          <th>Time</th>
        </tr>
      </thead>
      <tbody>
        <?php for($i=0;$i<count($assignment);$i++) { ?>
        <tr>
          <td><?php echo $assignment[$i]['User']['fname'] . " " . $assignment[$i]['User']['lname'] ?></td>
          <td><?php echo $assignment[$i]['Units']['name'] ?></td>
          <td><?php echo $assignment[$i]['Samples_no'] . " " .$sample_listing[0]['Packaging']['name'] ?></td>
          <td><?php echo $assignment[$i]['created_at'] ?></td>
        </tr>
        <?php }?>
      </tbody>
    </table>
  </fieldset>
<?php } ?>

<!-- Loop through array of all units/departments -->
<?php foreach ($all_units as $units) {
	//If department is not in array of those that have this sample assigned, show assignment form, else don't.
	//if(!in_array($units['Units']['id'], $assigned_units)){	
?>
	<legend id="unit" data-tableid = "<?php echo $units["id"] ?>" ><?php echo $units['name']; ?></legend>

<table id="tests" data-tableid="<?php echo $units["id"]?>" >
	
<tr><td colspan="4" ><hr></td></tr>	

<tr>
  <th>Samples Available</th>
  <th>Samples to Issue</th>
  <th>Analyst</th>
  <th>Assign</th>
</tr>

<form id = "sample_issue<?php echo $units["id"] ?>" data-dept = "<?php echo $units["id"] ?>" class = "sample_issue">
	<tr class="unitrows" id = "<?php echo $units["id"] ?>">
		<td class="samples_available"><span><?php echo $sample_listing[0]['sample_qty']; ?></span></td>
		<td class ="samples2issue"><input type="text" id="samples2issue" name="samples_no" required /></td>
		<td>
			<span>
				<select name="analyst_id" id="analyst<?php echo $units["id"] ?>">
	                            <option value="" >Select Analyst</option>
					<?php foreach($analysts as $analyst){?>
            <?php if(!in_array($analyst['id'], $analysts_assigned)){?>
						  <option value="<?php echo $analyst['id']; ?>"><?php echo $analyst['fname'] ." ".$analyst['lname']; ?></option>
					<?php } } ?>	
				</select>
			</span>
		</td>
	  <input type="hidden" name="analyst_name" id="analyst_name<?php echo $units['id'] ?>" value=""/>
    <input type="hidden" name="department_name" id="department<?php echo $units['id'] ?>" value="<?php echo $units['name'] ?>"/>
		<input type="hidden" name="dept_id" value="<?php echo $units["id"] ?>"/>
		<input type="hidden" name="lab_ref_no" value="<?php echo $reqid ?>"/>
		<input type="hidden" name="upd_samples_qty" value="<?php echo $sample_listing[0]['sample_qty']?>"/>
		<input type="hidden" name="status_id" value= "2"/>
		<td><span><input type ="submit" name="sample_assign" id="assign_button" class="submit-button" value="Assign"/> </span></td>	
	</tr>
</form>
</table>

<!--Include script inside PHP for loop so as to have form id unique -->
<script type="text/javascript">




$('#analyst<?php echo $units["id"] ?>').change(function(){
    analyst_name=$('#analyst<?php echo $units["id"] ?> option:selected').text();
    $('#analyst_name<?php echo $units["id"] ?>').val(analyst_name);
});


$('#sample_issue<?php echo $units["id"] ?>').submit(function(e){
e.preventDefault();
	$.ajax({
		type: 'POST',
		url: '<?php echo base_url() . "sample_issue/save" ?>',
		data: $('#sample_issue<?php echo $units["id"] ?>').serialize(),
		dataType: "json",
		success:function(response){
			if(response.status === "success"){
        console.log(response.post_data.samples_no);
        console.log(response.test_array);
        // Generate success message from post data
        var success_message = "<b>" + response.post_data.samples_no + " <?php echo $sample_listing[0]['Packaging']['name']; ?> " + "</b>" + "issued to " + "<b>" + response.post_data.analyst_name + "</b>" + " - <b>" + response.post_data.department_name + "</b>";
        tbls = $('table').length; 
        
        //console.log(tbls);
        if(tbls == 1 ){
        //Use noty to alert successful assign.
         noty({ text: success_message,
                  type: 'success',
            });
          //Delay closing of fancybox, to allow noty-fication of successful assignment of last remaining department/unit 
         setTimeout("parent.$.fancybox.close()", 1000);
        }
        else{       
           $('[data-tableid = "<?php echo $units["id"]; ?>"]').remove();
           //Use noty to alert successful assign.
           noty({ text: success_message,
                  type: 'success',
            });
        }
			}
			else if(response.status === "error"){
				   //Use noty to alert unsuccessful assign.
           noty({ text: 'Assign unsuccessful. Check selection of Analyst.',
                  type: 'error',
                  timeout: true,
            });

        	//alert(response.message);
			}
		},
		error:function(){
		}
	})
})
</script>



<?php } ?> <!-- Closes second if statement -->
<?php //} ?> <!-- Closes for loop -->


<script>
$(document).ready(function(){

  $('#assignment_table').dataTable({
    "bJQueryUI":true,
    "bRetrieve":false,
    "bSearchable":false,
    "bInfo":false,
    "bFilter":false,
    "bPaginate":false,
    "bSort":false
  });


  $('.unitrows').each(function(i){
  	
  $('.samples2issue input').eq(i).keyup(function(){
  	
  	var s_avail = $('.samples_available span').eq(i).text();
  	
    var samples_a = parseInt(s_avail);
  	
  	if($(this).val() > samples_a ) {
  	
  	alert("Samples to Issue must be less than Samples Available.");
  	
  	$(this).val("");
  	
  	}
  	
  	else if ($(this).val() <= 0) {
  		
  	alert("Samples to Issue must be greater than zero.");
  		
  	$(this).val("");
  	
  	}	
  	
  	else
    {
    	var diff = $('.samples_available span').eq(i).text() - $(this).val();
    	if($('.unitrows').length == 2){
     
     $('.samples_available span').eq(i+1).text(diff);
     $('.samples_available span').eq(i-1).text(diff); 	
  	}
  	
  	
  	else if ($('.unitrows').length == 3) {
  	
  	$('.samples_available span').eq(i+1).text(diff);
  	$('.samples_available span').eq(i+2).text(diff);
    $('.samples_available span').eq(i-1).text(diff); 	
  	$('.samples_available span').eq(i-2).text(diff);
  	}	
  		
  	}
  	
  	});
	});
    
})	
</script>


</html>
