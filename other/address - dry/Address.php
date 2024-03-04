<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

	protected $appends  = ['full_name', 'full_address', 'address_type_label'];

    protected $fillable = [
    	'user_id', 
		'first_name', 
		'last_name', 
		'mobile', 
		'email', 
		'address', 
		'country', 
		'state_id', 
		'city_id', 
		'postcode', 
		'is_default', 
		'address_type'
    ];

	public function getFullNameAttribute(){
		return $this->first_name.' '.$this->last_name;
	}

	public function getFullAddressAttribute(){
		return $this->address.', '.$this->city.', '.$this->state.', India - '.$this->postcode;
	}
	
	public function getaddressTypeLabelAttribute(){
		$address_type_label					=	'';
		
		switch($this->address_type):
			case 1:
				$address_type_label					=	'Home';
				break;
				
			case 2:
				$address_type_label					=	'Office';
				break;
					
			case 3:
				$address_type_label					=	'Other';
				break;

		endswitch;

		return $address_type_label;
	}
		
  	# Relationship 
  	public function state(){
    	return $this->belongsTo(State::class)->select('id', 'name');
    }
  
  	 public function city(){
    	return $this->belongsTo(City::class)->select('id', 'name');
    }
  	# End Relationship
  
	protected $casts	=	[
		'is_default'		=> 'bool'
	];
}
