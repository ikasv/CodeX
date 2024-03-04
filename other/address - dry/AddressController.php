<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Address;
use App\Models\State;
use App\Models\City;

class AddressController extends Controller
{
  	/*
    * @param : user
    * consturct
    * States
    * Cities
    * Address
    * Create or Update Address
    * Address 
    * Delete Address
    * Set Default Address
    */
  
  	protected $user;
  
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user	= auth()->user();

            return $next($request);
        });
    }
  
  	# States
  	public function states(){
      	
      	$arr						=	[];
    	$records					=	State::select('id', 'name')->get();
      	
      	if($records):
      		$arr					=	['status' => true, 'message' => 'Successfully data fetched', 'data' => $records];
      	else:
      		$arr					=	['status' => false, 'message' => 'No data found'];
      	endif;
      
      	return response()->json($arr);
     }
  	# End States
  	
  	# Cities
  	public function cities(){
      	
      	# Validation 
          $validator                              =   validator()->make(request()->all(), [
                                                        'state_id'      => 	'required|numeric|gt:0|exists:cities',
                                                      ]);

          if($validator->fails()):
          $arr                                =   array('status' => false, 'message' => $validator->errors()->first());
          return response()->json($arr);
          endif;
      	# End Validation
      
      	$arr						=	[];
    	$records					=	City::select('id', 'name')->whereStateId(request()->state_id)->get();
      	
      	if($records):
      		$arr					=	['status' => true, 'message' => 'Successfully data fetched', 'data' => $records];
      	else:
      		$arr					=	['status' => false, 'message' => 'No data found'];
      	endif;
      
      	return response()->json($arr);
     }
  	# End Cities
  
  	# Addresses 
    public function addresses(){

        $arr                                        =   array();
        
            $addresses                              =   Address::select('id', 'first_name', 'last_name', 'mobile', 'email', 'address', 'country', 'state_id', 'city_id', 'postcode', 'is_default', 'address_type')
                                                                    ->where('user_id', $this->user->id)
                                                                    ->orderBy('is_default', 'desc')
                                                                    ->get()->makeHidden(['address_type']);
            if(count($addresses)):
                $arr                                =   array('status' => true, 'message' => 'successfully fetched data', 'addresses' => $addresses);
            else:
      			
                $arr                                =   array('status' => false, 'message' => 'data not available', 'addresses' => []);
            endif;
        

        return response()->json($arr);
        
    }
    # End Addresses

    # Create or Update Address
    public function create_or_udpate_address(){
          $arr                                =   array();
        
        if(auth()->check()):
         
      		# Validation 
            $validator                              =   validator()->make(request()->all(), [
              																				# 'first_name'		=> 	'required|string|min:3|max:150',
              																				# 'last_name'			=> 	'required|alpha|min:3|max:150',
              																				# 'mobile'			=>	'required|numeric|digits:10',
              																				# 'email'				=> 	'required|email',
                                                                                            'address'           => 	'required|min:10|max:500',
                                                                                            'state_id'          => 	'required|numeric|exists:states,id',
                                                                                            'city_id'           => 	'required|numeric|exists:cities,id',
                                                                                            'postcode'          => 	'required|numeric|digits:6',
                                                                                            'is_default'        => 	'required|digits:1',
                                                                                            'address_type'      => 	'required|digits:1',
                                                                                        ]);

            if($validator->fails()):
                $arr                                =   array('status' => false, 'message' => $validator->errors()->first());
                return response()->json($arr);
            endif;
            # End Validation
            
            # Is Address Exists
                $is_address_exists                  =   Address::select('id')->whereUserId($this->user->id)->count();
                
                if($is_address_exists):
                    $exists_addresses               =   Address::whereUserId($this->user->id)->update(['is_default' => 0]);
                endif;
            # End Is Address Exists

            $address                                =   Address::updateOrCreate([
            																	  'id'					  	  => request()->id
            																	],
                                                                                [
                                                                                  'user_id'                   => $this->user->id,
                                                                                  'first_name'                => request()->first_name ?? $this->user->first_name ?? $this->user->name ?? '',
                                                                                  'last_name'                 => request()->last_name ?? $this->user->last_name ?? '',
                                                                                  'mobile'                    => request()->mobile ?? $this->user->mobile,
                                                                                  'email'                     => request()->email ?? $this->user->email,
                                                                                  'address'                   => request()->address,
                                                                                  'country'                   => "India",
                                                                                  'state_id'                  => request()->state_id,
                                                                                  'city_id'                   => request()->city_id,
                                                                                  'postcode'                  => request()->postcode,
                                                                                  'is_default'                => 1,
                                                                                  'address_type'              => request()->address_type,
                                                                              ]);
            
      		if($address->wasRecentlyCreated):
      			$arr 				=	[ 'status' => true, 'message' => 'successfully address created', 'address' => $address ];
            else:
                $arr 				=	[ 'status' => true, 'message' =>  'successfully address updated', 'address' => $address ];
            endif;

        endif;

        return response()->json($arr);
    }
    # End Create or Udpate Address

    # Address
    public function address(){
         # Validation 
         $validator                              =   validator()->make(request()->all(), [
                                                        'id'                => 'required|numeric|exists:addresses',
                                                       ],[
                                                           'id.exists'			=> 'Address not found'	
                                                         ]);

        if($validator->fails()):
            $arr                                =   array('status' => false, 'message' => $validator->errors()->first());
            return response()->json($arr);
        endif;
        # End Validation

        $address    =   Address::select('id', 'first_name', 'last_name', 'mobile', 'email', 'address', 'country', 'state_id', 'city_id', 'postcode', 'is_default', 'address_type')
                        ->whereId(request()->id)
                        ->whereUserId($this->user->id)
                        ->first();

        if($address):
            $address->makeHidden(['address_type']);
            $arr                                =   array('status' => true, 'message' => 'successfully address fetched', 'address' => $address);
        else:
            $arr                                =   array('status' => false, 'message' => 'no data found');
        endif;

        return response()->json($arr);
    }
    # End Address

    # Delete Address
    public function delete_address(){
      	
         # Validation 
         $validator                              =   validator()->make(request()->all(), [
                                                                                            'id'                => 'required|numeric|exists:addresses',
                                                                                        ],[
         																					'id.exists'			=> 'Address not found'	
         																				]);

        if($validator->fails()):
            $arr                                =   array('status' => false, 'message' => $validator->errors()->first());
            return response()->json($arr);
        endif;
        # End Validation

        $result    =    Address::whereId(request()->id)->whereUserId($this->user->id)->delete();

        if($result):
      		# Set default address if default address is deleted
      		$isDefaultExit							= 	Address::whereUserId($this->user->id)->whereIsDefault(1)->count();
      		
      		if(!$isDefaultExit):
      			$address							=	Address::whereUserId($this->user->id)->latest()->first();
                $address->is_default 				= 	1;
      			$address->save();
      		endif;
      
      		# End Set default address if default address is deleted
      
            $arr                                =   array('status' => true, 'message' => 'successfully address deleted');
        else:
            $arr                                =   array('status' => false, 'message' => 'some error occured');
        endif;
        
        return response()->json($arr);
    }
    # End Delete Address

    # Set Default Address
    public function set_default_address(){
          # Validation 
          $validator                              =   validator()->make(request()->all(), [
                                                                                            'id'                => 'required|numeric|exists:addresses',
                                                                                        ]);

        if($validator->fails()):
            $arr                                =   array('status' => false, 'message' => $validator->errors()->first());
            return response()->json($arr);
        endif;
        # End Validation

        $address                    =   Address::whereUserId($this->user->id)->whereId(request()->id)->update(['is_default' => 1]);

        Address::whereUserId(auth()->user()->id)->where('id', '!=', request()->id)->update(['is_default' => 0]);

        if($address):
            $arr                                =   array('status' => true, 'message' => 'successfully address set default', 'address' => Address::find(request()->id));
        else:
            $arr                                =   array('status' => false, 'message' => 'some error occured');
        endif;
        
        return response()->json($arr);

    }
    # End Set Default Address
}