<?php

namespace frontend\models;

use Yii;

use frontend\modelsAcademicOffering;

/**
 * This is the model class for table "application_status".
 *
 * @property integer $applicationstatusid
 * @property string $name
 * @property boolean $isactive
 * @property boolean $isdeleted
 *
 * @property Application[] $applications
 */
class ApplicationStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'application_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['isactive', 'isdeleted'], 'boolean'],
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'applicationstatusid' => 'Applicationstatusid',
            'name' => 'Name',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplications()
    {
        return $this->hasMany(Application::className(), ['applicationstatusid' => 'applicationstatusid']);
    }
    
    
    /**
     * Returns a tailored list of application status based on the current status of a particular application 
     * 
     * @param type $current_status_id
     * @return array
     * 
     * Author: Laurence Charles
     * Date Created : 23/02/2016
     * Date Last Modified: 23/02/2016
     */
    public static function generateAvailableStatuses($applicationid, $current_status_id)
    {
        $ids = array();
        $names = array();
        $container = array();
        
        if ($current_status_id == 6)        //if reject before interview
        {
            array_push($ids, 3);
            array_push($ids, 4);
            array_push($ids, 7);
            if(AcademicOffering::requiresInterview($applicationid) == true)
                array_push($ids, 8);
            else
                array_push($ids, 9);
            
            array_push($names, "Pending");
            array_push($names, "Shortlist");
            array_push($names, "Borderline");
            if(AcademicOffering::requiresInterview($applicationid) == true)
                array_push($names, "Interviewee");
            else
                array_push($names, "Offer");
                
            array_push($container, $ids);
            array_push($container, $names);
        }
        
        elseif ($current_status_id == 3)        //if pending
        {
            array_push($ids, 4);
            array_push($ids, 7);
            if(AcademicOffering::requiresInterview($applicationid) == true)
                array_push($ids, 8);
            else
                array_push($ids, 9);
            array_push($ids, 6);
            
            array_push($names, "Shortlist");
            array_push($names, "Borderline");
            if(AcademicOffering::requiresInterview($applicationid) == true)
                array_push($names, "Interviewee");
            else
                array_push($names, "Offer");
            array_push($names, "Reject");
            
            array_push($container, $ids);
            array_push($container, $names);
        }
        
        elseif ($current_status_id == 4)        //if shortlist
        {
            array_push($ids, 3);
            array_push($ids, 7);
            if(AcademicOffering::requiresInterview($applicationid) == true)
                array_push($ids, 8);
            else
                array_push($ids, 9);
            array_push($ids, 6);
            
            array_push($names, "Pending");
            array_push($names, "Borderline");
            if(AcademicOffering::requiresInterview($applicationid) == true)
                array_push($names, "Interviewee");
            else
                array_push($names, "Offer");
            array_push($names, "Reject");
            
            array_push($container, $ids);
            array_push($container, $names);
        }
        
        elseif ($current_status_id == 7)        //if borderline
        {
            array_push($ids, 3);
            array_push($ids, 4);
            if(AcademicOffering::requiresInterview($applicationid) == true)
                array_push($ids, 8);
            else
                array_push($ids, 9);
            array_push($ids, 6);
            
            array_push($names, "Pending");
            array_push($names, "Shortlist");
            if(AcademicOffering::requiresInterview($applicationid) == true)
                array_push($names, "Interviewee");
            else
                array_push($names, "Offer");
            array_push($names, "Reject");
            
            array_push($container, $ids);
            array_push($container, $names);
        }
        
        elseif ($current_status_id == 8)        //if interview/condition offer
        {
            array_push($ids, 3);
            array_push($ids, 4);
            array_push($ids, 7);
            array_push($ids, 6);        //just incase "Interview/Conditional Offer" was given in error
            array_push($ids, 9);
            array_push($ids, 10);
            
            array_push($names, "Pending");
            array_push($names, "Shortlist");
            array_push($names, "Borderline");
            array_push($names, "Reject");       //just incase "Interview/Conditional Offer" was given in error
            array_push($names, "Offer");
            array_push($names, "Reject Interviewee");
            
            array_push($container, $ids);
            array_push($container, $names);
        }
        
        elseif ($current_status_id == 9)        //if offer
        {
            /*
             * If it's a programme that requires interview, then once an offer is
             * given the only possible action susequent to this is th rejection
             * of this conditional offer
             */
            if(AcademicOffering::requiresInterview($applicationid) == true)
            {
                array_push($ids, 10);
                array_push($names, "Reject Interviewee");
            }
            
            /*
             * If it's a programme that doesn't require interview, then more
             * options are available to the user
             */
            else
            {
                array_push($ids, 3);
                array_push($ids, 4);
                array_push($ids, 7);
                array_push($ids, 6);
                
                array_push($names, "Pending");
                array_push($names, "Shortlist");
                array_push($names, "Borderline");
                array_push($names, "Reject");
            }
            
            
            array_push($container, $ids);
            array_push($container, $names);
        }
        
        elseif ($current_status_id == 10)        //if reject after interview
        {
            array_push($ids, 9);
            array_push($names, "Offer");
            
            array_push($container, $ids);
            array_push($container, $names);
        }
        return $container;
    }
    
    
}
