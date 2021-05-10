# Mage2 Product Attachment
# Description
Add file or URL file to product

## Installation

### Installation by Zip file
- Unzip the zip file and copy the content to `app/code/HTCMage/ProductAttachment`
- Run command: `php bin/magento module:enable HTCMage_ProductAttachment`
- Run command: `php bin/magento setup:upgrade`
- Flush cache: `php bin/magento cache:flush`
### Installation by Composer
- Run `composer require htcmage/productattachment` to install module
- Run command: `php bin/magento module:enable HTCMage_ProductAttachment`
- Run command: `php bin/magento setup:upgrade`
- Flush cache: `php bin/magento cache:flush`

## How to use
- Setting to enable/disable module: `Stores -> Configuration -> HTCMage -> Product Attachment`
- Manage attachments: `Catalog -> Attachments Management`
