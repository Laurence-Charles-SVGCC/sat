/* 
 * Contains all author created Javascript functions for the "Home" view.
 * Author: Laurence Charles
 * Date Created: 05/12/2015
 */


/**
 * Handles search method functinonality.
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 05/12/2015
 * Date Last Modified: 05/12/2015
 */
function checkSearchType()
{
//    alert ("oye");
    var search_by = document.getElementsByName('search_type');
    if (search_by[0].checked == true)   //if by division
    {
        if (document.getElementsByName("id_field")[0] != null)
            document.getElementsByName("id_field")[0].value = "";

        if (document.getElementsByName("fname_field")[0] != null)
            document.getElementsByName("fname_field")[0].value = "";
 
        if (document.getElementsByName("lname_field")[0] != null)
            document.getElementsByName("lname_field")[0].value = "";
          
        document.getElementById("by_div").style.display = "block";    
        document.getElementById("by_id").style.display = "none";
        document.getElementById("by_name").style.display = "none";
    }    
    else if (search_by[1].checked == true)           //if by studentid
    {      
        if (document.getElementsByName("division")[0] != null)
            document.getElementsByName("division")[0].selectedIndex = 0;

        if (document.getElementsByName("fname_field")[0] != null)
            document.getElementsByName("fname_field")[0].value = "";
 
        if (document.getElementsByName("lname_field")[0] != null)
            document.getElementsByName("lname_field")[0].value = "";    
        
        document.getElementById("by_div").style.display = "none";       
        document.getElementById("by_id").style.display = "block";
        document.getElementById("by_name").style.display = "none";
    } 
    else if (search_by[2].checked == true)           //if student name
    {         
        if (document.getElementsByName("division")[0] != null)
            document.getElementsByName("division")[0].selectedIndex = 0;
        
        if (document.getElementsByName("id_field")[0] != null)
            document.getElementsByName("id_field")[0].value = "";
               
        document.getElementById("by_div").style.display = "none";    
        document.getElementById("by_id").style.display = "none"; 
        document.getElementById("by_name").style.display = "block";
    } 
}



/**
 * Handles Permanent address functionality
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 29/12/2015
 * Date Last Modified: 29/12/2015
 */
function checkCountry()
{
     var countryValue = document.getElementById("country").value;
     if (countryValue==="st. vincent and the grenadines")
     {
        if (document.getElementById("permAddressLine").style.display=="none"  &&  document.getElementById("permLocalTown").style.display=="none")
        {
            document.getElementById("permLocalTown").style.display = "block";
        }
        if (document.getElementById("permAddressLine").style.display=="block")      //if other country was previously selected
        {     
            document.getElementById("permAddressLine").value = "";
            document.getElementById("permAddressLine").style.display="none";
            document.getElementById("permLocalTown").style.display = "block";
        }
    }
    else{   //if outside country under selection
        if (document.getElementById("permLocalTown").style.display == "block")      //if svg was previously under selection
        {     
            document.getElementById("permLocalTown").value = "";
            document.getElementById("permLocalTown").style.display = "none";
            document.getElementById("permAddressLine").value = "";
            document.getElementById("permAddressLine").style.display = "block";
        }
        else
        {
            document.getElementById("permAddressLine").style.display = "block";
        }       
     }  
}


/**
 * Handles 'addressline' functionality of the permanent address
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 29/12/2015
 * Date Last Modified: 29/12/2015
 */
function showAddressLine()
{
    var townValue = document.getElementById("permLocalTown").value;
    if (townValue==="other")
    {
          document.getElementById("permAddressLine").style.display = "block"; 
    }
    else
    {
        document.getElementById("permAddressLine").style.display = "none"; 
        document.getElementById("permAddressLine").value = "";
    }  
}


/**
 * Handles Residential address functionality
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 29/12/2015
 * Date Last Modified: 29/12/2015
 */
function checkCountry2()
{
     var countryValue = document.getElementById("country2").value;
     if (countryValue==="st. vincent and the grenadines")
     {
        if (document.getElementById("resdAddressLine").style.display=="none"  &&  document.getElementById("resdLocalTown").style.display=="none")
        {
            document.getElementById("resdLocalTown").style.display = "block";
        }
        if (document.getElementById("resdAddressLine").style.display=="block")      //if other country was previously selected
        {     
            document.getElementById("resdAddressLine").value = "";
            document.getElementById("resdAddressLine").style.display="none";
            document.getElementById("resdLocalTown").style.display = "block";
        }
     }
    else{
        if (document.getElementById("resdLocalTown").style.display == "block")      //if svg was previously under selection
        {     
            document.getElementById("resdLocalTown").value = "";
            document.getElementById("resdLocalTown").style.display = "none";
            document.getElementById("resdAddressLine").value = "";
            document.getElementById("resdAddressLine").style.display = "block";
        }
        else
        {
            document.getElementById("resdAddressLine").style.display = "block";
        }             
     }  
}


