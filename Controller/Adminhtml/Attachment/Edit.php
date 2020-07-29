<?php
namespace HTCMage\ProductAttachment\Controller\Adminhtml\Attachment;

use Magento\Backend\App\Action;

class Edit extends Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $registry = null;
    
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;
    
    /**
     * @var \Maxime\Jobs\Model\Department
     */
    protected $_model;
    protected $modelPA;
    
    /**
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Maxime\Jobs\Model\Department $model
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \HTCMage\ProductAttachment\Model\ProductAttachmentFactory $modelPA,
        \Magento\Framework\Registry $registry,
        \HTCMage\ProductAttachment\Model\Attachment $model
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->registry = $registry;
        $this->_model = $model;
        $this->modelPA = $modelPA;
        parent::__construct($context);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('htcmage_productattachment::attachment_save');
    }
    
    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('HTCMage_ProductAttachment::attachment')
        ->addBreadcrumb(__('Product Attachment'), __('Product Attachment'))
        ->addBreadcrumb(__('Manage Product Attachment'), __('Manage Product Attachment'));
        return $resultPage;
    }
    
    /**
     * Edit Department
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->_model;
        
        // If you have got an id, it's edition
        if ($id) {
            $model->load($id);
            $this->registry->register('product_attachment',$this->getProductByFilterAttachment($id));
            if (!$model->getId()) {
                $this->messageManager->addError(__('This attachment not exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                
                return $resultRedirect->setPath('*/attachment/');
            }
        }
        
        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
            
        }
        
        $this->registry->register('attachment', $model);
        
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
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
}