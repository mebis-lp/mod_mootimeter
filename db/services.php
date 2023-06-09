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
 * External service definitions for mod_mootimeter.
 *
 * @package     mod_mootimeter
 * @copyright   2023, ISB Bayern
 * @author      Peter Mayer <peter.mayer@isb.bayern.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$functions = [
    'mod_mootimeter_store_answer' => [
        'classname'     => 'mod_mootimeter\external\store_answer',
        'methodname'    => 'execute',
        'description'   => 'Store answer of mod_mootimeter.',
        'type'          => 'write',
        'ajax'          => true,
        'capabilities'  => 'mod/mootimeter:view',
    ],
    'mod_mootimeter_get_answers' => [
        'classname'     => 'mod_mootimeter\external\get_answers',
        'methodname'    => 'execute',
        'description'   => 'Store answer of mod_mootimeter.',
        'type'          => 'write',
        'ajax'          => true,
        'capabilities'  => 'mod/mootimeter:view',
    ],
    'mod_mootimeter_set_show_results_state' => [
        'classname'     => 'mod_mootimeter\external\set_show_results_state',
        'methodname'    => 'execute',
        'description'   => 'Set the state of show results..',
        'type'          => 'write',
        'ajax'          => true,
        'capabilities'  => 'mod/mootimeter:moderator',
    ],
];
