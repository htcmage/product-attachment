<?php
/**
 * NOTICE OF LICENSE
 *
 * @author HTCMage Team
 * @copyright Copyright (c) 2020 HTCMage (https://www.htcmage.com)
 * @package HTCMage_ProductAttachment
 *
 */

namespace HTCMage\ProductAttachment\Controller\Adminhtml\Attachment;

use Exception;
use HTCMage\ProductAttachment\Model\Attachment\FileUploader;
use HTCMage\ProductAttachment\Model\Attachment\ImageUploader;
use HTCMage\ProductAttachment\Model\Repository\AttachmentCustomerGroupRepository;
use HTCMage\ProductAttachment\Model\Repository\AttachmentDisplayRepository;
use HTCMage\ProductAttachment\Model\Repository\AttachmentRepository;
use HTCMage\ProductAttachment\Model\Repository\AttachmentStoreViewRepository;
use HTCMage\ProductAttachment\Model\Repository\ProductAttachmentRepository;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use RuntimeException;

/**
 * Class Save
 * @package HTCMage\ProductAttachment\Controller\Adminhtml\Attachment
 */
class Save extends Action
{

    /**
     * const
     */
    const ADMIN_RESOURCE = 'HTCMage_ProSlider::main';

    /**
     * @var AttachmentRepository
     */
    protected $attachmentRepository;
    /**
     * @var ImageUploader
     */
    protected $imageUploader;
    /**
     * @var FileUploader
     */
    protected $fileUploader;
    /**
     * @var ProductAttachmentRepository
     */
    protected $productAttachmentRepository;
    /**
     * @var AttachmentStoreViewRepository
     */
    protected $attachmentStoreViewRepository;
    /**
     * @var AttachmentCustomerGroupRepository
     */
    protected $attachmentCustomerGroupRepository;
    /**
     * @var AttachmentDisplayRepository
     */
    protected $attachmentDisplayRepositoryRepository;


    /**
     * Save constructor.
     * @param Action\Context $context
     * @param AttachmentRepository $attachmentRepository
     * @param ProductAttachmentRepository $productAttachmentRepository
     * @param AttachmentStoreViewRepository $attachmentStoreViewRepository
     * @param AttachmentCustomerGroupRepository $attachmentCustomerGroupRepository
     * @param AttachmentDisplayRepository $attachmentDisplayRepositoryRepository
     * @param Data $helper
     * @param ImageUploader $imageUploader
     * @param FileUploader $fileUploader
     */
    public function __construct(
        Action\Context $context,
        AttachmentRepository $attachmentRepository,
        ProductAttachmentRepository $productAttachmentRepository,
        AttachmentStoreViewRepository $attachmentStoreViewRepository,
        AttachmentCustomerGroupRepository $attachmentCustomerGroupRepository,
        AttachmentDisplayRepository $attachmentDisplayRepositoryRepository,
        ImageUploader $imageUploader,
        FileUploader $fileUploader
    )
    {
        parent::__construct($context);
        $this->attachmentRepository = $attachmentRepository;
        $this->imageUploader = $imageUploader;
        $this->fileUploader = $fileUploader;
        $this->productAttachmentRepository = $productAttachmentRepository;
        $this->attachmentStoreViewRepository = $attachmentStoreViewRepository;
        $this->attachmentCustomerGroupRepository = $attachmentCustomerGroupRepository;
        $this->attachmentDisplayRepositoryRepository = $attachmentDisplayRepositoryRepository;

    }

    /**
     * @return mixed
     */
    public function execute()
    {

        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            $id = $this->getRequest()->getParam('id') ?? null;
            $attachmentRepository = $this->attachmentRepository->getById($id);
            $data = $this->fommatData($data);
            try {
                $attachmentRepository->setData($data);
                $this->attachmentRepository->save($attachmentRepository);
                if ($this->attachmentRepository->save($attachmentRepository)) {
                    $attachmentId = $attachmentRepository->getId();
                    if ( array_key_exists('attachment_product', $data) ) {
                        $this->productAttachmentRepository->deleteProductsIfEdit($attachmentId);
                        if (! empty($data['attachment_product'])) {
                            
                            $this->productAttachmentRepository->addProductsAttachment($data['attachment_product'], $attachmentId);
                            foreach ($data['attachment_product'] as $productId => $value) {
                                $this->productAttachmentRepository->getAttachmentOfProduct($productId, $attachmentId);
                            }
                        }
                    }
                    $this->attachmentStoreViewRepository->saveStoreLinks($data['store_id'], $attachmentId);
                    $this->attachmentCustomerGroupRepository->saveGroupLinks($data['customer_group'], $attachmentId);
                    $this->attachmentDisplayRepositoryRepository->saveDisplayLinks($data['display'], $attachmentId);
                }
                $this->messageManager->addSuccess(__('Product Attachment saved'));
                $this->_getSession()->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('attachment/attachment/edit', ['id' => $attachmentRepository->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the attachment'));
            }
            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/edit');
    }

    /**
     * @param $data
     * @return mixed
     */
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
            $data['icon'] = $this->imageUploader->moveFileFromTmp($imageName);
        }
        if ($fileName) {
            $data['file'] = $this->fileUploader->moveFileFromTmp($fileName);
        }
        if (isset($data['attachment_product'])) {
            $data['attachment_product'] = json_decode($data['attachment_product'], true);
        }

        return $data;
    }

    /**
     * @return mixed
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('HTCMage_ProductAttachment::save');
    }

}
