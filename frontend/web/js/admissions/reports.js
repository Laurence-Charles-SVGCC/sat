/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Used to toggle between controls for reports contrained to a application
 * period and reports spanning across multiple application periods.
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 12/05/2016
 * Date Last Modified: 12/05/2016
 */
function togglePeriodScope()
{
    var period_scope = document.getElementsByName('period-scope');
    
    /***********De-selects all fields in 'report-category'  *****************/
    var period_sub_category = document.getElementsByName('report-category');
    for (var i=0 ; i<period_sub_category.length ; i++)
    {
        period_sub_category[i].checked = false;
    }
    
    /*********** De-selects all fields in 'dasgs-programme-search-criteria' ***************/
    var programme_criteria = document.getElementsByName('dasgs-programme-search-criteria');
    for (var i=0 ; i<programme_criteria.length ; i++)
    {
        programme_criteria[i].checked = false;
    }
    
    /*********** De-selects all fields in 'dasgs-programme-search-criteria' ***************/
    var programme_criteria = document.getElementsByName('none-dasgs-programme-search-criteria');
    for (var i=0 ; i<programme_criteria.length ; i++)
    {
        programme_criteria[i].checked = false;
    }
    
    
    
    document.getElementById('exception-dropdown').style.display = "none";
    document.getElementById('submit-button').style.display = "none"; 
    
    document.getElementById('none_dasgs_all_programme_field').selectedIndex=0;
    document.getElementById('dasgs_all_programme_field').selectedIndex=0;
    document.getElementById('assoc_programme_field').selectedIndex=0;
    document.getElementById('cape_subject_field').selectedIndex=0;
    
//    document.getElementById('none-dasgs-all-listing').style.display = "none"; 
    document.getElementById('dasgs-all-listing').style.display = "none"; 
    document.getElementById('assoc-listing').style.display = "none"; 
    document.getElementById('cape-listing').style.display = "none"; 
    
    document.getElementById('programme').style.display = "none"; 
    document.getElementById('applicant-summary').style.display = "none"; 
    document.getElementById('exception-reports').style.display = "none"; 
    
    document.getElementById('dasgs-programme-options').style.display = "none";
    document.getElementById('none-dasgs-programme-options').style.display = "none";
    
    document.getElementById('report-body').style.display = "none"; 
    
    document.getElementById('period_field').selectedIndex=0;
    
    
    if (period_scope[0].checked == true)           //if Application Period Specific
    {  
        document.getElementById('application-period-specific').style.display = "block"; 
        document.getElementById('application-period-aggregate').style.display = "none"; 
    }
    else if (period_scope[1].checked == true)           //if Application Period Aggregate
    {  
        document.getElementById('application-period-specific').style.display = "none"; 
        document.getElementById('application-period-aggregate').style.display = "block"; 
    }
    //N.B: In future I must add code to reet active fields that should not be considered
}


