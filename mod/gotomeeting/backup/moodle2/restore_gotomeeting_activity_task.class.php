<?php


/**
 * GoToWebinar module view file
 *
 * @package mod_gotomeeting
 * @copyright 2017 Alok Kumar Rai <alokr.mail@gmail.com,alokkumarrai@outlook.in>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/gotomeeting/backup/moodle2/restore_gotomeeting_stepslib.php'); // Because it exists (must)

/**
 * survey restore task that provides all the settings and steps to perform one
 * complete restore of the activity
 */
class restore_gotomeeting_activity_task extends restore_activity_task {

    /**
     * Define (add) particular settings this activity can have
     */
    protected function define_my_settings() {
        // No particular settings for this activity
    }

    /**
     * Define (add) particular steps this activity can have
     */
    protected function define_my_steps() {
        // gotomeeting only has one structure step
        $this->add_step(new restore_gotomeeting_activity_structure_step('gotomeeting_structure', 'gotomeeting.xml'));
    }

    /**
     * Define the contents in the activity that must be
     * processed by the link decoder
     */
    static public function define_decode_contents() {
        $contents = array();

        $contents[] = new restore_decode_content('gotomeeting', array('intro'), 'gotomeeting');

        return $contents;
    }

    /**
     * Define the decoding rules for links belonging
     * to the activity to be executed by the link decoder
     */
    static public function define_decode_rules() {
        $rules = array();

        $rules[] = new restore_decode_rule('GOTOLMSVIEWBYID', '/mod/gotomeeting/view.php?id=$1', 'course_module');
        $rules[] = new restore_decode_rule('GOTOLMSINDEX', '/mod/gotomeeting/index.php?id=$1', 'course');

        return $rules;

    }

    /**
     * Define the restore log rules that will be applied
     * by the {@link restore_logs_processor} when restoring
     * survey logs. It must return one array
     * of {@link restore_log_rule} objects
     */
    static public function define_restore_log_rules() {
        $rules = array();

        $rules[] = new restore_log_rule('gotomeeting', 'add', 'view.php?id={course_module}', '{gotomeeting}');
        $rules[] = new restore_log_rule('gotomeeting', 'update', 'view.php?id={course_module}', '{gotomeeting}');
        $rules[] = new restore_log_rule('gotomeeting', 'view', 'view.php?id={course_module}', '{gotomeeting}');
        //$rules[] = new restore_log_rule('gotomeeting', 'download', 'download.php?id={course_module}&type=[type]&group=[group]', '{gotomeeting}');
        //$rules[] = new restore_log_rule('gotomeeting', 'view report', 'report.php?id={course_module}', '{gotomeeting}');
        //$rules[] = new restore_log_rule('gotomeeting', 'submit', 'view.php?id={course_module}', '{gotomeeting}');
        //$rules[] = new restore_log_rule('gotomeeting', 'view graph', 'view.php?id={course_module}', '{gotomeeting}');
        //$rules[] = new restore_log_rule('gotomeeting', 'view form', 'view.php?id={course_module}', '{gotomeeting}');

        return $rules;
    }

    /**
     * Define the restore log rules that will be applied
     * by the {@link restore_logs_processor} when restoring
     * course logs. It must return one array
     * of {@link restore_log_rule} objects
     *
     * Note this rules are applied when restoring course logs
     * by the restore final task, but are defined here at
     * activity level. All them are rules not linked to any module instance (cmid = 0)
     */
    static public function define_restore_log_rules_for_course() {
        $rules = array();

        $rules[] = new restore_log_rule('gotomeeting', 'view all', 'index.php?id={course}', null);

        return $rules;
    }
}