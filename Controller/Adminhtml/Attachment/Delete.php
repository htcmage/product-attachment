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
use HTCMage\ProductAttachment\Model\Repository\AttachmentRepository;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;

/**
 * Class Delete
 * @package HTCMage\ProductAttachment\Controller\Adminhtml\Attachment
 */
class Delete extends Action
{

    /**
     * @var AttachmentRepository
     */
    protected $attachmentRepository;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param AttachmentRepository $attachmentRepository
     */
    public function __construct(
        Action\Context $context,
        AttachmentRepository $attachmentRepository
    )
    {
        $this->attachmentRepository = $attachmentRepository;
        parent::__construct($context);
    }

    /**
     * Delete action
     *
     * @return Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {

            try {
                $model = $this->attachmentRepository->getById($id);
                $this->attachmentRepository->delete($model);
                $this->messageManager->addSuccessMessage(
                    __('Att has been deleted.')
                );
                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('attachment/attachment/index');
            }
        }
        $this->messageManager->addError(__('We can\'t find a att to delete.'));
        return $resultRedirect->setPath('attachment/attachment/index');
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('HTCMage_ProductAttachment::delete');
    }
    
}
