<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "graduation_report_item".
 *
 * @property string $graduationreportitemid
 * @property string $graduationreportid
 * @property string $coursecatalogid
 * @property integer $coursework
 * @property integer $exam
 * @property integer $final
 * @property string $academicyerid
 * @property string $semesterid
 * @property string $isverified
 * @property string $verifiedby
 * @property integer $isexempted
 * @property string $exemptedby
 * @property integer $isdisregarded
 * @property string $disregardedby
 * @property integer $isactive
 * @property integer $isdeleted
 */
class GraduationReportItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'graduation_report_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['graduationreportid', 'coursecatalogid', 'coursework', 'exam', 'final', 'academicyerid', 'semesterid', 'isverified', 'verifiedby', 'isexempted', 'exemptedby', 'isdisregarded', 'disregardedby'], 'required'],
            [['graduationreportid', 'coursecatalogid', 'coursework', 'exam', 'final', 'academicyerid', 'semesterid', 'verifiedby', 'isexempted', 'exemptedby', 'isdisregarded', 'disregardedby', 'isactive', 'isdeleted'], 'integer'],
            [['isverified'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'graduationreportitemid' => 'Graduationreportitemid',
            'graduationreportid' => 'Graduationreportid',
            'coursecatalogid' => 'Coursecatalogid',
            'coursework' => 'Coursework',
            'exam' => 'Exam',
            'final' => 'Final',
            'academicyerid' => 'Academicyerid',
            'semesterid' => 'Semesterid',
            'isverified' => 'Isverified',
            'verifiedby' => 'Verifiedby',
            'isexempted' => 'Isexempted',
            'exemptedby' => 'Exemptedby',
            'isdisregarded' => 'Isdisregarded',
            'disregardedby' => 'Disregardedby',
            'isactive' => 'Isactive',
            'isdeleted' => 'Isdeleted',
        ];
    }
}
