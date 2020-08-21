<?php
/**
 * NOTICE OF LICENSE
 *
 * @author HTCMage Team
 * @copyright Copyright (c) 2020 HTCMage (https://www.htcmage.com)
 * @package HTCMage_ProductAttachment
 *
 */

namespace HTCMage\ProductAttachment\Block\Products\Attachment;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;

/**
 * Class UpdateAttachment
 * @package HTCMage\ProductAttachment\Block\Products\Attachment
 */
class UpdateAttachment extends Template
{
    /**
     * @var Registry
     */
    protected $_registry;

    /**
     * UpdateAttachment constructor.
     * @param Context $context
     * @param AttachmentFactory $model
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_registry = $registry;
    }

    /**
     * @return mixed
     */
    public function urlUpdate()
    {
        return $this->getUrl('attachment/attachment/resultajax');
    }

    /**
     * @return mixed
     */
    public function urlDowload()
    {
        return $this->getUrl('attachment/attachment/attachmentdowload');
    }


    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->_registry->registry('current_product')->getId();
    }
}
