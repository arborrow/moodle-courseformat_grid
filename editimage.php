<?php

require_once("../../../config.php");
require_once("./lib.php");
require_once("./imagelib.php");

$image_width = 218;
$image_height = 140;

$id = required_param('id', PARAM_INT);
if (! $sectionicon = get_record('course_grid_icon', 'id', $id)) {
    error("Section icon is incorrect");
}

if (! $section = get_record("course_sections", "id", $sectionicon->sectionid)) {
    error("Course section is incorrect");
}

if (! $course = get_record("course", "id", $section->course)) {
    error("Could not find the course!");
}

require_login($course->id);
require_capability('moodle/course:update', get_context_instance(CONTEXT_COURSE, $course->id));    

$stredit = get_string('editimage', 'format_grid');

$strsummaryof = "Edit image for topic " . $section->section;
print_header_simple($stredit, '', build_navigation(array(array('name' => $stredit, 'link' => null, 'type' => 'misc'))), 'theform.summary' );
print_heading($strsummaryof);    

print_simple_box_start('center');    

if ($form = data_submitted() and confirm_sesskey()) { //If the form has been submitted, process the image.
    
    if ($_FILES["userfile"]["error"] > 0) {
    	echo "Error: " . $_FILES["userfile"]["error"]."<br/>";
		echo '<div style="text-align: center;"> Failed to upload. <a href="../../view.php?id='.$course->id.'"> Return </a></div>';      	
    	exit(0);
    }
	if (!validate_upload("userfile")) { //imagelib.php
		echo '<div style="text-align: center;"> Failed to upload. <a href="../../view.php?id='.$course->id.'"> Return </a></div>';
		exit(0);
	}
	$file_name = $_FILES["userfile"]["name"];
    $base_dir = $CFG->dataroot;
    $relative_path = "/".$course->id.'/icons/' . $file_name;
    
    $create_base = true;
    $create_icons = true;
    if(!file_exists($base_dir . "/" . $course->id)) {
    	$create_base = mkdir($base_dir . "/" . $course->id);
    } 
    if(!file_exists($base_dir . "/" . $course->id . "/icons")) {
		$create_icons = mkdir($base_dir . "/" . $course->id . "/icons");    
    }    	
    
    if(!$create_base || !$create_icons) {
		echo '<div style="text-align: center;"> Could not create icon directory: '. $base_dir . '/' . $course->id .'/icons/  <a href="../../view.php?id='.$course->id.'"> Return </a></div>';
		exit(0);
    }
    
	if(!is_dir($base_dir . "/" . $course->id . '/icons')){
		echo '<div style="text-align: center;"> Error: ' . $base_dir . '/' . $course->id . '/icons/ is not a directory. <a href="../../view.php?id='.$course->id.'"> Return </a></div>';
   		exit(0);        		
	}

    if(!file_exists( $base_dir . $relative_path)){
        move_uploaded_file($_FILES["userfile"]["tmp_name"], $base_dir . $relative_path);
	}
	
	resizeImage($base_dir, $file_name, $course->id, $image_width, $image_height);
    set_field("course_grid_icon", "imagepath", $_FILES["userfile"]["name"], "id", $form->id);

    add_to_log($course->id, "course", "editimage", "format/project/editimage.php?id=$section->id", "$section->section");
    
    notify("Successfully uploaded. You will now be returned to the course page");
    redirect("../../view.php?id=$course->id");
	
} else {
    $form = $sectionicon;
    include('editimage_form.php');
}   

function resizeImage($base_path, $file_name, $courseid, $width, $height) {
	$image = new ImageFunctions(); //imagelib.php
	$image->load($base_path . "/" . $courseid."/icons/" . $file_name);
	$image->resizeAndCrop($width, $height);
	$image->save($base_path . "/" . $courseid."/icons/tn_" . $file_name);
}
    
?>