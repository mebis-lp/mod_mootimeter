import {call as fetchMany} from 'core/ajax';
import Log from 'core/log';
import {exception as displayException} from 'core/notification';
import Templates from 'core/templates';
import {execReloadPagelist as reloadPagelist} from 'mod_mootimeter/reload_pagelist';

export const init = (uniqueID) => {

    const obj = document.getElementById(uniqueID);
    if (!obj) {
        return;
    }
    obj.addEventListener("click", changePage);

    /**
     * Store the value.
     */
    function changePage() {
        var pageid = this.dataset.pageid;
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const cmid = urlParams.get('id');
        execReloadPage(pageid, cmid);
    }
};

/**
 * Call to store input value
 * @param {int} pageid
 * @param {int} cmid
 * @returns {array}
 */
const reloadPage = (
    pageid,
    cmid
) => fetchMany([{
    methodname: 'mod_mootimeter_get_pagecontentparams',
    args: {
        pageid,
        cmid
    },
}])[0];

/**
 * Executes the call to store input value.
 * @param {int} pageid
 * @param {int} cmid
 */
const execReloadPage = async(pageid, cmid) => {
    const response = await reloadPage(pageid, cmid);

    if (response.code != 200) {
        Log.error(response.string);
    }

    if (response.code == 200) {

        var mtmstate = document.getElementById('mootimeterstate');

        const pageparmas = JSON.parse(response.pageparams);

        // Set new pageid.
        mtmstate.setAttribute('data-pageid', pageparmas.pageid);

        // Replace the pagecontent.
        Templates.renderForPromise(pageparmas.pagecontent.template, pageparmas.pagecontent)
            .then(({html, js}) => {
                Templates.replaceNodeContents('#mootimeter-pagecontent', html, js);
                return true;
            })
            .catch((error) => displayException(error));

        // Replace the pagecontent menu.
        Templates.renderForPromise(pageparmas.contentmenu.template, pageparmas.contentmenu)
            .then(({html, js}) => {
                Templates.replaceNode('#mootimeter-pagecontentmenu', html, js);
                return true;
            })
            .catch((error) => displayException(error));

        // Replace the settings col if necessary.
        Templates.renderForPromise(pageparmas.colsettings.template, pageparmas.colsettings)
            .then(({html, js}) => {
                Templates.replaceNodeContents('#mootimeter-col-settings', html, js);
                return true;
            })
            .catch((error) => displayException(error));

        // Set URL parameter.
        setGetParam('pageid', pageparmas.pageid);

        // Set active page marked in pageslist.
        reloadPagelist(pageid, cmid, true);

        // Remove all tooltips of pageslist that are still present.
        document.querySelectorAll('.tooltip').forEach(e => e.remove());
    }
};

/**
 * Set the Query Parameter.
 * @param {string} key
 * @param {string} value
 */
function setGetParam(key, value) {
    if (history.pushState) {
        var params = new URLSearchParams(window.location.search);
        params.set(key, value);
        var newUrl = window.location.origin
            + window.location.pathname
            + '?' + params.toString();
        window.history.pushState({path: newUrl}, '', newUrl);
    }
}