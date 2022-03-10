<?php
/**
 * AppsGenni.
 *
 * @author     Uzair
 * @copyright  Copyright (c) 2022 AppsGenni. ( http://example.com )
 */
namespace AppsGenni\PasswordValidation\Model\ResourceModel\Collection;

use AppsGenni\PasswordValidation\Api\Data\PasswordValidationInterface;
use AppsGenni\PasswordValidation\Model\PasswordValidation;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use AppsGenni\PasswordValidation\Model\ResourceModel\PasswordValidation as ResourceModel;

/**
 * Collection class for Password Validation
 *
 * Class Collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = PasswordValidationInterface::MAIN_TABLE;

    /**
     * @var string
     */
    protected $_idFieldName = PasswordValidationInterface::ENTITY_ID;

    /**
     * @var string
     */
    protected $_eventObject = 'lv_password_validation_collection';

    /**
     * Initialization here
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(PasswordValidation::class, ResourceModel::class);
    }
}
