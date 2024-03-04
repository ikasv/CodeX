
<script>
        
        //  Gtm Events
        
        function gtm_event(event_type, data){
        
        window.dataLayer = window.dataLayer || [];
        dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
        
        
            
         if(event_type != 'purchase'){
            dataLayer.push({
              event: event_type,
              ecommerce: {
                    value           : data.value,
                    currency        : data.currency,
                    items           : data.items
              }
            });
        }else{
            dataLayer.push({
              event: event_type,
              ecommerce: {
                    transaction_id  : data?.transaction_id,
                    affiliation     : data?.affiliation,
                    tax             : data?.tax,
                    shipping        : data?.shipping,
                    coupon          : data?.coupon,
                    value           : data.value,
                    currency        : data.currency,
                    items           : data.items
              }
            });
        } 
    }
      
        //  End Gtm Events
    </script>
//??????????????????????????

#################### single product 

 var myArray                         =   []; 
        var items                           =   []; 
        
        myArray['currency']                 =   "INR"; 
        myArray['value']                    =   <?= $product['product_sale_price'] ?? '' ?>;
        
        prodcuts                               =   {
                                                    item_id                 :   "<?= $product['product_sku'] ?? '' ?>",
                                                    item_name               :   "<?= $product['product_name'] ?? '' ?>",
                                                    affiliation             :   "<?= $product['product_sku'] ?? '' ?>",
                                                    discount                :   "<?= $product['discount'] ?? '' ?>",
                                                    index                   :   0,
                                                    item_brand              :   "omask",
                                                    price                   :   "<?= $product['product_sale_price'] ?? '' ?>",
                                                    quantity                :   1
                                                }
        
        
        var categories                          =   [];
                                                
        <?php
            $i                              =   "";
            foreach($product['categories_data'] as $category):
        ?>
        
        categories["item_category<?= $i; ?>"]                          =   "<?= $category->category; ?>";
                                                
            
        <?php
            if($i == ""):
                $i                              =   1;
            endif;
                $i++;
            endforeach;
        ?>
               
        var items               =   {
                                        ...prodcuts,
                                        ...categories
                                    };                              
                    
        myArray['items']                    =   [items];
    
        
        console.log(myArray);
        
    gtm_event('view_item', myArray);

    <!--  -->

    ######## End single Products 

    ################# Muliple Products
    // view_item_list
        var myArray                             =   []; 
        var items                               =   []; 
        var products                            =   [];
        var category                            =   [];
        
        myArray['item_list_id']                 =   window.location.href; 
        myArray['item_list_name']               =   window.location.href;
        
        var data =  `<?php echo json_encode($products); ?>`;
                     
        JSON.parse(data).forEach(function(product, index){
        
            product.categories_data.forEach(function(item, index){
        	    category["item_category"+index]                          =   item.category;
            });
            
            products  = {
                            item_id               : product.product_sku,
                            item_name             : product.product_name,
                            currency              : "INR",
                            price                 : product.product_sale_price,
                            quantity              : 1
                        };
                            
            items.push({
                            ...products,
                            ...category
                        });
                    
        });
                    
                    
        myArray['items']                    =   items;
        
        gtm_event('view_item_list', myArray);
        
        // End view_item_list