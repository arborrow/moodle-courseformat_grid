<?php

/**
 * @copyright (C) 2012 Pave Evgenjevich Timoshenko
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package grid
 * @category course format
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Provides the information to backup grid course format
 */
class backup_format_grid_plugin extends backup_format_plugin {
 /**
     * Returns the format information to attach to course element
     */
    protected function define_course_plugin_structure() {

        // Define the virtual plugin element with the condition to fulfill
        $plugin = $this->get_plugin_element(null, '/course/format', 'grid');

        // Create one standard named plugin element (the visible container)
        $pluginwrapper = new backup_nested_element($this->get_recommended_name());

        // connect the visible container ASAP
        $plugin->add_child($pluginwrapper);

        // don't need to annotate ids nor files

        return $plugin;
    }

    /**
     * Returns the format information to attach to section element
     */
    protected function define_section_plugin_structure() {

        // Define the virtual plugin element with the condition to fulfill
        $plugin = $this->get_plugin_element(null, $this->get_format_condition(), 'grid');

        // Create one standard named plugin element (the visible container)
        $pluginwrapper = new backup_nested_element($this->get_recommended_name());

        // connect the visible container ASAP
        $plugin->add_child($pluginwrapper);

        // Now create the format own structures
        $summary = new backup_nested_element('summary', 
            array('id', 'show_summary', 'course_id'), null);
        $icons = new backup_nested_element('icons');
        $icon = new backup_nested_element('icon', 
            array('id'), array('imagepath'));
        
        // Now the own format tree
        $pluginwrapper->add_child($summary);
        $summary->add_child($icons);
        $icons->add_child($icon);
        

        // set source to populate the data
        $summary->set_source_table('format_grid_summary', array(
            'id' => backup::VAR_ACTIVITYID,
            'course_id' => backup::VAR_PARENTID));

        $caption->set_source_sql('
            SELECT *
              FROM {format_grid_icon}
             WHERE sectionid = ? ',
            array(backup::VAR_PARENTID));
     
        return $plugin;
    }

    /**
     * Returns the format information to attach to module element
     */
    protected function define_module_plugin_structure() {

        // Define the virtual plugin element with the condition to fulfill
        $plugin = $this->get_plugin_element(null, $this->get_format_condition(), 'grid');

        // Create one standard named plugin element (the visible container)
        $pluginwrapper = new backup_nested_element($this->get_recommended_name());

        // connect the visible container ASAP
        $plugin->add_child($pluginwrapper);

        return $plugin;
    }
}
