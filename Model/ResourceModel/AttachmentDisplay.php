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

namespace HTCMage\ProductAttachment\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Class AttachmentDisplay
 * @package HTCMage\ProductAttachment\Model\ResourceModel
 */
class AttachmentDisplay extends AbstractDb
{
    /**
     * AttachmentDisplay constructor.
     * @param Context $context
     */
    public function __construct(
        Context $context
    )
    {
        parent::__construct($context);
    }

    /**
     *
     */
    protected function _construct()
    {
        $this->_init('htcmage_productattachment_display', 'id');
    }

}