/**
 * Handles 'addressline' functionality of the residential address
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 29/12/2015
 * Date Last Modified: 29/12/2015
 */
function showAddressLine2()
{
    var townValue = document.getElementById("resdLocalTown").value;
    if (townValue==="other")
    {
        document.getElementById("resdAddressLine").style.display = "block"; 
    }
    else
    {
        document.getElementById("resdAddressLine").style.display = "none"; 
        document.getElementById("resdAddressLine").value = "";
    }  
}


/**
 * Handles Postal address functionality
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 29/12/2015
 * Date Last Modified: 29/12/2015
 */
function checkCountry3()
{
    var countryValue = document.getElementById("country3").value;
    if (countryValue==="st. vincent and the grenadines")
    {
        if (document.getElementById("postAddressLine").style.display=="none"  &&  document.getElementById("postLocalTown").style.display=="none")
        {
            document.getElementById("postLocalTown").style.display = "block";
        }
        if (document.getElementById("postAddressLine").style.display=="block")      //if other country was previously selected
        {     
            document.getElementById("postAddressLine").value = "";
            document.getElementById("postAddressLine").style.display="none";
            document.getElementById("postLocalTown").style.display = "block";
        }
    }
    else{
        if (document.getElementById("postLocalTown").style.display == "block")      //if svg was previously under selection
        {     
            document.getElementById("postLocalTown").value= "";
            document.getElementById("postLocalTown").style.display = "none";
            document.getElementById("postAddressLine").value = "";
            document.getElementById("postAddressLine").style.display = "block";
        }
        else
        {
            document.getElementById("postAddressLine").style.display = "block";
        } 
    }  
}


/**
 * Handles 'addressline' functionality of the postal address
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 29/12/2015
 * Date Last Modified: 29/12/2015
 */
function showAddressLine3()
{
     var townValue = document.getElementById("postLocalTown").value;
    if (townValue==="other")
    {
          document.getElementById("postAddressLine").style.display = "block"; 
     }
     else
     {
         document.getElementById("postAddressLine").style.display = "none"; 
         document.getElementById("postAddressLine").value = "";
     }  
}


/*************************** Old Benfeficiary **********************************/
/**
 * Handles 'addressline' functionality of the Old Beneficiary address
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 29/12/2015
 * Date Last Modified: 29/12/2015
 */
function showBeneficiaryAddressLine()
{
    var townValue = document.getElementById("BeneficiaryTown").value;
    if (townValue==="other")
    {
        document.getElementById("BeneficiaryAddressLine").style.display = "block"; 
    }
    else
    {
        document.getElementById("BeneficiaryAddressLine").style.display = "none"; 
        document.getElementById("BeneficiaryAddressLine").value = "";
    }  
}


/**
 * Handles Old Beneficiary address functionality
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 29/12/2015
 * Date Last Modified: 29/12/2015
 */
function checkBeneficiaryCountry()
{
    var countryValue = document.getElementById("BeneficiaryCountry").value;
    if (countryValue==="st. vincent and the grenadines")
    {
        if (document.getElementById("BeneficiaryAddressLine").style.display=="none"  &&  document.getElementById("BeneficiaryTown").style.display=="none")
        {
            document.getElementById("BeneficiaryTown").style.display = "block";
        }
        if (document.getElementById("BeneficiaryAddressLine").style.display=="block")      //if other country was previously selected
        {     
            document.getElementById("BeneficiaryAddressLine").value = "";
            document.getElementById("BeneficiaryAddressLine").style.display="none";
            document.getElementById("BeneficiaryTown").style.display = "block";
        }
    }
    else{
        if (document.getElementById("BeneficiaryTown").style.display == "block")      //if svg was previously under selection
        {     
            document.getElementById("BeneficiaryTown").value= "";
            document.getElementById("BeneficiaryTown").style.display = "none";
            document.getElementById("BeneficiaryAddressLine").value = "";
            document.getElementById("BeneficiaryAddressLine").style.display = "block";
        }
        else
        {
            document.getElementById("BeneficiaryAddressLine").style.display = "block";
        } 
    }  
}

