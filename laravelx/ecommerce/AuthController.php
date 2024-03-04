<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Cart;
use Illuminate\Http\Request;
use Validator;
use Hash;
use Session;
use Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        // 
    }
    
    # View    
    public function register(){
        return view('user-auth.register');
    }
    
    public function login(){
        return view('user-auth.login');
    }
    
    public function login_via_mobile_otp(){
        return view('user-auth.login-via-mobile-otp');
    }
    
    public function verify_email(){
        return view('user-auth.verify-email');
    }
    
    public function forget_password(){
        return view('user-auth.forget-password');
    }
    
    public function change_password(){
        return view('user-auth.change-password');
    }
    
    # !View    
    
    # process    
    public function register_process(Request $request){
        $arr            =   array();
        
        $validator      =   Validator::make($request->all(), [
                                'name'                  =>  'required|alpha|max:255',
                                'email'                 =>  'required|email|unique:customers,email',
                                'mobile'                =>  'required|numeric|digits:10|unique:customers,mobile',
                                'password'              =>  'required',
                                'confirm_password'      =>  'required|same:password',
                            ]);
        
        if ($validator->fails()) {
            $arr        =   array('status' => false, 'message' => 'Failed to submit form', 'errors' => $validator->getMessageBag()->toArray());
            return response()->json($arr,  200);
        }
        
        $validated      =   $validator->validated();
        
        # OTP to email
            $otp        =   rand(99999,999999);
            
            $to         =   $request->email;
            $from       =   env('MAIL_FROM_ADDRESS'); 
            $subject    =   "Verify Email"; 
            $html       =   "Your OTP is ".$otp;
            
             $headers = "From:" . $from;
            mail($to,$subject,$html, $headers);
            
            // sendMail($to,$from,$subject,$html);
        # !OTP to email
        
        $inputs         =   array(
                                'name'      =>  $request->name,
                                'email'     =>  $request->email,
                                'mobile'    =>  $request->mobile,
                                'password'  =>  Hash::make($request->password),
                                'otp'       =>  $otp
                            );
        
        $result         =  Customer::create($inputs);
    
        if($result != ''):
            $arr        =   array('status' => true, 'message' => 'OTP sent to '.$request->email.'', 'email' => $request->email);
        else:
            $arr        =   array('status' => false, 'message' => 'Some error occured');
        endif;
        
        return response()->json($arr,  200);
    }
    # process    
    
    public function login_process(Request $request){
        
        $session_id     =   '';
        
        $validator = Validator::make($request->all(),[
           'username' => 'required|max:255',
           'password' => 'required',
       ]);

       if($validator->fails()){
            return response()->json([
                'status'=>false,
                'message'=>implode(", ",$validator->messages()->all())
            ]);
       }
        
       $user = Customer::where("email", $request->username)->orWhere('mobile', $request->username)->first();
       
       if($user){
            
            if (Hash::check($request->password, $user->password)) {
                
                Auth::guard('customer')->login($user);
                
                #
                if(Session::has('temp_user')):
                    $update_data    =   array(
                                            'user_id'       => Auth::guard('customer')->user()->id,
                                            'session_id'    => ''
                                        );
                    $cart = Cart::where('session_id', Session::get('temp_user'));
                    $cart = $cart->update($update_data);
                    
                endif;
                #
                
            }else{
                 return response()->json([
                'status'=>false,
                'message'=>'Invalid password'
            ]);
            }

        
            /* $loginData = $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);
            if (!Auth::guard('customer')->attempt($loginData)) {
                return response(['status'=>false, 'message' => 'Invalid Credentials']);
            }*/
        
            // if($session_id):
            //     $update_data    =   array(
            //                             'user_id'       => $user->id,
            //                             'session_id'    => ''
            //                         );
            //     $cart = Cart::where('session_id', $session_id);
            //     $cart = $cart->update($update_data);
                
            // endif;
            
            
            return response()->json([
                'status'=>true,
                'message'=>'Login Successfully.'
            ]);
         
       }else{
            return response()->json([
                'status'=>false,
                'message'=>'User not found'
            ]);
           
       }
    }
    
    public function verify_mobile_otp_process(Request $request){
        $validator = Validator::make($request->all(),[
           'mobile'                 =>  'required|numeric|digits:10',
           'otp'                    =>  'required|numeric|digits:6',
       ]);

        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'message'=>implode(", ",$validator->messages()->all())
            ]);
        }
        
        $user = Customer::Where('mobile', $request->mobile)->first();
       
        if($user){
            if($request->otp == $user->otp){
                
                if($user->mobile_verify == 0):
                    Customer::Where('mobile', $request->mobile)->update(['mobile_verify' => 1]);
                endif;
                
                Auth::guard('customer')->login($user);
                
                return response()->json([
                    'status'    =>  true,
                    'mobile'    =>  $request->mobile,
                    'message'   =>  'Otp verify'
                ]);
            }else{
                return response()->json([
                    'status'    =>  false,
                    'message'   =>  'Incorrect Otp'
                ]);
            }
        }else{
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Mobile number not found'
            ]);
       }
    }

    public function verify_mobile(Request $request){
        
        $validator = Validator::make($request->all(),[
           'mobile'                =>  'required|numeric|digits:10',
       ]);

       if($validator->fails()){
            return response()->json([
                'status'=>false,
                'message'=>implode(", ",$validator->messages()->all())
            ]);
       }

       $user = Customer::Where('mobile', $request->mobile)->first();
       
       if($user){
            $otp        =   rand(99999,999999);
            
            $curl = curl_init();
        
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://www.fast2sms.com/dev/bulkV2?authorization=bEaMOCtYy2q1XvcWgPHNFIpK4iZxQs63dJnzeUT7BuAmrfS8jDsy0DFd6gjzPXHvk8bKcxNhAR3mr1QY&variables_values=$otp&route=otp&numbers=".urlencode($user->mobile),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache"
                    ),
                ));
                
                $response       = curl_exec($curl);
                $err            = curl_error($curl);
                
                curl_close($curl);
            
            $user       = Customer::Where('mobile', $request->mobile)->update(['otp' => $otp]);
            
            return response()->json([
                'status'    =>  true,
                'message'   =>  'Otp sent to given mobile'
            ]);
       }else{
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Mobile number not found'
            ]);
       }
    }
    
    public function verify_email_process(Request $request){
        $validator = Validator::make($request->all(),[
           'email' => 'required|email|max:255',
           'otp' => 'required|numeric|digits:6',
       ]);

       if($validator->fails()){
            return response()->json([
                'status'=>false,
                'message'=>implode(", ",$validator->messages()->all())
            ]);
       }

       $checkOTP = Customer::where("email", $request->email)->where("otp", $request->otp)->first();

        if(!$checkOTP){
            return response()->json([
                'status'=>false,
                'message'=>'Incorrect OTP.'
            ]);
       }
       else {
           $checkOTP = Customer::where("email", $request->email)->update(['email_verify' => 1]);
            return response()->json([
                    'status'=>true,
                    'message'=>'Register Successfully.',
                 ]);
       }
    
    }
    
    public function change_password_process(Request $request){
        
        if($request->type == 'forget-password'){
            $validator = Validator::make($request->all(),[
               'new_password'       => 'required',
               'confirm_password'   => 'required|same:new_password'
            ]);
            
            if($validator->fails()):
                return response()->json([
                    'status'=>false,
                    'message'=>implode(", ",$validator->messages()->all())
                ]);
            endif;
            
        }else{
            $validator = Validator::make($request->all(),[
               'old_password'       => 'required',
               'new_password'       => 'required',
               'confirm_password'   => 'required|same:new_password',
            ]);
            
            if($validator->fails()):
                return response()->json([
                    'status'=>false,
                    'message'=>implode(", ",$validator->messages()->all())
                ]);
            endif;
        }
        
            $check_mobile     =   Customer::where("mobile", $request->mobile)->first();
            
            if(!$check_mobile):
                return response()->json([
                    'status'        =>  false,
                    'message'       =>  'Incorrect mobile number',
                 ]);
            else:
                Customer::where("mobile", $request->mobile)->update(['password' => Hash::make($request->new_password)]);
                return response()->json([
                    'status'        =>  true,
                    'message'       =>  'Password Change successfully',
                 ]);
            endif;
        
        
    }
   
    public function logout(){
         Auth::guard('customer')->logout();
         return redirect()->route('home');
    }
    
}