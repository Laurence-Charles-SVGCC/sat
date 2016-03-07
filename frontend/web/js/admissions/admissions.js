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
        document.getElementById("create-academic-year-form").style.display = "none";
        document.getElementById("new-year-question").style.display = "none";
        document.getElementById("new-year-needed").style.display = "none";
        document.getElementById("dasgs-part").style.display = "none";
        document.getElementById("dtve-part").style.display = "none";
        document.getElementById("dte").style.display = "none";
        document.getElementById("dte-part").style.display = "none";
        document.getElementById("dne").style.display = "none";
        document.getElementById("dne-part").style.display = "none";
        document.getElementById("buttons").style.display = "block";
    }
    else if (division_question[1].checked == true)
    {
        document.getElementById("dasgs-dtve").style.display = "none";
        document.getElementById("create-academic-year-form").style.display = "none";
        document.getElementById("new-year-question").style.display = "none";
        document.getElementById("new-year-needed").style.display = "none";
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
        document.getElementById("new-year-question").style.display = "none";
        document.getElementById("new-year-needed").style.display = "none";
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
        document.getElementById("new-year-question").style.display = "block";
        document.getElementById("new-year-needed").style.display = "block";
        document.getElementById("dasgs-part").style.display = "none";
        document.getElementById("dtve-part").style.display = "none";
        document.getElementById("dte").style.display = "block";
        document.getElementById("dte-part").style.display = "none";
        document.getElementById("dne").style.display = "none";
        document.getElementById("dne-part").style.display = "none";
        document.getElementById("buttons").style.display = "none";
    }
    else if (division_question[4].checked == true)
    {
        document.getElementById("dasgs-dtve").style.display = "none";
        document.getElementById("create-academic-year-form").style.display = "none";
        document.getElementById("new-year-question").style.display = "none";
        document.getElementById("new-year-needed").style.display = "none";
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
        document.getElementById("new-year-question").style.display = "block";
        document.getElementById("new-year-needed").style.display = "block";
        document.getElementById("dasgs-part").style.display = "none";
        document.getElementById("dtve-part").style.display = "none";
        document.getElementById("dte").style.display = "none";
        document.getElementById("dte-part").style.display = "none";
        document.getElementById("dne").style.display = "block";
        document.getElementById("dne-part").style.display = "none";
        document.getElementById("buttons").style.display = "none";
    }
    else if (division_question[6].checked == true)
    {
        document.getElementById("dasgs-dtve").style.display = "none";
        document.getElementById("create-academic-year-form").style.display = "none";
        document.getElementById("new-year-question").style.display = "none";
        document.getElementById("new-year-needed").style.display = "none";
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
    {
        document.getElementById("create-academic-year-form").style.display = "block";
        document.getElementById("buttons").style.display = "none";
    }
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


/**
 * Handles search method functinonality.
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 24/02/2016
 * Date Last Modified: 24/02/2016
 */
function checkSearchHow()
{
//    alert ("oye");
    var search_by = document.getElementsByName('search_how');
    if (search_by[0].checked == true)           //if by applicantid
    {   
        if (document.getElementsByName("FirstName_field")[0] != null)
            document.getElementsByName("FirstName_field")[0].value = "";
 
        if (document.getElementsByName("LastName_field")[0] != null)
            document.getElementsByName("LastName_field")[0].value = ""; 
           
        if (document.getElementsByName("email_field")[0] != null)
            document.getElementsByName("email_field")[0].value = ""; 
        
        document.getElementById("applicantid").style.display = "block";       
        document.getElementById("name").style.display = "none";
        document.getElementById("email").style.display = "none";
    } 
    else if (search_by[1].checked == true)           //if by student name
    {         
        if (document.getElementsByName("applicantid_field")[0] != null)
            document.getElementsByName("applicantid_field")[0].value = "";
        
        if (document.getElementsByName("email_field")[0] != null)
            document.getElementsByName("email_field")[0].value = ""; 
               
        document.getElementById("applicantid").style.display = "none";       
        document.getElementById("name").style.display = "block";
        document.getElementById("email").style.display = "none";
    }
    else if (search_by[2].checked == true)           //if by email address
    {        
        if (document.getElementsByName("applicantid_field")[0] != null)
            document.getElementsByName("applicantid_field")[0].value = "";
        
        if (document.getElementsByName("FirstName_field")[0] != null)
            document.getElementsByName("FirstName_field")[0].value = "";
 
        if (document.getElementsByName("LastName_field")[0] != null)
            document.getElementsByName("LastName_field")[0].value = ""; 
        
        document.getElementById("applicantid").style.display = "none";       
        document.getElementById("name").style.display = "none";
        document.getElementById("email").style.display = "block";
    } 
}


/**
 * Handles the offer filteration mechanism.
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 06/03/2016
 * Date Last Modified: 06/03/2016
 */
function filterOffer()
{
    var search_by = document.getElementsByName('offer_filter');
    if (search_by[0].checked == true)           //if no filter
    {  
        document.getElementById("offer-division-field").selectedIndex = 0;
        document.getElementById("offer-programme-field").selectedIndex = 0;  
        document.getElementById("offer-cape-field").selectedIndex = 0;  
        
        document.getElementById("offer-division").style.display = "none";       
        document.getElementById("offer-programme").style.display = "none";
        document.getElementById("offer-cape").style.display = "none";
    } 
    
    if (search_by[1].checked == true)           //if by division
    {  
        document.getElementById("offer-programme-field").selectedIndex = 0;  
        document.getElementById("offer-cape-field").selectedIndex = 0;  
        
        document.getElementById("offer-division").style.display = "block";       
        document.getElementById("offer-programme").style.display = "none";
        document.getElementById("offer-cape").style.display = "none";
    } 
    else if (search_by[2].checked == true)           //if by programme
    {         
        document.getElementById("offer-division-field").selectedIndex = 0;
        document.getElementById("offer-cape-field").selectedIndex = 0;
               
        document.getElementById("offer-division").style.display = "none";       
        document.getElementById("offer-programme").style.display = "block";
        document.getElementById("offer-cape").style.display = "none";
    }
    else if (search_by[3].checked == true)           //if by CAPE subject
    {        
        document.getElementById("offer-division-field").selectedIndex = 0;  
        document.getElementById("offer-programme-field").selectedIndex = 0; 
        
        document.getElementById("offer-division").style.display = "none";       
        document.getElementById("offer-programme").style.display = "none";
        document.getElementById("offer-cape").style.display = "block";
    }
}


/**
 * Toggles the 'Filter' button for divisional filter
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 06/03/2016
 * Date Last Modified: 06/03/2016
 */
function showFilterButton1()
{
    var button = document.getElementById("offer-division-field").selectedIndex;
    if (button != 0)
       document.getElementById('divisional-filter-button').style.display = "block"; 
   else
       document.getElementById('divisional-filter-button').style.display = "none"; 
}


/**
 * Toggles the 'Filter' button for programme filter
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 06/03/2016
 * Date Last Modified: 06/03/2016
 */
function showFilterButton2()
{
    var button = document.getElementById("offer-programme-field").selectedIndex;
    if (button != 0)
       document.getElementById('programme-filter-button').style.display = "block"; 
   else
       document.getElementById('programme-filter-button').style.display = "none"; 
}


/**
 * Toggles the 'Filter' button for cape subject filter
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 06/03/2016
 * Date Last Modified: 06/03/2016
 */
function showFilterButton3()
{
    var button = document.getElementById("offer-cape-field").selectedIndex;
    if (button != 0)
       document.getElementById('cape-filter-button').style.display = "block"; 
   else
       document.getElementById('cape-filter-button').style.display = "none"; 
}