/*************************** New Benfeficiary **********************************/
/**
 * Handles 'addressline' functionality of the New Beneficiary address
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 30/12/2015
 * Date Last Modified: 30/12/2015
 */
function showNewBeneficiaryAddressLine()
{
    var townValue = document.getElementById("NewBeneficiaryTown").value;
    if (townValue==="other")
    {
        document.getElementById("NewBeneficiaryAddressLine").style.display = "block"; 
    }
    else
    {
        document.getElementById("NewBeneficiaryAddressLine").style.display = "none"; 
        document.getElementById("NewBeneficiaryAddressLine").value = "";
    }  
}


/**
 * Handles New Beneficiary address functionality
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 30/12/2015
 * Date Last Modified: 30/12/2015
 */
function checkNewBeneficiaryCountry()
{
    var countryValue = document.getElementById("NewBeneficiaryCountry").value;
    if (countryValue==="st. vincent and the grenadines")
    {
        if (document.getElementById("NewBeneficiaryAddressLine").style.display=="none"  &&  document.getElementById("NewBeneficiaryTown").style.display=="none")
        {
            document.getElementById("NewBeneficiaryTown").style.display = "block";
        }
        if (document.getElementById("NewBeneficiaryAddressLine").style.display=="block")      //if other country was previously selected
        {     
            document.getElementById("NewBeneficiaryAddressLine").value = "";
            document.getElementById("NewBeneficiaryAddressLine").style.display="none";
            document.getElementById("NewBeneficiaryTown").style.display = "block";
        }
    }
    else{
        if (document.getElementById("NewBeneficiaryTown").style.display == "block")      //if svg was previously under selection
        {     
            document.getElementById("NewBeneficiaryTown").value= "";
            document.getElementById("NewBeneficiaryTown").style.display = "none";
            document.getElementById("NewBeneficiaryAddressLine").value = "";
            document.getElementById("NewBeneficiaryAddressLine").style.display = "block";
        }
        else
        {
            document.getElementById("NewBeneficiaryAddressLine").style.display = "block";
        } 
    }  
}

/************************* Old Emergency Contact ******************************/
/**
 * Handles 'addressline' functionality of the Old Emergency Contact address
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 30/12/2015
 * Date Last Modified: 30/12/2015
 */
function showOldEmergencyContactAddressLine()
{
    var townValue = document.getElementById("OldEmergencyContactTown").value;
    if (townValue==="other")
    {
        document.getElementById("OldEmergencyContactAddressLine").style.display = "block"; 
    }
    else
    {
        document.getElementById("OldEmergencyContactAddressLine").style.display = "none"; 
        document.getElementById("OldEmergencyContactAddressLine").value = "";
    }  
}


/**
 * Handles Old Emergency Contact address functionality
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 30/12/2015
 * Date Last Modified: 30/12/2015
 */
function checkOldEmergencyContactCountry()
{
    var countryValue = document.getElementById("OldEmergencyContactCountry").value;
    if (countryValue==="st. vincent and the grenadines")
    {
        if (document.getElementById("OldEmergencyContactAddressLine").style.display=="none"  &&  document.getElementById("OldEmergencyContactTown").style.display=="none")
        {
            document.getElementById("OldEmergencyContactTown").style.display = "block";
        }
        if (document.getElementById("OldEmergencyContactAddressLine").style.display=="block")      //if other country was previously selected
        {     
            document.getElementById("OldEmergencyContactAddressLine").value = "";
            document.getElementById("OldEmergencyContactAddressLine").style.display="none";
            document.getElementById("OldEmergencyContactTown").style.display = "block";
        }
    }
    else{
        if (document.getElementById("OldEmergencyContactTown").style.display == "block")      //if svg was previously under selection
        {     
            document.getElementById("OldEmergencyContactTown").value= "";
            document.getElementById("OldEmergencyContactTown").style.display = "none";
            document.getElementById("OldEmergencyContactAddressLine").value = "";
            document.getElementById("OldEmergencyContactAddressLine").style.display = "block";
        }
        else
        {
            document.getElementById("OldEmergencyContactAddressLine").style.display = "block";
        } 
    }  
}

/************************* New Emergency Contact ******************************/
/**
 * Handles 'addressline' functionality of the New Emergency Contact address
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 30/12/2015
 * Date Last Modified: 30/12/2015
 */
