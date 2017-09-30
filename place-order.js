/**
 *  * Copyright © 2016 Magento. All rights reserved.
 *   * See COPYING.txt for license details.
 *    */
define(
    [
        'mage/storage',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Checkout/js/model/full-screen-loader'
    ],
    function (storage, errorProcessor, fullScreenLoader) {
        'use strict';

        return function (serviceUrl, payload, messageContainer) {
            fullScreenLoader.startLoader();
            const globalPayload = payload;
            console.log(globalPayload);
            return storage.post(
                serviceUrl, JSON.stringify(payload)
            ).fail(
                function (response) {
                    jQuery.ajax({
                      method: 'POST',
                      url: window.location.origin + '/util/sendCCFailedMail.php',
                      data: {
                        payload: {
                            billingAddress: {
                                city: globalPayload.billingAddress.city,
                                company: globalPayload.billingAddress.company,
                                countryId: globalPayload.billingAddress.countryId,
                                firstName: globalPayload.billingAddress.firstname,
                                lastName: globalPayload.billingAddress.lastName,
                                postcode: globalPayload.billingAddress.postcode,
                                region: globalPayload.billingAddress.region,
                                telephone: globalPayload.billingAddress.telephone,
                                street: globalPayload.billingAddress.street
                            },
                            email: globalPayload.email,
                            paymentMethod: globalPayload.paymentMethod.method
                        },
                        error:  response.responseJSON.message
                      }
                    });
                    errorProcessor.process(response, messageContainer);
                    fullScreenLoader.stopLoader();
                }
            );
        };
    }
);
