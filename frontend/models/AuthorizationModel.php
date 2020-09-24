<?php

namespace frontend\models;

use Yii;
use yii\web\Controller;
use yii\custom\UnauthorizedAccessException;
use yii\custom\AccountSuspendedException;

class AuthorizationModel
{
    public static function validateAuthorization($controller, $authorization)
    {
        if (Yii::$app->user->isGuest == true) {
            return $controller->redirect(['site/login']);
        } elseif (Yii::$app->user->isGuest == false) {
            $user = Yii::$app->user->identity;
            if ($user == false) {
                Yii::$app->session->setFlash('error', 'User account not found');
                return $controller->redirect(['site/error']);
            } elseif ($user == true && ($user->isactive == 0 || $user->isdeleted == 1)) {
                throw new AccountSuspendedException();

                return $controller->redirect(['site/error']);
            } elseif ($user == true && Yii::$app->user->can($authorization) == false) {
                throw new UnauthorizedAccessException();
            }
        }
    }


    /**
     * getUserRoles
     * @param  integer $user_id
     * @return AuthAssignment[]|[]
     */
    public static function getUserRoles($user_id)
    {
        return AuthAssignment::find()
    ->innerJoin('auth_item', '`auth_assignment`.`item_name` = `auth_item`.`name`')
    ->where(['auth_assignment.user_id' => $user_id, 'auth_item.type' => 1])
    ->all();
    }

    /**
     * Returns role assigned to user
     * @param  integer $user_id
     * @return String | NULL
     */
    public static function getUserRoleName($userID)
    {
        $role =
        AuthAssignment::find()
        ->innerJoin('auth_item', '`auth_assignment`.`item_name` = `auth_item`.`name`')
        ->where(['auth_assignment.user_id' => $userID, 'auth_item.type' => 1])
        ->one();

        if ($role == true) {
            return $role->item_name;
        }
        return null;
    }


    /**
     * packageRoleDetails
     * @param  AuthAssignment $role_assignment
     * @return array["name" => "", "description" => ""]
     */
    public static function packageRoleDetails($role_assignment)
    {
        if ($role_assignment == true) {
            $combined = array();
            $keys = array();
            $values = array();
            array_push($keys, "name");
            array_push($keys, "description");
            $k1 = strval($role_assignment->item_name);

            $description =
      AuthItem::find()
      ->where(['name' => $role_assignment->item_name])
      ->one()
      ->description;

            $k2 = strval($description);
            array_push($values, $k1);
            array_push($values, $k2);
            $combined = array_combine($keys, $values);
            return $combined;
        }
        return null;
    }

    /**
     * [getFormattedUserRoleDetails description]
     * @param  [type] $user_id [description]
     * @return [["name" => "", "description" => ""], ...]
     */
    public static function getFormattedUserRoleDetails($user_id)
    {
        $role_details = array();
        if ($user_id == true) {
            $role_assignments = self::getUserRoles($user_id);
            foreach ($role_assignments as $role_assignment) {
                array_push($role_details, self::packageRoleDetails($role_assignment));
            }
        }
        return $role_details;
    }


    /**
     * Assembles listing of child roles using recursion to traverse Role tree.
     * @param  [type] $listing   $listing must be passed by reference to facilitate roles stored to persist through recusive calls
     * @param  string $role_name
     * @return string[]|false
     */
    private static function getChildrenRoles(&$listing, $role_name)
    {
        $associations =
    AuthItemChild::find()
    ->innerJoin('auth_item', '`auth_item_child`.`child` = `auth_item`.`name`')
    ->where(['auth_item_child.parent' => $role_name, 'auth_item.type' => 1])
    ->all();

        if (!empty($associations)) {
            foreach ($associations as $association) {
                $item =
        AuthItem::find()
        ->where(['name' => $association->child, 'type' => 1])
        ->one();

                if ($item == true) {
                    if (in_array($item->name, $listing) == false) {
                        $listing[] = $item->name;
                        self::getChildrenRoles($listing, $item->name);
                    }
                }
            }
        }
        return false;
    }


