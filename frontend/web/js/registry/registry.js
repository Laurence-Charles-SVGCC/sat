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


