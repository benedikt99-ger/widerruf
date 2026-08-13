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
    

  public function widerrufFunction()
  {
		// empty
	}
}
