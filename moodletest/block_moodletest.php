<?php

class block_moodletest extends block_base {

    public function init() {
        global $CFG;

        require_once("{$CFG->libdir}/completionlib.php");

        $this->title = get_string('pluginname', 'block_moodletest');
    }

    public function applicable_formats() {
        return array('course' => true);
    }

    public function get_content() {
        global $USER;

        $rows = array();
        $srows = array();
        $prows = array();
        // If content is cached.
        if ($this->content !== null) {
            return $this->content;
        }

        $course = $this->page->course;
        $context = context_course::instance($course->id);

        // To Create empty content.
        $this->content = new stdClass();
        $this->content->text = '';
        $this->content->footer = '';

    
        $can_edit = has_capability('moodle/course:update', $context);

        global $DB;
	// To identify Course ID
	 $modinfo = get_fast_modinfo($course->id);
	 

foreach ($modinfo->get_cms() as $cminfo) {
    //Join Query to fetch Information 
    $query88 = $DB->get_records_sql("SELECT m.added,mc.completionstate,tu.id,mm.name FROM `mdl_course_modules_completion` as mc INNER JOIN mdl_user AS tu ON mc.userid = tu.id  and tu.id=$USER->id RIGHT OUTER JOIN mdl_course_modules m ON m.id=mc.coursemoduleid INNER JOIN mdl_modules mm ON mm.id=m.module WHERE m.id=$cminfo->id");

   foreach($query88 as $cgg)
   {
     $added = $cgg->added;
	 $status= $cgg->completionstate;
     $this->content->text .= "<a href=../mod/".$cgg->name."/view.php?id=".$cminfo->id.">".$cminfo->id." - ".$cminfo->get_formatted_name()."   ".date('d-m-Y',$added)." - " .($status ==0 ? "Not Completed" : "Completed").'<a><br>';
   }
}

     return $this->content;
    }

   
}
