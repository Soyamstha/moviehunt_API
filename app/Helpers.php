<?php
function apiSuccessResponse($data=null,$message='Request was successful'){
    $data =[
        'status' => true,
        'message' => $message,
        'data' => $data
    ];
    return response()->json($data, 200);
}
?>
