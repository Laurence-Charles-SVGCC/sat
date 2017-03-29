/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function checkVerifyDocuments()
{
    var choice = document.getElementsByName('verify-documents-choice');
    if (choice[0].checked == true)           //if "Yes"
    {   
        document.getElementById("go-to-verify-documents").style.display = "block";       
       } 
    else          //if "No"
    {         
        document.getElementById("go-to-verify-documents").style.display = "none";   
    }
}