    /**
     * Returns string array containing all descendant roles of the role in question
     * @param  string $role_name
     * @return string[]|false
     */
    public static function getRoleDescendants($role_name)
    {
        $descendants = array();
        self::getChildrenRoles($descendants, $role_name);
        return $descendants;
    }


    /**
     * Assembles listing of parent roles using recursion to traverse Role tree.
     * @param  array $listing  $listing must be passed by reference to facilitate roles stored to persist through recusive calls
     * @param  string $role_name
     * @return string[]|false
     */
    private static function getParentRoles(&$listing, $role_name)
    {
        $associations =
    AuthItemChild::find()
    ->innerJoin('auth_item', '`auth_item_child`.`child` = `auth_item`.`name`')
    ->where(['auth_item_child.child' => $role_name, 'auth_item.type' => 1])
    ->all();

        if (!empty($associations)) {
            foreach ($associations as $association) {
                $item =
        AuthItem::find()
        ->where(['name' => $association->parent, 'type' => 1])
        ->one();

                if ($item == true) {
                    if (in_array($item->name, $listing) == false) {
                        $listing[] = $item->name;
                        self::getParentRoles($listing, $item->name);
                    }
                }
            }
        }
        return false;
    }


    /**
     * Returns set of ancestors roles for given role
     * @param  string $role_name
     * @return string[]| []
     */
    public static function getRoleAncestors($role_name)
    {
        $ancestors = array();
        self::getParentRoles($ancestors, $role_name);
        return $ancestors;
    }


    /**
     * [getAncestorSet description]
     * @param  array $roles - [["name" => "", "description" => ""], ...]
     * @return array
     */
    public static function getAncestorSet($roles)
    {
        $ancestors = array();
        foreach ($roles as $role) {
            $parents = self::getRoleAncestors($role["name"]);
            foreach ($parents as $parent) {
                if (in_array($parent, $ancestors) == false) {
                    $ancestors[] = $parent;
                }
            }
        }
        return $ancestors;
    }

    // public static function getAncestorSetForRole($role_name)
    // {
    //   $ancestors = array();
    //   $parents = self::getRoleAncestors($role_name);
    //   foreach ($parents as $parent)
    //   {
    //     if(in_array($parent, $ancestors) == false)
    //     {
    //       $ancestors[] = $parent;
    //     }
    //   }
    //
    //   return $ancestors;
    // }
    //

    /**
     * getDescendantSet
     * @param  array $roles - [["name" => "", "description" => ""], ...]
     * @return array
     */
    public static function getDescendantSet($roles)
    {
        $descendants = array();
        foreach ($roles as $role) {
            $children = self::getRoleDescendants($role["name"]);
            foreach ($children as $child) {
                if (in_array($child, $descendants) == false) {
                    $descendants[] = $child;
                }
            }
        }
        return $descendants;
    }

    // public static function getDescendantSetForRole($role_name)
    // {
    //   $descendants = array();
    //   $children = self::getRoleDescendants($role_name);
    //   foreach ($children as $child) {
    //     if(in_array($child, $descendants) == false) {
    //       $descendants[] = $child;
    //     }
    //   }
    //
    //   return $descendants;
    // }