function togglePeriod()
{
    var period = document.getElementById('period_field').selectedIndex;
    
    /***********De-selects all fields in 'report-category'  *****************/
    var period_sub_category = document.getElementsByName('report-category');
    for (var i=0 ; i<period_sub_category.length ; i++)
    {
        period_sub_category[i].checked = false;
    }
    
    /*********** De-selects all fields in 'dasgs-programme-search-criteria' ***************/
    var programme_criteria = document.getElementsByName('dasgs-programme-search-criteria');
    for (var i=0 ; i<programme_criteria.length ; i++)
    {
        programme_criteria[i].checked = false;
    }
    
    /*********** De-selects all fields in 'dasgs-programme-search-criteria' ***************/
    var programme_criteria = document.getElementsByName('none-dasgs-programme-search-criteria');
    for (var i=0 ; i<programme_criteria.length ; i++)
    {
        programme_criteria[i].checked = false;
    }
    
    document.getElementById('submit-button').style.display = "none"; 
    document.getElementById('exception-dropdown').style.display = "none";
    
    document.getElementById('none_dasgs_all_programme_field').selectedIndex=0;
    document.getElementById('dasgs_all_programme_field').selectedIndex=0;
    document.getElementById('assoc_programme_field').selectedIndex=0;
    document.getElementById('cape_subject_field').selectedIndex=0;
    
//    document.getElementById('none-dasgs-all-listing').style.display = "none"; 
    document.getElementById('dasgs-all-listing').style.display = "none"; 
    document.getElementById('assoc-listing').style.display = "none"; 
    document.getElementById('cape-listing').style.display = "none"; 
    
    document.getElementById('submit-button').style.display = "none"; 
    document.getElementById('dasgs-programme-options').style.display = "none"; 
    document.getElementById('none-dasgs-programme-options').style.display = "none"; 
    document.getElementById('programme').style.display = "none";
    
    if(period != 0)
    {
        document.getElementById('report-body').style.display = "block"; 
    }
    else
    {
        document.getElementById('report-body').style.display = "none"; 
    }
}


function toggleCategories()
{
    var period_sub_category = document.getElementsByName('report-category');
    
    document.getElementById('submit-button').style.display = "none"; 
    
    document.getElementById('none_dasgs_all_programme_field').selectedIndex=0;
    document.getElementById('dasgs_all_programme_field').selectedIndex=0;
    document.getElementById('assoc_programme_field').selectedIndex=0;
    document.getElementById('cape_subject_field').selectedIndex=0;
    
    document.getElementById('none-dasgs-all-listing').style.display = "none"; 
    document.getElementById('dasgs-all-listing').style.display = "none"; 
    document.getElementById('assoc-listing').style.display = "none"; 
    document.getElementById('cape-listing').style.display = "none"; 
    
    /*********** De-selects all fields in 'dasgs-programme-search-criteria' ***************/
    var programme_criteria = document.getElementsByName('dasgs-programme-search-criteria');
    for (var i=0 ; i<programme_criteria.length ; i++)
    {
        programme_criteria[i].checked = false;
    }
    
    /*********** De-selects all fields in 'dasgs-programme-search-criteria' ***************/
    var programme_criteria = document.getElementsByName('none-dasgs-programme-search-criteria');
    for (var i=0 ; i<programme_criteria.length ; i++)
    {
        programme_criteria[i].checked = false;
    }
    
    if (period_sub_category[0].checked == true)           //if programme selected
    {  
        document.getElementById('programme').style.display = "block"; 
        document.getElementById('applicant-summary').style.display = "none"; 
        document.getElementById('exception-reports').style.display = "none";
        document.getElementById('exception-dropdown').style.display = "none";
        
        var period_field = document.getElementById('period_field');
        var text = period_field.options[period_field.selectedIndex].innerHTML;
        var val = text.slice(0,5);
        
        if(val=="DASGS")
        {
            document.getElementById('dasgs-programme-options').style.display = "block";
            document.getElementById('none-dasgs-programme-options').style.display = "none";
        }
        else
        {
            document.getElementById('dasgs-programme-options').style.display = "none";
            document.getElementById('none-dasgs-programme-options').style.display = "block";
//            alert("oye");
        }
    }
    else if (period_sub_category[1].checked == true)           //if application period selected
    {  
        
        document.getElementById('programme').style.display = "none"; 
        document.getElementById('applicant-summary').style.display = "block"; 
        document.getElementById('exception-reports').style.display = "none"; 
        document.getElementById('exception-dropdown').style.display = "none";
    }
    else if (period_sub_category[2].checked == true)           //if exception report selected
    {  
        
        document.getElementById('programme').style.display = "none"; 
        document.getElementById('applicant-summary').style.display = "none"; 
        document.getElementById('exception-reports').style.display = "block"; 
        document.getElementById('exception-dropdown').style.display = "block";
    }
}


