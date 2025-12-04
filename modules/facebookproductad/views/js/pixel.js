/*
*
* Dynamic Ads + Pixel
*
* @author    BusinessTech.fr - https://www.businesstech.fr
* @copyright Business Tech - https://www.businesstech.fr
* @license   Commercial
*
*           ____    _______
*          |  _ \  |__   __|
*          | |_) |    | |
*          |  _ <     | |
*          | |_) |    | |
*          |____/     |_|
*
*/

document.addEventListener('DOMContentLoaded', function () {

    if (btPixel.pixel_id != "") {
        // Init the pixel code
        !(function (f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function () {
                n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments);
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = "2.0";
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s);
        })(window, document, "script", "https://connect.facebook.net/en_US/fbevents.js");

        fbq('set', 'autoConfig', false, btPixel.pixel_id);

        if (btPixel.bUseConsent == true) {
            if (btPixel.bUseAxeption == 1) {
                void 0 === window._axcb && (window._axcb = []);
                window._axcb.push(function (axeptio) {
                    axeptio.on("cookies:complete", function (choices) {
                        if (choices.facebook_pixel) {
                            $.ajax({
                                type: "POST",
                                url: btPixel.ajaxUrl,
                                dataType: "json",
                                data: {
                                    ajax: 1,
                                    action: "updateConsent",
                                    token: btPixel.token,
                                },
                                success: function (jsonData, textStatus, jqXHR) {
                                    fbq("init", btPixel.pixel_id);
                                    fbq("track", "PageView", { 'eventID': btPixel.eventId });
                                    fbq("consent", "grant");
                                },
                            });
                        }
                    });
                });

            } else if (btPixel.bUseAxeption == 0) {
                if (btPixel.bConsentHtmlElement != "") {
                    $(btPixel.bConsentHtmlElement).on("click", function (event) {
                        $.ajax({
                            type: "POST",
                            url: btPixel.ajaxUrl,
                            dataType: "json",
                            data: {
                                ajax: 1,
                                action: "updateConsent",
                                token: btPixel.token,
                            },
                            success: function (jsonData, textStatus, jqXHR) {
                                fbq("init", btPixel.pixel_id);
                                fbq("track", "PageView", { 'eventID': btPixel.eventId });
                                fbq("consent", "grant");
                            },
                        });
                    });

                    if (btPixel.bConsentHtmlElementSecond != "") {
                        $(btPixel.bConsentHtmlElementSecond).on("click", function (event) {
                            $.ajax({
                                type: "POST",
                                url: btPixel.ajaxUrl,
                                dataType: "json",
                                data: {
                                    ajax: 1,
                                    action: "updateConsent",
                                    token: btPixel.token,
                                },
                                success: function (jsonData, textStatus, jqXHR) {
                                    fbq("init", btPixel.pixel_id);
                                    fbq("track", "PageView", { 'eventID': btPixel.eventId });
                                    fbq("consent", "grant");
                                },
                            });
                        });
                    }
                }
            }

            // Use the consent level from ACB
            if (btPixel.iConsentConsentLvl == 0) {
                fbq("consent", "revoke");
            } else if (btPixel.iConsentConsentLvl == 1) {
                fbq("consent", "revoke");
            } else if (btPixel.iConsentConsentLvl == 2 || btPixel.iConsentConsentLvl == 3) {
                if (btPixel.advancedMatchingData == false || btPixel.useAdvancedMatching == false) {
                    fbq("init", btPixel.pixel_id, {
                        external_id: btPixel.external_id,
                    });
                } else {
                    fbq("init", btPixel.pixel_id, {
                        external_id: btPixel.external_id,
                        em: btPixel.advancedMatchingData.em,
                        fn: btPixel.advancedMatchingData.fn,
                        ln: btPixel.advancedMatchingData.ln,
                    });
                }

                fbq("track", "PageView", { 'eventID': btPixel.eventId });
                fbq("consent", "grant");
            }

        } else {
            if (btPixel.advancedMatchingData == false || btPixel.useAdvancedMatching == false) {
                fbq("init", btPixel.pixel_id, {
                    external_id: btPixel.external_id,
                });
            } else {
                fbq("init", btPixel.pixel_id, {
                    external_id: btPixel.external_id,
                    em: btPixel.advancedMatchingData.em,
                    fn: btPixel.advancedMatchingData.fn,
                    ln: btPixel.advancedMatchingData.ln,
                });
            }

            fbq("track", "PageView", { 'eventID': btPixel.eventId });

            // Allow third-party modules to disable Pixel
            doNotConsentToPixel = false;
            fbq('consent', !!window.doNotConsentToPixel ? 'revoke' : 'grant');
        }

        // Use Ajax call to send API event to make better TTFB
        if (btPixel.useConversionApi == 1) {
            $.ajax({
                type: "POST",
                url: btPixel.ajaxUrl,
                dataType: "json",
                data: {
                    ajax: 1,
                    action: "sendApiData",
                    token: btPixel.token,
                    tagContent: btPixel.tagContentApi,
                    useApiForPageView: btPixel.useApiForPageView,
                    apiToken: btPixel.ApiToken,
                    pagetype: btPixel.currentPage,
                    id_order: btPixel.id_order,
                }
            });
        }


        if (typeof btPixel.tagContent !== "undefined" && typeof btPixel.tagContent.aTrackingType !== "undefined" || typeof btPixel.tagContent.aDynTags.content_name !== "undefined" || typeof btPixel.tagContent.aDynTags.content_ids !== "undefined") {

            if (btPixel.tagContent.aTrackingType.value == "ViewContent" && typeof btPixel.tagContent.aDynTags.content_name !== "undefined") {
                fbq("track", "ViewContent", {
                    content_name: btPixel.tagContent.aDynTags.content_name.value,
                    content_category: btPixel.tagContent.aDynTags.content_category.value,
                    content_ids: btPixel.tagContent.aDynTags.content_ids.value,
                    content_type: btPixel.tagContent.aDynTags.content_type.value,
                    value: btPixel.tagContent.aDynTags.value.value,
                    currency: btPixel.tagContent.aDynTags.currency.value,
                },
                    { 'eventID': btPixel.eventId });
            }

            if (typeof btPixel.tagContent.aDynTags.content_ids !== "undefined") {
                if (btPixel.tagContent.aTrackingType.value == "ViewCategory") {
                    fbq("trackCustom", "ViewCategory", {
                        content_name: btPixel.tagContent.aDynTags.content_name.value,
                        content_category: btPixel.tagContent.aDynTags.content_category.value,
                        content_ids: btPixel.tagContent.aDynTags.content_ids.value,
                        content_type: btPixel.tagContent.aDynTags.content_type.value,
                    },
                        { 'eventID': btPixel.eventId });
                }
            } else {
                if (btPixel.tagContent.aTrackingType.value == "ViewCategory") {
                    if (typeof btPixel.tagContent.aDynTags.content_name !== "undefined") {
                        fbq("trackCustom", "ViewCategory", {
                            content_name: btPixel.tagContent.aDynTags.content_name.value,
                            content_category: btPixel.tagContent.aDynTags.content_category.value,
                            content_type: btPixel.tagContent.aDynTags.content_type.value,
                        },
                            { 'eventID': btPixel.eventId });
                    }
                }
            }

            if (btPixel.tagContent.aTrackingType.value == "ViewContentHomepage") {

                if (typeof btPixel.tagContent.aDynTags.content_ids !== "undefined" && typeof btPixel.tagContent.aDynTags.content_ids.value !== "undefined") {
                    fbq("trackCustom", "ViewContentHomepage", {
                        content_name: btPixel.tagContent.aDynTags.content_name.value,
                        content_category: btPixel.tagContent.aDynTags.content_category.value,
                        content_ids: btPixel.tagContent.aDynTags.content_ids.value,
                        content_type: btPixel.tagContent.aDynTags.content_type.value,
                    },
                        { 'eventID': btPixel.eventId });
                } else {
                    fbq("trackCustom", "ViewContentHomepage", {
                        content_name: btPixel.tagContent.aDynTags.content_name.value,
                        content_category: btPixel.tagContent.aDynTags.content_category.value,
                        content_type: btPixel.tagContent.aDynTags.content_type.value,
                    },
                        { 'eventID': btPixel.eventId });
                }
            }

            if (btPixel.tagContent.aTrackingType.value == "AddToCart") {
                fbq("track", "AddToCart", {
                    content_ids: btPixel.tagContent.aDynTags.content_ids.value,
                    content_type: btPixel.tagContent.aDynTags.content_type.value,
                    value: btPixel.tagContent.aDynTags.value.value,
                    currency: btPixel.tagContent.aDynTags.currency.value,
                },
                    { 'eventID': btPixel.eventId });
            }

            // Handle Contact event
            if (btPixel.tagContent.aTrackingType.value == "Contact") {
                fbq("track", "Contact", {}, { 'eventID': btPixel.eventId });
            }

            if (btPixel.tagContent.aTrackingType.value == "Purchase") {
                fbq("track", "Purchase", {
                    content_ids: btPixel.tagContent.aDynTags.content_ids.value,
                    content_type: btPixel.tagContent.aDynTags.content_type.value,
                    value: btPixel.tagContent.aDynTags.value.value,
                    currency: btPixel.tagContent.aDynTags.currency.value,
                },
                    { 'eventID': btPixel.eventId });
            }

            if (btPixel.tagContent.aTrackingType.value == "Search") {
                fbq("track", "Search", {
                    content_ids: btPixel.tagContent.aDynTags.content_ids.value,
                    content_type: btPixel.tagContent.aDynTags.content_type.value,
                    query: btPixel.tagContent.aDynTags.search_string.value,
                },
                    { 'eventID': btPixel.eventId });
            }

            $(btPixel.btnAddToWishlist).on("click", function () {
                fbq("track", "AddToWishlist", {
                    content_type: 'product',
                },
                    { 'eventID': btPixel.eventId });
            });

            $('.ps-shown-by-js').each(function (index) {
                $(this).on("click", function (event) {
                    fbq("track", "AddPaymentInfo", {}, { 'eventID': btPixel.eventId });

                    // Use Ajax call to send API event to make better TTFB
                    if (btPixel.useConversionApi == 1) {
                        $.ajax({
                            type: "POST",
                            url: btPixel.ajaxUrl,
                            dataType: "json",
                            data: {
                                ajax: 1,
                                action: "sendPaymentInfoToApi",
                                token: btPixel.token,
                                eventId: btPixel.eventId,
                            }
                        });
                    }
                })
            });

            if (btPixel.tagContent.aTrackingType.value == "InitiateCheckout") {
                fbq("track", "InitiateCheckout", { 'eventID': btPixel.eventId });
            }
        }

        if (typeof prestashop !== 'undefined') {
            prestashop.on(
                'updateCart',
                function (event) {
                    if (event && event.reason && event.reason.linkAction == "add-to-cart") {
                        //Check the variable for ipa and ip
                        var idProduct = 0;
                        var idProductAttribute = 0;

                        if (typeof event.reason.idProductAttribute !== "undefined") {
                            idProductAttribute = event.reason.idProductAttribute;
                        } else if (typeof event.resp.id_product_attribute !== "undefined") {
                            idProductAttribute = event.resp.id_product_attribute;
                        }

                        if (typeof event.reason.idProduct !== "undefined") {
                            idProduct = event.reason.idProduct;
                        } else if (typeof event.resp.id_product !== "undefined") {
                            idProduct = event.resp.id_product;
                        }

                        $.ajax({
                            type: "POST",
                            url: btPixel.ajaxUrl,
                            dataType: "json",
                            data: {
                                ajax: 1,
                                action: "addToCart",
                                id_product_attribute: idProductAttribute,
                                id_product: idProduct,
                                token: btPixel.token,
                            },
                            success: function (jsonData, textStatus, jqXHR) {
                                fbq('set', 'autoConfig', false, btPixel.pixel_id);
                                fbq("track", "AddToCart", {
                                    content_ids: jsonData.content_ids,
                                    content_type: 'product',
                                    value: jsonData.value,
                                    currency: jsonData.currency,
                                },
                                    { 'eventID': btPixel.eventId });
                            },
                        });
                    }
                }
            );

            // Handle case for combination update
            prestashop.on(
                'updatedProduct',
                function (event) {
                    if (event) {
                        $.ajax({
                            type: "POST",
                            url: btPixel.ajaxUrl,
                            dataType: "json",
                            data: {
                                ajax: 1,
                                action: "updateCombination",
                                id_product_attribute: event.id_product_attribute,
                                id_product: $('input[name="id_product"').val(),
                                token: btPixel.token,
                            },
                            success: function (jsonData, textStatus, jqXHR) {
                                fbq('set', 'autoConfig', false, btPixel.pixel_id);
                                fbq("track", "ViewContent", {
                                    content_name: jsonData.content_name,
                                    content_category: jsonData.content_category,
                                    content_ids: jsonData.content_id,
                                    content_type: 'product',
                                    value: jsonData.value,
                                    currency: jsonData.currency,
                                });
                            },
                        });
                    }
                }
            );
        }
    }
});
