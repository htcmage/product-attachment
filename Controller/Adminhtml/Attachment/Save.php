<?php

namespace HTCMage\ProductAttachment\Controller\Adminhtml\Attachment;

use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use HTCMage\ProductAttachment\Model\Attachment\ImageUploader;
use HTCMage\ProductAttachment\Model\Attachment\FileUploader;

class Save extends Action
{

    protected $attachmentRepository;
    protected $helper;
    protected $imageUploader;
    protected $fileUploader;
    protected $productAttachmentRepository;
    protected $attachmentStoreViewRepository;
    protected $attachmentCustomerGroupRepository;
    protected $attachmentDisplayRepositoryRepository;


    public function __construct(
        Action\Context $context,
        \HTCMage\ProductAttachment\Model\Repository\AttachmentRepository $attachmentRepository,
        \HTCMage\ProductAttachment\Model\Repository\ProductAttachmentRepository $productAttachmentRepository,
        \HTCMage\ProductAttachment\Model\Repository\AttachmentStoreViewRepository $attachmentStoreViewRepository,
        \HTCMage\ProductAttachment\Model\Repository\AttachmentCustomerGroupRepository $attachmentCustomerGroupRepository,
        \HTCMage\ProductAttachment\Model\Repository\AttachmentDisplayRepository $attachmentDisplayRepositoryRepository,
        \HTCMage\ProductAttachment\Helper\Data $helper,
        ImageUploader $imageUploader,
        FileUploader $fileUploader
        ){
        parent::__construct($context);
        $this->attachmentRepository = $attachmentRepository;
        $this->helper = $helper;
        $this->imageUploader = $imageUploader;
        $this->fileUploader = $fileUploader;
        $this->productAttachmentRepository = $productAttachmentRepository;
        $this->attachmentStoreViewRepository = $attachmentStoreViewRepository;
        $this->attachmentCustomerGroupRepository = $attachmentCustomerGroupRepository;
        $this->attachmentDisplayRepositoryRepository = $attachmentDisplayRepositoryRepository;
    }


    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('htcmage_productattachment::newattachment_save');
    }

    public function checkData($data){
        if (!isset($data['icon']) || !isset($data['title']) || !isset($data['customer_group']) || !isset($data['file']) || !isset($data['status'])) {
            return false;
        }
        return true;
    }

    public function validateData($dataPost){
        $flag= true;
        foreach ($dataPost as $key => $value) {
                if ($key != 'id' && $key != 'url' && $key != 'position' && $key != 'number_of_download' ) {
                    if ($value == '' || $value == []) {
                        $flag= false;
                        break;
                    }
                }
            }
        return $flag;
    }

    public function fommatData($data)
    {
        if (empty($data['id'])) {
            $data['id'] = null;
        }
            $imageName = '';
            $imageName = $data['icon'][0]['name'];
            $fileName = '';
            $fileName = $data['file'][0]['name'];

            if ($imageName) {
                $data['icon']=  $this->imageUploader->moveFileFromTmp($imageName);
            }
            if ($fileName) {
                $data['file']=  $this->fileUploader->moveFileFromTmp($fileName);
            }
            if (isset($data['attachment_product'])) {
                $data['attachment_product'] = json_decode($data['attachment_product'], true);
            }

            return $data;
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $id = $this->getRequest()->getParam('id') ?? null;
            $attachmentRepository = $this->attachmentRepository->getById($id);

            if (!$this->checkData($data) || !$this->validateData($data)) {
                $this->messageManager->addError(__('Please enter all required information.'));
                return $resultRedirect->setPath('attachment/attachment/edit',['id' => $attachmentRepository->getId()]);
            }
            $data = $this->fommatData( $data );

            try {
                $attachmentRepository->setData($data);
                $this->attachmentRepository->save( $attachmentRepository);
                if ($this->attachmentRepository->save( $attachmentRepository)) {
                    $attachmentId = $attachmentRepository->getId();
                    if ( ! empty($data['attachment_product'])) {
                        $this->productAttachmentRepository->deleteProductsIfEdit($attachmentId);
                        $this->productAttachmentRepository->addProductsAttachment($data['attachment_product'],$attachmentId);
                    }

                    $this->attachmentStoreViewRepository->saveStoreLinks( $data['store_id'], $attachmentId );
                    $this->attachmentCustomerGroupRepository->saveGroupLinks($data['customer_group'], $attachmentId);
                    $this->attachmentDisplayRepositoryRepository->saveDisplayLinks($data['display'], $attachmentId);
                }
                $this->messageManager->addSuccess(__('Product Attachment saved'));
                $this->_getSession()->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('attachment/attachment/edit',['id' => $attachmentRepository->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the attachment'));
            }


            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit',['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/edit');
    }

}
