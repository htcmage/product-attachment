<?php
/**
 * NOTICE OF LICENSE
 *
 * @author HTCMage Team
 * @copyright Copyright (c) 2020 HTCMage (https://www.htcmage.com)
 * @package HTCMage_ProductAttachment
 *
 */

namespace HTCMage\ProductAttachment\Helper;

use Magento\Customer\Model\Context;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Data
 * @package HTCMage\ProductAttachment\Helper
 */
class Data extends AbstractHelper
{
    /**
     * @var Session
     */
    protected $_customerSession;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManagerInterface;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param Session $customerSession
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        StoreManagerInterface $storeManagerInterface,
        Session $customerSession
    )
    {
        $this->_customerSession = $customerSession;
        $this->storeManagerInterface = $storeManagerInterface;
        parent::__construct($context);
    }

    /**
     * @return mixed
     */
    public function getCustomerGroup()
    {
        $customerGroup = $this->_customerSession->getCustomer()->getGroupId();
        return $customerGroup;
    }

    /**
     * @return mixed
     */
    public function getStore()
    {
        $currentStore = $this->storeManagerInterface->getStore()->getId();
        return $currentStore;
    }

    /**
     * @return mixed
     */
    public function isEnable()
    {
        $storeScope = ScopeInterface::SCOPE_STORES;
        return $this->scopeConfig->getValue('attachment/general/enable', $storeScope);
    }

    /**
     * @param $file
     * @return string
     */
    public function getPathFile($file)
    {
        $path = $this->storeManagerInterface->getStore()->getBaseUrl(
                UrlInterface::URL_TYPE_MEDIA
            ) . 'htcmage/attachment/file/';
        return $path . $file;
    }
}
