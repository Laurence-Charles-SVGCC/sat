/* 
 * Contains all author created Javascript functions for the "Home" view.
 * Author: Laurence Charles
 * Date Created: 05/12/2015
 */


/**
 * Handles search method functinonality.
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 05/12/2015
 * Date Last Modified: 05/12/2015
 */
function checkSearchMethod()
{
//    alert ("oye");
    var search_by = document.getElementsByName('search_method');
    if (search_by[0].checked == true)   //if by division
    {
        if (document.getElementsByName("studentid_field")[0] != null)
            document.getElementsByName("studentid_field")[0].value = "";

        if (document.getElementsByName("firstname_field")[0] != null)
            document.getElementsByName("firstname_field")[0].value = "";
 
        if (document.getElementsByName("lastname_field")[0] != null)
            document.getElementsByName("lastname_field")[0].value = "";
          
        document.getElementById("by_division").style.display = "block";    
        document.getElementById("by_studentid").style.display = "none";
        document.getElementById("by_studentname").style.display = "none";
    }    
    else if (search_by[1].checked == true)           //if by studentid
    {      
        if (document.getElementsByName("division_choice")[0] != null)
            document.getElementsByName("division_choice")[0].selectedIndex = 0;

        if (document.getElementsByName("firstname_field")[0] != null)
            document.getElementsByName("firstname_field")[0].value = "";
 
        if (document.getElementsByName("lastname_field")[0] != null)
            document.getElementsByName("lastname_field")[0].value = "";    
        
        document.getElementById("by_division").style.display = "none";       
        document.getElementById("by_studentid").style.display = "block";
        document.getElementById("by_studentname").style.display = "none";
    } 
    else if (search_by[2].checked == true)           //if student name
    {         
        if (document.getElementsByName("division_choice")[0] != null)
            document.getElementsByName("division_choice")[0].selectedIndex = 0;
        
        if (document.getElementsByName("studentid_field")[0] != null)
            document.getElementsByName("studentid_field")[0].value = "";
               
        document.getElementById("by_division").style.display = "none";    
        document.getElementById("by_studentid").style.display = "none"; 
        document.getElementById("by_studentname").style.display = "block";
    } 
}
