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
function apiErrorResponse($message='Request was successful',$code=422){
    $data =[
        'status' => false,
        'message' => $message,
        'data' => null,
    ];
    return response()->json($data, $code);
}
?>
