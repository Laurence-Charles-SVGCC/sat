/**
 * Handles programme search method functinonality.
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 27/04/2016
 * Date Last Modified: 27/04/2016
 */
function overallSearchType()
{
//    alert ("oye");
    var search_by = document.getElementsByName('overall_search_type');
    if (search_by[0].checked == true)           //if by division
    {   
        if (document.getElementsByName("programme_field")[0] != null)
            document.getElementsByName("programme_field")[0].value = "";
 
        if (document.getElementsByName("course-code-field")[0] != null)
            document.getElementsByName("course-code-field")[0].value = "";
        
        if (document.getElementsByName("course-name-field")[0] != null)
            document.getElementsByName("course-name-field")[0].value = "";
        
        document.getElementById("div").style.display = "block";       
        document.getElementById("prog").style.display = "none";
        document.getElementById("course").style.display = "none";
    } 
    else if (search_by[1].checked == true)           //if by programme
    {         
        if (document.getElementsByName("programme_field")[0] != null)
            document.getElementsByName("programme_field")[0].value = "";
 
        if (document.getElementsByName("course-code-field")[0] != null)
            document.getElementsByName("course-code-field")[0].value = "";
        
        if (document.getElementsByName("course-name-field")[0] != null)
            document.getElementsByName("course-name-field")[0].value = "";
               
        document.getElementById("div").style.display = "none";       
        document.getElementById("prog").style.display = "block";
        document.getElementById("course").style.display = "none";
    }
    else if (search_by[2].checked == true)           //if by course
    {        
        if (document.getElementsByName("programme_field")[0] != null)
            document.getElementsByName("programme_field")[0].value = "";
 
        if (document.getElementsByName("course-code-field")[0] != null)
            document.getElementsByName("course-code-field")[0].value = "";
        
        if (document.getElementsByName("course-name-field")[0] != null)
            document.getElementsByName("course-name-field")[0].value = "";
        
        document.getElementById("div").style.display = "none";       
        document.getElementById("prog").style.display = "none";
        document.getElementById("course").style.display = "block";
    } 
}



function programmeSearchType()
{
//    alert ("oye");
    var search_by = document.getElementsByName('programme_search_type');
    if (search_by[0].checked == true)           //if all programmes
    {   
        if (document.getElementsByName("programme_field")[0] != null)
            document.getElementsByName("programme_field")[0].value = "";
        
        if (document.getElementsByName("course-code-field")[0] != null)
            document.getElementsByName("course-code-field")[0].value = "";
        
        if (document.getElementsByName("course-name-field")[0] != null)
            document.getElementsByName("course-name-field")[0].value = "";
 
        document.getElementById("all-programme").style.display = "block";       
        document.getElementById("by-programme-name").style.display = "none";
    } 
    else if (search_by[1].checked == true)           //if by programme name
    {         
        if (document.getElementsByName("programme_field")[0] != null)
            document.getElementsByName("programme_field")[0].value = "";
        
        if (document.getElementsByName("course-code-field")[0] != null)
            document.getElementsByName("course-code-field")[0].value = "";
        
        if (document.getElementsByName("course-name-field")[0] != null)
            document.getElementsByName("course-name-field")[0].value = ""; 
        
        document.getElementById("all-programme").style.display = "none";       
        document.getElementById("by-programme-name").style.display = "block";
    }
}


