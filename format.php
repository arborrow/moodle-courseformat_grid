<style type="text/css" media="screen">
/* <![CDATA[ */
    @import url(<?php echo $CFG->wwwroot ?>/course/format/grid/grid.css);
/* ]]> */
</style>
<?php

// Display the whole course as "topics" made of of modules
// Included from "view.php"
/**
 * Evaluation topics format for course display - NO layout tables, for accessibility, etc.
 *
 * A duplicate course format to enable the Moodle development team to evaluate
 * CSS for the multi-column layout in place of layout tables.
 * Less risk for the Moodle 1.6 beta release.
 *   1. Straight copy of topics/format.php
 *   2. Replace <table> and <td> with DIVs; inline styles.
 *   3. Reorder columns so that in linear view content is first then blocks;
 * styles to maintain original graphical (side by side) view.
 *
 * Target: 3-column graphical view using relative widths for pixel screen sizes
 * 800x600, 1024x768... on IE6, Firefox. Below 800 columns will shift downwards.
 *
 * http://www.maxdesign.com.au/presentation/em/ Ideal length for content.
 * http://www.svendtofte.com/code/max_width_in_ie/ Max width in IE.
 *
 * @copyright &copy; 2006 The Open University
 * @author N.D.Freear@open.ac.uk, and others.
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/filelib.php');
require_once($CFG->libdir.'/completionlib.php');

$topic = optional_param('topic', -1, PARAM_INT);

if ($topic != -1) {
    $displaysection = course_set_display($course->id, $topic);
} else {
    $displaysection = course_get_display($course->id);
}

$context = get_context_instance(CONTEXT_COURSE, $course->id);

if (($marker >=0) && has_capability('moodle/course:setcurrentsection', $context) && confirm_sesskey()) {
    $course->marker = $marker;
    $DB->set_field("course", "marker", $marker, array("id"=>$course->id));
}

$streditsummary    = get_string('editsummary');
$stradd            = get_string('add');
$stractivities     = get_string('activities');
$strshowalltopics  = get_string('showalltopics');
$strtopic          = get_string('topic');
$strgroups         = get_string('groups');
$strgroupmy        = get_string('groupmy');
$isediting         = $PAGE->user_is_editing();

if ($isediting) {
    $strtopichide = get_string('hidetopicfromothers');
    $strtopicshow = get_string('showtopicfromothers');
    $strmarkthistopic = get_string('markthistopic');
    $strmarkedthistopic = get_string('markedthistopic');
    $strmoveup   = get_string('moveup');
    $strmovedown = get_string('movedown');
}

// Print the Your progress icon if the track completion is enabled
$completioninfo = new completion_info($course);
echo $completioninfo->display_help_icon();

// Note, an ordered list would confuse - "1" could be the clipboard or summary.
echo "<ul class='topics'>\n";

/// If currently moving a file then show the current clipboard
if (ismoving($course->id)) {
    $stractivityclipboard = strip_tags(get_string('activityclipboard', '', $USER->activitycopyname));
    $strcancel= get_string('cancel');
    echo '<li class="clipboard">';
    echo $stractivityclipboard.'&nbsp;&nbsp;(<a href="mod.php?cancelcopy=true&amp;sesskey='.sesskey().'">'.$strcancel.'</a>)';
    echo "</li>\n";
}

echo html_writer::script('', $CFG->wwwroot.'/course/format/grid/jslib.js');
    
/* Internet Explorer min-width fix. (See theme/standard/styles_layout.css: min-width for Firefox.)
   Window width: 800px, Firefox 763px, IE 752px. (Window width: 640px, Firefox 602px, IE 588px.)    
*/
    
?>

<!--[if IE]>
  <style type="text/css">
  .weekscss-format { width: expression(document.body.clientWidth < 800 ? "752px" : "auto"); }
  </style>
<![endif]-->
<?php
/// Layout the whole page as three big columns (was, id="layout-table")

echo '<div class="topicscss-format">';



/// Start main column
$main_column_class = 'class="';
$main_column_class .= '"';
echo '<div id="middle-column" '. $main_column_class .'>'. skip_main_destination();

//print_heading_block($course->fullname . '', '');

