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
use Magento\Group\Model\GroupManagerInterface;


class AttachmentCustomerGroupRepository
{

    private $attachmentCustomerGroupFactory;

    private $resource;

    /**
     * AttachmentCustomerGroupRepository constructor.
     *
     * @param GroupManagerInterface $groupManager
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param Session $customerSession
     * @param AttachmentFactory $AttachmentFactory
     * @param ResourceModel\Attachment $resource
     */
    public function __construct(
        \HTCMage\ProductAttachment\Model\AttachmentCustomerGroupFactory $attachmentCustomerGroupFactory,
        \HTCMage\ProductAttachment\Model\ResourceModel\AttachmentCustomerGroup $resource
    )
    {
        $this->attachmentCustomerGroupFactory = $attachmentCustomerGroupFactory;
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
        $model = $this->attachmentCustomerGroupFactory->create();
        if ($id) {
            $this->resource->load($model, $id);
        }
        return $model;
    }

    /**
     * Save process
     *
     * @param Attachment $modelAttachmentCustomerGroup
     * @return Attachment|null
     */
    public function save(\HTCMage\ProductAttachment\Model\AttachmentCustomerGroup $modelAttachmentCustomerGroup)
    {
        try {
            $this->resource->save($modelAttachmentCustomerGroup);
            return $modelAttachmentCustomerGroup;
        } catch (Exception $exception) {
            return null;
        }
    }

    /**
     * Delete
     *
     * @param Attachment $modelAttachmentCustomerGroup
     * @return bool
     */
    public function delete(\HTCMage\ProductAttachment\Model\AttachmentCustomerGroup $modelAttachmentCustomerGroup)
    {
        try {
            $this->resource->delete($modelAttachmentCustomerGroup);
        } catch (Exception $exception) {
            return false;
        }
    }


    public function saveGroupLinks($listGroup, $attachmentId)
    {
        $model = $this->getById();
        $this->deleteGroupLink($attachmentId);
        if ( ! empty( $listGroup ) ) {
            foreach ( $listGroup as $groupId ) {
                $dataGroup['group_id'] = $groupId;
                $dataGroup['id_attachment'] = $attachmentId;
                $model->setData( $dataGroup );
                $this->save($model);
            }
        } else {
            return false;
        }
    }

    public function getGroupLinkByAttachment($idAttachment)
    {
        $collection = $this->getById()->getCollection();
        $collection->addFieldToFilter('id_attachment', ['eq' => "$idAttachment"]);
        return $collection->getData();
    }

    public function deleteGroupLink($idAttachment){
        $model = $this->getById();
        $data = $this->getGroupLinkByAttachment($idAttachment);
        foreach ($data as $value) {
            $model->load($value['id']);
            $this->delete($model);
        }
    }
}
