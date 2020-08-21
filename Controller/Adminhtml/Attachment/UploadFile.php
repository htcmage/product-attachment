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
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use PHPCuong\BannerSlider\Model\Banner\ImageUploader;

/**
 * Class Upload
 */
class UploadFile extends Action
{
    /**
     * @var FileUploader
     */
    protected $FileUploader;

    /**
     * Upload constructor.
     *
     * @param Context $context
     * @param ImageUploader $imageUploader
     */
    public function __construct(
        Context $context,
        FileUploader $FileUploader
    )
    {
        parent::__construct($context);
        $this->FileUploader = $FileUploader;
    }

    /**
     * Upload file controller action.
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $fileId = $this->_request->getParam('param_name', 'file');
        try {
            $resultFile = $this->FileUploader->saveFileToTmpDir($fileId);

            $resultFile['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (Exception $e) {
            $resultFile = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($resultFile);
    }
}
