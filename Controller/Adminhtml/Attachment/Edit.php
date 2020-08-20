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

namespace HTCMage\ProductAttachment\Controller\Adminhtml\Attachment;

use HTCMage\ProductAttachment\Model\Attachment;
use HTCMage\ProductAttachment\Model\ProductAttachmentFactory;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Maxime\Jobs\Model\Department;

/**
 * Class Edit
 * @package HTCMage\ProductAttachment\Controller\Adminhtml\Attachment
 */
class Edit extends Action
{
    /**
     * Core registry
     *
     * @var Registry
     */
    protected $registry = null;

    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var Department
     */
    protected $_model;

    /**
     * @var ProductAttachmentFactory
     */
    protected $modelPA;

    /**
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param Registry $registry
     * @param Department $model
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        ProductAttachmentFactory $modelPA,
        Registry $registry,
        Attachment $model
    )
    {
        $this->_resultPageFactory = $resultPageFactory;
        $this->registry = $registry;
        $this->_model = $model;
        $this->modelPA = $modelPA;
        parent::__construct($context);
    }

    /**
     * @return Page
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->_model;
        if ($id) {
            $model->load($id);
            $this->registry->register('product_attachment', $this->getProductByFilterAttachment($id));
            if (!$model->getId()) {
                $this->messageManager->addError(__('This attachment not exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/attachment/');
            }
        }
        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);

        }
        $this->registry->register('attachment', $model);
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Product Attachment') : __('New Product Attachment'),
            $id ? __('Edit Product Attachment') : __('New Product Attachment')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Product Attachment'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getName() : __('New Product Attachment'));
        return $resultPage;
    }

    /**
     * @param $idAttachment
     * @return array
     */
    public function getProductByFilterAttachment($idAttachment)
    {
        $collection = $this->modelPA->create()->getCollection();
        $collection->addFieldToFilter('id_attachment', ['eq' => "$idAttachment"]);
        $listProduct = $collection->getData();
        $product = [];
        foreach ($listProduct as $value) {
            array_push($product, $value['id_product']);
        }
        return $product;
    }

    /**
     * Init actions
     *
     * @return Page
     */
    protected function _initAction()
    {
        /** @var Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('HTCMage_ProductAttachment::attachment')
            ->addBreadcrumb(__('Product Attachment'), __('Product Attachment'))
            ->addBreadcrumb(__('Manage Product Attachment'), __('Manage Product Attachment'));
        return $resultPage;
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('htcmage_productattachment::attachment_save');
    }
}