function toggleNoneDasgsProgrameSearchCriteria()
{
    var programme_criteria = document.getElementsByName('none-dasgs-programme-search-criteria');
    
    document.getElementById('submit-button').style.display = "none"; 
    
    document.getElementById('none_dasgs_all_programme_field').selectedIndex=0;
    document.getElementById('dasgs_all_programme_field').selectedIndex=0;
    document.getElementById('assoc_programme_field').selectedIndex=0;
    document.getElementById('cape_subject_field').selectedIndex=0;
    
    if (programme_criteria[0].checked == true)           //if all programme selected
    {
        document.getElementById('none-dasgs-all-listing').style.display = "block"; 
        
    }
}



function toggleDasgsProgrameSearchCriteria()
{
    var programme_criteria = document.getElementsByName('dasgs-programme-search-criteria');
    
//    document.getElementById('submit-button').style.display = "block"; 
    document.getElementById('submit-button').style.display = "none"; 
    
    document.getElementById('none_dasgs_all_programme_field').selectedIndex=0;
    document.getElementById('dasgs_all_programme_field').selectedIndex=0;
    document.getElementById('assoc_programme_field').selectedIndex=0;
    document.getElementById('cape_subject_field').selectedIndex=0;
    
    if (programme_criteria[0].checked == true)           //if all programme selected
    {
        document.getElementById('dasgs-all-listing').style.display = "block"; 
        document.getElementById('assoc-listing').style.display = "none"; 
        document.getElementById('cape-listing').style.display = "none"; 
    }
    else if (programme_criteria[1].checked == true)           //if associate programme selected
    {
        document.getElementById('dasgs-all-listing').style.display = "none"; 
        document.getElementById('assoc-listing').style.display = "block"; 
        document.getElementById('cape-listing').style.display = "none"; 
    }
    else if (programme_criteria[2].checked == true)           //if CAPE subjects selected
    {
        document.getElementById('dasgs-all-listing').style.display = "none"; 
        document.getElementById('assoc-listing').style.display = "none"; 
        document.getElementById('cape-listing').style.display = "block"; 
    }
}   


function toggleSearchButton()
{
    document.getElementById('submit-button').style.display = "block"; 
}
    
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
function PrepareListing(e)
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
                var listingtype = myarray.listingtype;

                if (listingtype == 1)
                {
                    var menu = document.getElementById('none_dasgs_all_programme_field');
                }
                else if (listingtype == 2)
                {
                    var menu = document.getElementById('dasgs_all_programme_field');
                }
                else if (listingtype == 3)
                {
                    var menu = document.getElementById('assoc_programme_field');
                }
                else if (listingtype == 4)
                {
                    var menu = document.getElementById('cape_subject_field');
                }

                //Remove the options from 2nd dropdown list except 'select' option
                for(j=menu.options.length-1; j>0; j--)
                {
                    menu.options.remove(j);
                }

                //Adding new options
                for (i=0;i<myarray.programmes.length;i++)
                {
                    var optn1 = document.createElement("OPTION");
                    optn1.value = myarray.programmes[i].id; 
                    optn1.text = myarray.programmes[i].name;
                    menu.options.add(optn1);
                }
            }
        }
    } // end of function stateck
    
    
    /*******************Gets parameters for query ********************/
    var applicationperiodid=document.getElementById('period_field').value;
    
    var listing_type=null;
    
    if(document.getElementById('none-dasgs-programme-options').style.display == "block")
        listing_type = 1;
    else if(document.getElementById('dasgs-all-listing').style.display == "block")
        listing_type = 2;
    else if(document.getElementById('assoc-listing').style.display == "block")
        listing_type = 3;
    else if(document.getElementById('cape-listing').style.display == "block")
        listing_type = 4;
    
    
    var baseUrl = document.getElementsByName('preparelisting_baseUrl')[0].value;
    if (baseUrl.search("localhost")!=-1)
        url = "http://localhost:80/sat_dev/frontend/web/index.php?r=subcomponents%2Fadmissions%2Freports%2Fget-listing&";
    else
