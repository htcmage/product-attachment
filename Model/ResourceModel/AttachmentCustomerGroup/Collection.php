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

namespace HTCMage\ProductAttachment\Model\ResourceModel\AttachmentCustomerGroup;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package HTCMage\ProductAttachment\Model\ResourceModel\AttachmentCustomerGroup
 */
class Collection extends AbstractCollection
{
    /**
     * Identifier field name for collection items
     *
     * Can be used by collections with items without defined
     *
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     *
     */
    protected function _construct()
    {
        $this->_init('HTCMage\ProductAttachment\Model\AttachmentCustomerGroup', 'HTCMage\ProductAttachment\Model\ResourceModel\AttachmentCustomerGroup');
    }
}

