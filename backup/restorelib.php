<?php
/**
 * Backup routine for this format
 *
 * @author Pavel Evgenjevich Timoshenko
 * @version $Id$
 * @package format_grid
 **/

/**
 * Grid formats restore routine
 *
 * @param object $restore Restore object
 * @param array $data This is the xmlized information underneath FORMATDATA in the backup XML file.
 **/
function grid_restore_format_data($restore, $data) {
    global $CFG;

    $status = true;

    // Get the backup data
    if (!empty($data['FORMATDATA']['#']['SUMMARIES']['0']['#']['SUMMARY'])) {
        // Get all the pages and restore them, restoring page items along the way.
        $summaries = $data['FORMATDATA']['#']['SUMMARIES']['0']['#']['PAGE'];
        for ($i = 0; $i < count($summaries); $i++) {
            $summary_info = $summaries[$i];

            // Id will remap later when we know all ids are present
            $summary_oldid = backup_todb($summary_info['#']['ID']['0']['#']);
            
            $summary = new stdClass;
            $summary->show_summary = backup_todb($summary_info['#']['SHOW_SUMMARY']['0']['#']);
            $summary->course_id = $restore->course_id;

            if ($summary_newid = insert_record('format_grid_summary', $summary)) {
                backup_putid($restore->backup_unique_code, 'format_grid_summary', $summary_oldid, $summary_newid);

                // Now restore the icons
                if (isset($summary_info['#']['ICONS'])) {
                    $icons = $summary_info['#']['ICONS']['0']['#']['ITEM'];
                    for ($j = 0; $j < count($icons); $j++) {
                        $icon_info = $icons[$j];

                        $icon_oldid = backup_todb($icon_info['#']['ID']['0']['#']);

                        $icon = new stdClass;
                        $icon->sectionid = $newid;
                        $icon->imagepath = backup_todb($icon_info['#']['IMAGEPATH']['0']['#']);

                        if ($icon_newid = insert_record('format_grid_icon', $item)) {
                            backup_putid($restore->backup_unique_code, 'format_grid_icon', $icon_oldid, $icon_newid);
                        } else {
                            $status = false;
                            break;
                        }
                    }
                }
            } else {
                $status = false;
                break;
            }
        }

        //TODO: Need to fix sortorder for old courses.
    }
    return $status;
}

/**
 * This function makes all the necessary calls to {@link restore_decode_content_links_worker()}
 * function inorder to decode contents of this block from the backup 
 * format to destination site/course in order to mantain inter-activities 
 * working in the backup/restore process. 
 * 
 * This is called from {@link restore_decode_content_links()}
 * function in the restore process.  This function is called regarless of
 * the return value from {@link backuprestore_enabled()}.
 *
 * @param object $restore Standard restore object
 * @return boolean
 **/
function grid_decode_format_content_links_caller($restore) {
    return true;
}
    
/**
 * Return content decoded to support interactivities linking.
 * This is called automatically from
 * {@link restore_decode_content_links_worker()} function
 * in the restore process.
 *
 * @param string $content Content to be dencoded
 * @param object $restore Restore preferences object
 * @return string The dencoded content
 **/
function grid_decode_format_content_links($content, $restore) {
    //TODO: Convert universal id to link;
    return $content;
}