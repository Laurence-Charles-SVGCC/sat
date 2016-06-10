<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "course_outline".
 *
 * @property string $courseoutlineid
 * @property string $courseid
 * @property string $personid
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
            [['level', 'deliveryperiod', 'totalstudyhours', 'description', 'rational', 'outcomes', 'content', 'teachingmethod', 'assessmentmethod', 'resources'], 'string'],
            [['code'], 'string', 'max' => 10],
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'courseoutlineid' => 'Courseoutlineid',
            'courseid' => 'Courseid',
            'personid' => 'Personid',
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
    
    
    
    public static function getOutlines($iscape,  $programmecatalogid, $coursecatalogid)
    {
        if($iscape == 0)    //if !cape course
        {
            $course_outlines = CourseOutline::find()
                    ->innerJoin('course_offering', '`course_outline`.`courseid` = `course_offering`.`courseofferingid`')
                    ->where(['course_outline.isactive' => 1, 'course_outline.isdeleted' => 0,
                                    'course_offering.coursecatalogid' => $coursecatalogid, 'course_offering.isactive' => 1, 'course_offering.isdeleted' => 0
                                ])
                     ->orderBy('course_offering.courseofferingid DESC')
                    ->all();
        }
        else
        {
             $course_outlines = CourseOutline::find()
                    ->innerJoin('cape_course', '`course_outline`.`courseid` = `cape_course`.`capecourseid`')
                    ->where(['course_outline.isactive' => 1, 'course_outline.isdeleted' => 0,
                                    'cape_course.capecourseid' => $coursecatalogid, 'cape_course.isactive' => 1, 'cape_course.isdeleted' => 0
                                ])
                    ->one();
        }
        
        if($course_outlines)
                return $course_outlines;
       return false;     
    }
}