$summary_status = get_summary_visibility($course->id);
if($summary_status->show_summary == 1) {
    /// Section 0 gets placed at the top.

    $section = 0;
    $thissection = $sections[$section];
        
    if ($thissection->summary or $thissection->sequence or $isediting) {

    	// Note, no need for a 'left side' cell or DIV.
    	// Note, 'right side' is BEFORE content.
    	echo '<li id="section-0" class="section main clearfix" >';
    	echo '<div class="left side">&nbsp;</div>';
    	echo '<div class="right side" >&nbsp;</div>';
    	echo '<div class="content">';
    	if (!is_null($thissection->name)) {
        	echo $OUTPUT->heading($thissection->name, 3, 'sectionname');
    	}
    	echo '<div class="summary">';

    	$coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);
    	$summarytext = file_rewrite_pluginfile_urls($thissection->summary, 'pluginfile.php', $coursecontext->id, 'course', 'section', $thissection->id);
    	$summaryformatoptions = new stdClass();
    	$summaryformatoptions->noclean = true;
    	$summaryformatoptions->overflowdiv = true;
    	echo format_text($summarytext, $thissection->summaryformat, $summaryformatoptions);

    	if ($isediting && has_capability('moodle/course:update', $coursecontext)) {
    		echo '<a title="'.$streditsummary.'" '.
             ' href="editsection.php?id='.$thissection->id.'"><img src="'.$OUTPUT->pix_url('t/edit') . '" '.
             ' class="icon edit" alt="'.$streditsummary.'" /></a>';
    	}
    	echo '</div>';

    	print_section($course, $thissection, $mods, $modnamesused);

    	if ($isediting) {
        	print_section_add_menus($course, $section, $modnames);
    	}

    	echo '</div>';
    	echo "</li>\n";
	}
}

/// Print all of the icons.

echo '<div id="iconContainer">'."\n";
echo '<ul class="icons">'."\n";
$timenow = time();
$section = 0;
if($summary_status->show_summary == 1) {
    $section = 1; //or include the summary block if it's in the grid display
}
$sectionmenu = array();

while ($section <= $course->numsections) {

    if (!empty($sections[$section])) {
        $thissection = $sections[$section];

    } else {
        $thissection = new stdClass;
        $thissection->course  = $course->id;   // Create a new section structure
        $thissection->section = $section;
        $thissection->name    = null;
        $thissection->summary  = '';
        $thissection->summaryformat = FORMAT_HTML;
        $thissection->visible  = 1;
        $thissection->id = $DB->insert_record('course_sections', $thissection);
    }

    $showsection = (has_capability('moodle/course:viewhiddensections', $context) or $thissection->visible or !$course->hiddensections);

    if (!empty($displaysection) and $displaysection != $section) {  // Check this topic is visible
        if ($showsection) {
            $sectionmenu[$section] = get_section_name($course, $thissection);
        }
        $section++;
        continue;
    }

    if ($showsection) {
        $new_activity = new_activity($thissection, $course, $mods);
        $sectionicon = grid_format_get_icon($course, $thissection->id, $section, $mods);
        $strtitle = "";
        if($section == 0) {
            $strtitle = get_title($thissection);
            if($strtitle == '&nbsp;' || strlen(trim($strtitle)) == 0) {
                $strtitle = "General Information";
            }
        } else {
            $strtitle = get_title($thissection);
        }

        //Get the module icon

        if ($isediting && has_capability('moodle/course:update', get_context_instance(CONTEXT_COURSE, $course->id))) {
            $onclickevent = 'select_topic_edit(event, '.$thissection->section.')';
        } else {
            $onclickevent = 'select_topic(event, '.$thissection->section.')';
        }

        echo '<li> <a href="#section-'.$thissection->section.'" class="icon_link" onclick="'. $onclickevent .'"><p class="icon_content">' . $strtitle.'</p>';
        if($new_activity) {
            echo '<img class="new_activity" src="'.$url = $CFG->wwwroot.'/course/format/grid/images/new_activity.png" />';
        }
        echo '<div class="image_holder">';   
        
        
        if($sectionicon && $sectionicon->imagepath) {
            echo '<img src="'.$url = $CFG->wwwroot . '/pluginfile.php/' . $context->id . '/course/section/' . $thissection->id . 
            '/' . $sectionicon->imagepath .'"/>'; 
        } else if($section == 0) {
            echo '<img src="'.$url = $CFG->wwwroot.'/course/format/grid/info.png">';
        }        
/*
        if($sectionicon->imagepath) {    
            echo '<img src="'.$url = $CFG->wwwroot . '/pluginfile.php/' . $context->id . '/course/section/' . $thissection->id . 
        '/' . $sectionicon->imagepath .'"/>'; 
        }
*/
                
        echo "</div></a>";
        if ($isediting && has_capability('moodle/course:update', get_context_instance(CONTEXT_COURSE, $course->id))) {
            //echo ' <a title="'.get_string('editimage','format_grid').'" href="format/grid/editimage.php?id='.$sectionicon->id.'">'.
            echo ' <a title="'.get_string('editimage','format_grid').'" href="format/grid/editimage.php?sectionid='.$thissection->id.'&contextid='.$context->id.'&userid='.$USER->id.'">'.
                 '<img src="'.$OUTPUT->pix_url('t/edit').'" alt="'.get_string('editimage','format_grid').'" /> change image</a>';                    
            if($section == 0) {
                echo ' <a title="'.get_string('display_summary_alt','format_grid').'" href="format/grid/mod_summary.php?sesskey='.sesskey().'&amp;course='.$course->id.'&amp;showsummary=1">'.
                     '<img src="format/grid/images/out_of_grid.png" alt="'.get_string('display_summary_alt','format_grid').'" /> '.get_string('display_summary','format_grid').' </a>';
            }
        }
        echo "</li>";

    }        
    $section++;     
}

