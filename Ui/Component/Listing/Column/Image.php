<?php
/**
 * NOTICE OF LICENSE
 *
 * @author HTCMage Team
 * @copyright Copyright (c) 2020 HTCMage (https://www.htcmage.com)
 * @package HTCMage_ProductAttachment
 *
 */

namespace HTCMage\ProductAttachment\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Asset\Repository;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Image
 * @package HTCMage\ProductAttachment\Ui\Component\Listing\Column
 */
class Image extends Column
{
    /**
     *
     */
    const NAME = 'thumbnail';
    /**
     *
     */
    const ALT_FIELD = 'name';
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var Repository
     */
    private $assetRepo;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        StoreManagerInterface $storeManager,
        Repository $assetRepo,
        array $components = [],
        array $data = []
    )
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->storeManager = $storeManager;
        $this->assetRepo = $assetRepo;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            $path = $this->storeManager->getStore()->getBaseUrl(
                    UrlInterface::URL_TYPE_MEDIA
                ) . 'htcmage/attachment/';

            $baseImage = $this->assetRepo->getUrl('HTCMage_ProductAttachment::images/icon.jpg');
            foreach ($dataSource['data']['items'] as & $item) {
                if ($item['icon']) {
                    $item['icon' . '_src'] = $path . $item['icon'];
                    $item['icon' . '_alt'] = $item['title'];
                    $item['icon' . '_orig_src'] = $path . $item['icon'];
                } else {
                    $item['icon' . '_src'] = $baseImage;
                    $item['icon' . '_alt'] = 'Att';
                    $item['icon' . '_orig_src'] = $baseImage;
                }
            }
        }

        return $dataSource;
    }
}
