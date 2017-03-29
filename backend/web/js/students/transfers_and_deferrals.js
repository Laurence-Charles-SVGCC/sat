/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function checkTransferOrDeferral()
{
    var search_by = document.getElementsByName('listing_category');
    if (search_by[0].checked == true)   //if by transfer
    {
        document.getElementById("transfers").style.display = "block";    
        document.getElementById("pre-registration-deferrals").style.display = "none";
        document.getElementById("post-registration-deferrals").style.display = "none";
    }    
    else if (search_by[1].checked == true)           //if by pre-registration deferral
    {
        document.getElementById("transfers").style.display = "none"    
        document.getElementById("pre-registration-deferrals").style.display = "block";
        document.getElementById("post-registration-deferrals").style.display = "none";
    }
    else if (search_by[2].checked == true)           //if by post-registration deferral
    {
        document.getElementById("transfers").style.display = "none"    
        document.getElementById("pre-registration-deferrals").style.display = "none";
        document.getElementById("post-registration-deferrals").style.display = "block";
    }
}


