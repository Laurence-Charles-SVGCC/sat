/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function toggleGradesBatchTypeDiv()
{
    var option = document.getElementById('grades_batch_type');
    
    document.getElementById("grades_level").selectedIndex = 0;
    document.getElementById("grades_subject_type").selectedIndex = 0;
    document.getElementById("grades_subject").selectedIndex = 0;
    document.getElementById("grades_year").selectedIndex = 0;
    document.getElementById("grades_term").selectedIndex = 0;
    
    if (option.selectedIndex == 0)  
    {
         document.getElementById("grades-level-div").style.display = "none";
        document.getElementById("grades-subject-type-div").style.display = "none";
        document.getElementById("grades-subject-div").style.display = "none";
        document.getElementById("grades-year-div").style.display = "none";
        document.getElementById("grades-term-div").style.display = "none";
        document.getElementById("grades-submit-button").style.display = "none";
    }
    else
    {
        document.getElementById("grades-level-div").style.display = "block";
        document.getElementById("grades-subject-type-div").style.display = "none";
        document.getElementById("grades-subject-div").style.display = "none";
        document.getElementById("grades-year-div").style.display = "none";
        document.getElementById("grades-term-div").style.display = "none";
        document.getElementById("grades-submit-button").style.display = "none";
    }
}


function toggleGradesLevelDiv()
{
    var option = document.getElementById('grades_level');
    
    document.getElementById("grades_subject_type").selectedIndex = 0;
    document.getElementById("grades_subject").selectedIndex = 0;
    document.getElementById("grades_year").selectedIndex = 0;
    document.getElementById("grades_term").selectedIndex = 0;
    
    if (option.selectedIndex == 0)  
    {
        document.getElementById("grades-subject-type-div").style.display = "none";
        document.getElementById("grades-subject-div").style.display = "none";
        document.getElementById("grades-year-div").style.display = "none";
        document.getElementById("grades-term-div").style.display = "none";
        document.getElementById("grades-submit-button").style.display = "none";
    }
    else
    {
        document.getElementById("grades-subject-type-div").style.display = "block";
        document.getElementById("grades-subject-div").style.display = "none";
        document.getElementById("grades-year-div").style.display = "none";
        document.getElementById("grades-term-div").style.display = "none";
        document.getElementById("grades-submit-button").style.display = "none";
    }
}


function toggleGradesSubjectTypeDiv()
{
    var option = document.getElementById('grades_subject_type');
    
    document.getElementById("grades_subject").selectedIndex = 0;
    document.getElementById("grades_year").selectedIndex = 0;
    document.getElementById("grades_term").selectedIndex = 0;
    
    if (option.selectedIndex == 0)  
    {
        document.getElementById("grades-subject-div").style.display = "none";
        document.getElementById("grades-year-div").style.display = "none";
        document.getElementById("grades-term-div").style.display = "none";
        document.getElementById("grades-submit-button").style.display = "none";
    }
    else
    {
        document.getElementById("grades-subject-div").style.display = "block";
        document.getElementById("grades-year-div").style.display = "none";
        document.getElementById("grades-term-div").style.display = "none";
        document.getElementById("grades-submit-button").style.display = "none";
    }
}


function toggleGradesSubjectDiv()
{
    var option = document.getElementById('grades_subject');
    
    document.getElementById("grades_year").selectedIndex = 0;
    document.getElementById("grades_term").selectedIndex = 0;
    
    if (option.selectedIndex == 0)  
    {
        document.getElementById("grades-year-div").style.display = "none";
        document.getElementById("grades-term-div").style.display = "none";
        document.getElementById("grades-submit-button").style.display = "none";
    }
    else
    {
        document.getElementById("grades-year-div").style.display = "block";
        document.getElementById("grades-term-div").style.display = "none";
        document.getElementById("grades-submit-button").style.display = "none";
    }
}


