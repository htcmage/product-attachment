<?php

namespace HTCMage\ProductAttachment\Model;

use Magento\Framework\Model\AbstractModel;

class Attachment extends AbstractModel
{
    const CACHE_TAG = 'htcmage_productattachment';

   protected $_cacheTag = 'htcmage_productattachment';



   protected $_idFieldName = 'id';
    protected function _construct()
    {
        $this->_init('HTCMage\ProductAttachment\Model\ResourceModel\Attachment');
    }
}
