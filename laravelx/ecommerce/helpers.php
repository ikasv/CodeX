<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

use App\Models\Module;
use App\Models\Product;
use App\Models\Cart;
use App\Models\ProductImage;
use App\Models\Category;

    # Send Mail
    if (!function_exists('sendMail')) {
        function sendMail($to,$from,$subject,$html){
            $from               =       ($from)?$from:env('MAIL_FROM_ADDRESS');
            $result             =       Mail::send(array(), array(), function ($message) use ($to,$from,$subject,$html) {
                                        $message->to($to)->subject($subject)->from($from)->setBody($html, 'text/html');
                                    });
            return ($result)?1:0;
        }
    }
    # End Send Mail

    # Get User Email
    if (!function_exists('get_user_email')) {
        function get_user_email(){
            $user               =       Auth::user();
            return $user->email;
        }
    }
    # End Get User Email

    # Get Permissions
    if (!function_exists('get_permissions')) {
        function get_permissions($role_id=null){
        $permissions            =   array();
        if(Auth::user()){
          $role_id              =   ($role_id)?$role_id:Auth::user()->role_id;
          $permission_data      =   Module::select('module_code','rr_create', 'rr_edit', 'rr_delete', 'rr_view')->leftJoin('role_rights', function($join) use ($role_id) {
                                        $join->on('role_rights.module_id', '=', 'modules.id');
                                        $join->where('role_rights.role_id', '=', $role_id);
                                    })->get();
            if($permission_data){
                foreach($permission_data as $row){
                    $permissions[$row->module_code] = $row;
                }
            }
        }
        return $permissions;
    }
    # End Get Permissions
    
    ############# E-Commerece Methods ###################
    # Get Temporary User
        if (!function_exists('getTempUser')) {
            function getTempUser(){
                $temp_user      =   '';
                if(Session::has('temp_user')):
                    $temp_user  =  Session::get('temp_user');
                else:
                    Session::put('temp_user', Str::random(40));
                    $temp_user = Session::get('temp_user');
                endif;
        
                return $temp_user;
            }
        }
    # End Get Temporary User
    
    # Get Product
    if(!function_exists('getProducts')) {
        function getProduct($id_or_slug){
                 
                # Prodcuts
                $product               =   Product::select('products.*', 'carts.id as cart_id', 'info.benefits', 'info.how_to_use' , 'info.faq', 'info.other',
                                            DB::raw("(CASE 
                                                            WHEN carts.id > 0  THEN true 
                                                            ELSE false 
                                                            END ) AS isInCart"),
                                            DB::raw("(CASE 
                                                            WHEN ( products.manage_stock = '1' AND products.stock_status = '1' AND products.quantity > 0 ) THEN true 
                                                            WHEN ( products.manage_stock = '0' ) THEN true 
                                                            ELSE false 
                                                            END ) AS isInStock"));
                                            // $product->join('product_images', 'product_images.product_id', '=', 'products.id');
                                            $product->leftJoin('product_additional_infos as info', 'info.product_id', '=', 'products.id');
                                            $product->leftJoin('carts', function($join)
                                            {
                                              $join->on('carts.product_id', '=', 'products.id');
                                                if(Auth::guard('customer')->check()):
                                                        $join->on('user_id', DB::raw("'".Auth::guard('customer')->user()->id."'"));
                                                    else:
                                                        $join->on('session_id', DB::raw("'".getTempUser()."'"));
                                                    endif;
                                            });
                                            $product->where('products.parent_id', 0);
                                            $product->where('products.id', "$id_or_slug");
                                            $product->orWhere('products.slug', "$id_or_slug");
                                            $product->where('products.status', 1);
                                          
                $product               =   $product->first();
                # End Prodcuts
                
                $product->product_images                 =   ProductImage::where('product_id', $product->id)->get();
                
                if($product->benefits != ''):
                $product->benefits         =   json_decode($product->benefits);
            endif;
            
            if($product->how_to_use != ''):
                $product->how_to_use       =   json_decode($product->how_to_use);
            endif;
            
            if($product->faq != ''):
                $product->faq              =   json_decode($product->faq);
            endif;
            
             if($product->other != ''):
                $product->other             =   json_decode($product->other);
            endif;
            
                $product->user_id                =   '';
                $product->isUser                 =   false;
         
                if(Auth::guard('customer')->check()):
                    $product->isUser                 =   true;
                    $product->user_id                =   Auth::guard('customer')->user()->id;
                else:
                    $product->temp_user_id             =   getTempUser();
                endif;
                
                return $product;
            
        }
    }
    # End Get Product
    
    # Get Products
        if(!function_exists('getProducts')) {
            function getProducts($page_type='home', $search = '', $category_slug = ''){
        
                if($category_slug){
                    $category = Category::where('slug', $category_slug)->first();
                }
                
                # Prodcuts
                $products               =   Product::select('products.*', 'carts.id as cart_id',
                                            DB::raw("(CASE 
                                                            WHEN carts.id > 0  THEN true 
                                                            ELSE false 
                                                            END ) AS isInCart"),
                                            DB::raw("(CASE 
                                                            WHEN ( products.manage_stock = '1' AND products.stock_status = '1' AND products.quantity > 0 ) THEN true 
                                                            WHEN ( products.manage_stock = '0' ) THEN true 
                                                            ELSE false 
                                                            END ) AS isInStock"));
                                            $products->leftJoin('carts', function($join)
                                            {
                                              $join->on('carts.product_id', '=', 'products.id');
                                                if(Auth::guard('customer')->check()):
                                                        $join->on('user_id', DB::raw("'".Auth::guard('customer')->user()->id."'"));
                                                    else:
                                                        $join->on('session_id', DB::raw("'".getTempUser()."'"));
                                                    endif;
                                            });
                                            $products->where('products.status', 1);
                                            
                                            if($page_type == 'home'):        
                                                $products->where('products.parent_id', 0);
                                                $products->limit(8);
                                            endif;
                                            
                                            # Comment Because - List Js Filter - Getting products via category Slug
                                            // if($category_slug):        
                                            //     $products->whereRaw('FIND_IN_SET('.(int)$category->id.', products.category_ids)');
                                            // endif;
                                            # End Comment Because - List Js Filter - Getting products via category Slug
                                    
                                            $products->where('products.product_name', 'like',  '%' . $search .'%');
                $products               =   $products->get();
                # End Prodcuts
                
                $products->user_id                =   '';
                $products->isUser                 =   false;
         
                if(Auth::guard('customer')->check()):
                    $products->isUser                 =   true;
                    $products->user_id                =   Auth::guard('customer')->user()->id;
                else:
                    $products->temp_user_id             =   getTempUser();
                endif;
                
                return $products;
            }
        }
    # End Get Products
    
    # Get Cart Items
        if (!function_exists('getCartItems')) {
            function getCartItems(){
                    $sub_total                  =   0;
                    $delivery_charge            =   0;
      
                    $cart_items                 =   Product::select("products.id", "products.category_ids", "products.weight", "products.label", "products.product_name", "products.slug", "products.manage_stock", "products.stock_status", 
                                                            "products.quantity", "products.mrp_price", "products.price as sale_price", "products.featured_image", "carts.qty")
                                                   ->join('carts', 'carts.product_id', '=', 'products.id')
                                                   ->where('products.status',1);
              
                                                    if(Auth::guard('customer')->check()){
                                                        $cart_items = $cart_items->where('user_id', Auth::guard('customer')->user()->id);
                                                    }
                                                    else {
                                                        $cart_items = $cart_items->where('session_id', getTempUser());
                                                    }
                          
                    $cart_items                 =   $cart_items->get();
      
                    foreach($cart_items as $cart_item):
                        $sub_total              +=   $cart_item->sale_price * $cart_item->qty;
                    endforeach;
      
            $cart_items->total_count            =   $cart_items->count();
            $cart_items->sub_total              =   $sub_total;
            $cart_items->delivery_charge        =   $delivery_charge;
            $cart_items->total                  =   $sub_total + $delivery_charge;
        return $cart_items;
      }
    }
    # End Get Cart Items
    
    # Update Cart Item
        if(!function_exists('update_cart')) {
            function update_cart($product_id, $quantity){
                if($quantity < 1):
                    return false;
                endif;
                
                $cart_items  =  Cart::where('product_id', $product_id);
                
                if(Auth::guard('customer')->check()):
                    $cart_items->where('user_id', Auth::guard('customer')->user()->id);
                else:
                    $cart_items->where('session_id', getTempUser());
                endif;
                
                $cart_items = $cart_items->update(['qty' => $quantity]);
                
                return $cart_items;
          }
        }
    # End Update Cart Item
    
    # Remove Cart Item
    if (!function_exists('remove_cart_item')) {
        function remove_cart_item($product_id){
            $cart_items  =  Cart::where('product_id', $product_id);
    
            if(Auth::guard('customer')->check()):
                $cart_items->where('user_id', Auth::guard('customer')->user()->id);
            else:
                $cart_items->where('session_id', getTempUser());
            endif;
    
            $result = $cart_items->delete();
            return $result;
        }
    }
    # End Remove Cart Item

    # Empty Cart 
    if (!function_exists('empty_cart')) {
        function empty_cart($user_id){
            $cart_items  =  Cart::where('user_id', $user_id)->delete();
            return true;
        }
    }
    # End Empty Cart
    
    
    
    ############# End E-Commerece Methods ###############
  
}



