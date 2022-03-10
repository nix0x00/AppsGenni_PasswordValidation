<?php
/**
 * AppsGenni.
 *
 * @author     Uzair
 * @copyright  Copyright (c) 2022 AppsGenni. ( http://example.com )
 */
namespace AppsGenni\PasswordValidation\Model;

use AppsGenni\PasswordValidation\Api\Data\PasswordValidationSearchResultInterface;
use Magento\Framework\Api\Search\SearchResult;

/**
 * Password Validation Search Result Class to
 * retrieve result based on search criteria
 *
 * Class PasswordValidationSearchResult
 */
class PasswordValidationSearchResult extends SearchResult implements PasswordValidationSearchResultInterface
{

}
