function toggleLevelDiv()
{
    var option = document.getElementById('level');
    
    document.getElementById("subject_type").selectedIndex = 0;
    document.getElementById("subject").selectedIndex = 0;
    document.getElementById("year").selectedIndex = 0;
    document.getElementById("term").selectedIndex = 0;
    
    if (option.selectedIndex == 0)  
    {
        document.getElementById("subject-type-div").style.display = "none";
        document.getElementById("subject-div").style.display = "none";
        document.getElementById("year-div").style.display = "none";
        document.getElementById("term-div").style.display = "none";
        document.getElementById("submit-button").style.display = "none";
    }
    else
    {
        document.getElementById("subject-type-div").style.display = "block";
        document.getElementById("subject-div").style.display = "none";
        document.getElementById("year-div").style.display = "none";
        document.getElementById("term-div").style.display = "none";
        document.getElementById("submit-button").style.display = "none";
    }
}


function toggleSubjectTypeDiv()
{
    var option = document.getElementById('subject_type');
    
    document.getElementById("subject").selectedIndex = 0;
    document.getElementById("year").selectedIndex = 0;
    document.getElementById("term").selectedIndex = 0;
    
    if (option.selectedIndex == 0)  
    {
        document.getElementById("subject-div").style.display = "none";
        document.getElementById("year-div").style.display = "none";
        document.getElementById("term-div").style.display = "none";
        document.getElementById("submit-button").style.display = "none";
    }
    else
    {
        document.getElementById("subject-div").style.display = "block";
        document.getElementById("year-div").style.display = "none";
        document.getElementById("term-div").style.display = "none";
        document.getElementById("submit-button").style.display = "none";
    }
}


function toggleSubjectDiv()
{
    var option = document.getElementById('subject');
    
    document.getElementById("year").selectedIndex = 0;
    document.getElementById("term").selectedIndex = 0;
    
    if (option.selectedIndex == 0)  
    {
        document.getElementById("year-div").style.display = "none";
        document.getElementById("term-div").style.display = "none";
        document.getElementById("submit-button").style.display = "none";
    }
    else
    {
        document.getElementById("year-div").style.display = "block";
        document.getElementById("term-div").style.display = "none";
        document.getElementById("submit-button").style.display = "none";
    }
}


function toggleYearDiv()
{
    var option = document.getElementById('year');
    
    document.getElementById("term").selectedIndex = 0;
    
    if (option.selectedIndex == 0)  
    {
        document.getElementById("term-div").style.display = "none";
        document.getElementById("submit-button").style.display = "none";
    }
    else
    {
        document.getElementById("term-div").style.display = "block";
        document.getElementById("submit-button").style.display = "none";
    }
}


