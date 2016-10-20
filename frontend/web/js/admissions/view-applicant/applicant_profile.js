/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function toggleFlagControls()
{
    var choice = document.getElementsByName('flag_status');
    if (choice[0].checked == true)           //if "Yes"
    {   
        document.getElementById("flag-controls").style.display = "block";       
    } 
    else          //if "No"
    {         
        document.getElementById("flag-controls").style.display = "none";   
    }
}