//        url = "http://www.svgcc.vc/subdomains/sat/frontend/web/index.php?r=subcomponents%2Fadmissions%2Freports%2Fget-listing&";
        url = "http://www.svgcc.vc/index.php?r=subcomponents%2Fadmissions%2Freports%2Fget-listing&";
        
   
    url+= "applicationperiodid=" + applicationperiodid;
    
    url+= "&listing_type="+listing_type;
    
    httpxml.onreadystatechange=stateck;
   
    httpxml.open("GET",url,true);
    httpxml.send(null);
}


/*********************************Unregistered Applicant Functions **********************************/

function toggleUnregisteredSearchButton()
{
    document.getElementById('unregistered-applicant-submit-button').style.display = "block"; 
}

/*********************************Intake Report Functions **********************************/
function toggleIntakeProgrammeOptions()
{
    var period = document.getElementById('intake_period_field');
    var index = period.selectedIndex;
    var text = period.options[period.selectedIndex].innerHTML;
    var val = text.slice(0,5);
    
    /***********De-selects all fields in 'dasgs_programme_options'  *****************/
    var dasgs_programme_category = document.getElementsByName('dasgs_programme_search_criteria');
    for (var i=0 ; i<dasgs_programme_category.length ; i++)
    {
        dasgs_programme_category[i].checked = false;
    }
    
    /***********De-selects all fields in 'non_dasgs_programme_options'  *****************/
    var programme_category = document.getElementsByName('non_dasgs_programme_search_criteria');
    for (var i=0 ; i<programme_category.length ; i++)
    {
        programme_category[i].checked = false;
    }
   
    document.getElementById('intake-submit-button').style.display = "none"; 
    
    document.getElementById('programme_field').selectedIndex=0;
    document.getElementById('subject_field').selectedIndex=0;
    
    document.getElementById('intake-all-programmes').style.display = "none"; 
    document.getElementById('intake-cape-listing').style.display = "none"; 
    
    if(index != 0)
    {
        if(val=="DASGS")
        {
            document.getElementById('dasgs-intake-programme-options').style.display = "block";
            document.getElementById('non-dasgs-intake-programme-options').style.display = "none";
        }
        else
        {
            document.getElementById('dasgs-intake-programme-options').style.display = "none";
            document.getElementById('non-dasgs-intake-programme-options').style.display = "block";
        } 
    }
    else
    {
        document.getElementById('dasgs-intake-programme-options').style.display = "none";
        document.getElementById('non-dasgs-intake-programme-options').style.display = "none"; 
    }
}


function toggleDASGSIntakeSearchCriteria()
{
    /***********De-selects all fields in 'non_dasgs_programme_options'  *****************/
    var programme_category = document.getElementsByName('non_dasgs_programme_search_criteria');
    for (var i=0 ; i<programme_category.length ; i++)
    {
        programme_category[i].checked = false;
    }
   
    document.getElementById('intake-submit-button').style.display = "none"; 
    document.getElementById('non-dasgs-intake-programme-options').style.display = "none"; 
    
    var programme_search_criteria = document.getElementsByName('dasgs_programme_search_criteria');
    
    document.getElementById('programme_field').selectedIndex=0;
    document.getElementById('subject_field').selectedIndex=0;
    
    if (programme_search_criteria[0].checked == true)           //if all programmes selected
    {
        document.getElementById('intake-all-programmes').style.display = "block"; 
        document.getElementById('intake-cape-listing').style.display = "none"; 
    }
    else if (programme_search_criteria[1].checked == true)           //if all programmes selected
    {
        document.getElementById('intake-all-programmes').style.display = "none"; 
        document.getElementById('intake-cape-listing').style.display = "block"; 
    }
    else
    {
        document.getElementById('intake-all-programmes').style.display = "none"; 
        document.getElementById('intake-cape-listing').style.display = "none"; 
    }
}


