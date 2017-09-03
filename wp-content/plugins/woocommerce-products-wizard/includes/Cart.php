<?php
namespace WCProductsWizard;

/**
 * WCProductsWizard FrontEnd Cart Class
 *
 * @class Cart
 * @version 2.1.1
 */
class Cart
{
    /**
     * Session key variable
     *
     * @var Cart session
     */
    public $sessionKey = 'woocommerce-products-wizard-cart';

    /**
     * Get the products cart from the session
     *
     * @param integer $postId
     * @param array $args
     *
     * @return array
     */
    public function get($postId, $args = [])
    {
        $defaults = [
            'groupByTerm' => false
        ];

        $args = array_replace_recursive($defaults, $args);
        $cart = [];

        if (isset($_SESSION[$this->sessionKey][$postId])) {
            foreach ($_SESSION[$this->sessionKey][$postId] as $key => $item) {
                $unSerializedItem = unserialize($item);

                if ($args['groupByTerm']) {
                    if (!isset($unSerializedItem['term_id']) || empty($unSerializedItem['term_id'])) {
                        continue;
                    }

                    $cart[$unSerializedItem['term_id']][] = $unSerializedItem;

                    continue;
                }

                $cart[$key] = $unSerializedItem;
            }
        }

        return apply_filters('wcProductWizardCart', $cart);
    }

    /**
     * Get products from the cart by the term ID
     *
     * @param integer $postId
     * @param integer $termId
     *
     * @return array
     */
    public function getProductsByTermId($postId, $termId)
    {
        $cart = $this->get($postId);
        $products = [];

        foreach ($cart as $cartItem) {
            if ($cartItem['term_id'] != $termId) {
                continue;
            }

            $products[$cartItem['product_id']] = $cartItem;
        }

        return apply_filters('wcProductWizardCartProduct', $products);
    }

    /**
     * Add a product to the cart
     *
     * @param integer $postId
     * @param array $cartItem
     */
    public function add($postId, $cartItem)
    {
        // Ensure we don't add a variation to the cart directly by variation ID
        $id = (isset($cartItem['variation_id']) && !empty($cartItem['variation_id']))
            ? $cartItem['variation_id']
            : $cartItem['product_id'];

        $cartItem['data'] = !empty($cartItem['data']) ? $cartItem['data'] : wc_get_product($id);

        if (isset($cartItem['variation'])) {
            foreach ($cartItem['variation'] as &$variation) {
                $variation = sanitize_text_field($variation);
            }
        }

        $keyId = WC()->cart->generate_cart_id(
            $cartItem['product_id'],
            (isset($cartItem['variation_id']) && !empty($cartItem['variation_id'])) ? $cartItem['variation_id'] : 0,
            isset($cartItem['variation']) ? $cartItem['variation'] : [],
            $cartItem['data']
        );

        $_SESSION[$this->sessionKey][$postId][$keyId] = serialize(
            apply_filters('wcProductWizardAddToCartItem', $cartItem, $postId, $this->sessionKey)
        );
    }

    /**
     * Remove the product from the cart by the product id
     *
     * @param integer $postId
     * @param integer|string $productId
     *
     * @return bool
     */
    public function removeByProductId($postId, $productId)
    {
        $productKey = $this->getProductKey($postId, $productId);

        if (!$productKey) {
            return false;
        }

        $this->removeByProductKey($postId, $productKey);

        return true;
    }

    /**
     * Remove the product from the cart by the product cart key
     *
     * @param integer $postId
     * @param integer|string $key
     *
     * @return bool
     */
    public function removeByProductKey($postId, $key)
    {
        if (!isset($_SESSION[$this->sessionKey][$postId][$key])) {
            return false;
        }

        unset($_SESSION[$this->sessionKey][$postId][$key]);

        return true;
    }

    /**
     * Check product is in the cart
     *
     * @param integer $postId
     * @param integer $productId
     *
     * @return bool
     */
    public function productIsset($postId, $productId)
    {
        $cart = $this->get($postId);

        foreach ($cart as $cartItem) {
            if ($cartItem['product_id'] != $productId) {
                continue;
            }

            return true;
        }

        return false;
    }

    /**
     * Get product cart key
     *
     * @param integer $postId
     * @param integer $productId
     *
     * @return bool|string
     */
    public function getProductKey($postId, $productId)
    {
        $cart = $this->get($postId);

        foreach ($cart as $cartItemKey => $cartItem) {
            if ($cartItem['product_id'] != $productId) {
                continue;
            }

            return $cartItemKey;
        }

        return false;
    }

    /**
     * Remove the products from the cart by term Id
     *
     * @param integer $postId
     * @param integer|string $termId
     */
    public function removeByTermId($postId, $termId)
    {
        $cart = $this->get($postId);

        foreach ($cart as $key => $item) {
            if ($item['term_id'] != $termId) {
                continue;
            }

            $this->removeByProductKey($postId, $key);
        }
    }

    /**
     * Truncate the cart
     *
     * @param integer $postId
     */
    public function truncate($postId)
    {
        unset($_SESSION[$this->sessionKey][$postId]);
    }

    /**
     * Get the total price of the cart
     *
     * @param integer $postId
     *
     * @return number
     */
    public function getTotal($postId)
    {
        $total = 0;

        foreach ($this->get($postId) as $cartItem) {
            $productId = isset($cartItem['variation_id']) ? $cartItem['variation_id'] : $cartItem['product_id'];
            $product = wc_get_product($productId);

            if (!$product || !$product->exists() || $cartItem['quantity'] <= 0) {
                continue;
            }


            $total += (float) ($product->get_price() * $cartItem['quantity']);
        }

        return apply_filters('wcProductWizardCartTotal', $total);
    }
}
