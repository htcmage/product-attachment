<?php

namespace HTCMage\ProductAttachment\Block\Adminhtml;

use Magento\Backend\Block\Widget\Form\Container;

class Edit extends Container
{

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
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