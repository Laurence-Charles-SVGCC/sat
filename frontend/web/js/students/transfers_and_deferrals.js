/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function checkTransferOrDeferral()
{
    var search_by = document.getElementsByName('listing_category');
    if (search_by[0].checked == true)   //if by division
    {
        document.getElementById("transfers").style.display = "block";    
        document.getElementById("deferrals").style.display = "none";
    }    
    else if (search_by[1].checked == true)           //if by studentid
    {
        document.getElementById("transfers").style.display = "none"    
        document.getElementById("deferrals").style.display = "block";
    }
}