function toggleIntakeSearchCriteria()
{
    /***********De-selects all fields in 'dasgs_programme_options'  *****************/
    var dasgs_programme_category = document.getElementsByName('dasgs_programme_search_criteria');
    for (var i=0 ; i<dasgs_programme_category.length ; i++)
    {
        dasgs_programme_category[i].checked = false;
    }
   
    document.getElementById('intake-submit-button').style.display = "none"; 
    document.getElementById('dasgs-intake-programme-options').style.display = "none"; 
    
    var programme_search_criteria = document.getElementsByName('non_dasgs_programme_search_criteria');
    
    document.getElementById('programme_field').selectedIndex=0;
    document.getElementById('subject_field').selectedIndex=0;
    
    if (programme_search_criteria[0].checked == true)           //if all programmes selected
    {
        document.getElementById('intake-all-programmes').style.display = "block"; 
        document.getElementById('intake-cape-listing').style.display = "none"; 
    }
    else
    {
        document.getElementById('intake-all-programmes').style.display = "none"; 
        document.getElementById('intake-cape-listing').style.display = "none"; 
    }
}


function toggleIntakeSearchButton()
{
    document.getElementById('intake-submit-button').style.display = "block"; 
}







/**
 * Load appropriate data into Subject, Proficiency and Grade dropdown lists
 * 
 * @param {type} e
 * @returns {Boolean}
 * 
 * Author: Laurence Charles
 * Date Created: 15/05/2016
 * Date Last Modified: 15/05/2016
 */
function IntakePrepareListing(e)
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
                var listingtype = myarray.listingtype;

                if (listingtype == 1)
                {
                    var menu = document.getElementById('programme_field');
                }
                else if (listingtype == 2)
                {
                    var menu = document.getElementById('subject_field');
                }

                //Remove the options from 2nd dropdown list except 'select' option
                for(j=menu.options.length-1; j>0; j--)
                {
                    menu.options.remove(j);
                }

                //Adding new options
                for (i=0;i<myarray.programmes.length;i++)
                {
                    var optn1 = document.createElement("OPTION");
                    optn1.value = myarray.programmes[i].id; 
                    optn1.text = myarray.programmes[i].name;
                    menu.options.add(optn1);
                }
            }
        }
    } // end of function stateck
    
    
    /*******************Gets parameters for query ********************/
    var applicationperiodid=document.getElementById('intake_period_field').value;
    
    var listing_type=null;
    
    if(document.getElementById('dasgs-intake-programme-options').style.display == "block")
    {
        var programme_search_criteria = document.getElementsByName('dasgs_programme_search_criteria');
        if (programme_search_criteria[0].checked == true)           //if all programmes selected
        {
            listing_type = 1;
        }
        if (programme_search_criteria[1].checked == true)           //if all programmes selected
        {
            listing_type = 2;
        }
    }
    else if(document.getElementById('non-dasgs-intake-programme-options').style.display == "block")
        listing_type = 1;
    
    
    var baseUrl = document.getElementsByName('intake_listing_baseUrl')[0].value;
    if (baseUrl.search("localhost")!=-1)
        url = "http://localhost:80/sat_dev/frontend/web/index.php?r=subcomponents%2Fadmissions%2Freports%2Fget-intake-listing&";
    else
//        url = "http://www.svgcc.vc/subdomains/sat/frontend/web/index.php?r=subcomponents%2Fadmissions%2Freports%2Fget-intake-listing&";
        url = "http://www.svgcc.vc/index.php?r=subcomponents%2Fadmissions%2Freports%2Fget-intake-listing&";
        
 
    url+= "applicationperiodid=" + applicationperiodid;
    
    url+= "&listing_type="+listing_type;
    
    httpxml.onreadystatechange=stateck;
   
    httpxml.open("GET",url,true);
    httpxml.send(null);
}







