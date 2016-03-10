/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



/**
 * Load appropriate data into Subject, Proficiency and Grade dropdown lists
 * 
 * @param {type} e
 * @returns {Boolean}
 * 
 * Author: Laurence Charles
 * Date Created: 04/01/2016
 * Date Last Modified: 04/01/2016
 */
function AddCsecQualificationAjaxFunction(e)
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
            
            var pass = myarray.pass;
            if (pass == 1)
            {    
                /***************************** Handles Subject dropdownlist **************************/
                var subject = document.getElementById('csecqualification-subjectid');

                //Remove the options from 2nd dropdown list except 'select' option
                for(j=subject.options.length-1; j>0; j--)
                {
                    subject.options.remove(j);
                }
                
                //Adding new options
                for (i=0;i<myarray.subjects.length;i++)
                {
                    var optn1 = document.createElement("OPTION");
                    optn1.value = myarray.subjects[i].id; 
                    optn1.text = myarray.subjects[i].name;
                    subject.options.add(optn1);
                }

                /************************** Handles Proficiency Dropdownlist **************************/
                var proficiency = document.getElementById('csecqualification-examinationproficiencytypeid');

                //Remove the options from 2nd dropdown list except 'select' option
                for(j=proficiency.options.length-1; j>0; j--)
                {
                    proficiency.options.remove(j);
                }
                
                //Adding new options
                for (i=0;i<myarray.proficiencies.length;i++)
                {
                    var optn2 = document.createElement("OPTION");
                    optn2.value = myarray.proficiencies[i].id; 
                    optn2.text = myarray.proficiencies[i].name;
                    proficiency.options.add(optn2);
                }

                /****************************** Handles Garde dropdownlist ****************************/
                var grade = document.getElementById('csecqualification-examinationgradeid');
                
                //Remove the options from 2nd dropdown list except 'select' option
                for(j=grade.options.length-1; j>0; j--)
                {
                    grade.options.remove(j);
                }
                
                //Adding new options
                for (i=0;i<myarray.grades.length;i++)
                {
                    var optn3 = document.createElement("OPTION");
                    optn3.value = myarray.grades[i].id; 
                    optn3.text = myarray.grades[i].name;
                    grade.options.add(optn3);
                }
                /************************************************************************************/
            }
        }
    } // end of function stateck
    
    
    /*******************Gets id of element triggering event********************/
    var targ;
    if (!e) 
        var e = window.event;
    if (e.target) 
        targ = e.target;
    else if (e.srcElement)
    {
        targ = e.srcElement;
    }
    if (targ.nodeType == 3) // defeat Safari bug
	targ = targ.parentNode;

    var targetID = targ.id;
    /**************************************************************************/

    //For live sat_dev implementation
//    var url = "http://www.svgcc.vc/subdomains/sat_dev/frontend/web/index.php?r=subcomponents%2Fstudents%2Fprofile%2Fexamination-body-dependants&exam_body_id=";
    
    //For live sat implementation
    var url = "http://www.svgcc.vc/subdomains/sat/frontend/web/index.php?r=subcomponents%2Fstudents%2Fprofile%2Fexamination-body-dependants&exam_body_id=";
    
    
    //For local implementation
//    var url="http://localhost:80/sat_dev/frontend/web/index.php?r=subcomponents%2Fstudents%2Fprofile%2Fexamination-body-dependants&exam_body_id=";

    var exam_body = document.getElementById(targetID).value;

    url+= exam_body;
    
    httpxml.onreadystatechange=stateck;
   
    httpxml.open("GET",url,true);
    httpxml.send(null);
}


/**
 * Load appropriate data into Subject, Proficiency and Grade dropdown lists
 * 
 * @param {type} e
 * @returns {Boolean}
 * 
 * Author: Laurence Charles
 * Date Created: 04/01/2016
 * Date Last Modified: 04/01/2016
 */
function EditCsecQualificationAjaxFunction(e)
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

            var pass = myarray.pass;
            
            if (pass == 1)
            {    
                /***************************** Handles Subject dropdownlist **************************/
                var subject = document.getElementById('csecqualification-subjectid');                
                
                //Remove the options from 2nd dropdown list except 'select' option
                for(j=subject.options.length-1; j>0; j--)
                {
                    subject.options.remove(j);
                }
                
                //Adding new options
                for (i=0;i<myarray.subjects.length;i++)
                {
                    var optn1 = document.createElement("OPTION");
                    optn1.value = myarray.subjects[i].id; 
                    optn1.text = myarray.subjects[i].name;
                    subject.options.add(optn1);
                }

                /************************** Handles Proficiency Dropdownlist **************************/
                var proficiency = document.getElementById('csecqualification-examinationproficiencytypeid');
                
                //Remove the options from 2nd dropdown list except 'select' option
                for(j=proficiency.options.length-1; j>0; j--)
                {
                    proficiency.options.remove(j);
                }
                
                //Adding new options
                for (i=0;i<myarray.proficiencies.length;i++)
                {
                    var optn2 = document.createElement("OPTION");
                    optn2.value = myarray.proficiencies[i].id; 
                    optn2.text = myarray.proficiencies[i].name;
                    proficiency.options.add(optn2);
                }

                /****************************** Handles Garde dropdownlist ****************************/
                var grade = document.getElementById('csecqualification-examinationgradeid');
                
                //Remove the options from 2nd dropdown list except 'select' option
                for(j=grade.options.length-1; j>0; j--)
                {
                    grade.options.remove(j);
                }
                
                //Adding new options
                for (i=0;i<myarray.grades.length;i++)
                {
                    var optn3 = document.createElement("OPTION");
                    optn3.value = myarray.grades[i].id; 
                    optn3.text = myarray.grades[i].name;
                    grade.options.add(optn3);
                }
                /************************************************************************************/
            }
        }
    } // end of function stateck
       
    /*******************Gets id of element triggering event********************/
    var targ;
    if (!e) 
        var e = window.event;
    if (e.target) 
        targ = e.target;
    else if (e.srcElement)
    {
        targ = e.srcElement;
    }
    if (targ.nodeType == 3) // defeat Safari bug
	targ = targ.parentNode;
    
    var targetID = targ.id;
    
    /**************************************************************************/
    //For live sat_dev implementation
//    var url = "http://www.svgcc.vc/subdomains/sat_dev/frontend/web/index.php?r=subcomponents%2Fstudents%2Fprofile%2Fexamination-body-dependants&exam_body_id=";
    
    //For live sat implementation
    var url = "http://www.svgcc.vc/subdomains/sat/frontend/web/index.php?r=subcomponents%2Fstudents%2Fprofile%2Fexamination-body-dependants&exam_body_id=";
     
       
    //For local implementation
//    var url="http://localhost:80/sat_dev/frontend/web/index.php?r=subcomponents%2Fstudents%2Fprofile%2Fexamination-body-dependants&exam_body_id=";

    var exam_body = document.getElementById(targetID).value;
   
    url+= exam_body;
    
    httpxml.onreadystatechange=stateck;
   
    httpxml.open("GET",url,true);
    httpxml.send(null);
}


