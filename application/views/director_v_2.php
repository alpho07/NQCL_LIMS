                                 <?php error_reporting(1);?>
<style>
    #rej{
        display:none;
    }
</style>
<script>
    $(document).ready(function(){
      $('.reject').on('click',function(){
        $.fancybox.open({
          href:'#rej',
          width:600,
          height:500
       });
      });
      
      $('#rejecting').click(function(){
        $.ajax({
            type:'post',
            url:"<?php echo base_url();?>directors/reject_coa_draft/"+$('#labref').val()+"/"+$('#level').val(),
            data:$('#reasons').serialize(),
            success:function(){
               window.location.href='<?php echo base_url();?>directors/draft_coa_review/';
            },
            error:function(){
              alert('Notice: You have already posted a rejection reason for this sample!');  
               window.location.href='<?php echo base_url();?>directors/draft_coa_review/';
            }
        });
      });
       $('#canceling').click(function(){
          alert('a');
      });
      
      $('.reason').on('mouseover',function(){          
          id=$(this).prop('id');
          
           $.ajax({
            type:'get',
            url:"<?php echo base_url();?>directors/reject_reason/"+id+"/COA_Reviewer/",
            dataType:'json',
            success:function(data){
               alert(data[0].reject_reason);
            },
            error:function(){
              alert('An error occured when attempting to retrieve Rejection reason!');  
              
            }
        });
        
          
    });
    });
</script>
<body> 
    <p>DRAFT COA REVIEW</p>
<hr />


</div> 
<?php $user_id = $this->session->userdata('user_id'); ?>
<!-- End Menu --> 
<div>
    <table id = "refsubs">
        <thead>
            <tr>
                <th>File Name</th>
                <th>Lab Reference No</th>
<!--                <th>Download </th> -->
                <th>View</th>
                <th>Status</th>
                <th>Action</th>
                <th>Reject</th>
                <th>Priority</th>

            </tr>
        </thead>
        <tbody>
             
                   
            <?php foreach ($worksheets as $sheet): ?>
                <tr>
                     <input type="hidden" id="level" value="COA_Review"/>
                     <input type="hidden" id="labref" value="<?php echo $sheet->folder;?>"/>
                    <td><?php echo $sheet->folder . '.xlsx'; ?></td>
                    <td><strong><em><?php echo $sheet->folder; ?></em> </strong></td>
<!--                    <td>Worksheet: <?php echo anchor('COA/' . $sheet->folder . '_COA.xlsx', 'Download'); ?> &nbsp; | &nbsp;COA: <?php echo anchor('COA/' . $sheet->folder . '_COA.xlsx', 'Download'); ?></td>-->
                    <td><?php echo anchor('coa/generateCoa_cr/' . $sheet->folder, 'View COA') ?></td>

                    <?php if ($sheet->approval_status === '0') { ?>
                    <td style="background-color: yellow;"><span style=" color: black; font-weight: bold; border-radius: 2px;">Not yet Approved</span></td>
                    <?php } else if ($sheet->approval_status === '1') { ?>
                        <td style="background-color: yellowgreen;"><span  style=" color:white; font-weight: bold; border-radius: 2px;">Approved</span ></td>
                    <?php } else if ($sheet->approval_status === '2') { ?>
                        <td style="background-color: #FF0000;"><span style="color: white; font-weight: bold; border-radius: 2px;">Rejected : <a href="" class="reason" id="<?php echo $sheet->folder;?>">Why?</a></span></td> 
                    <?php } ?>
                    <td><?php echo anchor('directors/approve_coa_draft/' . $sheet->folder, 'Approve','class="approve"'); ?></td>
                    <td style="background: #0FF; font-weight: bolder; "><a href="#rej" class="reject">Reject</a></td>
                    <?php if ($sheet->priority === '1') { ?>
                        <td><span id="high">High</span></td>
                    <?php } else { ?>
                        <td><span id="low">Low</span></td>    
                    <?php } ?>

                <?php endforeach; ?>
            </tr>
        </tbody>
    </table>
    <div id="rej">
            <?php $this->load->view('rejections_v'); ?>
        </div>

    <script type="text/javascript">
        $('#refsubs').dataTable({
            "bJQueryUI": true
        }).rowGrouping({
            iGroupingColumnIndex: 1,
            sGroupingColumnSortDirection: "asc",
            iGroupingOrderByColumnIndex: 1,
            //bExpandableGrouping:true,
            //bExpandSingleGroup: true,
            iExpandGroupOffset: -1

        });

        $('.history').live("click", function(e) {
            e.preventDefault();
            var nTr = this.parentNode.parentNode;

            if ($(this).text() == 'Show') {

                $(this).text("Hide");

                //alert("Under Construction");

                var id = $(this).attr("id");
                //var type = $(this).attr("rel");

                $.post("<?php echo site_url('inventory/columns_showHistory'); ?>" + "/" + id, function(history) {

                    rtable.fnOpen(nTr, history, 'history');
                })


            }


            else {

                rtable.fnClose(nTr);

                $(this).text("Show");

            }


        });

    </script>


</div>


</body> 
</html> 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             