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

function get_user_info() {
  global $USER, $CFG;

  $returneddata[] = array(
    'userid'          => $USER->id,
    'firstname'       => $USER->firstname,
    'fullname'        => $USER->firstname.' '.$USER->lastname,
    'email'           => $USER->email,
    'idnumber'        => $USER->idnumber,
    'wwwroot'         => $CFG->wwwroot
  );

  return $returneddata;
}

function get_mand_data() {
  global $USER, $DB;
    // // Connect to the database
    // $db = new mysqli("localhost", "username", "password", "database_name");

    // // Query the database to get the list of completed training courses
    $config = get_config('local_learningdashboard');
    $catid = $config->mandatorycategory;
    $sql = "SELECT c.id, c.fullname, c.shortname, c.summary from {course} AS c JOIN {course_categories} AS cats ON c.category = cats.id
        WHERE c.category = cats.id
        AND (
          cats.path LIKE '%/".$catid."/%'
          OR cats.path LIKE '%/".$catid."'
        ) ";
    $courses = $DB->get_records_sql($sql);
    $match = $config->matchuser;
    if ($match = 'email') {
      $where = $USER->email;
    } else if ($match = 'idnumber') {
      $where = $USER->idnumber;
    }

    $data = array();
    foreach ($courses as $course) {
        $record = $DB->get_record_sql("
          SELECT * 
          FROM {local_stafftrain} 
          WHERE ".$match." = '".$where."'
          AND coursetitle = :name
          AND id 
            IN (
              select MAX(id) 
              FROM {local_stafftrain}

              GROUP BY email, coursetitle)"
          ,array('name'=>$course->shortname));
      

      $data[] = array(
          'course'          => $course->fullname,
          'id'              => $course->id,
          'date_completed'  => gmdate("Y-m-d", $record->enddate),
          'expiration_date' => gmdate("Y-m-d", $record->renewaldate),
          'grade'           => $record->eventscore
      );
    };

    // Sort the array of courses by expiry date
    usort($data, function($a, $b) {
        return $a['expiration_date'] <=> $b['expiration_date'];
    });
    
    // Loop through the data and add each row to the HTML variable
    foreach ($data as $row) {
      $course = $row['course'];
      $date_completed = date("F d, Y", strtotime($row['date_completed']));
      $expiration_date = date("F d, Y", strtotime($row['expiration_date']));
      $grade = $row['grade'];

      $courseid = $DB->get_field('course', 'id', array('fullname'=>$course));
      $coursesummary = $DB->get_field('course', 'summary', array('fullname'=>$course));
      // Calculate the number of days left until the certification expires
      $today = time();
      $expiration_timestamp = strtotime($row['expiration_date']);
      $completion_timestamp = strtotime($row['date_completed']);
      $total_days = ceil(($expiration_timestamp - $completion_timestamp) / 86400);
      $days_left = ceil(($expiration_timestamp - $today) / 86400);

      // Calculate the percentage of the countdown progress bar
      $percentage = round(($days_left / $total_days) * 100);
      if ($percentage < 0) {
        $percentage = 0;
      }
      if (empty($completion_timestamp)) {
        $completed = false;
      } else {
        $completed = true;
      }

      if ($days_left > 0) {
        $expired = false;
      } else {
        $expired = true;
      }

      if ($days_left > 0 && $days_left < 45) {
        $expiringsoon = true;
      } else {
        $expiringsoon = false;
      }

      $returneddata[] = array(
        'course'          => $course,
        'courseid'        => $courseid,
        'coursesummary'   => $coursesummary,
        'date_completed'  => $date_completed,
        'expiration_date' => $expiration_date,
        'grade'           => $grade,
        'days_left'       => $days_left,
        'percentage'      => $percentage,
        'total_days'      => $total_days,
        'completed'       => $completed,
        'expired'         => $expired,
        'expiringsoon'    => $expiringsoon
      );
      
    }

    return $returneddata;
}


function get_meta_data() {
  global $USER, $DB;
  
  // DB lookup the other data - we'll just define an array for now to avoid errors
  $data = array(
    array(
      'course' => 'JavaScript Fundamentals',
      'link'   => 'https://something.com'
    ),
    array(
      'course' => 'PHP Fundamentals',
      'link'   => 'https://something.com'
    ),
    array(
      'course' => 'MySQL Fundamentals',
      'link'   => 'https://something.com'
    )
  );

  foreach ($data as $row) {

    // Handle the data and define the array values here

    // Build the array values
    $returneddata[] = array(
      'course'            => $row['course'],
      'link'              => $row['link']
      // 'coursesummary'   => '',
      // 'date_completed'  => '',
      // 'expiration_date' => '',
      // 'grade'           => '',
      // 'days_left'       => '',
      // 'percentage'      => '',
      // 'total_days'      => '',
      // 'completed'       => '',
      // 'expired'         => '',
      // 'expiringsoon'    => ''
    );
  }
  
  return $returneddata;
}

function get_other_data() {
  global $USER, $DB;

  // DB lookup the other data - we'll just define an array for now to avoid errors
  $data = array(
    array(
      'course' => 'JavaScript Fundamentals',
      'link'   => '12'
    ),
    array(
      'course' => 'PHP Fundamentals',
      'link'   => '22'
    ),
    array(
      'course' => 'MySQL Fundamentals',
      'link'   => '32'
    )
  );


  foreach ($data as $row) {

    // Handle the data and define the array values here

    // Build the array values
    $returneddata[] = array(
      'course'  => $row['course'],
      'link'        => $row['link']
    );
  }

  return $returneddata;
}