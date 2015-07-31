<?php
    include '../../connection.php';
    $activeRequestsSql = "SELECT COUNT(request_ID)
                          FROM order_request
                          WHERE active = 1;";
    $activeRequestsResult = mysqli_query($link, $activeRequestsSql);
    $activeRequests = mysqli_fetch_array($activeRequestsResult);
    if(!$activeRequestsResult){
      echo mysqli_error($link);
    }
    if($activeRequests[0] > 0){
      echo "(".$activeRequests[0].") Purchasing";
    } else{
        echo "Purchasing";
    }
 ?>