function showNewEmergencyContactAddressLine()
{
    var townValue = document.getElementById("NewEmergencyContactTown").value;
    if (townValue==="other")
    {
        document.getElementById("NewEmergencyContactAddressLine").style.display = "block"; 
    }
    else
    {
        document.getElementById("NewEmergencyContactAddressLine").style.display = "none"; 
        document.getElementById("NewEmergencyContactAddressLine").value = "";
    }  
}


/**
 * Handles New Emergency Contact address functionality
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 30/12/2015
 * Date Last Modified: 30/12/2015
 */
function checkNewEmergencyContactCountry()
{
    var countryValue = document.getElementById("NewEmergencyContactCountry").value;
    if (countryValue==="st. vincent and the grenadines")
    {
        if (document.getElementById("NewEmergencyContactAddressLine").style.display=="none"  &&  document.getElementById("NewEmergencyContactTown").style.display=="none")
        {
            document.getElementById("NewEmergencyContactTown").style.display = "block";
        }
        if (document.getElementById("NewEmergencyContactAddressLine").style.display=="block")      //if other country was previously selected
        {     
            document.getElementById("NewEmergencyContactAddressLine").value = "";
            document.getElementById("NewEmergencyContactAddressLine").style.display="none";
            document.getElementById("NewEmergencyContactTown").style.display = "block";
        }
    }
    else{
        if (document.getElementById("NewEmergencyContactTown").style.display == "block")      //if svg was previously under selection
        {     
            document.getElementById("NewEmergencyContactTown").value= "";
            document.getElementById("NewEmergencyContactTown").style.display = "none";
            document.getElementById("NewEmergencyContactAddressLine").value = "";
            document.getElementById("NewEmergencyContactAddressLine").style.display = "block";
        }
        else
        {
            document.getElementById("NewEmergencyContactAddressLine").style.display = "block";
        } 
    }  
}

/**************************** Mother Contact **********************************/
/**
 * Handles 'addressline' functionality of the Mother address
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 30/12/2015
 * Date Last Modified: 30/12/2015
 */
function showMotherAddressLine()
{
    var townValue = document.getElementById("MotherTown").value;
    if (townValue==="other")
    {
        document.getElementById("MotherAddressLine").style.display = "block"; 
    }
    else
    {
        document.getElementById("MotherAddressLine").style.display = "none"; 
        document.getElementById("MotherAddressLine").value = "";
    }  
}


/**
 * Handles Mother address functionality
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 30/12/2015
 * Date Last Modified: 30/12/2015
 */
function checkMotherCountry()
{
    var countryValue = document.getElementById("MotherCountry").value;
    if (countryValue==="st. vincent and the grenadines")
    {
        if (document.getElementById("MotherAddressLine").style.display=="none"  &&  document.getElementById("MotherTown").style.display=="none")
        {
            document.getElementById("MotherTown").style.display = "block";
        }
        if (document.getElementById("MotherAddressLine").style.display=="block")      //if other country was previously selected
        {     
            document.getElementById("MotherAddressLine").value = "";
            document.getElementById("MotherAddressLine").style.display="none";
            document.getElementById("MotherTown").style.display = "block";
        }
    }
    else{
        if (document.getElementById("MotherTown").style.display == "block")      //if svg was previously under selection
        {     
            document.getElementById("MotherTown").value= "";
            document.getElementById("MotherTown").style.display = "none";
            document.getElementById("MotherAddressLine").value = "";
            document.getElementById("MotherAddressLine").style.display = "block";
        }
        else
        {
            document.getElementById("MotherAddressLine").style.display = "block";
        } 
    }  
}

/**************************** Father Contact **********************************/
/**
 * Handles 'addressline' functionality of the Father address
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 30/12/2015
 * Date Last Modified: 30/12/2015
 */
function showFatherAddressLine()
{
    var townValue = document.getElementById("FatherTown").value;
    if (townValue==="other")
    {
        document.getElementById("FatherAddressLine").style.display = "block"; 
    }
    else
    {
        document.getElementById("FatherAddressLine").style.display = "none"; 
        document.getElementById("FatherAddressLine").value = "";
    }  
}


/**
 * Handles Father address functionality
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 30/12/2015
 * Date Last Modified: 30/12/2015
 */
