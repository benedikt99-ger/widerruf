<?php

declare(strict_types=1);

namespace nuenemann\widerruf\Controller\Admin;

use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Application\Controller\Admin\AdminController;

/**
 * @eshopExtension
 *
 * This is an example for a module extension (chain extend) of
 * the shop start controller.
 * NOTE: class must not be final.
 */
class widerrufController extends AdminController
{

    protected $_sThisTemplate = '@bn_afterbuy/admin/widerruf';
    public function render()
    {
     
		$result = "@bn_afterbuy/admin/widerruf";
		parent::render();
		
		$order = $this->getOrder();
        $orderId = $this->getEditObjectId();
        $this->addTplParam('oxid', $orderId);
        $this->addTplParam('order', $order);
		// $this->addTplParam('afterbuykdnr', $order->oxorder__oxbillnr->value);
		// $this->addTplParam('afterbuyuid', $order->oxorder__oxtrackcode->value);
        return $result;
    }

    public function saveData(): void
    {
        // $editRequest = $this->getServiceFromContainer(EditRequestInterface::class);
        // $productFactsFactory = $this->getServiceFromContainer(ProductFactsFactoryInterface::class);
        // $factsService = $this->getServiceFromContainer(FactsServiceInterface::class);
        // $factsService->saveProductFacts($editRequest->getProductId(), $productFactsFactory->getFromRequest());
    }
	
}
