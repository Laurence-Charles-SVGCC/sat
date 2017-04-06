/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Toggle input capture widgets for user search
 * 
 * @returns {undefined}
 */
function checkUserSearchCriteria()
{
//    alert ("oye");
    var search_by = document.getElementsByName('search_type');
    
    if (document.getElementsByName("fname_field")[0] != null)
        document.getElementsByName("fname_field")[0].value = "";

    if (document.getElementsByName("lname_field")[0] != null)
        document.getElementsByName("lname_field")[0].value = "";

     if (document.getElementsByName("username_field")[0] != null)
        document.getElementsByName("username_field")[0].value = "";

     if (document.getElementsByName("personid_field")[0] != null)
        document.getElementsByName("personid_field")[0].value = "";
        
    if (search_by[0].checked == true)   //if by name
    {
        document.getElementById("by_name").style.display = "block";    
        document.getElementById("by_username").style.display = "none";
        document.getElementById("by_personid").style.display = "none";
    }    
    else if (search_by[1].checked == true)           //if by username
    {      
        document.getElementById("by_name").style.display = "none";    
        document.getElementById("by_username").style.display = "block";
        document.getElementById("by_personid").style.display = "none";
    } 
    else if (search_by[2].checked == true)           //if by personid
    {        
        document.getElementById("by_name").style.display = "none";    
        document.getElementById("by_username").style.display = "none";
        document.getElementById("by_personid").style.display = "block";
    } 
}