function toggleTermDiv()
{
    var option = document.getElementById('term');
    
    if (option.selectedIndex == 0)  
    {
        document.getElementById("submit-button").style.display = "none";
    }
    else
    {
        document.getElementById("submit-button").style.display = "block";
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
function PrepareSubjectListing(e)
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
                 var menu = document.getElementById('subject');
                 
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
    var subject_type = document.getElementsByName('subject_type_field');
    var subjecttypeid = subject_type[0].value;
   
    var baseUrl = document.getElementsByName('batch_baseUrl')[0].value;
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
function PrepareTermListing(e)
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
                 var menu = document.getElementById('term');
                 
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
    var year = document.getElementsByName('year_field');
    var yearid = year[0].value;
   
    var baseUrl = document.getElementsByName('batch_baseUrl')[0].value;
    if (baseUrl.search("localhost")!=-1)
        url = "http://localhost:80/sat_dev/frontend/web/index.php?r=subcomponents%2Flegacy%2Fyear%2Fget-listing&";
    else
        url = "http://www.svgcc.vc/subdomains/sat/frontend/web/index.php?r=subcomponents%2Flegacy%2Fyear%2Fget-listing&";
   
    url+= "yearid=" + yearid;
    
    httpxml.onreadystatechange=stateck;
   
    httpxml.open("GET", url, true);
    httpxml.send(null);
}















function toggleEnrollBatchTypeDiv()
{
    var option = document.getElementById('enroll_batch_type');
    
    document.getElementById("enroll_level").selectedIndex = 0;
    document.getElementById("enroll_subject_type").selectedIndex = 0;
    document.getElementById("enroll_subject").selectedIndex = 0;
    document.getElementById("enroll_year").selectedIndex = 0;
    document.getElementById("enroll_term").selectedIndex = 0;
    document.getElementById("enroll_student_count").selectedIndex = 0;
    
    if (option.selectedIndex == 0)  
    {
         document.getElementById("enroll-level-div").style.display = "none";
        document.getElementById("enroll-subject-type-div").style.display = "none";
        document.getElementById("enroll-subject-div").style.display = "none";
        document.getElementById("enroll-year-div").style.display = "none";
        document.getElementById("enroll-term-div").style.display = "none";
        document.getElementById("enroll-student-count-div").style.display = "none";
        document.getElementById("enroll-submit-button").style.display = "none";
    }
    else
    {
        document.getElementById("enroll-level-div").style.display = "block";
        document.getElementById("enroll-subject-type-div").style.display = "none";
        document.getElementById("enroll-subject-div").style.display = "none";
        document.getElementById("enroll-year-div").style.display = "none";
        document.getElementById("enroll-term-div").style.display = "none";
        document.getElementById("enroll-student-count-div").style.display = "none";
        document.getElementById("enroll-submit-button").style.display = "none";
    }
}


function toggleEnrollLevelDiv()
{
    var option = document.getElementById('enroll_level');
    
    document.getElementById("enroll_subject_type").selectedIndex = 0;
    document.getElementById("enroll_subject").selectedIndex = 0;
    document.getElementById("enroll_year").selectedIndex = 0;
    document.getElementById("enroll_term").selectedIndex = 0;
    document.getElementById("enroll_student_count").selectedIndex = 0;
    
    if (option.selectedIndex == 0)  
    {
        document.getElementById("enroll-subject-type-div").style.display = "none";
        document.getElementById("enroll-subject-div").style.display = "none";
        document.getElementById("enroll-year-div").style.display = "none";
        document.getElementById("enroll-term-div").style.display = "none";
        document.getElementById("enroll-student-count-div").style.display = "none";
        document.getElementById("enroll-submit-button").style.display = "none";
    }
    else
    {
        document.getElementById("enroll-subject-type-div").style.display = "block";
        document.getElementById("enroll-subject-div").style.display = "none";
        document.getElementById("enroll-year-div").style.display = "none";
        document.getElementById("enroll-term-div").style.display = "none";
        document.getElementById("enroll-student-count-div").style.display = "none";
        document.getElementById("enroll-submit-button").style.display = "none";
    }
}


function toggleEnrollSubjectTypeDiv()
{
    var option = document.getElementById('enroll_subject_type');
    
    document.getElementById("enroll_subject").selectedIndex = 0;
    document.getElementById("enroll_year").selectedIndex = 0;
    document.getElementById("enroll_term").selectedIndex = 0;
    document.getElementById("enroll_student_count").selectedIndex = 0;
    
    if (option.selectedIndex == 0)  
    {
        document.getElementById("enroll-subject-div").style.display = "none";
        document.getElementById("enroll-year-div").style.display = "none";
        document.getElementById("enroll-term-div").style.display = "none";
        document.getElementById("enroll-student-count-div").style.display = "none";
        document.getElementById("enroll-submit-button").style.display = "none";
    }
    else
    {
        document.getElementById("enroll-subject-div").style.display = "block";
        document.getElementById("enroll-year-div").style.display = "none";
        document.getElementById("enroll-term-div").style.display = "none";
        document.getElementById("enroll-student-count-div").style.display = "none";
        document.getElementById("enroll-submit-button").style.display = "none";
    }
}


function toggleEnrollSubjectDiv()
{
    var option = document.getElementById('enroll_subject');
    
    document.getElementById("enroll_year").selectedIndex = 0;
    document.getElementById("enroll_term").selectedIndex = 0;
    document.getElementById("enroll_student_count").selectedIndex = 0;
    
    if (option.selectedIndex == 0)  
    {
        document.getElementById("enroll-year-div").style.display = "none";
        document.getElementById("enroll-term-div").style.display = "none";
        document.getElementById("enroll-student-count-div").style.display = "none";
        document.getElementById("enroll-submit-button").style.display = "none";
    }
    else
    {
        document.getElementById("enroll-year-div").style.display = "block";
        document.getElementById("enroll-term-div").style.display = "none";
        document.getElementById("enroll-student-count-div").style.display = "none";
        document.getElementById("enroll-submit-button").style.display = "none";
    }
}


function toggleEnrollYearDiv()
{
    var option = document.getElementById('enroll_year');
    
    document.getElementById("enroll_term").selectedIndex = 0;
    document.getElementById("enroll_student_count").selectedIndex = 0;
    
    if (option.selectedIndex == 0)  
    {
        document.getElementById("enroll-term-div").style.display = "none";
        document.getElementById("enroll-student-count-div").style.display = "none";
        document.getElementById("enroll-submit-button").style.display = "none";
    }
    else
    {
        document.getElementById("enroll-term-div").style.display = "block";
        document.getElementById("enroll-student-count-div").style.display = "none";
        document.getElementById("enroll-submit-button").style.display = "none";
    }
}


function toggleEnrollTermDiv()
{
    var option = document.getElementById('enroll_term');
    
    document.getElementById("enroll_student_count").selectedIndex = 0;
    
    if (option.selectedIndex == 0)  
    {
        document.getElementById("enroll-student-count-div").style.display = "none";
        document.getElementById("enroll-submit-button").style.display = "none";
    }
    else
    {
        document.getElementById("enroll-student-count-div").style.display = "block";
        document.getElementById("enroll-submit-button").style.display = "none";
    }
}


function toggleEnrollStudentCountDiv()
{
    var option = document.getElementById('enroll_student_count');
    
    if (option.selectedIndex == 0)  
    {
        document.getElementById("enroll-submit-button").style.display = "none";
    }
    else
    {
        document.getElementById("enroll-submit-button").style.display = "block";
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
function PrepareEnrollSubjectListing(e)
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
                 var menu = document.getElementById('enroll_subject');
                 
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
    var subject_type = document.getElementsByName('enroll_subject_type_field');
    var subjecttypeid = subject_type[0].value;
   
    var baseUrl = document.getElementsByName('enroll_baseUrl')[0].value;
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
function PrepareEnrollTermListing(e)
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
                 var menu = document.getElementById('enroll_term');
                 
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
    var year = document.getElementsByName('enroll_year_field');
    var yearid = year[0].value;
   
    var baseUrl = document.getElementsByName('enroll_baseUrl')[0].value;
    if (baseUrl.search("localhost")!=-1)
        url = "http://localhost:80/sat_dev/frontend/web/index.php?r=subcomponents%2Flegacy%2Fyear%2Fget-listing&";
    else
        url = "http://www.svgcc.vc/subdomains/sat/frontend/web/index.php?r=subcomponents%2Flegacy%2Fyear%2Fget-listing&";
   
    url+= "yearid=" + yearid;
    
    httpxml.onreadystatechange=stateck;
   
    httpxml.open("GET", url, true);
    httpxml.send(null);
}
