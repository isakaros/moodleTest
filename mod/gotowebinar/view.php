<?php


/**
 * GoToWebinar module view file
 *
 * @package mod_gotowebinar
 * @copyright 2017 Alok Kumar Rai <alokr.mail@gmail.com,alokkumarrai@outlook.in>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require('../../config.php');
require_once($CFG->dirroot . '/mod/gotowebinar/locallib.php');
require_once($CFG->dirroot . '/mod/gotowebinar/lib/OSD.php');
require_once($CFG->libdir . '/completionlib.php');
global $DB, $USER;
$id = required_param('id', PARAM_INT); // Course Module ID

if ($id) {
    if (!$cm = get_coursemodule_from_id('gotowebinar', $id)) {
        print_error('invalidcoursemodule');
    }
    $gotowebinar = $DB->get_record('gotowebinar', array('id' => $cm->instance), '*', MUST_EXIST);
}
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
$meeturl = '';
$gototrainingdownloads = array();
$gotomeeting = get_gotowebinar($gotowebinar);
$meeturl = $gotomeeting;

$meetinginfo = json_decode($gotowebinar->meetinfo);
require_course_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability('mod/gotowebinar:view', $context);


$PAGE->set_url('/mod/gotowebinar/view.php', array('id' => $cm->id));
$PAGE->set_title($course->shortname . ': ' . $gotowebinar->name);
$PAGE->set_heading($course->fullname);
$PAGE->set_activity_record($gotowebinar);

$completion = new completion_info($course);
$completion->set_module_viewed($cm);
echo $OUTPUT->header();
echo $OUTPUT->heading('Course:  ' . $course->fullname);
$table = new html_table();
$table->head = array('GoToWebinar');
$table->headspan = array(2);
$table->size = array('30%', '70%');

$cell1 = new html_table_cell("Meeting Title");
$cell1->colspan = 1;
$cell1->style = 'text-align:left;';

$cell2 = new html_table_cell("<b>" . $gotowebinar->name . "</b>");
$cell2->colspan = 1;
$cell2->style = 'text-align:left;';
$table->data[] = array($cell1, $cell2);

$cell1 = new html_table_cell("Meeting Description");
$cell1->colspan = 1;
$cell1->style = 'text-align:left;';

$cell2 = new html_table_cell("<b>" . strip_tags($gotowebinar->intro) . "</b>");
$cell2->colspan = 1;
$cell2->style = 'text-align:left;';

$table->data[] = array($cell1, $cell2);


$cell1 = new html_table_cell("Meeting start date and time");
$cell1->colspan = 1;
$cell1->style = 'text-align:left;';

$cell2 = new html_table_cell("<b>" . userdate($gotowebinar->startdatetime) . "</b>");
$cell2->colspan = 1;
$cell2->style = 'text-align:left;';

$table->data[] = array($cell1, $cell2);


$cell1 = new html_table_cell("Meeting end date and time");
$cell1->colspan = 1;
$cell1->style = 'text-align:left;';

$cell2 = new html_table_cell("<b>" . userdate($gotowebinar->enddatetime) . "</b>");
$cell2->colspan = 1;
$cell2->style = 'text-align:left;';

$table->data[] = array($cell1, $cell2);

$cell2 = new html_table_cell(html_writer::link(trim($meeturl, '"'), 'Join Meeting', array("target" => "_blank", 'class' => 'btn btn-primary')));
$cell2->colspan = 2;
$cell2->style = 'text-align:center;';

$table->data[] = array($cell2);

foreach ($gototrainingdownloads as $gototrainingdownload) {
    $cell1 = new html_table_cell("Meeting Recording");
    $cell1->colspan = 1;
    $cell1->style = 'text-align:left;';
    $downloadlink = html_writer::link($gototrainingdownload->downloadUrl, 'Download Link ');
    $cell2 = new html_table_cell("<b>$downloadlink</b>");
    $cell2->colspan = 1;
    $cell2->style = 'text-align:left;';
    $table->data[] = array($cell1, $cell2);
}


echo html_writer::table($table);



echo $OUTPUT->footer();
