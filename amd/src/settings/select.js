// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

import Setting from "mod_mootimeter/settings/setting";
import * as Util from "mod_mootimeter/util";

/**
 * Class for select setting.
 *
 * @module     mod_mootimeter/settings/select
 * @copyright  2023 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
export default class Select extends Setting {

    input;

    async renderSetting() {
        const options = [];
        for (const key in this.config.options) {
            options.push({
                value: key,
                label: this.config.options[key],
                selected: key + '' === this.value + ''
            });
        }

        const context = {
            elementname: this.config.elementname,
            label: this.config.label,
            help: this.config.help,
            value: this.config.value,
            options: options
        };

        const node = await Util.renderTemplate('mod_mootimeter/settings/select', context);
        this.input = node.querySelector('select');
        return node;
    }

    getValue() {
        return this.input.value;
    }

    async setValue(value) {
        this.input.value = value;
    }

}