    /**
     * getEmployeePermissionDetails
     * @param  integer $personid
     * @return array
     */
    public static function getEmployeePermissionDetails($personid)
    {
        $permissions = array();
        $permission_details = array();

        if ($personid == false) {
            return $permissions;
        }

        $employee_role =
    AuthAssignment::find()
    ->where(['user_id' => $personid])
    ->one();

        if ($employee_role == true) {
            $role_name = $employee_role->item_name;

            $direct_permissions =
      AuthItemChild::find()
      ->innerJoin('auth_item', '`auth_item_child`.`child` = `auth_item`.`name`')
      ->where(['auth_item_child.parent' => $role_name, 'auth_item.type' => 2])
      ->all();

            foreach ($direct_permissions as $direct_permission) {
                if (in_array($direct_permission->child, $permissions) == false) {
                    $permissions[] = $direct_permission->child;
                }
            }

            $descendant_roles = self::getRoleDescendants($role_name);
            if (empty($descendant_roles) == false) {
                foreach ($descendant_roles  as $descendant_role) {
                    $associated_permissions =
          AuthItemChild::find()
          ->innerJoin('auth_item', '`auth_item_child`.`child` = `auth_item`.`name`')
          ->where(['auth_item_child.parent' => $descendant_role, 'auth_item.type' => 2])
          ->all();

                    foreach ($associated_permissions as $associated_permission) {
                        if (in_array($associated_permission->child, $permissions) == false) {
                            $permissions[] = $associated_permission->child;
                        }
                    }
                }
            }
        }

        if (empty($permissions) == false) {
            foreach ($permissions as $record) {
                $combined = array();
                $keys = array();
                $values = array();
                array_push($keys, "name");
                array_push($keys, "description");
                $k1 = strval($record);
                $description = AuthItem::find()
          ->where(['name' => $record])
          ->one()
          ->description;
                $k2 = strval($description);
                array_push($values, $k1);
                array_push($values, $k2);
                $combined = array_combine($keys, $values);
                array_push($permission_details, $combined);
            }
        }
        return $permission_details;
    }

    public static function getUserCurrentRole($id)
    {
        return AuthAssignment::find()
      ->where(['user_id' => $id])
      ->one();
    }

    public static function getAvailableRoleChanges($id)
    {
        $current_role = self::getUserCurrentRole($id);
        return AuthItem::find()
      ->where(['type' => 1])
      ->andWhere(['not', ['name' => $current_role->item_name]])
      ->all();
    }

    public static function getUserCurrentRoleName($id)
    {
        $role = self::getUserCurrentRole($id);

        return $role->item_name;
    }


    /**
     * getAllRoles
     * @return AuthItem[]
     */
    public static function getAllRoles()
    {
        return AuthItem::find()->where(['type' => 1])->all();
    }

    public static function getAllPermissions()
    {
        return AuthItem::find()
      ->where(['type' => 2])
      ->all();
    }

    //if auth_item is role and role is not yet assigned to user and it is not a parent role
    public static function isRoleUsed($name)
    {
        return AuthAssignment::find()->where(['item_name' => $name])->count() > 0
    || AuthItemChild::find()->where(['parent' => $name])->count() > 0;
    }


    //if auth_item is permission and it has not been assigned to any roles.
    //a permission's association with a role must be removed before that permission can be deleted
    public static function isPermissionUsed($name)
    {
        return AuthItemChild::find()->where(['child' => $name])->count() > 0;
    }


    /**
     * If permission is directly associated with the role in question
     * @param  string  $role
     * @param  string  $permission
     * @return boolean
     */
    public static function isPermissionChildOfRole($role, $permission)
    {
        $association =
    AuthItemChild::find()
    ->where(['parent' => $role, 'child' => $permission])
    ->one();

        if ($association == true) {
            return true;
        }
        return false;
    }


    /**
     * Returns all permission except those listing in argurment array
     * @param  string[] $exclusions
     * @return AuthItem[]|[]
     */
    public static function getAllPermissionExcluding($exclusions)
    {
        return AuthItem::find()
    ->where(['type' => 2])
    ->andWhere(['not', ['name' => $exclusions]])
    ->all();
    }

    /**
     * getChildPermissionsOfRole
     * @param  string $role
     * @return AuthItemChild[]|NULL
     */
    public static function getChildPermissionsOfRole($role)
    {
        return AuthItemChild::find()
    ->innerJoin('auth_item', '`auth_item_child`.`child` = `auth_item`.`name`')
    ->where(['auth_item_child.parent' => $role, 'auth_item.type' => 2])
    ->all();
    }

    /**
     * getRoleByName
     * @param  string $role_name
     * @return AuthItem|NULL
     */
    public static function getRoleByName($role_name)
    {
        return AuthItem::find()
    ->where(['name' => $role_name, 'type' => 1])
    ->one();
    }

