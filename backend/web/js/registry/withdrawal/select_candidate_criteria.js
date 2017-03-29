/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function toggleSubmitButton()
{
    var period = document.getElementById('period_id_field').selectedIndex;
    
    if(period != 0)
    {
        document.getElementById('withdrawal-submit-button').style.display = "block"; 
    }
    else
    {
        document.getElementById('withdrawal-submit-button').style.display = "none"; 
    }
}


