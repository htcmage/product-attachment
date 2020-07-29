<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace HTCMage\ProductAttachment\Block\Products\Attachment;

use Magento\Store\Model\StoreManagerInterface;
class Attachment extends \Magento\Backend\Block\Template
{
    private $storeManager;
     
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->storeManager = $storeManager;
        $this->setTabTitle();
    }
     public function setTabTitle()
    {
        $title = __('Product Attachment');
        $this->setTitle($title);

    }

    public function getPathIcon($icon)
    {
        $path = $this->storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            ).'htcmage/attachment/';
        return $path.$icon;
    }

    public function getPathFile($file){
        $path = $this->storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            ).'htcmage/attachment/file/';
        return $path.$file;
    }
}
