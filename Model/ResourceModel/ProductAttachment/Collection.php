<?php

namespace HTCMage\ProductAttachment\Model\ResourceModel\ProductAttachment;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

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
    protected function _construct()
    {
        $this->_init('HTCMage\ProductAttachment\Model\ProductAttachment', 'HTCMage\ProductAttachment\Model\ResourceModel\ProductAttachment');
    }
}

