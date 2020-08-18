<?php

namespace HTCMage\ProductAttachment\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Class ProductAttachment
 * @package HTCMage\ProductAttachment\Model\ResourceModel
 */
class ProductAttachment extends AbstractDb
{
    /**
     * ProductAttachment constructor.
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
        $this->_init('htcmage_productattachment_product', 'id');
    }
}

