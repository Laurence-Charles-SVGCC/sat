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
 * Date Created: 18/03/2016
 * Date Last Modified: 18/03/2016
 */
function ProcessExaminationBody(e)
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
            
            var index = myarray.recordid;
//            alert(index);
            
            var pass = myarray.pass;
            if (pass == 1)
            {    
                /***************************** Handles Subject dropdownlist **************************/
                var subject = document.getElementById('csecqualification-' + index + '-subjectid');

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
                var proficiency = document.getElementById('csecqualification-' + index + '-examinationproficiencytypeid');

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
                var grade = document.getElementById('csecqualification-' + index + '-examinationgradeid');
                
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
//        alert(e);
    if (e.target) 
        targ = e.target;
    else if (e.srcElement)
    {
        targ = e.srcElement;
    }
    if (targ.nodeType == 3) // defeat Safari bug
	targ = targ.parentNode;
//    alert(targ);

    var targetID = targ.id;
//    alert(targetID);
    
    var recordID1 = targ.name.slice(18,19);
    var recordID2 = targ.name.slice(19,20);
    var recordID = null;
    var is_number = isNaN(recordID2);
    if (is_number == true)
    {
        recordID = recordID1;
    }
    else
    {
        var rec1 = recordID1.toString();
        var rec2 = recordID2.toString();
        var combined = rec1 + rec2;
        var result = parseInt(combined);
        recordID = result;
    }
    /**************************************************************************/
    var baseUrl = document.getElementsByName('viewApplicantQualifications_baseUrl')[0].value;
    
    // (laurence_charles) - Customized URL for ajax call based on user's current URLs
    // This must be dont to avert cross site scripting block that may occur as user may access feature through 3 different URLs;
    //1. http://localhost/sat_dev/frontend/web/index.php?r=subcomponents%2Fadmissions%2Fverify-applicants%2F...
    //2. http://www.sat.svgcc.vc/index.php?r=subcomponents%2Fadmissions%2Fverify-applicants%2F....
    //3. http://sat.svgcc.vc/index.php?r=subcomponents%2Fadmissions%2Fverify-applicants%2F...
    if (baseUrl.search("localhost") >= 0)
    {
        var url = "http://localhost:80/sat_dev/frontend/web/index.php?r=subcomponents%2Fadmissions%2Fverify-applicants%2Fexamination-body-dependants&";
    }
    else if(baseUrl.search("sat.svgcc.vc") >= 0)
    {
        var url = "http://sat.svgcc.vc/index.php?r=subcomponents%2Fadmissions%2Fverify-applicants%2Fexamination-body-dependants&";
    }
    else if(baseUrl.search("www.svgcc.vc/subdomains") >= 0)
    {
        var url = "http://www.svgcc.vc/subdomains/sat/frontend/web/index.php?r=subcomponents%2Fadmissions%2Fverify-applicants%2Fexamination-body-dependants&"
    }
    
    
    
    
    
    
    
    
//    if (baseUrl.search("localhost") != -1)
//    {
//        var url = "http://localhost:80/sat_dev/frontend/web/index.php?r=subcomponents%2Fadmissions%2Fverify-applicants%2Fexamination-body-dependants&";
//    }
//    else
//    {
//        //var url = "http://sat.svgcc.vc/index.php?r=subcomponents%2Fadmissions%2Fverify-applicants%2Fexamination-body-dependants&";  
//        var url = "http://www.svgcc.vc/subdomains/sat/frontend/web/index.php?r=subcomponents%2Fadmissions%2Fverify-applicants%2Fexamination-body-dependants&"
//    }
    

    //For live sat_dev implementation
//    var url = "http://www.svgcc.vc/subdomains/sat_dev/frontend/web/index.php?r=subcomponents%2Fadmissions%2Fverify-applicants%2Fexamination-body-dependants&";
    
    //Implementation for live server
//    var url = "http://www.svgcc.vc/subdomains/sat/frontend/web/index.php?r=subcomponents%2Fadmissions%2Fverify-applicants%2Fexamination-body-dependants&";
    
    
    //For local implementation
//    var url="http://localhost:80/sat_dev/frontend/web/index.php?r=subcomponents%2Fadmissions%2Fverify-applicants%2Fexamination-body-dependants&";
    
    url+="exam_body_id=";

    var exam_body = document.getElementById(targetID).value;
//    alert(exam_body);

    url+= exam_body;
    
    url+= "&index="+recordID;
//    alert(url);
    
    httpxml.onreadystatechange=stateck;
   
    httpxml.open("GET",url,true);
    httpxml.send(null);
}








