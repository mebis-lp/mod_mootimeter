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
 * Web service to store a answer by the student.
 *
 * @package     mootimetertool_quiz
 * @copyright   2023, ISB Bayern
 * @author      Peter Mayer <peter.mayer@isb.bayern.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mootimetertool_quiz\external;

use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_value;
use dml_exception;
use core_external\external_single_structure;
use invalid_parameter_exception;

/**
 * Web service to store an option.
 *
 * @package     mootimetertool_quiz
 */
class store_answeroption_text extends external_api {
    /**
     * Describes the parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters([
            'pageid' => new external_value(PARAM_INT, 'The page id to obtain results for.', VALUE_REQUIRED),
            'inputname' => new external_value(PARAM_TEXT, 'The name of the input to store.', VALUE_REQUIRED),
            'inputvalue' => new external_value(PARAM_TEXT, 'The value of the input to store.', VALUE_REQUIRED),
            'thisDataset' => new external_value(PARAM_RAW, 'The value of the input to store.', VALUE_REQUIRED),
        ]);
    }

    /**
     * Execute the service.
     * @param int $pageid
     * @param string $inputname
     * @param string $inputvalue
     * @param string $datasetjson
     * @return array
     * @throws invalid_parameter_exception
     * @throws dml_exception
     */
    public static function execute(int $pageid, string $inputname, string $inputvalue, string $datasetjson = ""): array {
        global $DB;

        [
            'pageid' => $pageid,
            'inputname' => $inputname,
            'inputvalue' => $inputvalue,
            'thisDataset' => $datasetjson,
        ] = self::validate_parameters(self::execute_parameters(), [
            'pageid' => $pageid,
            'inputname' => $inputname,
            'inputvalue' => $inputvalue,
            'thisDataset' => $datasetjson,
        ]);

        try {
            $helper = new \mod_mootimeter\helper();
            $page = $helper->get_page($pageid);
            $classname = "\mootimetertool_" . $page->tool . "\\" . $page->tool;

            if (!class_exists($classname)) {
                return ['pagecontent' => ['error' => "Class '" . $page->tool . "' is missing in tool " . $page->tool]];
            }

            $toolhelper = new $classname();
            if (!method_exists($toolhelper, 'store_answer_option')) {
                return ['pagecontent' => [
                    'error' => "Method 'store_answer_option' is missing in tool helper class " . $page->tool,
                ]];
            }

            $dataset = json_decode($datasetjson);
            $record = $toolhelper->get_answer_option(['id' => $dataset->aoid, 'pageid' => $pageid]);
            $record->optiontext = $inputvalue;
            $toolhelper->store_answer_option($record);

            $return = ['code' => 200, 'string' => 'ok'];
        } catch (\Exception $e) {

            $return = ['code' => 500, 'string' => $e->getMessage()];
        }
        return $return;
    }

    /**
     * Describes the return structure of the service..
     *
     * @return external_single_structure
     */
    public static function execute_returns() {
        return new external_single_structure(
            [
                'code' => new external_value(PARAM_INT, 'Return code of storage process.'),
                'string' => new external_value(PARAM_TEXT, 'Return string of storage process.'),
            ],
            'Response of storing the answer option details'
        );
    }
}