    public static function getPermissionByName($permission_name)
    {
        return AuthItem::find()
      ->where(['name' => $permission_name, 'type' => 2])
      ->one();
    }


    /**
     * getAllRoleAssignments
     * @return AuthAssignment[]
     */
    public static function getAllRoleAssignments()
    {
        return AuthAssignment::find()->all();
    }


    /**
     * getParentChildAssociation
     * @param  string $parent
     * @param  string $child
     * @return AuthItemChild|NULL
     */
    public static function getParentChildAssociation($parent, $child)
    {
        return AuthItemChild::find()
    ->where(['parent' => $parent, 'child' => $child])
    ->one();
    }


    /**
     * getUserRoleAssignment
     * @param  string $item_name
     * @param  integer $user_id
     * @return AuthAssignment|NULL
     */
    public static function getUserRoleAssignment($item_name, $user_id)
    {
        return AuthAssignment::find()
    ->where(['item_name' => $item_name, 'user_id' => $user_id])
    ->one();
    }


    /**
     * getAllRolesExcluding
     * @param  string[] $exclusions
     * @return AuthItem[]|NULL
     */
    public static function getAllRolesExcluding($exclusions)
    {
        return AuthItem::find()
    ->where(['type' => 1])
    ->andWhere(['not', ['name' => $exclusions]])
    ->orderBy('name ASC')
    ->all();
    }

    public static function getAllAssociations()
    {
        return AuthItemChild::find()->all();
    }

    public static function getRoleRoleAssociations()
    {
        $role_associations = self::getAllAssociations();
        $container = array();

        foreach ($role_associations as $role_association) {
            $data = array();

            $parent_type = AuthItem::find()->where(['name' => $role_association->parent])->one()->type;
            $child_type = AuthItem::find()->where(['name' => $role_association->child])->one()->type;
            if ($parent_type == 1 && $child_type == 1) {
                $data['parent'] =  $role_association->parent;
                $data['child'] = $role_association->child;
                $container[] = $data;
            }
        }

        return $container;
    }

    public static function getRolePermisssionAssociations()
    {
        $role_associations = self::getAllAssociations();
        $container = array();

        foreach ($role_associations as $role_association) {
            $data = array();

            $parent_type = AuthItem::find()->where(['name' => $role_association->parent])->one()->type;
            $child_type = AuthItem::find()->where(['name' => $role_association->child])->one()->type;
            if ($parent_type == 1 && $child_type == 2) {
                $data['parent'] =  $role_association->parent;
                $data['child'] = $role_association->child;
                $container[] = $data;
            }
        }

        return $container;
    }



    /**
     * [assignRoleToUser description]
     * @param  User $user
     * @param  string $role_name
     * @return AuthAssignment|NULL
     */
    public static function assignRoleToUser($user, $role_name)
    {
        if ($user == true && $role_name == true) {
            $current_user_role_assignment =
      AuthAssignment::find()
      ->where(['user_id' => $user->personid])
      ->one();

            if ($current_user_role_assignment == true) {
                $current_user_role_assignment->item_name = $role_name;
                if ($current_user_role_assignment->save() == true) {
                    return $current_user_role_assignment;
                }
            } else {
                $new_user_role_assignment = new AuthAssignment();
                $new_user_role_assignment->user_id = $user->personid;
                $new_user_role_assignment->item_name = $role_name;
                $new_user_role_assignment->created_at = time();

                if ($new_user_role_assignment->save() == true) {
                    return $new_user_role_assignment;
                }
            }
        }

        return null;
    }


    /**
     * authItemsLikeQuery
     * @param  string $query
     * @return AuthItem[]|NULL
     */
    public static function authItemsLikeQuery($query)
    {
        if ($query == true) {
            return AuthItem::find()
      ->where(['like', 'name', [$query]])
      ->all();
        }
        return null;
    }


