import { call as fetchMany } from 'core/ajax';

export const init = (uniqueID) => {
    var obj = document.getElementById(uniqueID);

    if (!document.getElementById(uniqueID)) {
        return;
    }

    obj.addEventListener("click", store);

    /**
     * Create new page.
     */
    function store() {

        var tool = this.dataset.name;
        var instance = this.dataset.instance;

        storeNewPage(tool, instance);
    }
};

/**
 * Call to create a new instance
 * @param {string} tool
 * @param {int} instance
 * @returns
 */
const createNewPage = (
    tool,
    instance,
) => fetchMany([{
    methodname: 'mod_mootimeter_add_new_page',
    args: {
        tool,
        instance,
    },
}])[0];

/**
 * Executes the call to create a new page.
 * @param {string} tool
 * @param {int} instance
 */
const storeNewPage = async (tool, instance) => {
    const response = await createNewPage(tool, instance);
    window.location.href = window.location.origin
        + window.location.pathname + "?id=" + response.cmid + "&pageid=" + response.pageid;
};