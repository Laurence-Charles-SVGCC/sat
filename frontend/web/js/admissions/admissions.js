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
        document.getElementById("offer-home").style.display = "block";
    } 
    
    if (search_by[1].checked == true)           //if by division
    {  
        document.getElementById("offer-programme-field").selectedIndex = 0;  
        document.getElementById("offer-cape-field").selectedIndex = 0;  
        
        document.getElementById("offer-division").style.display = "block";       
        document.getElementById("offer-programme").style.display = "none";
        document.getElementById("offer-cape").style.display = "none";
        document.getElementById("offer-home").style.display = "none";
    } 
    else if (search_by[2].checked == true)           //if by programme
    {         
        document.getElementById("offer-division-field").selectedIndex = 0;
        document.getElementById("offer-cape-field").selectedIndex = 0;
               
        document.getElementById("offer-division").style.display = "none";       
        document.getElementById("offer-programme").style.display = "block";
        document.getElementById("offer-cape").style.display = "none";
        document.getElementById("offer-home").style.display = "none";
    }
    else if (search_by[3].checked == true)           //if by CAPE subject
    {        
        document.getElementById("offer-division-field").selectedIndex = 0;  
        document.getElementById("offer-programme-field").selectedIndex = 0; 
        
        document.getElementById("offer-division").style.display = "none";       
        document.getElementById("offer-programme").style.display = "none";
        document.getElementById("offer-cape").style.display = "block";
        document.getElementById("offer-home").style.display = "none";
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


/**
 * Add an additional CsecQualification Record
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 17/03/2016
 * Date Last Modified: 17/03/2016
 */
function addNewCertificate()
{
    var found = false;
    var status = null;
    
    var record_count_array = document.getElementsByName("record_count");
    var record_count = parseInt(record_count_array[0].value);

    var qual_limit_array = document.getElementsByName("qual_limit");
    var qual_limit = parseInt(qual_limit_array[0].value);

    var i = record_count;

    var lastStatus = document.getElementById("qualification[" + (qual_limit-1) + "]").style.display;

    if (lastStatus == "block")          //if record limit has been reached
    {
        $('#modal-too-many-qualifications').modal('show');       
    }
    else                                //if record limit has not been reached
    {  

        while (i < qual_limit && found == false)
        {
            status = document.getElementById("qualification[" + i + "]").style.display;

            if (status == "none")
            {
                //Initializes all field to blank
                document.getElementById("csecqualification-" + i + "-cseccentreid").selectedIndex = 0;
                document.getElementById("csecqualification-" + i + "-candidatenumber").value = "";
                document.getElementById("csecqualification-" + i + "-examinationbodyid").selectedIndex = 0;
                document.getElementById("csecqualification-" + i + "-subjectid").selectedIndex = 0;
                document.getElementById("csecqualification-" + i + "-examinationproficiencytypeid").selectedIndex = 0;
                document.getElementById("csecqualification-" + i + "-year").selectedIndex = 0;
                document.getElementById("csecqualification-" + i + "-examinationgradeid").selectedIndex = 0;
                
                
                //Reveals initialized fileds
                document.getElementById("save-new-certifcates").style.display = "block";        //reveals 'save' button
                document.getElementById("qualification[" + i + "]").style.display = "block";
                found = true;
            }
            i++;        
        }
    }   
}


/**
 * Remove a CsecQualification Record
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 17/03/2016
 * Date Lat Modified: 17/03/2016
 */
function removeNewCertificate()
{
    var found = false;
    var status = null;
    var record_count_array = document.getElementsByName("record_count");
    var record_count = parseInt(record_count_array[0].value);
    var qual_limit_array = document.getElementsByName("qual_limit");
    var qual_limit = parseInt(qual_limit_array[0].value);
    
    var i = record_count;
    
    var firstStatus = document.getElementById("qualification[" + record_count + "]").style.display;
    
    if (firstStatus == "none")
    {
        $('#modal-no-more-qualifications').modal('show');       
    }
    else
    {
        //Exception case has to be used when record limit has been reached.
        var lastStatus = document.getElementById("qualification[" + (qual_limit-1) + "]").style.display;
        if (lastStatus == "block")          //if record limit has been reached
        {
            //alter record in some manner for identification by action to omit saving
            document.getElementById("csecqualification-" + (qual_limit-1) + "-cseccentreid").selectedIndex = 0;
            document.getElementById("csecqualification-" + (qual_limit-1) + "-candidatenumber").value = "";
            document.getElementById("csecqualification-" + (qual_limit-1) + "-examinationbodyid").selectedIndex = 0;
            document.getElementById("csecqualification-" + (qual_limit-1) + "-subjectid").selectedIndex = 0;
            document.getElementById("csecqualification-" + (qual_limit-1) + "-examinationproficiencytypeid").selectedIndex = 0;
            document.getElementById("csecqualification-" + (qual_limit-1) + "-year").selectedIndex = 0;
            document.getElementById("csecqualification-" + (qual_limit-1) + "-examinationgradeid").selectedIndex = 0;

            //hide what was the last valid record
            document.getElementById("qualification[" + (qual_limit-1) + "]").style.display="none";
        }
        else                                //When displayed records are between indeexes 0 and limit-1
        {
            while (i < qual_limit && found == false)
            {
                status = document.getElementById("qualification[" + i + "]").style.display;
                if (status == "none"){
                    var j = i -1;

                    //alter record in some manner for identification by action to omit saving
                    document.getElementById("csecqualification-" + j + "-cseccentreid").selectedIndex = 0;
                    document.getElementById("csecqualification-" + j + "-candidatenumber").value = "";
                    document.getElementById("csecqualification-" + j + "-examinationbodyid").selectedIndex = 0;
                    document.getElementById("csecqualification-" + j + "-subjectid").selectedIndex = 0;
                    document.getElementById("csecqualification-" + j + "-examinationproficiencytypeid").selectedIndex = 0;
                    document.getElementById("csecqualification-" + j + "-year").selectedIndex = 0;
                    document.getElementById("csecqualification-" + j + "-examinationgradeid").selectedIndex = 0;
                    
                    //hide what was the last valid record
                    document.getElementById("qualification[" + j + "]").style.display="none";

                    found = true;
                }
                i++;
            }
        }
        
        var updatedFirstStatus = document.getElementById("qualification[" + record_count + "]").style.display;
        if (updatedFirstStatus == "none")
        {
            document.getElementById("save-new-certifcates").style.display = "none";
        }
        
    }
}



/**
 * Populates fields with dummy data to trick clientside validation [these recrods are not saved to database]
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 17/03/2016
 * Date Last Modified: 17/03/2016
 */
function generateQualificationBlanks()
{
    var record_count_array = document.getElementsByName("record_count");
    var record_count = parseInt(record_count_array[0].value);
    var qual_limit_array = document.getElementsByName("qual_limit");
    var qual_limit = parseInt(qual_limit_array[0].value);
    
    var i;
    for (i = record_count ; i < qual_limit ; i++)
    {
        var e1 = document.getElementById("csecqualification-" + i + "-cseccentreid");
        var cseccentreid = e1.options[e1.selectedIndex].value;
        
        var e2 = document.getElementById("csecqualification-" + i + "-examinationbodyid");
        var examinationbodyid = e2.options[e2.selectedIndex].value;
        
        var e3 = document.getElementById("csecqualification-" + i + "-subjectid");
        var subjectid = e3.options[e3.selectedIndex].value;
        
        var e4 = document.getElementById("csecqualification-" + i + "-examinationproficiencytypeid");
        var examinationproficiencytypeid = e4.options[e4.selectedIndex].value;
        
        var e5 = document.getElementById("csecqualification-" + i + "-examinationgradeid");
        var examinationgradeid = e5.options[e5.selectedIndex].value;
        
        var candidatenumber = document.getElementById("csecqualification-" + i + "-candidatenumber").value;
        
        var e6 = document.getElementById("csecqualification-" + i + "-year");
        var year = e6.options[e6.selectedIndex].value;
    
        //If model is untouched them it must 'be dummified'
        if (
            (cseccentreid == null || cseccentreid == "")
            && (examinationbodyid == null || examinationbodyid == "")
            && (subjectid == null || subjectid == "")
            && (examinationproficiencytypeid == null || examinationproficiencytypeid == "")
            && (examinationgradeid == null || examinationgradeid == "" )
            && (candidatenumber == null || candidatenumber == "")
            && (year == null || year == "")
            
        ){
            //Sets dummy records
            document.getElementById("csecqualification-" + i + "-cseccentreid").selectedIndex = 1;
            document.getElementById("csecqualification-" + i + "-candidatenumber").value = "00000";
            document.getElementById("csecqualification-" + i + "-examinationbodyid").selectedIndex = 1;
            
            var optn1 = document.createElement("OPTION");
            optn1.value = "1";      //must correspond to valid subjectid
            optn1.text = "Mathematics";
            document.getElementById("csecqualification-" + i + "-subjectid").options.add(optn1);
            document.getElementById("csecqualification-" + i + "-subjectid").selectedIndex = 1;
            
            var optn2 = document.createElement("OPTION");
            optn2.value = "1";      //must correspond to valid examinationproficiencytypeid
            optn2.text = "General";
            document.getElementById("csecqualification-" + i + "-examinationproficiencytypeid").options.add(optn2);
            document.getElementById("csecqualification-" + i + "-examinationproficiencytypeid").selectedIndex = 1;
            
            var optn3 = document.createElement("OPTION");
            optn3.value = "1";      //must correspond to valid examinationproficiencytypeid
            optn3.text = "I";
            document.getElementById("csecqualification-" + i + "-examinationgradeid").options.add(optn3);
            document.getElementById("csecqualification-" + i + "-examinationgradeid").selectedIndex = 1;
            
            //last year
            document.getElementById("qualification-" + i + "-year").selectedIndex = 47;
        }
    }
}
