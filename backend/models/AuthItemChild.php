<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "auth_item_child".
 *
 * @property string $parent
 * @property string $child
 *
 * @property AuthItem $parent
 * @property AuthItem $child
 */
class AuthItemChild extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_item_child';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent', 'child'], 'required'],
            [['parent', 'child'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'parent' => 'Parent',
            'child' => 'Child',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'parent']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChild()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'child']);
    }
    
    // (laurence_charles) - Assembles listing of child roles using recursion to traverse Role tree.
    // $listing must be passed by reference to facilitate roles stored to persist through recusive calls
    public static function getChildrenRoles(&$listing, $role_name)
    {
        $associations = AuthItemChild::find()
                ->innerJoin('auth_item' , '`auth_item_child`.`child` = `auth_item`.`name`')
                ->where(['auth_item_child.parent' => $role_name, 'auth_item.type' => 1])
                ->all();
        if (empty($associations) == true)
        {
            return false;
        }
        else
        {
            foreach ($associations as $association)
            {
                $item = AuthItem::find()
                        ->where(['name' => $association->child, 'type' => 1])
                        ->one();
                if ($item == true)
                {
                    if (in_array($item->name, $listing) == false)
                    {
                        $listing[] = $item->name;
                        self::getChildrenRoles($listing, $item->name);
                    }
                }
            }
        }
    }
   
    
    // (laurence_charles) - Returns string array containing all descendant roles of the role in question
    public static function getRoleDescendants($role_name)
    {
        $descendants = array();
        self::getChildrenRoles($descendants, $role_name);
        return $descendants;
    }
    
    
    
    // (laurence_charles) - Assembles listing of parent roles using recursion to traverse Role tree.
    // $listing must be passed by reference to facilitate roles stored to persist through recusive calls
    public static function getParentRoles(&$listing, $role_name)
    {
        $associations = AuthItemChild::find()
                ->innerJoin('auth_item' , '`auth_item_child`.`child` = `auth_item`.`name`')
                ->where(['auth_item_child.child' => $role_name, 'auth_item.type' => 1])
                ->all();
        if (empty($associations) == true)
        {
            return false;
        }
        else
        {
            foreach ($associations as $association)
            {
                $item = AuthItem::find()
                        ->where(['name' => $association->parent, 'type' => 1])
                        ->one();
                if ($item == true)
                {
                    if (in_array($item->name, $listing) == false)
                    {
                        $listing[] = $item->name;
                        self::getParentRoles($listing, $item->name);
                    }
                }
            }
        }
    }
    
    
     // (laurence_charles) - Returns string array containing all descendant roles of the role in question
    public static function getRoleAncestors($role_name)
    {
        $ancestors = array();
        self::getParentRoles($ancestors, $role_name);
        return $ancestors;
    }
}
