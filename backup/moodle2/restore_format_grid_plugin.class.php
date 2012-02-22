<?php

/**
 * @copyright (C) 2012 Pavel Evgenjevich Timoshenko
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package grid
 * @category course format
 */

defined('MOODLE_INTERNAL') || die();

/**
 * restore plugin class that provides the necessary information
 * needed to restore one grid course format plugin
 */
class restore_format_grid_plugin extends restore_format_plugin {
    /**
     * Returns the paths to be handled by the plugin at section level
     */
    protected function define_section_plugin_structure() {

        $paths = array();

        // Add own format stuff
        $elename = 'grid';
        $elepath = $this->get_pathfor('/grid');
        $paths[] = new restore_path_element($elename, $elepath);

        return $paths; // And we return the interesting paths
    }

    /**
     * Process the format/week element
     */
    public function process_topic($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        // We only process this information if the course we are restoring to
        // has 'slides' format (target format can change depending of restore options)
        $format = $DB->get_field('course', 'format', array('id' => $this->task->get_courseid()));
        if ($format != 'grid') {
            return;
        }

        $data->course_id = $this->task->get_courseid();
        $data->topic_id = $this->task->get_sectionid();

        // Note: This breaks self-containing, because perhaps the section hasn't been restored yet!!
        // Note: Although if the format always group with "previous" (already restored) it will work
        // Note: If so, you've been really lucky! :-)
        // $data->groupwithsectionid = $this->get_mappingid('course_section', $data->groupwithsectionid);

        $DB->insert_record('format_slides', $data);

        // No need to annotate anything here
    }

    /**
     * Returns the paths to be handled by the plugin at module level
     */
    protected function define_module_plugin_structure() {

        $paths = array();

        // Add own format stuff
        $elename = 'modicons';
        $elepath = $this->get_pathfor('/modicons'); // we used get_recommended_name() so this works
        $paths[] = new restore_path_element($elename, $elepath);

        return $paths; // And we return the interesting paths
    }

    /**
     * Process the format/modicons element
     */
    public function process_modicons($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        // $oldActivityId = $data->activity_id;

        // We only process this information if the course we are restoring to
        // has 'slides' format (target format can change depending of restore options)
        $format = $DB->get_field('course', 'format', array('id' => $this->task->get_courseid()));
        if ($format != 'slides') {
            return;
        }

        $data->course_id = $this->task->get_courseid();
        $data->activity_id = $this->task->get_moduleid();

        $newid = $DB->insert_record('format_slides_modicons', $data);
        $this->set_mapping('modicons', $oldid, $newid);
        
        /*        
        $restoreid =  $this->task->get_restoreid();
         $record = array(
            'backupid' => $restoreid
         );
         
         $dbrec = $DB->get_record('backup_ids_temp', $record);
         */
        
        // No need to annotate anything here
    }
    
    protected function after_execute_module() {
        // Add slides course format custom activity/module icons
         //$this->add_related_files('format_slides', 'activity_icon');
    }
    
    // protected function after_execute_section() { }
    
}