function courseSearchType()
{
//    alert ("oye");
    var search_by = document.getElementsByName('course_search_type');
    if (search_by[0].checked == true)           //if all courses
    {   
        if (document.getElementsByName("programme_field")[0] != null)
            document.getElementsByName("programme_field")[0].value = "";
        
        if (document.getElementsByName("course-code-field")[0] != null)
            document.getElementsByName("course-code-field")[0].value = "";
        
        if (document.getElementsByName("course-name-field")[0] != null)
            document.getElementsByName("course-name-field")[0].value = "";
            
        document.getElementById("all-courses").style.display = "block";       
        document.getElementById("by-course-division").style.display = "none";
        document.getElementById("by-course-department").style.display = "none";       
        document.getElementById("by-course-code").style.display = "none";
        document.getElementById("by-course-name").style.display = "none"; 
    } 
    else if (search_by[1].checked == true)           //if by division
    {         
        if (document.getElementsByName("programme_field")[0] != null)
            document.getElementsByName("programme_field")[0].value = "";
        
        if (document.getElementsByName("course-code-field")[0] != null)
            document.getElementsByName("course-code-field")[0].value = "";
        
        if (document.getElementsByName("course-name-field")[0] != null)
            document.getElementsByName("course-name-field")[0].value = "";
        
        document.getElementById("all-courses").style.display = "none";       
        document.getElementById("by-course-division").style.display = "block";
        document.getElementById("by-course-department").style.display = "none";       
        document.getElementById("by-course-code").style.display = "none";
        document.getElementById("by-course-name").style.display = "none"; 
    }
    else if (search_by[2].checked == true)           //if by department
    {         
        if (document.getElementsByName("programme_field")[0] != null)
            document.getElementsByName("programme_field")[0].value = "";
        
        if (document.getElementsByName("course-code-field")[0] != null)
            document.getElementsByName("course-code-field")[0].value = "";
        
        if (document.getElementsByName("course-name-field")[0] != null)
            document.getElementsByName("course-name-field")[0].value = "";
        
        document.getElementById("all-courses").style.display = "none";       
        document.getElementById("by-course-division").style.display = "none";
        document.getElementById("by-course-department").style.display = "block";       
        document.getElementById("by-course-code").style.display = "none";
        document.getElementById("by-course-name").style.display = "none"; 
    }
    else if (search_by[3].checked == true)           //if by course code
    {         
        if (document.getElementsByName("programme_field")[0] != null)
            document.getElementsByName("programme_field")[0].value = "";
        
        if (document.getElementsByName("course-code-field")[0] != null)
            document.getElementsByName("course-code-field")[0].value = "";
        
        if (document.getElementsByName("course-name-field")[0] != null)
            document.getElementsByName("course-name-field")[0].value = "";
        
        document.getElementById("all-courses").style.display = "none";       
        document.getElementById("by-course-division").style.display = "none";
        document.getElementById("by-course-department").style.display = "none";       
        document.getElementById("by-course-code").style.display = "block";
        document.getElementById("by-course-name").style.display = "none"; 
    }
    else if (search_by[4].checked == true)           //if by course name
    {         
        if (document.getElementsByName("programme_field")[0] != null)
            document.getElementsByName("programme_field")[0].value = "";
        
        if (document.getElementsByName("course-code-field")[0] != null)
            document.getElementsByName("course-code-field")[0].value = "";
        
        if (document.getElementsByName("course-name-field")[0] != null)
            document.getElementsByName("course-name-field")[0].value = "";
        
        document.getElementById("all-courses").style.display = "none";       
        document.getElementById("by-course-division").style.display = "none";
        document.getElementById("by-course-department").style.display = "none";       
        document.getElementById("by-course-code").style.display = "none";
        document.getElementById("by-course-name").style.display = "block"; 
    }
}


function toggleProgrammeOptions()
{
    var search_by = document.getElementsByName('programme_options');    //captures input
    if (search_by[0].checked == true)            //if View Course Outlines
    {
         document.getElementById("view-course-outlines").style.display = "block";       
         document.getElementById("investigate-academic-year").style.display = "none";
         document.getElementById("view-intake-reports").style.display = "none";
         document.getElementById("view-student-performance-options").style.display = "none";
    }
    else if (search_by[1].checked == true)        //if Investigate Academic Year
    {
         document.getElementById("view-course-outlines").style.display = "none";       
         document.getElementById("investigate-academic-year").style.display = "block";
         document.getElementById("view-intake-reports").style.display = "none";
         document.getElementById("view-student-performance-options").style.display = "none";
    }
    else if (search_by[2].checked == true)        //if View Intake Reports
    {
         document.getElementById("view-course-outlines").style.display = "none";       
         document.getElementById("investigate-academic-year").style.display = "none";
         document.getElementById("view-intake-reports").style.display = "block";
         document.getElementById("view-student-performance-options").style.display = "none";
    }
    else if (search_by[3].checked == true)        //if View Student Performance Options
    {
         document.getElementById("view-course-outlines").style.display = "none";       
         document.getElementById("investigate-academic-year").style.display = "none";
         document.getElementById("view-intake-reports").style.display = "none";
         document.getElementById("view-student-performance-options").style.display = "block";
    }
}


