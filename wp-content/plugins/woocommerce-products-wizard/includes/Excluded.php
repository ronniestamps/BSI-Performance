<?php
namespace WCProductsWizard;

/**
 * WCProductsWizard FrontEnd Excluded Class
 *
 * @class Excluded
 * @version 2.0.1
 */
class Excluded
{
    /**
     * Session key variable
     *
     * @var Excluded session
     */
    public $sessionKey = 'woocommerce-products-wizard-excluded-terms';

    /**
     * Get the excluded products from the session
     *
     * @param integer $postId
     *
     * @return array
     */
    public function get($postId)
    {
        return isset($_SESSION[$this->sessionKey][$postId]) ? $_SESSION[$this->sessionKey][$postId] : [];
    }

    /**
     * Add the term to the excluded
     *
     * @param integer $postId
     *
     * @param integer $postId
     * @param integer $termId
     */
    public function add($postId, $termId)
    {
        $_SESSION[$this->sessionKey][$postId][$termId] = $termId;
    }

    /**
     * Remove the term from the excluded
     *
     * @param integer $postId
     *
     * @param integer|string $termId
     */
    public function remove($postId, $termId)
    {
        unset($_SESSION[$this->sessionKey][$postId][$termId]);
    }

    /**
     * Truncate the excluded
     *
     * @param integer $postId
     */
    public function truncate($postId)
    {
        unset($_SESSION[$this->sessionKey][$postId]);
    }
}
