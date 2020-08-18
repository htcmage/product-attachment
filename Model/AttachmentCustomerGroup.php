<?php

namespace HTCMage\ProductAttachment\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class AttachmentCustomerGroup
 * @package HTCMage\ProductAttachment\Model
 */
class AttachmentCustomerGroup extends AbstractModel
{
    /**
     *
     */
    const CACHE_TAG = 'htcmage_productattachment_store_view';
    /**
     * @var string
     */
    protected $_cacheTag = 'htcmage_productattachment_store_view';
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     *
     */
    protected function _construct()
    {
        $this->_init('HTCMage\ProductAttachment\Model\ResourceModel\AttachmentCustomerGroup');
    }
}
