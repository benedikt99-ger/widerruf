<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace nuenemann\widerruf\Model;

/**
 * @eshopExtension
 *
 * This is an example for a module extension (chain extend) of
 * the shop user model.
 * NOTE: class must not be final.
 *
 * @mixin \OxidEsales\Eshop\Application\Model\Order
 */
class Order extends Order_parent
{
    
 /**
   * @extend finalizeOrder
   * 
   * @param oxBasket $oBasket
   * @param object $oUser
   * @param boolean $blRecalculatingOrder
   * @return boolean 
   */
  public function finalizeOrder( oxBasket $oBasket, $oUser, $blRecalculatingOrder = false )
  {
    $return = parent::finalizeOrder($oBasket, $oUser, $blRecalculatingOrder);

	if ($this->oxorder__oxbillemail=="") $widerruf__SendOrder=true;
	if ($this->oxorder__oxbillstreet=="") $widerruf__SendOrder=true;
	if ($this->oxorder__oxbillzip=="") $widerruf__SendOrder=true;
	if ($this->oxorder__oxbillcity=="") $widerruf__SendOrder=true;
	
	// $return loggen
	$filename = $this->getConfig()->getConfigParam( 'sShopDir' ) . '/log/widerruf_log.txt'; 	// write $response in File
	$content = date("Ymd H:i:s").";".$HTTP_SERVER_VARS['REMOTE_ADDR'].";return ".$return." ordernr ".$this->oxorder__oxordernr." billemail ".$this->oxorder__oxbillemail."\n";
	$file = fopen ($filename, "a");	
	if ($file) {		
		fwrite($file, $content);
	}
	fclose ($file);	
	
	if ($return==1) {
		if ($widerruf__SendOrder == false) {
			$this->widerrufFunction();
		} else {
		}
	}
    return $return;
  }	

  public function widerrufFunction()
  {
		// empty
	}
}
