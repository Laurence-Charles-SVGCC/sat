/* 
 * Contains all author created Javascript functions for the "Admissions" module.
 * Author: 11/02/2016
 */


/**
 * Toggle display message based on intended type of application period
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 12/02/2016
 * Date Last Modified: 12/02/2016
 * 
 */
function toggleAcademicYearMessage()
{
    var division_question = document.getElementsByName('intent');
    
    if (division_question[0].checked == true)
    {
        document.getElementById("dasgs-dtve").style.display = "block";
        document.getElementById("new-year-question").style.display = "block";
        document.getElementById("new-year-needed").style.display = "block";
        document.getElementById("dasgs-part").style.display = "none";
        document.getElementById("dtve-part").style.display = "none";
        document.getElementById("dte").style.display = "none";
        document.getElementById("dte-part").style.display = "none";
        document.getElementById("dne").style.display = "none";
        document.getElementById("dne-part").style.display = "none";
        document.getElementById("buttons").style.display = "none";
    }
    else if (division_question[1].checked == true)
    {
        document.getElementById("dasgs-dtve").style.display = "none";
        document.getElementById("create-academic-year-form").style.display = "none";
        document.getElementById("dasgs-part").style.display = "block";
        document.getElementById("dtve-part").style.display = "none";
        document.getElementById("dte").style.display = "none";
        document.getElementById("dte-part").style.display = "none";
        document.getElementById("dne").style.display = "none";
        document.getElementById("dne-part").style.display = "none";
        document.getElementById("buttons").style.display = "block";
    }
    else if (division_question[2].checked == true)
    {
        document.getElementById("dasgs-dtve").style.display = "none";
        document.getElementById("create-academic-year-form").style.display = "none";
        document.getElementById("dasgs-part").style.display = "none";
        document.getElementById("dtve-part").style.display = "block";
        document.getElementById("dte").style.display = "none";
        document.getElementById("dte-part").style.display = "none";
        document.getElementById("dne").style.display = "none";
        document.getElementById("dne-part").style.display = "none";
        document.getElementById("buttons").style.display = "block";
    }
    else if (division_question[3].checked == true)
    {
        document.getElementById("dasgs-dtve").style.display = "none";
        document.getElementById("create-academic-year-form").style.display = "none";
        document.getElementById("dasgs-part").style.display = "none";
        document.getElementById("dtve-part").style.display = "none";
        document.getElementById("dte").style.display = "block";
        document.getElementById("dte-part").style.display = "none";
        document.getElementById("dne").style.display = "none";
        document.getElementById("dne-part").style.display = "none";
        document.getElementById("buttons").style.display = "block";
    }
    else if (division_question[4].checked == true)
    {
        document.getElementById("dasgs-dtve").style.display = "none";
        document.getElementById("create-academic-year-form").style.display = "none";
        document.getElementById("dasgs-part").style.display = "none";
        document.getElementById("dtve-part").style.display = "none";
        document.getElementById("dte").style.display = "none";
        document.getElementById("dte-part").style.display = "block";
        document.getElementById("dne").style.display = "none";
        document.getElementById("dne-part").style.display = "none";
        document.getElementById("buttons").style.display = "block";
    }
    else if (division_question[5].checked == true)
    {
        document.getElementById("dasgs-dtve").style.display = "none";
        document.getElementById("create-academic-year-form").style.display = "none";
        document.getElementById("dasgs-part").style.display = "none";
        document.getElementById("dtve-part").style.display = "none";
        document.getElementById("dte").style.display = "none";
        document.getElementById("dte-part").style.display = "none";
        document.getElementById("dne").style.display = "block";
        document.getElementById("dne-part").style.display = "none";
        document.getElementById("buttons").style.display = "block";
    }
    else if (division_question[6].checked == true)
    {
        document.getElementById("dasgs-dtve").style.display = "none";
        document.getElementById("create-academic-year-form").style.display = "none";
        document.getElementById("dasgs-part").style.display = "none";
        document.getElementById("dtve-part").style.display = "none";
        document.getElementById("dte").style.display = "none";
        document.getElementById("dte-part").style.display = "none";
        document.getElementById("dne").style.display = "none";
        document.getElementById("dne-part").style.display = "block";
        document.getElementById("buttons").style.display = "block";
    }
}


/**
 * Toggle new academic year form
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 12/02/2016
 * Date Last Modified: 12/02/2016
 * 
 */
function toggleAcademicYearForm()
{
    var new_year = document.getElementsByName('new-year');
    
    if (new_year[0].checked == true)
        document.getElementById("create-academic-year-form").style.display = "block";
    else
    {
        document.getElementById("create-academic-year-form").style.display = "none";
        document.getElementById("buttons").style.display = "block";
    }
}


/**
 * Toggles academic offering form
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 12/02/2016
 * Date Last Modified: 12/02/2016
 * 
 */
function toggleAcademicOfferingForm()
{
    var more_programmes = document.getElementsByName('more-programmes');
    if (more_programmes[1].checked == true)
        document.getElementById("add-academic-offering-form").style.display = "block";
    else
    {
        document.getElementById("add-academic-offering-form").style.display = "none";
    }
    
}


/**
 * Toggles 'Update' button on 'view_applications_by_status' view
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 19/02/2016
 * Date Last Modified: 19/02/2016
 * 
 */
function showUpdateButton()
{
//    var dropdownlist = document.getElementsByName('programme').value;
//    var programmeid = dropdownlist.options[dropdownlist.selectedIndex].value;

    document.getElementById("update-button").style.display = "block";
}


