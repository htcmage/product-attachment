<?php

namespace HTCMage\ProductAttachment\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class AttachmentStoreView extends AbstractDb
{
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }
    protected function _construct()
    {
        $this->_init('htcmage_productattachment_store_view','id');
    }

}

