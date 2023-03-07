<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin administration pages are defined here.
 *
 * @package     local_learningdashboard
 * @category    admin
 * @copyright   2023 Your Name <you@example.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    
    $settings = new admin_settingpage('local_learningdashboard', new lang_string('learningdashboardssettings', 'local_learningdashboard'));
    $ADMIN->add('localplugins', $settings);

    $settings->add(new admin_setting_configtext('local_learningdashboard/mandatorycategory',
        new lang_string('mandatorycategory', 'local_learningdashboard'),
        new lang_string('mandatorycategory_help', 'local_learningdashboard'), '', PARAM_TEXT));

    $choices = array('email'=>'Email','idnumber'=>'Employee ID');
    $settings->add(new admin_setting_configselect('local_learningdashboard/matchuser',
            new lang_string('matchuser', 'local_learningdashboard'),
            new lang_string('matchuser_help', 'local_learningdashboard'), 'Email', $choices));

}