function checkFatherCountry()
{
    var countryValue = document.getElementById("FatherCountry").value;
    if (countryValue==="st. vincent and the grenadines")
    {
        if (document.getElementById("FatherAddressLine").style.display=="none"  &&  document.getElementById("FatherTown").style.display=="none")
        {
            document.getElementById("FatherTown").style.display = "block";
        }
        if (document.getElementById("FatherAddressLine").style.display=="block")      //if other country was previously selected
        {     
            document.getElementById("FatherAddressLine").value = "";
            document.getElementById("FatherAddressLine").style.display="none";
            document.getElementById("FatherTown").style.display = "block";
        }
    }
    else{
        if (document.getElementById("FatherTown").style.display == "block")      //if svg was previously under selection
        {     
            document.getElementById("FatherTown").value= "";
            document.getElementById("FatherTown").style.display = "none";
            document.getElementById("FatherAddressLine").value = "";
            document.getElementById("FatherAddressLine").style.display = "block";
        }
        else
        {
            document.getElementById("FatherAddressLine").style.display = "block";
        } 
    }  
}

/**************************** Spouse Contact **********************************/
/**
 * Handles 'addressline' functionality of the Spouse address
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 30/12/2015
 * Date Last Modified: 30/12/2015
 */
function showSpouseAddressLine()
{
    var townValue = document.getElementById("SpouseTown").value;
    if (townValue==="other")
    {
        document.getElementById("SpouseAddressLine").style.display = "block"; 
    }
    else
    {
        document.getElementById("SpouseAddressLine").style.display = "none"; 
        document.getElementById("SpouseAddressLine").value = "";
    }  
}


/**
 * Handles Spouse address functionality
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 30/12/2015
 * Date Last Modified: 30/12/2015
 */
function checkSpouseCountry()
{
    var countryValue = document.getElementById("SpouseCountry").value;
    if (countryValue==="st. vincent and the grenadines")
    {
        if (document.getElementById("SpouseAddressLine").style.display=="none"  &&  document.getElementById("SpouseTown").style.display=="none")
        {
            document.getElementById("SpouseTown").style.display = "block";
        }
        if (document.getElementById("SpouseAddressLine").style.display=="block")      //if other country was previously selected
        {     
            document.getElementById("SpouseAddressLine").value = "";
            document.getElementById("SpouseAddressLine").style.display="none";
            document.getElementById("SpouseTown").style.display = "block";
        }
    }
    else{
        if (document.getElementById("SpouseTown").style.display == "block")      //if svg was previously under selection
        {     
            document.getElementById("SpouseTown").value= "";
            document.getElementById("SpouseTown").style.display = "none";
            document.getElementById("SpouseAddressLine").value = "";
            document.getElementById("SpouseAddressLine").style.display = "block";
        }
        else
        {
            document.getElementById("SpouseAddressLine").style.display = "block";
        } 
    }  
}

/************************* Next Of Kin Contact ********************************/
/**
 * Handles 'addressline' functionality of the Next Of Kin address
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 30/12/2015
 * Date Last Modified: 30/12/2015
 */
function showNextOfKinAddressLine()
{
    var townValue = document.getElementById("NextOfKinTown").value;
    if (townValue==="other")
    {
        document.getElementById("NextOfKinAddressLine").style.display = "block"; 
    }
    else
    {
        document.getElementById("NextOfKinAddressLine").style.display = "none"; 
        document.getElementById("NextOfKinAddressLine").value = "";
    }  
}


/**
 * Handles Next Of Kin address functionality
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 30/12/2015
 * Date Last Modified: 30/12/2015
 */
function checkNextOfKinCountry()
{
    var countryValue = document.getElementById("NextOfKinCountry").value;
    if (countryValue==="st. vincent and the grenadines")
    {
        if (document.getElementById("NextOfKinAddressLine").style.display=="none"  &&  document.getElementById("NextOfKinTown").style.display=="none")
        {
            document.getElementById("NextOfKinTown").style.display = "block";
        }
        if (document.getElementById("NextOfKinAddressLine").style.display=="block")      //if other country was previously selected
        {     
            document.getElementById("NextOfKinAddressLine").value = "";
            document.getElementById("NextOfKinAddressLine").style.display="none";
            document.getElementById("NextOfKinTown").style.display = "block";
        }
    }
    else{
        if (document.getElementById("NextOfKinTown").style.display == "block")      //if svg was previously under selection
        {     
            document.getElementById("NextOfKinTown").value= "";
            document.getElementById("NextOfKinTown").style.display = "none";
            document.getElementById("NextOfKinAddressLine").value = "";
            document.getElementById("NextOfKinAddressLine").style.display = "block";
        }
        else
        {
            document.getElementById("NextOfKinAddressLine").style.display = "block";
        } 
    }  
}

