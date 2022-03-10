<?php
/**
 * AppsGenni.
 *
 * @author     Uzair
 * @copyright  Copyright (c) 2022 AppsGenni. ( http://example.com )
 */
namespace AppsGenni\PasswordValidation\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Retrieve Store Configuration Values
 *
 * Class Configuration
 */
class Configuration
{
    /**
     * @const Password Validation Path
     */
    const XML_PASSWORD_VALIDATION_PATH = 'lv_password_validation/general/enable';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Configuration constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Retrieve Config Value
     *
     * @param string $field
     * @param mixed $storeId
     * @return mixed
     */
    public function getConfigurationValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue($field, $storeId);
    }

    /**
     * Check if Password Validation is enabled
     *
     * @param mixed $storeId
     * @return bool
     */
    public function ifPassValidationEnabled($storeId = null)
    {
        return ($this->getConfigurationValue(self::XML_PASSWORD_VALIDATION_PATH, $storeId) == 1) ? true : false;
    }
}
