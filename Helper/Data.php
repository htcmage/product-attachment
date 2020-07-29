<?php

namespace HTCMage\ProductAttachment\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Customer\Model\Context;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
   protected $_customerSession;
    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory
     * @param \Magento\Framework\Image\AdapterFactory $adapterFactory
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\App\Http\Context $httpContext
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->_customerSession = $customerSession;
        parent::__construct($context);
    }

    public function CustomerGroup()
    {
        $customerGroup = false;
        if($this->_customerSession->isLoggedIn()){
            $customerGroup=$this->_customerSession->getCustomer()->getGroupId();
        }
        return $customerGroup;
    }
}
