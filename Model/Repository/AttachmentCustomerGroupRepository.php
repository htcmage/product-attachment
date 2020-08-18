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
use HTCMage\ProductAttachment\Model\AttachmentCustomerGroup;
use HTCMage\ProductAttachment\Model\AttachmentCustomerGroupFactory;
use Magento\Customer\Model\Context;
use Magento\Customer\Model\Group;
use Magento\Customer\Model\Session;
use Magento\Group\Model\GroupManagerInterface;


/**
 * Class AttachmentCustomerGroupRepository
 * @package HTCMage\ProductAttachment\Model\Repository
 */
class AttachmentCustomerGroupRepository
{

    /**
     * @var AttachmentCustomerGroupFactory
     */
    private $attachmentCustomerGroupFactory;

    /**
     * @var ResourceModel\Attachment|\HTCMage\ProductAttachment\Model\ResourceModel\AttachmentCustomerGroup
     */
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
        AttachmentCustomerGroupFactory $attachmentCustomerGroupFactory,
        \HTCMage\ProductAttachment\Model\ResourceModel\AttachmentCustomerGroup $resource
    )
    {
        $this->attachmentCustomerGroupFactory = $attachmentCustomerGroupFactory;
        $this->resource = $resource;
    }

    /**
     * @param $listGroup
     * @param $attachmentId
     * @return bool
     */
    public function saveGroupLinks($listGroup, $attachmentId)
    {
        $model = $this->getById();
        $this->deleteGroupLink($attachmentId);
        if (!empty($listGroup)) {
            foreach ($listGroup as $groupId) {
                $dataGroup['group_id'] = $groupId;
                $dataGroup['id_attachment'] = $attachmentId;
                $model->setData($dataGroup);
                $this->save($model);
            }
        } else {
            return false;
        }
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
     * @param $idAttachment
     */
    public function deleteGroupLink($idAttachment)
    {
        $model = $this->getById();
        $data = $this->getGroupLinkByAttachment($idAttachment);
        foreach ($data as $value) {
            $model->load($value['id']);
            $this->delete($model);
        }
    }

    /**
     * @param $idAttachment
     * @return mixed
     */
    public function getGroupLinkByAttachment($idAttachment)
    {
        $collection = $this->getById()->getCollection();
        $collection->addFieldToFilter('id_attachment', ['eq' => "$idAttachment"]);
        return $collection->getData();
    }

    /**
     * Delete
     *
     * @param Attachment $modelAttachmentCustomerGroup
     * @return bool
     */
    public function delete(AttachmentCustomerGroup $modelAttachmentCustomerGroup)
    {
        try {
            $this->resource->delete($modelAttachmentCustomerGroup);
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * Save process
     *
     * @param Attachment $modelAttachmentCustomerGroup
     * @return Attachment|null
     */
    public function save(AttachmentCustomerGroup $modelAttachmentCustomerGroup)
    {
        try {
            $this->resource->save($modelAttachmentCustomerGroup);
            return $modelAttachmentCustomerGroup;
        } catch (Exception $exception) {
            return null;
        }
    }
}
