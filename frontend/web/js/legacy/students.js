/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function showCreationMode()
{
    var mode= document.getElementsByName('student_creation_mode');
    if (mode[0].checked == true)           //if single student
    {
        document.getElementById("student-count-field").selectedIndex = 0;
        document.getElementById("single-mode").style.display = "block";       
        document.getElementById("batch-mode").style.display = "none";
    }  
    else
    {
        document.getElementById("student-count-field").selectedIndex = 0;
        document.getElementById("single-mode").style.display = "none";       
        document.getElementById("batch-mode").style.display = "block";
    }
}


function showBatchCreationButton()
{
    var button = document.getElementById("student-count-field").selectedIndex;
    if (button != 0)
       document.getElementById('batch-button').style.display = "block"; 
   else
       document.getElementById('batch-button').style.display = "none"; 
}


function generateStudentBlanks()
{
    var record_count_array = document.getElementsByName("legacy_record_count");
    var record_count = parseInt(record_count_array[0].value);
//    alert("record_count = " + record_count);
    var i;
    for (i = 0 ; i < record_count ; i++)
    {
        var e1 = document.getElementById("legacystudent-" + i + "-title");
        var title = e1.options[e1.selectedIndex].value;
        
        var firstname = document.getElementById("legacystudent-" + i + "-firstname").value;
        
//        var middlename = document.getElementById("legacystudent-" + i + "-middlename").value;
        
        var  lastname = document.getElementById("legacystudent-" + i + "-lastname").value;
        
//        var dateofbirth = document.getElementById("legacystudent-" + i + "-dateofbirth").value;
        
//        var address = document.getElementById("legacystudent-" + i + "-address").value;
        
        var e6 = document.getElementById("legacystudent-" + i + "-gender");
        var gender = e6.options[e6.selectedIndex].value;
        
        var e7 = document.getElementById("legacystudent-" + i + "-legacyyearid");
        var admission_year = e7.options[e7.selectedIndex].value;
        
         var e8 = document.getElementById("legacystudent-" + i + "-legacyfacultyid");
        var faculty = e8.options[e8.selectedIndex].value;
    
        //If model is untouched them it must 'be dummified'
        if (title==false  && firstname==false && /*middlename==false  &&*/ lastname==false  && /*dateofbirth==false
                && address==false  &&*/ gender==false && admission_year==false && faculty==false)
        {
            //Sets dummy records
            document.getElementById("legacystudent-" + i + "-title").selectedIndex = 1;
            document.getElementById("legacystudent-" + i + "-firstname").value = "default";
//            document.getElementById("legacystudent-" + i + "-middlename").value= "default";
            document.getElementById("legacystudent-" + i + "-lastname").value = "default";
//            document.getElementById("legacystudent-" + i + "-dateofbirth").value= "2000-01-01";
//            document.getElementById("legacystudent-" + i + "-address").value = "default";
            document.getElementById("legacystudent-" + i + "-gender").selectedIndex = 1;
            document.getElementById("legacystudent-" + i + "-legacyyearid").selectedIndex = 1;
            document.getElementById("legacystudent-" + i + "-legacyfacultyid").selectedIndex = 1;
        }
    }
}
    

