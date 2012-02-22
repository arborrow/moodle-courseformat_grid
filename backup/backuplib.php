<?php
/**
 * Backup routine for this format
 *
 * @author Pavel Evgenjevich Timoshenko
 * @version $Id$
 * @package format_grid
 **/

/**
 * Format's backup routine
 *
 * @param handler $bf Backup file handler
 * @param object $preferences Backup preferences
 * @return boolean
 **/
function grid_backup_format_data($bf, $preferences) {
    $status = true;

    if ($summaries = get_records('format_grid_summary', 'course_id',
        $preferences->backup_course)) {
    
        $status = $status or fwrite ($bf, start_tag('SUMMARIES', 3, true));
        foreach ($summaries as $summary) {
            $status = $status or fwrite ($bf, start_tag('SUMMARY', 4, true));
            $status = $status or fwrite ($bf, full_tag('ID', 5, false, $summary->id));
            $status = $status or fwrite ($bf, full_tag('SHOW_SUMMARY', 5, false, $summary->show_summary));
            $status = $status or fwrite ($bf, full_tag('COURSE_ID', 5, false, $summary->course_id));

            // Now grab the icons
            if ($icons = get_records('format_grid_icon', 'sectionid', $summary->id)) {
                $status = $status or fwrite ($bf, start_tag('ICONS', 5, true));
                foreach($icons as $icon) {
                    $status = $status or fwrite ($bf, start_tag('ICON', 6, true));
                    $status = $status or fwrite ($bf, full_tag('ID', 7, false, $icon->id));
                    $status = $status or fwrite ($bf, full_tag('IMAGEPATH', 7, false, $icon->imagepath));
                    $status = $status or fwrite ($bf, end_tag('ICON', 6, true));
                }
                $status = $status or fwrite ($bf, end_tag('ICONS', 5, true));
            }
            $status = $status or fwrite ($bf, end_tag('SUMMARY', 4, true));
        }
        $status = $status or fwrite ($bf,end_tag('SUMMARIES', 3, true));
    }
    return $status;
}

/**
 * Return a content encoded to support interactivities linking. This function is
 * called automatically from the backup procedure by {@link backup_encode_absolute_links()}.
 *
 * @param string $content Content to be encoded
 * @param object $restore Restore preferences object
 * @return string The encoded content
 **/
function grid_encode_format_content_links($content, $restore) {
    global $CFG;

    $base = preg_quote($CFG->wwwroot, '/');

    //TODO: Convert lins to universal id;
    return $content;
}