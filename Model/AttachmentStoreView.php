<?php

namespace HTCMage\ProductAttachment\Model;

use Magento\Framework\Model\AbstractModel;

class AttachmentStoreView extends AbstractModel
{
    const CACHE_TAG = 'htcmage_productattachment_store_view';

 

   protected $_cacheTag = 'htcmage_productattachment_store_view';



   protected $_idFieldName = 'id';
    protected function _construct()
    {
        $this->_init('HTCMage\ProductAttachment\Model\ResourceModel\AttachmentStoreView');
    }
}
