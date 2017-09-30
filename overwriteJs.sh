#!/usr/bin/env bash

cp -avr /var/www/store/vendor/magento/module-checkout/view/frontend/web/js/model/place-order.js /var/www/store/vendor/magento/module-checkout/view/frontend/web/js/model/place-order.js.bak
echo "place-order.js backup has been made."
cp -avr place-order.js /var/www/store/vendor/magento/module-checkout/view/frontend/web/js/model/place-order.js
echo "place-order.js has been overwritten."
composer install
echo "Dependencies have been installed."
exit 0
