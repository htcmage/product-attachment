<?php
/**
 * *
 *  *
 *  *  NOTICE OF LICENSE
 *  *   @author HTCMage Team
 *  *   @copyright Copyright (c) 2020 HTCMage (https://www.htcmage.com)
 *  *   @package HTCMage_ProductAttachment
 *  *
 *
 */

namespace HTCMage\ProductAttachment\Block\Products\Attachment;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Attachment
 * @package HTCMage\ProductAttachment\Block\Products\Attachment
 */
class Attachment extends Template
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Attachment constructor.
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->storeManager = $storeManager;
        $this->setTabTitle();
    }

    /**
     *
     */
    public function setTabTitle()
    {
        $title = __('Product Attachment');
        $this->setTitle($title);

    }

    /**
     * @param $icon
     * @return string
     */
    public function getPathIcon($icon)
    {
        $path = $this->storeManager->getStore()->getBaseUrl(
                UrlInterface::URL_TYPE_MEDIA
            ) . 'htcmage/attachment/';
        return $path . $icon;
    }

    /**
     * @return string
     */
    public function getCountDownload($attachment)
    {
        if ($attachment['limited'] == 1) {
            if ($attachment['number_of_download'] > 0) {
                if ($attachment['number_of_download'] == 1) {
                    return "(" . $attachment['number_of_download'] . " download)";
                }
                return "(" . $attachment['number_of_download'] . " downloads)";
            }
        }
        return false;
    }
}
