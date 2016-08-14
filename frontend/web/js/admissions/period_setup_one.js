/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function displayPeriodType()
{
    var divisionid = document.getElementById("applicationperiod-divisionid").selectedIndex;
    
    if ( divisionid == 0)
    {
        if (document.getElementById("new-year-options").style.display == "block")
         {
             document.getElementById("academicyear-title").value = null;
             document.getElementById("academicyear-startdate").value = null;
             document.getElementById("academicyear-enddate").value = null;
         }
         
        document.getElementById("applicationperiod-applicationperiodtypeid").selectedIndex = 0;
        document.getElementById("applicationperiodtypeid-field").style.display = "none";  
        
        document.getElementById("application-period-exists-alert").style.display = "none";
        document.getElementById("new-year-options").style.display = "none";
        document.getElementById("buttons").style.display = "none";
         
         
    }
    else
    {
        if (document.getElementById("new-year-options").style.display == "block")
         {
             document.getElementById("academicyear-title").value = null;
             document.getElementById("academicyear-startdate").value = null;
             document.getElementById("academicyear-enddate").value = null;
         }
         
        document.getElementById("applicationperiod-applicationperiodtypeid").selectedIndex = 0;
        document.getElementById("applicationperiodtypeid-field").style.display = "block";  
        
        document.getElementById("application-period-exists-alert").style.display = "none";
        document.getElementById("new-year-options").style.display = "none";
        document.getElementById("buttons").style.display = "none";
    }
}
    
    
     /**
 * Load appropriate data into Subject, Proficiency and Grade dropdown lists
 * 
 * @param {type} e
 * @returns {Boolean}
 * 
 * Author: Laurence Charles
 * Date Created: 12/08/2016
 * Date Last Modified: 12/08/2016
 */
function calculateApplicantIntent(e)
{
    var httpxml;
    try
    {
        // Firefox, Opera 8.0+, Safari
        httpxml=new XMLHttpRequest();
    }
    catch (e)
    {
        // Internet Explorer
        try
        {
            httpxml=new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (e)
        {
            try
            {
                httpxml=new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (e)
            {
                alert("Your browser does not support AJAX!");
                return false;
            }
        }
    }
    
    function stateck() 
    {
        if(httpxml.readyState==4)
        {
            var myarray = JSON.parse(httpxml.responseText); 
            
            var academicYearExists = myarray.academicYearExists;
            var applicationPeriodExists = myarray.applicationPeriodExists;
            
            if (document.getElementById("new-year-options").style.display == "block")
            {
                document.getElementById("academicyear-title").value = null;
                document.getElementById("academicyear-startdate").value = null;
                document.getElementById("academicyear-enddate").value = null;
            }
         
            if ( academicYearExists == 1 && applicationPeriodExists == 1 )
            {
                document.getElementById("application-period-exists-alert").style.display = "block";
                document.getElementById("new-year-options").style.display = "none";
                document.getElementById("buttons").style.display = "none";
            }
            else if ( academicYearExists == 0 && applicationPeriodExists == 0 )
            {
                document.getElementById("application-period-exists-alert").style.display = "none";
                document.getElementById("new-year-options").style.display = "block";
                document.getElementById("buttons").style.display = "block";
            }
            else if ( academicYearExists == 1 && applicationPeriodExists == 0 )
            {
                document.getElementById("application-period-exists-alert").style.display = "none";
                document.getElementById("new-year-options").style.display = "none";
                document.getElementById("buttons").style.display = "block";
            }
            
        }
    } // end of function stateck
    
    
    /*******************Gets parameters for query ********************/
    var divisionid = document.getElementById('applicationperiod-divisionid').value;
    var applicationperiodtypeid = document.getElementById('applicationperiod-applicationperiodtypeid').value;
    
    var applicantintentid = 0;
    
    if ((divisionid == 4 || divisionid == 5) && applicationperiodtypeid == 1 )
    {
        applicantintentid = 1;
    }
    else if (divisionid == 4 && applicationperiodtypeid == 2 )
    {
       applicantintentid = 2;
    }
    else if (divisionid == 5 && applicationperiodtypeid == 2 )
    {
       applicantintentid = 3;
    }
    else if (divisionid == 6 && applicationperiodtypeid == 1 )
    {
       applicantintentid = 4;
    }
    else if (divisionid == 6 && applicationperiodtypeid == 2 )
    {
       applicantintentid = 5;
    }
    else if (divisionid == 7 && applicationperiodtypeid == 1 )
    {
       applicantintentid = 6;
    }
    else if (divisionid == 7 && applicationperiodtypeid == 1 )
    {
       applicantintentid = 7;
    }
   
    
    var baseUrl = document.getElementsByName('applicationPeriodCreation_baseUrl')[0].value;
    if (baseUrl.search("localhost")!=-1)
        url = "http://localhost:80/sat_dev/frontend/web/index.php?r=subcomponents%2Fadmissions%2Fadmissions%2Fprocess-applicant-intentid&";
    else
//        url = "http://www.svgcc.vc/subdomains/sat/frontend/web/index.php?r=subcomponents%2Fadmissions%2admissions%2Fprocess-applicant-intentid&";
        url = "http://sat.svgcc.vc/index.php?r=subcomponents%2Fadmissions%2Fadmissions%2Fprocess-applicant-intentid&";



//Implementation for live server
//    var url = "http://www.svgcc.vc/subdomains/sat/frontend/web/index.php?r=subcomponents%2Fadmissions%2Fverify-applicants%2Fexamination-body-dependants&";
    
    //For local implementation
//    var url="http://localhost:80/sat_dev/frontend/web/index.php?r=subcomponents%2Fadmissions%2Freports%2Fget-listing&";
    
    
    url+= "divisionid=" + divisionid;
    
    url+= "&applicationperiodtypeid="+ applicationperiodtypeid;
    
    url+= "&applicantintentid="+ applicantintentid;
    
    httpxml.onreadystatechange=stateck;
   
    httpxml.open("GET",url,true);
    httpxml.send(null);
}


function generateAcademicYearBlanks()
{
    document.getElementById("academicyear-title").value = "default";
    document.getElementById("academicyear-startdate").value = "1990-01-01";
}
    


