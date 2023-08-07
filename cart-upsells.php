<?php
function unrelatedCartProductsContent(){

	$cartItemsCount = WC()->cart->get_cart_contents_count();
	$cartProducts = WC()->cart->get_cart();
	$packingupsellIDs = array();
	$cartProductsIDs = array();
	$flags = array();

	if ( $cartItemsCount != 0 ) {
		foreach($cartProducts as $cartProduct){
			$cartProductID = $cartProduct['product_id'];
			$cartProductsIDs[] = $cartProductID;
			if(has_term(41, 'product_cat', $cartProductID)){
				$cartItem = wc_get_product($cartProductID); // create new product because the variation has no upsells
				$upsellIDs = $cartItem->upsell_ids; //get the upsell ids in an array
	
				if($upsellIDs){
					//save upsells in an array with key as the product ID
					foreach($upsellIDs as $upsellID){
						if(has_term(41, 'product_cat', $upsellID)){
							$packingupsellIDs[$cartProductID] = $upsellIDs;
						}
						
					}
				}
			}
		}
		
		if(count($cartProductsIDs) > 1){
			foreach($packingupsellIDs as $key => $value){
				
				foreach($value as $idKey => $id){
					$flag = in_array($key, array_column($packingupsellIDs, $idKey)); //check if product is an upsell of another
					$flags[] = $flag;
				}
			}
			//if flags has false print warning message
			if(in_array(false, $flags)){
				echo "WARNING";
			}
		}
	}
}
add_action('woocommerce_before_cart_contents', 'unrelatedCartProductsContent');