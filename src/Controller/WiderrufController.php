<?php

declare(strict_types=1);

namespace nuenemann\widerruf\Controller;

use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Application\Controller\FrontendController;

/**
 * @eshopExtension
 *
 * This is an example for a module extension (chain extend) of
 * the shop start controller.
 * NOTE: class must not be final.
 */
class WiderrufController extends FrontendController
{

   protected $_sThisTemplate = '@widerruf/widerrufform.html.twig';

    public function getTitle()
    {
        return Registry::getLang()->translateString("WITHDRAWAL_FORM", Registry::getLang()->getBaseLanguage(), false);
    }

    public function getBreadCrumb()
    {
        $aPaths = array();
        $aPath = array();

        $aPath['title'] = $this->getTitle();
        $aPath['link'] = 'index.php?cl=withdrawalform';
        $aPaths[] = $aPath;

        return $aPaths;
    }

    public function isLegacyBS() {
        return (Registry::getConfig()->getConfigParam('WiderrufBootstrapVersion') == "v3");
    }

    public function getUserOrders()
    {
        if ($oUser = $this->getUser()) {
            $oOrders = oxNew(\OxidEsales\Eshop\Core\Model\ListModel::class);
            $oOrders->init('oxorder');


            $sQ = 'SELECT * FROM oxorder WHERE oxuserid = :oxuserid OR oxbillemail = :oxbillemail ORDER BY oxorderdate DESC'; // and oxorderdate >= :oxorderdate
            $oOrders->selectString($sQ, [
                ':oxuserid' => $oUser->oxuser__oxid->value,
                ':oxbillemail' => $oUser->oxuser__oxusername->value,
                //':oxorderdate' => date('Y-m-d', strtotime('-30 days'))
            ]);

            return ($oOrders->count() > 0 ? $oOrders : false);
        }
        return false;
    }
    
    public function getOrderArticles()
    {
        $wdf = Registry::getConfig()->getRequestParameter("wdf");
        if (!empty($wdf["oxorderid"])) {
            $oArticleList = oxNew(\OxidEsales\Eshop\Application\Model\ArticleList::class);
            $oArticleList->init('oxorderarticle');
            $oArticleList->selectString(
                'select * from oxorderarticles where oxorderid = :oxorderid',
                [':oxorderid' => $wdf["oxorderid"] ]
            );
            $this->addTplParam("aOrderProducts", $oArticleList);
        } else {
            $this->addTplParam("aOrderProducts", false);
        }
    }
    public function getWithdrawalReasons()
    {
        $aReasonst = Registry::getConfig()->getConfigParam('WiderrufReasons');
        return (!empty($aReasonst) ? $aReasonst : false);
    }

    public function getRecaptchaSiteKey()
    {
        return Registry::getConfig()->getConfigParam('WiderrufSitekey');
    }
    protected function _checkRecaptcha($token)
    {
        if (!$token) {
            $this->addTplParam("submitError", "1");
            return false;
        }
        $this->addTplParam("errorArgs", Registry::getConfig()->getActiveShop()->oxshops__oxorderemail->value);

        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $ip = $_SERVER['REMOTE_ADDR'];
        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        }

        $data = [
            'secret' => Registry::getConfig()->getConfigParam("WiderrufSecret"),
            'response' => $token,
            'remoteip' => $ip
        ];

        $ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = (object) json_decode(curl_exec($ch));
        curl_close($ch);

        if (!isset($response->success)) {
            return true;
        } // gar kein response. trotzdem email versenden
        elseif ($response->success === true) {
            return true;
        } elseif ($response->success === false) {
            $this->addTplParam("submitError", "2");
        } // captcha nicht bestätigt
        else {
            $this->addTplParam("submitError", "3");
        } // sollte eigentlich nicht passieren
        return false;
    }

    public function submitWithdrawal()
    {
        $wdf = Registry::getConfig()->getRequestParameter("wdf");

        if($wdf["datenschutz"] !== "1") {
            $this->addTplParam("submitError", "DATENSCHUTZ");
            return false;
        };

        if (!Registry::getConfig()->getUser() && !$this->_checkRecaptcha(Registry::getConfig()->getRequestParameter("g-recaptcha-response"))) {
            return;
        }

        try {
            /** @var oxEmail $oEmail */
            $oEmail = oxNew("oxEmail");
            $x = $oEmail->sendWithdrawalRequestToOwner($wdf);
            $y = $oEmail->sendWithdrawalRequestToUser($wdf);
        } catch (Exception $oException) {
            $this->addTplParam("submitError", "3");
            $this->addTplParam("errorArgs", $oEmail->getShop()->oxshops__oxorderemail->value);
        }

        if ($x) {
            $this->addTplParam("submitSuccess", true);
        } else {
            $this->addTplParam("submitError", "3");
            $this->addTplParam("errorArgs", $oEmail->getShop()->oxshops__oxorderemail->value);
        }
        //if(!$r2) Registry::getUtils()->writeToLog("")
    }
	
}
