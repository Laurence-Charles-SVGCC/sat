/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function toggleCordinatorType()
{
    //resets all dropdownlist selection to 0
    document.getElementById('cordinator-cordinatortypeid').selectedIndex=0;
    document.getElementById('department_field').selectedIndex=0;
    document.getElementById('academic_offering_field').selectedIndex=0;
    document.getElementById('course_offering_field').selectedIndex=0;
    document.getElementById('cape_subject_field').selectedIndex=0;
    
    //hide all fields dependant of the election of 'cordinator-cordinatortypeid' field
    document.getElementById("cordinator-department").style.display = "none"; 
    document.getElementById("cordinator-programme").style.display = "none"; 
    document.getElementById("cordinator-course").style.display = "none"; 
    document.getElementById("cordinator-subject").style.display = "none"; 
    
    var academic_year_index = document.getElementById("cordinator-academicyearid").selectedIndex
    if (academic_year_index==0)
    {
        document.getElementById("cordinator-cordinatortype").style.display = "none"; 
    }
    else
    {
         document.getElementById("cordinator-cordinatortype").style.display = "block"; 
    }
    
}



function toggleDetails()
{
    var cordinator_type_index = document.getElementById("cordinator-cordinatortypeid").selectedIndex
    if (cordinator_type_index==1)
    {
        document.getElementById("cordinator-department").style.display = "block"; 
        document.getElementById("cordinator-programme").style.display = "none"; 
        document.getElementById("cordinator-course").style.display = "none"; 
        document.getElementById("cordinator-subject").style.display = "none"; 
    }
    else if(cordinator_type_index==2)
    {
        document.getElementById("cordinator-department").style.display = "none"; 
        document.getElementById("cordinator-programme").style.display = "block"; 
        document.getElementById("cordinator-course").style.display = "none"; 
        document.getElementById("cordinator-subject").style.display = "none";
    }
    else if(cordinator_type_index==3)
    {
        document.getElementById("cordinator-department").style.display = "none"; 
        document.getElementById("cordinator-programme").style.display = "none"; 
        document.getElementById("cordinator-course").style.display = "block"; 
        document.getElementById("cordinator-subject").style.display = "none";
    }
    else if(cordinator_type_index==4)
    {
        document.getElementById("cordinator-department").style.display = "none"; 
        document.getElementById("cordinator-programme").style.display = "none"; 
        document.getElementById("cordinator-course").style.display = "none"; 
        document.getElementById("cordinator-subject").style.display = "block";
    }
    else
    {
        document.getElementById("cordinator-department").style.display = "none"; 
        document.getElementById("cordinator-programme").style.display = "none"; 
        document.getElementById("cordinator-course").style.display = "none"; 
        document.getElementById("cordinator-subject").style.display = "none"; 
    }
}




/**
 * Load appropriate academicofferings, courseofferings and capesubjects
 * based on the academic-year selected by user
 * 
 * @param {type} e
 * @returns {Boolean}
 * 
 * Author: Laurence Charles
 * Date Created: 24/06/2016
 * Date Last Modified: 24/06/2016
 */
function respondToAcademicYearSelection(e)
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
            
            var found = myarray.found;
            if(found == 1)
            {
                var listingtype = myarray.listingtype;

               if (listingtype == 'academic_offering')
                {
                    var menu = document.getElementById('academic_offering_field');
                }
                else if (listingtype == 'course_offering')
                {
                    var menu = document.getElementById('course_offering_field');
                }
                else if (listingtype == 'cape_subject')
                {
                    var menu = document.getElementById('cape_subject_field');
                }

                //Remove the options from 2nd dropdown list except 'select' option
                for(j=menu.options.length-1; j>0; j--)
                {
                    menu.options.remove(j);
                }

                //Adding new options
                for (i=0;i<myarray.listing.length;i++)
                {
                    var optn1 = document.createElement("OPTION");
                    optn1.value = myarray.listing[i].id; 
                    optn1.text = myarray.listing[i].name;
                    menu.options.add(optn1);
                }
            }
        }
    } // end of function stateck
    
    
    /*******************Gets parameters for query ********************/
    var academicyearid = document.getElementById('cordinator-academicyearid').value;
    var cordinatortypeid = document.getElementById('cordinator-cordinatortypeid').value;
    
    if(cordinatortypeid == 1)
        listing_type = "department";
    else if(cordinatortypeid == 2)
        listing_type = "academic_offering";
    else if(cordinatortypeid == 3)
        listing_type = "course_offering";
    else if(cordinatortypeid == 4)
        listing_type = "cape_subject";
    
    
    var baseUrl = document.getElementsByName('cordinator_assignment_baseUrl')[0].value;
    if (baseUrl.search("localhost")!=-1)
        url = "http://localhost:80/sat_dev/frontend/web/index.php?r=subcomponents%2Fprogrammes%2Fcordinator%2Fget-academic-year-listings&";
    else
        url = "http://www.sat.svgcc.vc/subdomains/sat/frontend/web/index.php?r=subcomponents%2Fprogrammes%2Fcordinator%2Fget-academic-year-listings&";
    
    url+= "listingtype=" + listing_type;
    
    url+= "&academicyearid=" + academicyearid;
    
    httpxml.onreadystatechange=stateck;
   
    httpxml.open("GET",url,true);
    httpxml.send(null);
}
