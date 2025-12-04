/*
* Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
* 
*    @author    Jpresta
*    @copyright Jpresta
*    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
*               is permitted for one Prestashop instance only but you can install it on your test instances.
*/

function pcGetParameterValue(e) {
    let t = "[\\?&]" + e + "=([^&#]*)";
    let n = new RegExp(t);
    let r = n.exec(window.location.href);
    if (r == null) return "";
    else return r[1]
}

function pcSplitUri(uri) {
    let splitRegExp = new RegExp('^' + '(?:' + '([^:/?#.]+)' + ':)?' + '(?://' + '(?:([^/?#]*)@)?' + '([\\w\\d\\-\\u0100-\\uffff.%]*)' + '(?:(:[0-9]+))?' + ')?' + '([^?#]+)?' + '(?:(\\?[^#]*))?' + '(?:(#.*))?' + '$');
    let split = uri.match(splitRegExp);
    for (let i = 1; i < 8; i++) {
        if (typeof split[i] === 'undefined') {
            split[i] = '';
        }
    }
    return {
        'scheme': split[1],
        'user_info': split[2],
        'domain': split[3],
        'port': split[4],
        'path': split[5],
        'query_data': split[6],
        'fragment': split[7]
    }
}

$(document).ready(function () {
    /* Refresh dynamic modules */
    try {
        if (typeof processDynamicModules === 'function' && !/bot|googlebot|crawler|spider|robot|crawling|gtmetrix|chrome-lighthouse/i.test(navigator.userAgent)) {
            let hooks = {};
            $('.dynhook').each(function(index, domhook){
                hooks['hk_' + index] = $(this).attr('id') + '|' + $(this).data('hooktype') + '|' + $(this).data('module') + '|' + $(this).data('hook') + '|' + $(this).data('hookargs');
            });
            let urlparts = pcSplitUri(document.URL);
            let url = urlparts['scheme'] + '://' + urlparts['domain'] + urlparts['port'] + urlparts['path'] + urlparts['query_data'];
            let indexEnd = url.indexOf('?');
            if (indexEnd >= 0 && indexEnd < url.length) {
                url += '&ajax=true&page_cache_dynamics_mods=1';
            }
            else {
                url += '?ajax=true&page_cache_dynamics_mods=1';
            }
            $.ajax({url: url, type: 'POST', data: hooks, dataType: 'json', cache: false,
                success: processDynamicModules,
                error: function(jqXHR, textStatus, errorThrown) {
                    try {
                        let indexStart = jqXHR.responseText.indexOf('{');
                        let responseFixed = jqXHR.responseText.substring(indexStart, jqXHR.responseText.length);
                        dyndatas = $.parseJSON(responseFixed);
                        if (dyndatas != null) {
                            processDynamicModules(dyndatas);
                            return;
                        }
                    }
                    catch(err) {
                        console.error("PageCache cannot parse data of error=" + err, err);
                    }
                    console.error("PageCache cannot display dynamic modules: error=" + textStatus + " exception=" + errorThrown);
                    console.log("PageCache dynamic module URL: " + url);
                }});
        }
    } catch (e) {
        console.error("PageCache cannot display dynamic modules: " + e.message, e);
    }

    /* Forward dbgpagecache parameter */
    try {
        if (typeof baseDir === 'undefined') {
            baseDir = prestashop.urls.base_url;
        }
        if (window.location.href.indexOf("dbgpagecache=") > 0) {
            $("a:not(.pagecache)").each(function () {
                let e = $(this).attr("href");
                let t = this.search;
                let n = "dbgpagecache=" + pcGetParameterValue("dbgpagecache");
                let r = baseDir.replace("https", "http");
                if (typeof e !== "undefined" && e.substr(0, 1) !== "#" && (e.replace("https", "http").substr(0, r.length) === r || e.indexOf('://') === -1) && e.indexOf('javascript:') === -1 && e.indexOf('mailto:') === -1 && e.indexOf('tel:') === -1 && e.indexOf('callto:') === -1) {
                    if (t.length === 0) this.search = n;
                    else this.search += "&" + n
                }
            })
        }
    } catch (e) {
        console.warn("Cannot forward dbgpagecache parameter on all links: " + e.message, e)
    }

});
