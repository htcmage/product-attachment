<?php

namespace HTCMage\ProductAttachment\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Class Attachment
 * @package HTCMage\ProductAttachment\Model\ResourceModel
 */
class Attachment extends AbstractDb
{
    /**
     * Attachment constructor.
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
        $this->_init('htcmage_productattachment', 'id');
    }
}

