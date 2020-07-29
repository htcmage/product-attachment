<?php
/**
 * *
 *  * @author HTCMage Team
 *  * @copyright Copyright (c) 2020 HTCMage (https://www.htcmage.com)
 *  * @package HTCMage_Attachment
 *
 */

namespace HTCMage\ProductAttachment\Model\Repository;

use Exception;
use Magento\Customer\Model\Context;
use Magento\Customer\Model\Group;
use Magento\Customer\Model\Session;
use Magento\Store\Model\StoreManagerInterface;


class AttachmentDisplayRepository
{

    private $attachmentDisplayFactory;

    private $resource;

    /**
     * AttachmentDisplayRepository constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param Session $customerSession
     * @param AttachmentFactory $AttachmentFactory
     * @param ResourceModel\Attachment $resource
     */
    public function __construct(
        \HTCMage\ProductAttachment\Model\AttachmentDisplayFactory $attachmentDisplayFactory,
        \HTCMage\ProductAttachment\Model\ResourceModel\AttachmentDisplay $resource
    )
    {
        $this->attachmentDisplayFactory = $attachmentDisplayFactory;
        $this->resource = $resource;
    }

    /**
     * Get Attachment by id
     *
     * @param int|null $id
     * @return Attachment
     */
    public function getById($id = null)
    {
        $model = $this->attachmentDisplayFactory->create();
        if ($id) {
            $this->resource->load($model, $id);
        }
        return $model;
    }

    /**
     * Save process
     *
     * @param Attachment $modelAttachmentDisplay
     * @return Attachment|null
     */
    public function save(AttachmentDisplay $modelAttachmentDisplay)
    {
        try {
            $this->resource->save($modelAttachmentDisplay);
            return $modelAttachmentDisplay;
        } catch (Exception $exception) {
            return null;
        }
    }

    /**
     * Delete
     *
     * @param AttachmentDisplay $modelAttachmentDisplay
     * @return bool
     */
    public function delete(AttachmentDisplay $modelAttachmentDisplay)
    {
        try {
            $this->resource->delete($modelAttachmentDisplay);
        } catch (Exception $exception) {
            return false;
        }
    }


    public function saveDisplayLinks($listDisplay, $attachmentId)
    {
        $model = $this->getById();
        $this->deleteDisplayLink($attachmentId);
        if ( ! empty( $listDisplay ) ) {
            foreach ( $listDisplay as $displayId ) {
                $dataDisplay['display'] = $displayId;
                $dataDisplay['id_attachment'] = $attachmentId;
                $model->setData( $dataDisplay );
                $model->save();
            }
        } else {
            return false;
        }
    }

    public function getDisplayLinkByAttachment($idAttachment)
    {
        $collection = $this->getById()->getCollection();
        $collection->addFieldToFilter('id_attachment', ['eq' => "$idAttachment"]);
        return $collection->getData();
    }

    public function deleteDisplayLink($idAttachment){
        $model = $this->getById();
        $data = $this->getDisplayLinkByAttachment($idAttachment);
        foreach ($data as $value) {
            $model->load($value['id']);
            $model->delete();
        }
    }
}
