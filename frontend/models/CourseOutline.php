<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "course_outline".
 *
 * @property string $courseoutlineid
 * @property string $courseparent
 * @property string $courseid
 * @property string $personid
 * @property string $courseprovider
 * @property string $prerequisites
 * @property string $corequisites
 * @property string $code
 * @property string $name
 * @property string $level
 * @property string $deliveryperiod
 * @property integer $credits
 * @property string $totalstudyhours
 * @property string $description
 * @property string $rational
 * @property string $outcomes
 * @property string $content
 * @property string $teachingmethod
 * @property string $assessmentmethod
 * @property string $resources
 * @property integer $isactive
 * @property integer $isdeleted
 *
 * @property Person $person
 */
class CourseOutline extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_outline';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['courseid', 'personid', 'code', 'name', 'level', 'deliveryperiod', 'credits', 'totalstudyhours', 'description', 'rational', 'outcomes', 'content', 'teachingmethod', 'assessmentmethod', 'resources'], 'required'],
            [['courseid', 'personid', 'credits', 'isactive', 'isdeleted'], 'integer'],
            [['prerequisites', 'corequisites', 'courseprovider',  'level', 'deliveryperiod', 'totalstudyhours', 'description', 'rational', 'outcomes', 'content', 'teachingmethod', 'assessmentmethod', 'resources'], 'string'],
            [['code'], 'string', 'max' => 10],
            [['name'], 'string', 'max' => 100],
             [['courseparent'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'courseoutlineid' => 'Courseoutlineid',
            'courseparent' => 'CourseParent',
            'courseid' => 'Courseid',
            'personid' => 'Personid',
            'prerequisites' => 'Pre-requisites',
             'corequisites' => 'Co-requisites',
            'courseprovider' => 'Course Provider',
            'code' => 'Code',
            'name' => 'Name',
            'level' => 'Level',
            'deliveryperiod' => 'Deliveryperiod',
            'credits' => 'Credits',
            'totalstudyhours' => 'Totalstudyhours',
            'description' => 'Description',
            'rational' => 'Rational',
            'outcomes' => 'Outcomes',
            'content' => 'Content',
            'teachingmethod' => 'Teachingmethod',
            'assessmentmethod' => 'Assessmentmethod',
            'resources' => 'Resources',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['personid' => 'personid']);
    }
    
    
    /**
     * Returns a collection of CourseOutline 
     * 
     * @param type $iscape
     * @param type $code
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 17/06/2016
     * Date Last Modified: 19/06/2016
     */
    public static function getCourseOutlines($iscape, $code)
    {
        if($iscape == 0)    //if !cape course
        {
            $course_outlines = CourseOutline::find()
                    ->where(['courseparent' => $code])
                    ->orderBy('courseid DESC')
                    ->all();
        }
        else
        {
            $catalog = CapeCourse::find()
                    ->where(['capecourseid' => $code, 'isactive' => 1, 'isdeleted' => 0])
                    ->one();
                    
            $course_outlines = CourseOutline::find()
                    ->where(['courseparent' => $catalog->coursecode])
                    ->orderBy('courseid DESC')
                    ->all();
        }
        
        if($course_outlines)
                return $course_outlines;
       return false;     
    }
    
    
    /**
     *Returns a CourseOutline record
     *  
     * @param type $code
     * @return boolean
     * 
     * Author: Laurence Charles
     * Date Created: 19/06/2016
     * Date Last Modified: 19/06/2016
     */
    public static function getSpecificOutline($code)
    {
        $course_outline = CourseOutline::find()
                ->where(['courseid' => $code])
                ->one();
        if($course_outline)
            return $course_outline;
        return false;
    }
}
