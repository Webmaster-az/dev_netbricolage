/*
 * (C) 2023 Motive Commerce Search Corp S.L. <info@motive.co>
 *
 * This file is part of Motive Commerce Search.
 *
 * This file is licensed to you under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @author Motive (motive.co)
 * @copyright (C) 2023 Motive Commerce Search Corp S.L. <info@motive.co>
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

const motTcId = (new URL(location.href)).searchParams.get('mot_tcid');
if (motTcId) {
    history.pushState = function(state, unused, url) {
        url = new URL(url);
        url.searchParams.set('mot_tcid', motTcId);
        Object.getPrototypeOf(history).pushState.call(history, state, unused, url.href);
    }
}

window.initX = function initX() {
    MotiveDisableFormSubmit(motive.initParams.triggerSelector)

    if (typeof ajaxCart === 'undefined') {
        $.ajax({
            type: 'GET',
            headers: {"cache-control": "no-cache"},
            url: baseUri,
            cache: false,
            dataType: 'json',
            data: {
                rand: Date.now(),
                controller: 'cart',
                ajax: 'true',
                token: static_token,
            },
            success: jsonData => (motive.cartInfo = jsonData),
        })
    }

    motive.initParams.callbacks = {
        UserClickedResultAddToCart: MotiveAddToCart,
        CartHandlerGettingCartInfo() {
            return {
                productsCount: typeof ajaxCart === 'undefined' ? motive.cartInfo.nbTotalProducts : ajaxCart.nb_total_products
            };
        }
    }

    if (motive.options.showPrices) {
        if (motive.options.shopperPrices) {
            motive.initParams.callbacks.AppendedResultsChanged = MotiveShopperPriceGetter;
            motive.initParams.callbacks.RecommendationsChanged = MotiveShopperPriceGetter;
            motive.initParams.transformPriceRange = (min, max) => [
                min * motive.options.priceRates.min,
                max * motive.options.priceRates.max
            ];
        } else {
            motive.initParams.callbacks.AppendedResultsChanged = MotiveCurrencyResultTransform;
            motive.initParams.callbacks.RecommendationsChanged = MotiveCurrencyResultTransform;
            motive.initParams.transformPriceRange = (min, max) => [
                min * motive.options.priceRates.static,
                max * motive.options.priceRates.static
            ];
        }

        const shopperPriceUrl = new URL(motive.endpoint);
        shopperPriceUrl.searchParams.append('action', 'shopperPrices');
        motive.url = {
            shopperPrices: shopperPriceUrl
        }
    }

    return motive.initParams;
}

function MotiveShopperPriceGetter(data) {
    data = data.map(p => p.variants ? {id: p.id, variants: p.variants.map(v => ({id: v.id}))} : {id: p.id});
    const options = {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'Content-Type': 'application/json' }
    }
    return fetch(motive.url.shopperPrices, options).then(r => r.json());
}

function MotivePriceMul(obj) {
    if (obj.price) {
        const rate = motive.options.priceRates.static;
        obj.price.originalValue *= rate;
        obj.price.value *= rate;
    }
}

function MotiveCurrencyResultTransform(results) {
    results.forEach(result => {
        MotivePriceMul(result);
        if (result.variants) {
            result.variants.forEach(MotivePriceMul)
        }
    });
    return results;
}

function MotiveDisableFormSubmit(selector) {
    Array.from(document.querySelectorAll(selector))
        .map(el => el.closest('form') || el.querySelector('form'))
        .filter((el, i, arr) => el && arr.indexOf(el) === i)
        .forEach(form => form.onsubmit = function () { return false });
}

function MotiveAddToCart(result) {
    const [idProduct, idProductAttribute] = result.id.split('-');
    const qty = result.availability?.minimal_quantity || 1;

    const url = new URL(baseUri, location);
    url.searchParams.set('mot_tcid', result.tagging.add2cart.params.clickId);
    url.searchParams.set('rand', Date.now());

    return new Promise(function (resolve, reject) {
        $.ajax({
            type: 'POST',
            headers: {"cache-control": "no-cache"},
            url: url.href,
            cache: false,
            dataType: 'json',
            data: {
                controller: 'cart',
                add: 1,
                ajax: true,
                qty,
                id_product: idProduct,
                token: static_token,
                ipa: idProductAttribute,
                id_customization: undefined
            },
            success: function (jsonData) {
                if (typeof ajaxCart !== 'undefined') {
                    ajaxCart.updateCart(jsonData);
                } else {
                    motive.cartInfo = jsonData;
                }
                if (jsonData.hasError) {
                    reject();
                } else {
                    resolve();
                }
            },
            error: function () {
                reject();
            }
        })
    })
}

{
    const s = document.createElement('script');
    s.setAttribute('src', motive.motive_x_url);
    s.setAttribute('type','module');
    document.head.appendChild(s);
};
