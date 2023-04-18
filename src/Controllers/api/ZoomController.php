<?php

namespace Noorisyslaravel\Zoom\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MacsiDigital\Zoom\Facades\Zoom;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ZoomController extends Controller
{
    public function User(Request $req)
    {
        $data = $req->only('first_name', 'last_name', 'email', 'password');
        $validator = Validator::make($data, [
            'first_name'   => 'required|regex:/^[\pL\s]+$/u|min:3',
            'last_name'   => 'required|regex:/^[\pL\s]+$/u|min:3',
            'password'   => 'required||max:20||min:8',
            'email' => 'required|unique:users',

        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    'status'    => 'failed',
                    'errors'    =>  $validator->errors(),
                    'message'   =>  trans('validation.custom.input.invalid'),
                ],
                400
            );
        } 
        else
        {
            try
                {
                    $first_name = $req->first_name;
                    $last_name = $req->last_name;
                    $email = $req->email;
                    $password = $req->password;
                    // will return the created model so you can capture it if required.
                    $user = Zoom::user()->create([
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'email' => $email,
                        'password' => $password
                    ]); 
                    $user->save();
                    if(!empty($user))
                    {
                        return response()->json([
                            'status'  => 'success',
                            'message' => 'User Created Successfuly!',
                            'data'   => $user
                        ],200);
                    }
                    else
                    {
                        return response()->json([
                            'status'  => 'failed',
                            'message' => 'Something went wrong!',
                            
                        ],400);
                    }
                    
                }
                catch (\Throwable $e)
            {
                return response()->json([
                    'status'  => 'failed',
                    'message' => trans('validation.custom.invalid.request'),
                    'error'   => $e->getMessage()
                ],500);
            }
        }
        
    }
    public function updateUser(Request $req)
    {
        $data = $req->only('id','first_name', 'last_name');
        $validator = Validator::make($data, [
            'id' => 'required',
            'first_name'   => 'required|regex:/^[\pL\s]+$/u|min:3',
            'last_name'   => 'required|regex:/^[\pL\s]+$/u|min:3',
            

        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    'status'    => 'failed',
                    'errors'    =>  $validator->errors(),
                    'message'   =>  trans('validation.custom.input.invalid'),
                ],
                400
            );
        } 
        else
        {
            $id = $req->id;
            $first_name = $req->first_name;
            $last_name = $req->last_name;
            
            try
                {
                    $user = Zoom::user()->find($id)->update(['first_name' => $first_name, 'last_name' => $last_name]);
                    return response()->json([
                        'status'  => 'success',
                        'message' => 'User Updated Successfuly!',
                        'data'   => $user
                    ],200);
                }
                catch (\Throwable $e)
                {
                    return response()->json([
                        'status'  => 'failed',
                        'message' => trans('validation.custom.invalid.request'),
                        'error'   => $e->getMessage()
                    ],500);
                }
        }
    }
    public function allUser()
    {
        try
        {
            $users = Zoom::user()->all();
            if(!empty($users))
            {
                return response()->json([
                    'status'  => 'success',
                    'message' => 'All Users',
                    'data'   => $users
                ],200);
            }
            else
            {
                return response()->json([
                    'status'  => 'failed',
                    'message' => 'User Not Exists',
                    
                ],400);
            }
            
        }
        catch (\Throwable $e)
            {
                return response()->json([
                    'status'  => 'failed',
                    'message' => trans('validation.custom.invalid.request'),
                    'error'   => $e->getMessage()
                ],500);
            }
    }
    public function meetings(Request $req)
    {
        
          
            try
            {
                
                $meetings = Zoom::user()->find('me')->meetings;
                if(!empty( $meetings))
                {
                    return response()->json([
                        'status'  => 'success',
                        'message' => 'All Meetings',
                        'data'   => $meetings
                    ],200);
                }
                else
                {
                    return response()->json([
                        'status'  => 'failed',
                        'message' => 'Meeting Not Exists',
                        'data'   => $meetings
                    ],200);
                }
            }
            catch (\Throwable $e)
                {
                    return response()->json([
                        'status'  => 'failed',
                        'message' => trans('validation.custom.invalid.request'),
                        'error'   => $e->getMessage()
                    ],500);
                }
        
    }
    public function createMeeting(Request $req)
    {
        $data = $req->only('id','topic','start_time');
        $validator = Validator::make($data, [
            'id'   => 'required',
            'topic' => 'required',
            'start_time' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    'status'    => 'failed',
                    'errors'    =>  $validator->errors(),
                    'message'   =>  trans('validation.custom.input.invalid'),
                ],
                400
            );
        } 
        else
        {
            $id = $req->id;
            $topic = $req->topic;
            $start_time = $req->start_time;
            try
            {
                $meeting = Zoom::meeting();
                $meeting = Zoom::meeting()->make([
                    'topic' => $topic,
                    'type' => 8,
                    'start_time' => $start_time, // best to use a Carbon instance here.
                ]);
                $meeting->recurrence()->make([
                    'type' => 2,
                    'repeat_interval' => 1,
                    'weekly_days' => '2',
                    'end_times' => 5
                ]);
                $meeting->settings()->make([
                    'join_before_host' => true,
                    'approval_type' => 2,
                    // 'registration_type' => 2,
                    'enforce_login' => false,
                    'waiting_room' => false,
                    'focus_mode' =>'1',
                    'mute_upon_entry' => true,
                    'host_save_video_order' =>'1'
                ]);
                
                $user = Zoom::user()->find($id)->meetings()->save($meeting);

                //   $user->meetings()->save($meeting);

                return response()->json([
                    'status'  => 'success',
                    'message' => 'Meeting Created Successfuly!',
                    'data'   => $meeting
                ],200);
            }
            catch (\Throwable $e)
                {
                    return response()->json([
                        'status'  => 'failed',
                        'message' => trans('validation.custom.invalid.request'),
                        'error'   => $e->getMessage()
                    ],500);
                }
        }        
    }
    public function updateMeeting(Request $req)
    {
        $data = $req->only('id','agenda','duration','password');
        $validator = Validator::make($data, [
            'id'   => 'required',
            'agenda' => 'required',
            'duration' => 'required',
            'password' => 'required|max:10||min:10'
        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    'status'    => 'failed',
                    'errors'    =>  $validator->errors(),
                    'message'   =>  trans('validation.custom.input.invalid'),
                ],
                400
            );
        } 
        else
        {
            $id = $req->id;
            $agenda = $req->agenda;
            $duration = $req->duration;
            $password = $req->password;
            try
            {
               $meeting = Zoom::meeting()->find($id)->update([
                'agenda' => $agenda,
                'duration' => $duration,
                'password' => $password,
                'pre_schedule' => true,
                'type' => '2',
                'approval_type' => 2,
                'topic' => 'My new updated meeting',
                'start_time' => '2023-04-18 12:36:00'

               ]);
               return response()->json([
                'status'  => 'success',
                'message' => 'Meeting Updated',
                'data'   => $meeting
            ],200);
            }
            catch (\Throwable $e)
                {
                    return response()->json([
                        'status'  => 'failed',
                        'message' => trans('validation.custom.invalid.request'),
                        'error'   => $e->getMessage()
                    ],500);
                }
        }
    }
    public function deleteMeeting(Request $req)
    {
        $data = $req->only('id');
        $validator = Validator::make($data, [
            'id'   => 'required',
            
        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    'status'    => 'failed',
                    'errors'    =>  $validator->errors(),
                    'message'   =>  trans('validation.custom.input.invalid'),
                ],
                400
            );
        } 
        else
        {
            $id = $req->id;
            try
            {
                $meeting = Zoom::meeting();
                $meeting->find($id)->delete(['schedule_for_reminder' => false]);
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Meeting Deleted! ',
                    'data'   => $meeting
                ],200);
            }
            catch (\Throwable $e)
                {
                    return response()->json([
                        'status'  => 'failed',
                        'message' => trans('validation.custom.invalid.request'),
                        'error'   => $e->getMessage()
                    ],500);
                }
        }
    }
    public function endMeeting(Request $req)
    {
        $data = $req->only('id');
        $validator = Validator::make($data, [
            'id'   => 'required',
            
        ]);
        if ($validator->fails()) 
        {
            return response()->json(
                [
                    'status'    => 'failed',
                    'errors'    =>  $validator->errors(),
                    'message'   =>  trans('validation.custom.input.invalid'),
                ],
                400
            );
        } 
        else
        {
            $id = $req->id;
            try
            {
                $meeting = Zoom::meeting()->update([
                    'id' => $id,
                    'action' => 'end',
                ]);
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Meeting Ended',
                    'data'   => $meeting
                ],200);
            }
            catch (\Throwable $e)
                {
                    return response()->json([
                        'status'  => 'failed',
                        'message' => trans('validation.custom.invalid.request'),
                        'error'   => $e->getMessage()
                    ],500);
                }
        }
    }
    // public function role()
    // {
    //     try
    //     {
    //         $role = Zoom::role();
    //         $role->get();
    //         return response()->json([
    //             'status'  => 'success',
    //             'message' => 'Roles',
    //             'data'   => $role
    //             ],200);
    //     }
    //     catch (\Throwable $e)
    //     {
    //         return response()->json([
    //             'status'  => 'failed',
    //             'message' => trans('validation.custom.invalid.request'),
    //             'error'   => $e->getMessage()
    //         ],500);
    //     }
    // }
    // public function webinar(Request $req)
    // {
    //     $data = $req->only('id','agenda');
    //     $validator = Validator::make($data, [
    //         'id'   => 'required',
    //         'agenda' => 'required',
    //         // 'start_time' => 'required'
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json(
    //             [
    //                 'status'    => 'failed',
    //                 'errors'    =>  $validator->errors(),
    //                 'message'   =>  trans('validation.custom.input.invalid'),
    //             ],
    //             400
    //         );
    //     } 
    //     else
    //     {
    //         $id = $req->id;
    //         $agenda = $req->topic;
    //         // $start_time = $req->start_time;
    //         try
    //         {
    //             // echo "hiiiii";exit;
    //             $webinar = Zoom::webinar(); 
    //             $webinar = Zoom::webinar()->make([
    //                 'topic' => $agenda,
    //                 'type' => 5,
    //                 // 'start_time' => $start_time, // best to use a Carbon instance here.
    //               ]);
              
    //             //   $webinar->recurrence()->make([
    //             //     'type' => 2,
    //             //     'repeat_interval' => 1,
    //             //     'weekly_days' => "2",
    //             //     'end_times' => 5
    //             //   ]);
              
    //             //   $webinar->settings()->make([
    //             //     'approval_type' => 1,
    //             //     'registration_type' => 2,
    //             //     'enforce_login' => false,
    //             //   ]);
              
    //               $user = Zoom::user()->find($id)->webinars()->save($webinar);   
    //               return response()->json([
    //                 'status'  => 'success',
    //                 'message' => 'Meeting Created',
    //                 'data'   => $webinar
    //             ],200);
    //         }
    //         catch (\Throwable $e)
    //             {
    //                 return response()->json([
    //                     'status'  => 'failed',
    //                     'message' => trans('validation.custom.invalid.request'),
    //                     'error'   => $e->getMessage()
    //                 ],500);
    //             }
    //     }
    // }

}
