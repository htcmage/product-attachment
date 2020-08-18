<?php

namespace HTCMage\ProductAttachment\Block\Adminhtml;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form\Container;
use Magento\Framework\Registry;

/**
 * Class Edit
 * @package HTCMage\ProductAttachment\Block\Adminhtml
 */
class Edit extends Container
{

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    )
    {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'HTCMage_ProductAttachment';
        $this->_controller = 'adminhtml';

        parent::_construct();
        $this->buttonList->update('save', 'label', __('Save'));
        $this->buttonList->update('back', 'label', __('Back'));


    }
}