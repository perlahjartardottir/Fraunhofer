<?php
include '../../connection.php';

$sql = "SELECT sample_ID, sample_name, sample_material, sample_comment
FROM sample;";
$result = mysqli_query($link, $sql);

?>

<div class='row well well-lg'>
   <table class='table table-responsive' style='width:92%;'>
    <thead>
      <tr>
        <th>Sample Name</th>
        <th>Material</th>
        <th>Comment</th>
      </tr>
    </thead>
    <tbody>
    <?php
    	while($row = mysqli_fetch_array($result)){
    		echo"
            <tr>
               <td><a href='#' data-toggle='modal' data-target='#".$row[0]."'>".$row[1]."</a><td>
               <td>".$row[2]."</td>
               <td>".$row[3]."</td>
            </tr>";
    	}
    ?>
    </tbody>
   </table>
    <button type='button' class='btn btn-primary col-md-2' onclick='' style='float:right'>Finish</button>
</div>