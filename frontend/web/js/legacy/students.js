/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function showCreationMode()
{
    var mode= document.getElementsByName('student_creation_mode');
    if (mode[0].checked == true)           //if single student
    {
        document.getElementById("student-count-field").selectedIndex = 0;
        document.getElementById("single-mode").style.display = "block";       
        document.getElementById("batch-mode").style.display = "none";
    }  
    else
    {
        document.getElementById("student-count-field").selectedIndex = 0;
        document.getElementById("single-mode").style.display = "none";       
        document.getElementById("batch-mode").style.display = "block";
    }
}


function showBatchCreationButton()
{
    var button = document.getElementById("student-count-field").selectedIndex;
    if (button != 0)
       document.getElementById('batch-button').style.display = "block"; 
   else
       document.getElementById('batch-button').style.display = "none"; 
}

    

