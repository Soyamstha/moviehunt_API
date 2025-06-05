<?php
function apiSuccessResponse($data=null,$message='Request was successful',$token=null){
    $data =[
        'status' => true,
        'message' => $message,
        'data' => $data,
        'token' => $token
    ];
    return response()->json($data, 200);
}
?>
