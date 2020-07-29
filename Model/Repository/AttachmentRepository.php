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


class AttachmentRepository
{

    /**
     * Const
     */
    const STORE_VIEW_ATTACHMENT = 'htcmage_productattachment_store_view';

    /**
     * Const
     */
    const CUSTOMER_GROUP_ATTACHMENT = 'htcmage_productattachment_customer_group';

    /**
     * Const
     */
    const PRODUCT_ATTACHMENT = 'htcmage_productattachment_product';

    /**
     * Const
     */
    const DISPLAY_ATTACHMENT = 'htcmage_productattachment_display';

    const ENABLE = '1';
    const DISABLE = '0';

    /**
     * Name of Main Table.
     *
     * @var string
     */
    protected $mainTable = 'htcmage_productattachment';

    private $attachmentFactory;

    private $resource;

    private $resourceModelPA;

    private $customerSession;

    /**
     * HTTP context
     *
     * @var \Magento\Framework\App\Http\Context $httpContext
     */
    private $httpContext;

    /**
     * Store manager
     *
     * @var StoreManagerInterface $storeManager
     */
    private $storeManager;

    /**
     * AttachmentRepository constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param Session $customerSession
     * @param AttachmentFactory $AttachmentFactory
     * @param ResourceModel\Attachment $resource
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        \Magento\Framework\App\Http\Context $httpContext,
        Session $customerSession,
        \HTCMage\ProductAttachment\Model\AttachmentFactory $attachmentFactory,
        \HTCMage\ProductAttachment\Model\ResourceModel\Attachment $resource,
        \HTCMage\ProductAttachment\Model\ProductAttachmentFactory $resourceModelPA
    )
    {
        $this->storeManager = $storeManager;
        $this->httpContext = $httpContext;
        $this->customerSession = $customerSession;
        $this->attachmentFactory = $attachmentFactory;
        $this->resource = $resource;
        $this->resourceModelPA = $resourceModelPA;
    }

    public function getMainTable(){
        return $this->resource->getTable($this->mainTable);
    }

    /**
     * Get Customer Group
     *
     * @return int
     */
    public function getCustomerGroup()
    {
        if ($this->isCustomerLoggedIn()) {
            return $this->httpContext->getValue(Context::CONTEXT_GROUP);
        } else {
            return Group::NOT_LOGGED_IN_ID;
        }
    }

    /**
     * Check Customer logged in
     *
     * @return mixed|null
     */
    public function isCustomerLoggedIn()
    {
        return $this->httpContext->getValue(Context::CONTEXT_AUTH);
    }

    /**
     * Get Attachment by id
     *
     * @param int|null $id
     * @return Attachment
     */
    public function load($id = null)
    {
        $model = $this->attachmentFactory->create();
        if ($id) {
            $this->resource->load($model, $id);
        }
        return $model;
    }

    public function getResourceModelPA()
    {
        return $this->resourceModelPA;
    }

    /**
     * @param null $id
     * @return mixed
     */
    public function create($id = null)
    {
        $model = $this->attachmentFactory->create();
        return $model;
    }

    /**
     * Save process
     *
     * @param Attachment $modelAttachment
     * @return Attachment|null
     */
    public function save($modelAttachment)
    {
        try {
            $this->resource->save($modelAttachment);
            return $modelAttachment;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * Delete
     *
     * @param Attachment $modelAttachment
     * @return bool
     */
    public function delete(Attachment $modelAttachment)
    {
        try {
            $this->resource->delete($modelAttachment);
        } catch (Exception $exception) {
            return false;
        }

    }

    /**
     * Get Banner by Id
     *
     * @param $id
     * @return Banner
     */
    public function getById($id = null)
    {
        $model = $this->attachmentFactory->create();
        if ($id) {
            $this->resource->load($model, $id);
        }
        return $model;
    }

    public function getAttachment($display, $store, $customerGroup, $productId)
    {
        $adapter = $this->resource->getConnection();
        $storeTbl = $this->resource->getTable(self::STORE_VIEW_ATTACHMENT);
        $groupTbl = $this->resource->getTable(self::CUSTOMER_GROUP_ATTACHMENT);
        $productTbl = $this->resource->getTable(self::PRODUCT_ATTACHMENT);
        $displayTbl = $this->resource->getTable(self::DISPLAY_ATTACHMENT);
        $select = $adapter->select()->from(
            ['mainTbl' => $this->resource->getMainTable()],
            ['*']
        )->joinLeft(
            ['storeViewTbl' => $storeTbl],
            'mainTbl.id = storeViewTbl.id_attachment',
            []
        )->joinLeft(
            ['groupTbl' => $groupTbl],
            'mainTbl.id = groupTbl.id_attachment',
            []
        )->joinLeft(
            ['productTbl' => $productTbl],
            'mainTbl.id = productTbl.id_attachment',
            []
        )->joinLeft(
            ['displayTbl' => $displayTbl],
            'mainTbl.id = displayTbl.id_attachment',
            []
        )->where(
            'status = ?', self::ENABLE
        )->where(
            'storeViewTbl.store_id = ?', $store
        )->where(
            'groupTbl.group_id = ?', $customerGroup
        )->where(
            'productTbl.id_product = ?', $productId
        )->where(
            'displayTbl.display = ?', $display
        )->order(
            'mainTbl.position DESC'
        );
        return $adapter->query($select);
    }

}
