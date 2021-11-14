Flatsome.behavior('cart-refresh', {
  attach: function () {
    if (!jQuery('.cart-auto-refresh').length) {
      return
    }
    let updateCartButton = jQuery('button[name=\'update_cart\']')
    let updateCart = null

    jQuery('.woocommerce-cart-form').find('.cart_item .qty').on('change', function () {
      if (updateCart != null) {
        clearTimeout(updateCart)
      }
      updateCart = setTimeout(function () {
        updateCartButton.trigger('click')
      }, 1200)
    })
  }
})