/*************************** Guardian Contact *********************************/
/**
 * Handles 'addressline' functionality of the Guardian address
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 30/12/2015
 * Date Last Modified: 30/12/2015
 */
function showGuardianAddressLine()
{
    var townValue = document.getElementById("GuardianTown").value;
    if (townValue==="other")
    {
        document.getElementById("GuardianAddressLine").style.display = "block"; 
    }
    else
    {
        document.getElementById("GuardianAddressLine").style.display = "none"; 
        document.getElementById("GuardianAddressLine").value = "";
    }  
}


/**
 * Handles Guardian functionality
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 30/12/2015
 * Date Last Modified: 30/12/2015
 */
function checkGuardianCountry()
{
    var countryValue = document.getElementById("GuardianCountry").value;
    if (countryValue==="st. vincent and the grenadines")
    {
        if (document.getElementById("GuardianAddressLine").style.display=="none"  &&  document.getElementById("GuardianTown").style.display=="none")
        {
            document.getElementById("GuardianTown").style.display = "block";
        }
        if (document.getElementById("GuardianAddressLine").style.display=="block")      //if other country was previously selected
        {     
            document.getElementById("GuardianAddressLine").value = "";
            document.getElementById("GuardianAddressLine").style.display="none";
            document.getElementById("GuardianTown").style.display = "block";
        }
    }
    else{
        if (document.getElementById("GuardianTown").style.display == "block")      //if svg was previously under selection
        {     
            document.getElementById("GuardianTown").value= "";
            document.getElementById("GuardianTown").style.display = "none";
            document.getElementById("GuardianAddressLine").value = "";
            document.getElementById("GuardianAddressLine").style.display = "block";
        }
        else
        {
            document.getElementById("GuardianAddressLine").style.display = "block";
        } 
    }  
}


/**
 * Controls visibility of email field
 * 
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 03/01/2015
 * Date Last Modified: 03/01/2015
 */
function showNewRelativeEmailField()
{
    
    var answer = document.getElementsByName('Relation[receivemail]');
    if (answer[1].checked==true)        //if 'yes' is selected
    {       
//        document.getElementById("email_label").style.display="block";
        document.getElementById("email_field").style.display="block";
    }    
    if (answer[2].checked==true)        //if 'no' is selected
    {         
//        if (document.getElementById("email_label").style.display=="block"  &&  document.getElementById("email_field").style.display=="block")      //if email field is showing
        if (document.getElementById("email_field").style.display=="block")      //if email field is showing
        {    
//            document.getElementById("email_label").style.display="none";
            document.getElementById("email_field").style.display="none";
            document.getElementById("relation-email").value = "";
        }
    }
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

    //For live implementation
//    var url= "http://www.svgcc.vc/subdomains/apply/frontend/web/index.php?r=qualifications%2Fexamination-body-dependants&";
    
    //For local implementation
    var url="http://localhost:80/sat_dev/frontend/web/index.php?r=subcomponents%2Fstudents%2Fprofile%2Fexamination-body-dependants&exam_body_id=";

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
    //For live implementation
//    var url= "http://www.svgcc.vc/subdomains/apply/frontend/web/index.php?r=qualifications%2Fexamination-body-dependants&";
       
    //For local implementation
    var url="http://localhost:80/sat_dev/frontend/web/index.php?r=subcomponents%2Fstudents%2Fprofile%2Fexamination-body-dependants&exam_body_id=";

    var exam_body = document.getElementById(targetID).value;
   
    url+= exam_body;
    
    httpxml.onreadystatechange=stateck;
   
    httpxml.open("GET",url,true);
    httpxml.send(null);
}


/**
 * Toggles cape subject selection elements
 * 
 * 
 * @param {type} current_cape_id
 * @returns {undefined}
 * 
 * Author: Laurence Charles
 * Date Created: 09/01/2016
 * Date Last Modified: 09/01/2015
 */
function showCape(current_cape_id){
    var academicofferingid = document.getElementById("academicoffering-id").value;
    var divisionid = document.getElementById("application-divisionid").value;
    
    //Needs to be changed yearly or implementation made more flexible
    if (academicofferingid == current_cape_id  && divisionid == 4){    //if CAPE program isselected
        document.getElementById("cape-choice").style.display = "block";
    }
    else {
         document.getElementById("cape-choice").style.display = "none";
    }    
}









