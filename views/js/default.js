$(document).ready(function () {
'use strict';

window.hostname = 'http://localhost/';
init_a_listeners();

// Uncomment the following line to enable first chance exceptions.
//Debug.enableFirstChanceException(true);

WinJS.Application.onmainwindowactivated = function (e) {
    if (e.detail.kind === Windows.ApplicationModel.Activation.ActivationKind.launch) {
        // TODO: startup code here
        //loadMainContent('search');//partials/search.html
        initGeolocation();
    }
}

function loadMainContent(httpLink) {
    mainContent.innerHTML = "Loading from " + httpLink + " ...";
    var httpLink = httpLink || '';
    // Load the html pages into view
    WinJS.xhr({ url: window.hostname + httpLink }).then(function (req) { //'ms-wwa://' + document.location.host + '/' + httpLink
        setInnerHTMLUnsafe(mainContent, req.response);
        $("#main").animate({ opacity: 1, left: '0px', leaveTransforms: true }, { queue: false, duration: 400, easing: 'easeInOutQuart' });
        init_a_listeners();
    }, function () {
        setInnerHTMLUnsafe(mainContent, 'Oh no! It failed...');
    });
}


/**
     * Initialize navigation menu
     * Animate the transition between pages
     */
function init_a_listeners() {
    $("#container nav ul a[rel], button[rel]").click(function (event) {
        var pageLink = $(this).attr("rel");
        $("#main").animate(window.slideAnimation, {
            queue: false, duration: 400, easing: 'easeInOutQuart', complete: function () { $("#main").css('left', '50px'); loadMainContent(pageLink); }
        });
    });
    $("a[href], button[rel]").click(function (event) {
        event.preventDefault();
        var pageLink = $(this).attr("href");
        $("#main").animate(window.slideAnimation, {
            queue: false, duration: 400, easing: 'easeInOutQuart', complete: function () { $("#main").css('left', '50px'); loadMainContent(pageLink); }
        });
    });
}


/**
     * Initialize the navigation menu
     * Animate the transition between pages
/*
    $('nav a').click(function (event) {
        event.preventDefault();
        var linkLocation = $(this).attr("rel");
        $("#main").animate(window.slideAnimation, {
            queue: false, duration: 400, easing: 'easeInOutQuart', complete: function () {
                loadMainContent(linkLocation);
            }
        });
    });*/
//appendDiv(document, data, "postTitle");

function id(elementId) {
    return document.getElementById(elementId);
}
function appendDiv(parent, html, className) {
    var div = document.createElement("div");
    div.innerHTML = html;
    div.className = className;
    parent.appendChild(div);
}
var nav = null;
function initGeolocation() {
    if (nav == null) {
        nav = window.navigator;
    }
    if (nav != null) {
        var geoloc = nav.geolocation;

        if (geoloc != null) {
            geoloc.getCurrentPosition(successCallback, errorCallback);
        }
        else {
            console.log("Geolocation not supported");
        }
    }
    else {
        console.log("Navigator not found");
    }
}

function errorCallback(error) { console.error("ERROR!"); }


function getPositionHandler(pos) {
        id('latitude_2').innerHTML = pos.coordinate.latitude + '!';
        id('longitude_2').innerHTML = pos.coordinate.longitude;
        id('accuracy_2').innerHTML = pos.civicAddress.country;
}

function initGeolocation() {
    var locator = new Windows.Devices.Geolocation.Geolocator();
    if (locator) {
        id('latitude_2').innerHTML = "Trying...";
        locator.getGeopositionAsync().then(function (e){ getPositionHandler(e); });
    } else {
        id('latitude_2').innerHTML = "Couldn't create Geolocator.";
    }
}



function openFileDialog() {
    var picker = new Windows.Storage.Pickers.FileOpenPicker();
    picker.viewMode = Windows.Storage.Pickers.PickerViewMode.thumbnail;
    // if you want to put the user at a specific folder initially
    picker.suggestedStartLocation = Windows.Storage.Pickers.PickerLocationId.picturesLibrary;
    picker.fileTypeFilter.replaceAll([".png", ".jpg", ".jpeg"]);

    openPicker.pickSingleFileAsync().then(function (file) {
        if (file) {
            // we have a file
        } else {
            // we do not
        }
    });
}


WinJS.Application.start();
});



//Add entry point to the Settings pane
(function () {

    var n = Windows.UI.ApplicationSettings;
    var settingsPane = n.SettingsPane.getForCurrentView();
    settingsPane.addEventListener('opening', onSettingsOpening);

    settingsPane.applicationCommands.append(new n.SettingsCommand(n.KnownSettingsCommand.preferences, onPreferences));

    function onSettingsOpening(e) {
        defaultPage.preferencesBack();
    }

    function onPreferences(sender) {
        var rect = Windows.UI.ViewManagement.VisualMetrics.suggestedFlyoutRect;

        var currentIntervalVal = Stocks.State.getValue(Stocks.Constants.updateIntervalKey);
        var updateIntervalSelectElem = document.getElementById('updateIntervalSelect');
        if (updateIntervalSelectElem && currentIntervalVal) {
            for (var i = 0, len = updateIntervalSelectElem.length; i < len; i++) {
                if (updateIntervalSelectElem[i] && updateIntervalSelectElem[i].value) {
                    var intervalVal = parseInt(updateIntervalSelectElem[i].value);
                    if (intervalVal && intervalVal == currentIntervalVal) {
                        updateIntervalSelectElem.selectedIndex = i;
                    }
                }
            }
        }

        if (!Stocks.State.getNetworkStatus()) {
            var tileConfigElem = document.getElementById('pref-tile-config');
            tileConfigElem.disabled = true;
            tileConfigElem.style.opacity = 0.4;
        }

        Stocks.Settings.initialize();

        var preferencesElem = document.getElementById('Windows.Settings.Preferences');
        preferencesElem.style.backgroundColor = 'black';
        preferencesElem.style.width = '350' + 'px';
        preferencesElem.style.position = 'absolute'
        preferencesElem.style.height = rect.height.toString() + 'px';
        preferencesElem.style.top = '0px';
        preferencesElem.style.right = '0px';
        preferencesElem.style.display = 'block';
        preferencesElem.style.zIndex = '1001';

    }

    function onPreferencesShow(sender, id) {
    }

    function onPrefernecesHide(sender, id) {
    }
})();