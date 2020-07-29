<?php

namespace HTCMage\ProductAttachment\Controller\Adminhtml\Attachment;

use Magento\Backend\App\Action;

class Delete extends Action
{

    protected $attachmentRepository;

    /**
     * @param Action\Context $context
     * @param \Bss\Testimonials\Model\TestimonialFactory $testimonialFactory
     */
    public function __construct(
        Action\Context $context,
        \HTCMage\ProductAttachment\Model\Repository\AttachmentRepository $attachmentRepository
    ) {
        $this->attachmentRepository = $attachmentRepository;
        parent::__construct($context);
    }

    /**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('id');

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {

            try {
                $model = $this->attachmentRepository->getById($id);
                $this->attachmentRepository->delete($model);
                $this->messageManager->addSuccessMessage(
                    __('Att has been deleted.')
                );
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('attachment/attachment/index');
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a att to delete.'));
        // go to grid
        return $resultRedirect->setPath('attachment/attachment/index');
    }
}
