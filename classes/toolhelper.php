<?php
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

/**
 * The toolhelper methods must be implemented of each tool.
 *
 * @package     mod_mootimeter
 * @category    string
 * @copyright   2023, ISB Bayern
 * @author      Peter Mayer <peter.mayer@isb.bayern.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_mootimeter;

use coding_exception;
use cache_exception;
use dml_exception;

/**
 * The toolhelper methods must be implemented of each tool.
 *
 * @package     mod_mootimeter
 * @category    string
 * @copyright   2023, ISB Bayern
 * @author      Peter Mayer <peter.mayer@isb.bayern.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class toolhelper extends \mod_mootimeter\helper {

    /**
     * Get the tools answer column.
     * @return string
     */
    abstract public function get_answer_column();

    /**
     * Get the tools answer table.
     * @return string
     */
    abstract public function get_answer_table();

    /**
     * Get the userid column name in the answer table of the tool.
     * @return ?string the column name where the user id is stored in the answer table, null if no user id is stored
     */
    public function get_answer_userid_column(): ?string {
        return null;
    }

    /**
     * Insert the answer.
     *
     * @param object $page
     * @param mixed $answer
     * @return void
     */
    abstract public function insert_answer(object $page, $answer);

    /**
     * Get the timestamp of the last answer.
     *
     * @param int|object $pageorid
     * @return int
     */
    abstract public function get_last_update_time(int|object $pageorid);

    /**
     * Delete Page
     *
     * @param object $page
     * @return bool
     */
    abstract public function delete_page_tool(object $page);

    /**
     * Get all parameters that are necessary for rendering the tools view.
     *
     * @param object $page
     * @return array
     */
    abstract public function get_renderer_params(object $page);

    /**
     * Get the settings column.
     *
     * @param object $page
     * @param array $defaultparams
     * @return mixed
     */
    abstract public function get_col_settings_tool_params(object $page, array $defaultparams);

    /**
     * Will be executed after the page is created.
     * @param object $page
     * @return void
     */
    abstract public function hook_after_new_page_created(object $page);

    /**
     * Handels inplace_edit.
     * @param string $itemtype
     * @param string $itemid
     * @param mixed $newvalue
     * @return mixed
     */
    abstract public function handle_inplace_edit(string $itemtype, string $itemid, mixed $newvalue);

    /**
     * Get the rendered answer overview view.
     *
     * @param object $cm
     * @param object $page
     * @return string
     */
    abstract public function get_answer_overview(object $cm, object $page): string;

    /**
     * Checks if a select option is selected.
     *
     * @param int $optionid
     * @param object $config
     * @param string $attribute
     * @return bool
     */
    public function is_option_selected(int $optionid, object $config, string $attribute): bool {

        if (empty($config->{$attribute})) {
            return false;
        }

        if ($config->{$attribute} == $optionid) {
            return true;
        }

        return false;
    }

}
