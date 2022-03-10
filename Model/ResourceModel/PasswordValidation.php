<?php
/**
 * AppsGenni.
 *
 * @author     Uzair
 * @copyright  Copyright (c) 2022 AppsGenni. ( http://example.com )
 */
namespace AppsGenni\PasswordValidation\Model\ResourceModel;

use AppsGenni\PasswordValidation\Api\Data\PasswordValidationInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Resource Model class for LV Password Validation
 *
 * Class PasswordValidation
 */
class PasswordValidation extends AbstractDb
{
    /**
     * @const Table Name
     */
    const TABLE_FIELD = PasswordValidationInterface::MAIN_TABLE;
    /**
     * @const ID Field
     */
    const ID_FIELD = 'entity_id';

    /**
     * Initialization here
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_FIELD, self::ID_FIELD);
    }

}