    /**
     * getPermissionOwners
     * @param  string $permission_name
     * @return string[]|NULL
     */
    public static function getPermissionOwners($permission_name)
    {
        $permission =
    AuthItem::find()
    ->where(["name" => $permission_name])
    ->one();

        if ($permission_name == true && $permission != null
    && $permission->type === 2) {
            $authorized_roles = array();

            $direct_permission_owners =
      AuthItemChild::find()
      ->where(['child' => $permission_name])
      ->orderBy('parent ASC')
      ->all();

            foreach ($direct_permission_owners as $direct_permission_owner) {
                if (in_array($direct_permission_owner->parent, $authorized_roles) == false) {
                    array_push($authorized_roles, $direct_permission_owner->parent);
                }

                $indirect_permission_owners =
        self::getRoleAncestors($direct_permission_owner->parent);
                foreach ($indirect_permission_owners as $indirect_permission_owner) {
                    if (in_array($indirect_permission_owner, $authorized_roles) == false) {
                        array_push($authorized_roles, $indirect_permission_owner);
                    }
                }
            }
            return $authorized_roles;
        }

        return null;
    }


    /**
     * generateUserRolesListing
     * @return []
     */
    public static function generateUserRolesListing()
    {
        return [
      "Admission Team Adjuster (DASGS)" => "Admission Team Adjuster (DASGS)",
      "Admission Team Adjuster (DTVE)" => "Admission Team Adjuster (DTVE)",
      "Admission Team Adjuster (DTE)" => "Admission Team Adjuster (DTE)",
      "Admission Team Adjuster (DNE)" => "Admission Team Adjuster (DNE)",
      "Admissions Member (DASGS)" => "Admissions Member (DASGS)",
      "Admissions Member (DTVE)" => "Admissions Member (DTVE)",
      "Admissions Member (DTE)" => "Admissions Member (DTE)",
      "Admissions Member (DNE)" => "Admissions Member (DNE)",
      "Assistant Registrar" => "Assistant Registrar",
      "Bursar" => "Bursar",
      "Bursary Staff" => "Bursary Staff",
      "DTE Secretary" => "DTE Secretary",
      "DTVE Legacy Assessor" => "DTVE Legacy Assessor",
      "DTVE Legacy Clerk" => "DTVE Legacy Clerk",
      "DTVE Legacy Coordinator" => "DTVE Legacy Coordinator",
      "DTVE Legacy Data Entry" => "DTVE Legacy Data Entry",
      "Dean (DASGS)" => "Dean (DASGS)",
      "Deputy Dean (DASGS)" => "Deputy Dean (DASGS)",
      "Dean (DTVE)" => "Dean (DTVE)",
      "Deputy Dean (DTVE)" => "Deputy Dean (DTVE)",
      "Dean (DTE)" => "Dean (DTE)",
      "Deputy Dean (DTE)" => "Deputy Dean (DTE)",
      "Dean (DNE)" => "Dean (DNE)",
      "Deputy Dean (DNE)" => "Deputy Dean (DNE)",
      "Deputy Director" => "Deputy Director",
      "Deputy Librarian" => "Deputy Librarian",
      "Director" => "Director",
      "Divisional Staff (DASGS)" => "Divisional Staff (DASGS)",
      "Divisional Staff (DTVE)" => "Divisional Staff (DTVE)",
      "Divisional Staff (DTE)" => "Divisional Staff (DTE)",
      "Divisional Staff (DNE)" => "Divisional Staff (DNE)",
      "E-College Head" => "E-College Head",
      "Head Librarian" => "Head Librarian",
      "Librarian (DASGS)" => "Librarian (DASGS)",
      "Librarian (DTVE)" => "Librarian (DTVE)",
      "Librarian (DTE)" => "Librarian (DTE)",
      "Librarian (DNE)" => "Librarian (DNE)",
      "Library Staff (DASGS)" => "Library Staff (DASGS)",
      "Library Staff (DTVE)" => "Library Staff (DTVE)",
      "Library Staff (DTE)" => "Library Staff (DTE)",
      "Library Staff (DNE)" => "Library Staff (DNE)",
      "Registrar" => "Registrar",
      "Registry Staff" => "Registry Staff",
      "Counsellor" => "Counsellor"
    ];
    }
}
