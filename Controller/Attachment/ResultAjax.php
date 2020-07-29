<?php

namespace HTCMage\ProductAttachment\Controller\Attachment;

use Magento\Backend\App\Action\Context;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\View\Result\PageFactory;

class ResultAjax extends Action
{

    /**
     * @var
     */
    protected $attachmentRepository;
    /**
     * @var Session
     */
    protected $customerSession;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManagerInterface;
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var JsonHelper
     */
    protected $resultJsonFactory;
    /**
     * @var FilterProvider
     */
    protected $resultPageFactory;



    /**
     * ResultAjax constructor.
     * @param Context $context
     * @param Session $customerSession
     * @param HTCMage\ProductAttachment\Model\Repository\AttachmentRepository $attachmentRepository
     * @param \HTCMage\ProductAttachment\Model\Repository\AttachmentStoreViewRepository $attachmentStoreViewRepository
     * @param StoreManagerInterface $storeManagerInterface
     * @param resultJsonFactory $resultJsonFactory
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        \HTCMage\ProductAttachment\Model\Repository\AttachmentRepository $attachmentRepository,
        StoreManagerInterface $storeManagerInterface,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        ScopeConfigInterface $scopeConfig
    )
    {
        parent::__construct($context);
        $this->attachmentRepository = $attachmentRepository;
        $this->customerSession = $customerSession;
        $this->storeManagerInterface = $storeManagerInterface;
        $this->scopeConfig = $scopeConfig;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;

    }

    /**
     * Execute
     *
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        if ($this->isEnable() == 1) {
            $result = $this->resultJsonFactory->create();
            $data['detail'] = $this->getHtml($this->listAttachment(0));
            $data['aftercart'] = $this->getHtml($this->listAttachment(1));
            $data['footer'] = $this->getHtml($this->listAttachment(2));
            return $result->setData($data);
        }
        // return $this->getResponse()->setBody($this->resultJsonFactory->serialize($listProductAttachment));
    }

    public function getHtml($listProductAttachment){
        $resultPage = $this->resultPageFactory->create();
        $html = $resultPage->getLayout()
            ->createBlock('HTCMage\ProductAttachment\Block\Products\Attachment\Attachment')->setData('listProductAttachment', $listProductAttachment)
            ->setTemplate('HTCMage_ProductAttachment::attachmentlist.phtml')
            ->toHtml();
        return $html;
    }

    /**
     * @return array
     */
    public function listAttachment($display)
    {
        $store = $this->getStore();
        $customerGroup = $this->getCustomerGroup();
        $productId = $this->getRequest()->getParam('product');
        $listAttachment = $this->attachmentRepository->getAttachment($display, $store, $customerGroup, $productId);       
        $data = [];
        if ($listAttachment) {
            foreach ($listAttachment as $attachment) {
                if ($attachment['limited'] == 1) {
                    if ($attachment['number_of_download'] > 0) {
                        array_push($data, $attachment);
                    }
                }else{
                    array_push($data, $attachment);
                }
            }
        }
        return $data;
    }

    /**
     * @return mixed
     */
    public function isEnable()
    {
        $storeScope = ScopeInterface::SCOPE_STORES;
        return $this->scopeConfig->getValue('attachment/general/enable', $storeScope);
    }

    public function getCustomerGroup()
    {
        $customerGroup = $this->customerSession->getCustomer()->getGroupId();
        return $customerGroup;
    }

    public function getStore()
    {
        $currentStore = $this->storeManagerInterface->getStore()->getId();
        return $currentStore;
    }

    public function checkQtyDownload($attachment){

    }
}