function toggleGradesYearDiv()
{
    var option = document.getElementById('grades_year');
    
    document.getElementById("grades_term").selectedIndex = 0;
    
    if (option.selectedIndex == 0)  
    {
        document.getElementById("grades-term-div").style.display = "none";
        document.getElementById("grades-submit-button").style.display = "none";
    }
    else
    {
        document.getElementById("grades-term-div").style.display = "block";
        document.getElementById("grades-submit-button").style.display = "none";
    }
}


function toggleGradesTermDiv()
{
    var option = document.getElementById('grades_term');
    
    if (option.selectedIndex == 0)  
    {
        document.getElementById("grades-submit-button").style.display = "none";
    }
    else
    {
        document.getElementById("grades-submit-button").style.display = "block";
    }
}



/**
 * Load appropriate data into Subjects dropdown lists
 * 
 * @param {type} e
 * @returns {Boolean}
 * 
 * Author: Laurence Charles
 * Date Created: 12/07/2016
 * Date Last Modified: 12/07/2016
 */
function PrepareGradesSubjectListing(e)
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
            if(found==1)
            {
                //Remove the options from subject dropdown list except 'select' option
                 var menu = document.getElementById('grades_subject');
                 
                for(j=menu.options.length-1; j>0; j--)
                {
                    menu.options.remove(j);
                }

                //Adding new options
                for (i=0;i<myarray.subjects.length;i++)
                {
                    var optn1 = document.createElement("OPTION");
                    optn1.value = myarray.subjects[i].id; 
                    optn1.text = myarray.subjects[i].name;
                    menu.options.add(optn1);
                }
            }
        }
    } // end of function stateck
    
    
    /*******************Gets parameters for query ********************/
    var subject_type = document.getElementsByName('grades_subject_type_field');
    var subjecttypeid = subject_type[0].value;
   
    var baseUrl = document.getElementsByName('grades_baseUrl')[0].value;
    if (baseUrl.search("localhost")!=-1)
        url = "http://localhost:80/sat_dev/frontend/web/index.php?r=subcomponents%2Flegacy%2Fsubjects%2Fget-listing&";
    else
        url = "http://www.svgcc.vc/subdomains/sat/frontend/web/index.php?r=subcomponents%2Flegacy%2Fsubjects%2Fget-listing&";
   
    url+= "subjecttypeid=" + subjecttypeid;
    
    httpxml.onreadystatechange=stateck;
   
    httpxml.open("GET", url, true);
    httpxml.send(null);
}


/**
 * Load appropriate data into Subjects dropdown lists
 * 
 * @param {type} e
 * @returns {Boolean}
 * 
 * Author: Laurence Charles
 * Date Created: 12/07/2016
 * Date Last Modified: 12/07/2016
 */
function PrepareGradesTermListing(e)
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
            if(found==1)
            {
                //Remove the options from subject dropdown list except 'select' option
                 var menu = document.getElementById('grades_term');
                 
                for(j=menu.options.length-1; j>0; j--)
                {
                    menu.options.remove(j);
                }

                //Adding new options
                for (i=0;i<myarray.terms.length;i++)
                {
                    var optn1 = document.createElement("OPTION");
                    optn1.value = myarray.terms[i].id; 
                    optn1.text = myarray.terms[i].name;
                    menu.options.add(optn1);
                }
            }
        }
    } // end of function stateck
    
    
    /*******************Gets parameters for query ********************/
    var year = document.getElementsByName('grades_year_field');
    var yearid = year[0].value;
   
    var baseUrl = document.getElementsByName('grades_baseUrl')[0].value;
    if (baseUrl.search("localhost")!=-1)
        url = "http://localhost:80/sat_dev/frontend/web/index.php?r=subcomponents%2Flegacy%2Fyear%2Fget-listing&";
    else
        url = "http://www.svgcc.vc/subdomains/sat/frontend/web/index.php?r=subcomponents%2Flegacy%2Fyear%2Fget-listing&";
   
    url+= "yearid=" + yearid;
    
    httpxml.onreadystatechange=stateck;
   
    httpxml.open("GET", url, true);
    httpxml.send(null);
}

