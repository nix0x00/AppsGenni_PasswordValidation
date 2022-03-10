<?php
/**
 * AppsGenni.
 *
 * @author     Uzair
 * @copyright  Copyright (c) 2022 AppsGenni. ( http://example.com )
 */
namespace AppsGenni\PasswordValidation\Setup\Patch\Data;

use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * Data patch to copy the customer
 * password hashes and keep a record
 *
 * Class CopyPasswordHash
 */
class CopyPasswordHash implements DataPatchInterface
{
    /**
     * @const Password Validation DB Table
     */
    const PASSWORD_VALIDATION_TABLE = 'lv_password_validation';

    /**
     * @var ModuleDataSetupInterface
     */
    private $dataSetup;

    /**
     * @var CollectionFactory
     */
    private $customerCollection;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CollectionFactory $collection
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CollectionFactory $collection
    ) {
        $this->customerCollection = $collection;
        $this->dataSetup = $moduleDataSetup;
    }

    /**
     * Method applies the data patch
     * and inserts data i.e. makes a
     * copy of customer data
     *
     * @return void
     */
    public function apply()
    {
        $data = [];
        $setup = $this->dataSetup;
        $collection = $this->customerCollection->create()->getItems();
        if (count($collection) <= 0) {
            return;
        }
        foreach ($collection as $customer) {
            $data[] = [
                'customer_id'    => (int)$customer->getId(),
                'customer_email' => $customer->getEmail(),
                'password_hash'  => $customer->getPasswordHash()
            ];
        }
        $setup->startSetup();
        $setup->getConnection()->insertArray(
            $setup->getTable(self::PASSWORD_VALIDATION_TABLE),
            ['customer_id', 'customer_email', 'password_hash'],
            $data
        );
        $setup->endSetup();
    }

    /**
     * Get array of patches that have to be executed prior to this.
     *
     * @return string[]
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * Get aliases (previous names) for the patch.
     *
     * @return string[]
     */
    public function getAliases(): array
    {
        return [];
    }
}
