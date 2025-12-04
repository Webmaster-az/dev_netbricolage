





var cssNamespace = (cssNamespace == null)?{}:cssNamespace;
cssNamespace.tracking = (cssNamespace.tracking == null)?{}:cssNamespace.tracking;
cssNamespace.tracking.ON = "OFF";
cssNamespace.tracking.LANGUAGE = "";
cssNamespace.tracking.PAGENAME = "/de/privatkunde/login";
cssNamespace.tracking.CLIENTID = "node02k4f52kgmjjh1cmxwmfe5q1931352958-org.apache.sling";


<!-- tracking 2.0 Variables -->
window.digitalData = window.digitalData || {};
if(window.digitalData.page == null) {
    window.digitalData.page = {
        pageInfo: {},
        attributes: {}
    };
}

var splittedUrlByLang = "/de/privatkunde/login".split(/\/en\/|\/de\/|\/fr\/|\/it\//);
var relativeUrl = splittedUrlByLang.length > 1 && splittedUrlByLang[1] || "";
var languageWithCode = navigator.language || navigator.userLanguage;

window.digitalData.page.pageInfo = {
    ...window.digitalData.page.pageInfo,
    pageId: "224988ad-a4ee-4388-9ef4-8d5482f553fd",
    pageName: relativeUrl,
    language: languageWithCode && languageWithCode.substring(0, 2) || "",
    type: "mycss"
};

if(document.referrer.startsWith(location.origin)) {
    var splittedReferrerByUrl = document.referrer.split(/\/en\/|\/de\/|\/fr\/|\/it\//);
    var referredUrl = splittedReferrerByUrl.length > 1 && splittedReferrerByUrl[1] || "";
    window.digitalData.page.pageInfo.internalReferrer = referredUrl.split("?")[0].replace(".html", "");
    window.digitalData.page.pageInfo.externalReferrer = "";
} else {
    window.digitalData.page.pageInfo.internalReferrer = "";
    window.digitalData.page.pageInfo.externalReferrer = document.referrer;
}

var path = relativeUrl.split('/').filter(element => !!element);
var environment = "prd";
if(window.location.hostname.includes("my-")) {
    environment = window.location.hostname.substring(3, 6);
}

var queryMap = window.location.search.replace('?', '').split('&').reduce((map, item) => {
    var splitted = item.split('=');
    if(splitted.length > 1) {
        map[splitted[0]] = splitted[1];
    }
    return map;
}, {});

window.digitalData.page.attributes = {
    ...window.digitalData.page.attributes,
    URL: window.location.href,
    URLHostname: window.location.hostname,
    unifiedURL: "/de/" + relativeUrl,
    defaultURL: "/de/privatkunde/login",

    URLpath: relativeUrl,
    application: "mycss",
    URLQueryString: window.location.search,
    URLFragment: window.location.hash,

    environment: environment,
    trackingId: "" || queryMap["tid"] || "",
    campaignId: "" || queryMap["campaignid"] || queryMap["campaignId"] || "",
    websiteId: "" || queryMap["websiteid"] || queryMap["websiteId"] || "",
};

window.digitalData.page.category = {
    ...window.digitalData.page.category,
    level01: path.length > 0 && path[0] || "",
    level02: path.length > 1 && path[1] || "",
    level03: path.length > 2 && path[2] || "",
    level04: path.length > 3 && path[3] || "",
    level05: path.length > 4 && path[4] || "",
    level06: path.length > 5 && path[5] || "",
    breadCrumbs: path
}
