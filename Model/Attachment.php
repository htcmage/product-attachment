<?php
/**
 * NOTICE OF LICENSE
 *
 * @author HTCMage Team
 * @copyright Copyright (c) 2020 HTCMage (https://www.htcmage.com)
 * @package HTCMage_ProductAttachment
 *
 */

namespace HTCMage\ProductAttachment\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class Attachment
 * @package HTCMage\ProductAttachment\Model
 */
class Attachment extends AbstractModel
{
    /**
     *
     */
    const CACHE_TAG = 'htcmage_productattachment';

    /**
     * @var string
     */
    protected $_cacheTag = 'htcmage_productattachment';
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     *
     */
    protected function _construct()
    {
        $this->_init('HTCMage\ProductAttachment\Model\ResourceModel\Attachment');
    }
}