echo '</ul>'."\n";  
echo '</div>'."\n"; 

// Note, an ordered list would confuse - "1" could be the clipboard or summary.

echo '<div id="shadebox">';
echo '<div id="shadebox_overlay" style="display:none;" onclick="toggle_shadebox();"></div>';
//echo '<div id="shadebox_overlay" style="display:none;"></div>';
echo '<div id="shadebox_content">';
echo '<img id="shadebox_close" style="display: none;" src="'.$CFG->wwwroot.'/course/format/grid/close.png" onclick="toggle_shadebox();">';

echo "<ul class='topics'>\n";

/// If currently moving a file then show the current clipboard
if (ismoving($course->id)) {
    $stractivityclipboard = strip_tags(get_string('activityclipboard', '', $USER->activitycopyname));
    $strcancel= get_string('cancel');
    echo '<li class="clipboard">';
    echo $stractivityclipboard.'&nbsp;&nbsp;(<a href="mod.php?cancelcopy=true&amp;sesskey='.sesskey().'">'.$strcancel.'</a>)';
    echo "</li>\n";
}

/// Print Section 0 with general activities
	if($summary_status->show_summary == 0) {
    $section = 0;
    
    if ($thissection->summary or $thissection->sequence or $isediting) {
        echo '<li id="section-0" class="section main grid_section">';
        echo '<div class="right side">&nbsp;</div>';
    
        echo '<div class="content">';
    
        echo '<div class="summary">';

    $coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);
    $summarytext = file_rewrite_pluginfile_urls($thissection->summary, 'pluginfile.php', $coursecontext->id, 'course', 'section', $thissection->id);
    $summaryformatoptions = new stdClass();
    $summaryformatoptions->noclean = true;
    $summaryformatoptions->overflowdiv = true;
    echo format_text($summarytext, $thissection->summaryformat, $summaryformatoptions);

    if ($isediting && has_capability('moodle/course:update', $coursecontext)) {
        echo '<a title="'.$streditsummary.'" '.
             ' href="editsection.php?id='.$thissection->id.'"><img src="'.$OUTPUT->pix_url('t/edit') . '" '.
             ' class="icon edit" alt="'.$streditsummary.'" /></a>';
    }
    echo '</div>';
    
        print_section($course, $thissection, $mods, $modnamesused);
    
        if ($isediting) {
            print_section_add_menus($course, $section, $modnames);
        }
    
        echo '</div>';
        echo '</li>';
        //echo '</ul>';
    }
	}


/// Now all the normal modules by topic
/// Everything below uses "section" terminology - each "section" is a topic/module. 

$section = 1;
$sectionmenu = array();

