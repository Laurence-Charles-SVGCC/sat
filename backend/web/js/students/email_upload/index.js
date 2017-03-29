/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function toggleEmailActions()
{
    var action = document.getElementsByName('email-action');
    
    if (action[0].checked == true)           //if 'uplaod new file' selected
    {
        document.getElementById('upload-new-file').style.display = "block"; 
        document.getElementById('process-file').style.display = "none"; 
    }
    else if (action[1].checked == true)           //if 'process file' selected
    {
        document.getElementById('upload-new-file').style.display = "none"; 
        document.getElementById('process-file').style.display = "block"; 
    }
    else
    {
        document.getElementById('upload-new-file').style.display = "none"; 
        document.getElementById('process-file').style.display = "none"; 
    }
    
    
    
}

