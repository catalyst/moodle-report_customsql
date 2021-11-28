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


namespace report_customsql\external;

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot . '/webservice/tests/helpers.php');


/**
 * Tests for the get_query_result web service.
 *
 * @package   report_customsql
 * @category  external
 * @author    Jwalit Shah <jwalitshah@catalyst-au.net>
 * @copyright 2021 Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class get_query_result_testcase extends \externallib_advanced_testcase {

    /**
     * Function to build some reports to test external api against.
     *
     * @return void
     */
    private function setup_test_reports() {
        global $DB;

        // Add a manual report.
        $report = new stdClass();
        $report->displayname = 'test report name';
        $report->description = 'test report description';
        $report->querysql = 'SELECT * FROM prefix_user WHERE username = :username';
        $report->queryparams = 'a:1:{s:8:"username";s:14:"Enter username";}';
        $report->capability = 'report/customsql:view';
        $report->runable = 'manual';
        $report->categoryid = 1;

        $DB->insert_record('report_customsql_queries', $report);

        // Add a daily scheduled report.
        $timenow = time();
        $dateparts = getdate($timenow);
        $currenthour = $dateparts['hours'];
        $today = mktime($currenthour, 0, 0, $dateparts['mon'], $dateparts['mday'], $dateparts['year']);

        $report = new stdClass();
        $report->displayname = 'test scheduled report';
        $report->description = 'test scheduled description';
        $report->querysql = 'SELECT * FROM prefix_user WHERE username = :username';
        $report->queryparams = 'a:1:{s:8:"username";s:14:"Enter username";}';
        $report->capability = 'report/customsql:view';
        $report->runable = 'daily';
        $report->at = $currenthour;
        $report->lastrun = $today;
        $report->lastexecutiontime = 7;
        $report->categoryid = 1;

        $DB->insert_record('report_customsql_queries', $report);

    }
}