while ($section <= $course->numsections) {

    //if section doesnt exist create it. Set $thissection to point to it.
    if (!empty($sections[$section])) {
        $thissection = $sections[$section];

    } else {
        //Section should have been created in the icons section above. If it's empty then its an error.
        unset($thissection);
        notify('Error, section ' . $section . ' not found!');
        $section++;        
        continue;    
    }

    $showsection = (has_capability('moodle/course:viewhiddensections', $context) or $thissection->visible or !$course->hiddensections);
           
    if ($showsection) {

        $currenttopic = ($course->marker == $section);

        $currenttext = '';
        if (!$thissection->visible) {
            $sectionstyle = ' hidden';
        } else if ($currenttopic) {
            $sectionstyle = ' current';
            $currenttext = get_accesshide(get_string('currenttopic','access'));
        } else {
            $sectionstyle = '';
        }

        echo '<li id="section-'.$section.'" class="section main'.$sectionstyle.' grid_section" >';
        // Note, 'right side' is BEFORE content.
        echo '<div class="right side">';
   
        if ($isediting && has_capability('moodle/course:update', get_context_instance(CONTEXT_COURSE, $course->id))) {

            
            if ($course->marker == $section) {  // Show the "light globe" on/off
                echo '<a href="view.php?id='.$course->id.'&amp;marker=0&amp;sesskey='.sesskey().'#section-'.$section.'" title="'.$strmarkedthistopic.'">'.'<img src="'.$OUTPUT->pix_url('i/marked') . '" alt="'.$strmarkedthistopic.'" /></a><br />';
            } else {
                echo '<a href="view.php?id='.$course->id.'&amp;marker='.$section.'&amp;sesskey='.sesskey().'#section-'.$section.'" title="'.$strmarkthistopic.'">'.'<img src="'.$OUTPUT->pix_url('i/marker') . '" alt="'.$strmarkthistopic.'" /></a><br />';
            }
            
            if ($thissection->visible) {        // Show the hide/show eye
                echo '<a href="view.php?id='.$course->id.'&amp;hide='.$section.'&amp;sesskey='.sesskey().'#section-'.$section.'" title="'.$strtopichide.'">'.
                     '<img src="'.$OUTPUT->pix_url('i/hide') . '" class="icon hide" alt="'.$strtopichide.'" /></a><br />';
            } else {
                echo '<a href="view.php?id='.$course->id.'&amp;show='.$section.'&amp;sesskey='.sesskey().'#section-'.$section.'" title="'.$strtopicshow.'">'.
                     '<img src="'.$OUTPUT->pix_url('i/show') . '" class="icon hide" alt="'.$strtopicshow.'" /></a><br />';
            }
            if ($section > 1) {                       // Add a arrow to move section up
                echo '<a href="view.php?id='.$course->id.'&amp;random='.rand(1,10000).'&amp;section='.$section.'&amp;move=-1&amp;sesskey='.sesskey().'#section-'.($section-1).'" title="'.$strmoveup.'">'.
                     '<img src="'.$OUTPUT->pix_url('t/up') . '" class="icon up" alt="'.$strmoveup.'" /></a><br />';
            }

            if ($section < $course->numsections) {    // Add a arrow to move section down
                echo '<a href="view.php?id='.$course->id.'&amp;random='.rand(1,10000).'&amp;section='.$section.'&amp;move=1&amp;sesskey='.sesskey().'#section-'.($section+1).'" title="'.$strmovedown.'">'.
                     '<img src="'.$OUTPUT->pix_url('t/down') . '" class="icon down" alt="'.$strmovedown.'" /></a><br />';
            }
        }
        echo '</div>';

       echo '<div class="content">';
        if (!has_capability('moodle/course:viewhiddensections', $context) and !$thissection->visible) {   // Hidden for students
            echo get_string('notavailable');
        } else {
            if (!is_null($thissection->name)) {
                echo $OUTPUT->heading($thissection->name, 3, 'sectionname');
            }
            echo '<div class="summary">';
            if ($thissection->summary) {
                $coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);
                $summarytext = file_rewrite_pluginfile_urls($thissection->summary, 'pluginfile.php', $coursecontext->id, 'course', 'section', $thissection->id);
                $summaryformatoptions = new stdClass();
                $summaryformatoptions->noclean = true;
                $summaryformatoptions->overflowdiv = true;
                echo format_text($summarytext, $thissection->summaryformat, $summaryformatoptions);
            } else {
               echo '&nbsp;';
            }

            if ($isediting && has_capability('moodle/course:update', get_context_instance(CONTEXT_COURSE, $course->id))) {
                echo ' <a title="'.$streditsummary.'" href="editsection.php?id='.$thissection->id.'">'.
                     '<img src="'.$OUTPUT->pix_url('t/edit') . '" class="icon edit" alt="'.$streditsummary.'" /></a><br /><br />';
            }
            echo '</div>';

            print_section($course, $thissection, $mods, $modnamesused);
            echo '<br />';
            if ($isediting) {
                print_section_add_menus($course, $section, $modnames);
            }
        }

        echo '</div>';
        echo "</li>\n";

    }

    unset($sections[$section]);
    $section++;
}
echo "</ul>\n";
echo '</div></div>';

if (!empty($sectionmenu)) {
    echo '<div class="jumpmenu">';
    echo popup_form($CFG->wwwroot.'/course/view.php?id='.$course->id.'&amp;', $sectionmenu,
               'sectionmenu', '', get_string('jumpto'), '', '', true);
    echo '</div>';
}

echo '</div>';

echo '</div>';
echo '<div class="clearer"></div>';

if (!($isediting && has_capability('moodle/course:update', get_context_instance(CONTEXT_COURSE, $course->id)))) {
    echo '<script> hide_sections(); </script>';
}

?>
