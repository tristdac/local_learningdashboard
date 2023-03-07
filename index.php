<?php

// defined('MOODLE_INTERNAL') || die;

require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');

require_login();

$PAGE->set_url(new moodle_url('/local/learningdashboard/index.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(format_string('My Learning Dashboard'));
// $PAGE->set_heading(format_string('Learning Dashboard'));

echo $OUTPUT->header();
// echo $OUTPUT->heading();

$content = new stdClass();
$content->user = array_values(get_user_info());
$content->mandatory = array_values(get_mand_data());
$content->meta = array_values(get_meta_data());
$content->other = array_values(get_other_data());

echo $OUTPUT->render_from_template('local_learningdashboard/template', $content);

echo $OUTPUT->footer();
