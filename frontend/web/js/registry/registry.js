/* 
 * Author: Laurence Charles
 * Date: 25/04/2016
 */


function toggleAwardType()
{
    var award_type = document.getElementById("award-awardtypeid").selectedIndex;
    
    if(award_type == 1)
    {
        document.getElementById("award-semester").style.display = "block"; 
        document.getElementById("award-year").style.display = "none";
    }
    else if(award_type == 2)
    {
        document.getElementById("award-semester").style.display = "none"; 
        document.getElementById("award-year").style.display = "block"; 
    }
}


function toggleAwardScope()
{
    var award_scope = document.getElementById("award-awardscopeid").selectedIndex;
    
    if(award_scope == 2)
    {
        document.getElementById("award-division").style.display = "block"; 
        document.getElementById("award-department").style.display = "none";
        document.getElementById("award-programme").style.display = "none"; 
        document.getElementById("award-subject").style.display = "none";
    }
    else if(award_scope == 3)
    {
        document.getElementById("award-division").style.display = "none"; 
        document.getElementById("award-department").style.display = "block";
        document.getElementById("award-programme").style.display = "none"; 
        document.getElementById("award-subject").style.display = "none";
    }
    else if(award_scope == 4)
    {
        document.getElementById("award-division").style.display = "none"; 
        document.getElementById("award-department").style.display = "none";
        document.getElementById("award-programme").style.display = "block"; 
        document.getElementById("award-subject").style.display = "none";
    }
    else if(award_scope == 5)
    {
        document.getElementById("award-division").style.display = "none"; 
        document.getElementById("award-department").style.display = "none";
        document.getElementById("award-programme").style.display = "none"; 
        document.getElementById("award-subject").style.display = "block";
    }
}


