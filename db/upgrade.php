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
 * Keeps track of upgrades to the subcourse module
 *
 * @package     mod_subcourse
 * @category    upgrade
 * @copyright   2008 David Mudrak <david@moodle.com>
 * @copyright   2014 Vadim Dvorovenko <Vadimon@mail.ru>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Performs upgrade of the database structure and data
 *
 * @param int $oldversion the version we are upgrading from
 * @return bool true
 */
function xmldb_subcourse_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2013102501) {
        // drop the 'grade' field from the 'subcourse' table

        $table = new xmldb_table('subcourse');
        $field = new xmldb_field('grade');
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        upgrade_mod_savepoint(true, 2013102501, 'subcourse');
    }

    if ($oldversion < 2014053101) {
        // Define field instantredirect to be added to subcourse
        $table = new xmldb_table('subcourse');
        $field = new xmldb_field('instantredirect', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'refcourse');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        upgrade_mod_savepoint(true, 2014053101, 'subcourse');
    }

    return true;
}
