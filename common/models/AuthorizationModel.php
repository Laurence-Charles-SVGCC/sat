<?php

namespace common\models;

use Yii;
use yii\custom\AccountSuspendedException;
use yii\custom\LoginSessionExpiredException;
use yii\custom\UnauthorizedAccessException;
use yii\custom\UserSessionInvalidException;
use yii\helpers\ArrayHelper;

class AuthorizationModel
{
    public static function getUserLayout()
    {
        $user = Yii::$app->user->identity;
        if ($user == true) {
            $roleName = self::getUserRoleName($user->personid);
            if ($roleName != null) {
                $trimmed = trim($roleName);
                $lowerCased = strtolower($trimmed);

                $replaceSpacesWithUnderscores =
                    str_replace(" ", "_", $lowerCased);

                $replaceDashesWithUnderscores =
                    str_replace("-", "_", $replaceSpacesWithUnderscores);

                return $replaceDashesWithUnderscores;
            }
        }
        return null;
    }


    public static function userLoginSessionExpired()
    {
        if (Yii::$app->user->isGuest == true) {
            return true;
        }
        return false;
    }


    public static function userAccountSuspended()
    {
        $user = Yii::$app->user->identity;
        if ($user == true && ($user->isactive == 0 || $user->isdeleted == 1)) {
            return true;
        }
        return false;
    }


    public static function userFailsAuthorizationCheck($authorization)
    {
        $user = Yii::$app->user->identity;
        if ($user == true && Yii::$app->user->can($authorization) == false) {
            return true;
        }
        return false;
    }

    public static function validateAccountActive()
    {
        if (self::userAccountSuspended() == true) {
            throw new AccountSuspendedException();
        }
    }

    public static function validateAction($authorization)
    {
        if (self::userLoginSessionExpired() == true) {
            throw new LoginSessionExpiredException();
        } elseif (self::userAccountSuspended() == true) {
            throw new AccountSuspendedException();
        } elseif (self::userFailsAuthorizationCheck($authorization) == true) {
            throw new UnauthorizedAccessException();
        }
    }


    public static function prepareFormattedListingForAllPermissions()
    {
        $data = array();
        $permissions = AuthorizationModel::getAllPermissions();

        foreach ($permissions as $permission) {
            $data[] =
                self::formatPermissionDetailsIntoAssociativeArray($permission);
        }
        return $data;
    }

    public static function getAuthorizedUsers($roleName)
    {
        $authorizedUsers = array();

        $authAssignments =
            AuthAssignment::find()
            ->where(['item_name' => $roleName])
            ->all();

        foreach ($authAssignments as $authAssignment) {
            $record = array();
            $user = Identity::findUserByID($authAssignment->user_id);
            $record["username"] = $user->username;
            $record["fullName"] = UserModel::getUserFullName($user);
            $authorizedUsers[] = $record;
        }
        return $authorizedUsers;
    }

    public static function getAllPermissionsAssignedToRole($roleName)
    {
        $permissions = array();
        $directPermissions = self::getChildPermissionsOfRole($roleName);
        foreach ($directPermissions as $directPermission) {
            if (in_array($directPermission->child, $permissions) == false) {
                $permissions[] = $directPermission->child;
            }
        }

        $descendantRoles = self::getRoleDescendants($roleName);
        foreach ($descendantRoles as $descendantRole) {
            $associatedPermissions =
                self::getChildPermissionsOfRole($descendantRole);

            foreach ($associatedPermissions as $associatedPermission) {
                if (in_array($associatedPermission->child, $permissions) == false) {
                    $permissions[] = $associatedPermission->child;
                }
            }
        }
        return $permissions;
    }

    public static function formatRoleDetailsIntoAssociativeArray($role)
    {
        $role_info = array();
        $role_info['name'] = $role->name;
        $role_info['description'] = $role->description;
        return $role_info;
    }

    public static function prepareFormattedRoleListing()
    {
        $data = array();
        $roles = AuthorizationModel::getAllRoles();

        foreach ($roles as $role) {
            $data[] = self::formatRoleDetailsIntoAssociativeArray($role);
        }
        return $data;
    }

    public static function generateRoleAssignmentDropdownPlaceholder(
        $currentRole
    ) {
        if ($currentRole == true) {
            return "Change from {$currentRole->item_name} to ..";
        } else {
            return "Assign first user role ...";
        }
    }

    public static function formatPermissionDetailsIntoAssociativeArray(
        $permission
    ) {
        $permissionInfo = array();
        $permissionInfo['name'] = $permission["name"];
        $permissionInfo['description'] = $permission["description"];
        return $permissionInfo;
    }

    public static function prepareFormattedPermissionListing($user)
    {
        $data = array();
        $permissions = self::getEmployeePermissionDetails($user->id);

        foreach ($permissions as $permission) {
            $data[] =
                self::formatPermissionDetailsIntoAssociativeArray($permission);
        }
        return $data;
    }


    /**
     * getUserRoles
     * @param  integer $user_id
     * @return AuthAssignment[]|[]
     */
    public static function getUserRoles($user_id)
    {
        return AuthAssignment::find()
            ->innerJoin(
                'auth_item',
                '`auth_assignment`.`item_name` = `auth_item`.`name`'
            )
            ->where(['auth_assignment.user_id' => $user_id, 'auth_item.type' => 1])
            ->all();
    }

    public static function getUserRole($user_id)
    {
        $authAssignments =
            AuthAssignment::find()
            ->innerJoin(
                'auth_item',
                '`auth_assignment`.`item_name` = `auth_item`.`name`'
            )
            ->where(
                ['auth_assignment.user_id' => $user_id, 'auth_item.type' => 1]
            )
            ->one();

        if (!empty($authAssignments)) {
            return $authAssignments[0];
        }
        return false;
    }

    /**
     * Returns role assigned to user
     * @param  integer $user_id
     * @return String | NULL
     */
    public static function getUserRoleName($user_id)
    {
        $role = self::getUserRole($user_id);
        if ($role != null) {
            return $role->item_name;
        }
        return $role;
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
                array_push(
                    $role_details,
                    self::packageRoleDetails($role_assignment)
                );
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
        $listing = array();

        $associations =
            AuthItemChild::find()
            ->innerJoin(
                'auth_item',
                '`auth_item_child`.`child` = `auth_item`.`name`'
            )
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
        return $listing;
    }


    public static function getRoleDescendants($role_name)
    {
        $descendants = array();
        self::getChildrenRoles($descendants, $role_name);
        return $descendants;
    }


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


    public static function getRoleAncestors($role_name)
    {
        $ancestors = array();
        self::getParentRoles($ancestors, $role_name);
        return $ancestors;
    }


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


    public static function getEmployeePermissionDetails($id)
    {
        $permissions = array();
        $permission_details = array();

        if ($id == false) {
            return $permissions;
        }

        $employee_role =
            AuthAssignment::find()
            ->where(['user_id' => $id])
            ->one();

        if ($employee_role == true) {
            $role_name = $employee_role->item_name;

            $direct_permissions =
                AuthItemChild::find()
                ->innerJoin(
                    'auth_item',
                    '`auth_item_child`.`child` = `auth_item`.`name`'
                )
                ->where(
                    ['auth_item_child.parent' => $role_name, 'auth_item.type' => 2]
                )
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
                        ->innerJoin(
                            'auth_item',
                            '`auth_item_child`.`child` = `auth_item`.`name`'
                        )
                        ->where(
                            [
                                'auth_item_child.parent' => $descendant_role,
                                'auth_item.type' => 2
                            ]
                        )
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

                $description =
                    AuthItem::find()
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


    public static function getUserCurrentRole($userID)
    {
        return AuthAssignment::find()
            ->where(['user_id' => $userID])
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
        if ($role == true) {
            return $role->item_name;
        }
        return null;
    }


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


    public static function isRoleUsed($name)
    {
        return AuthAssignment::find()->where(['item_name' => $name])->count() > 0
            || AuthItemChild::find()->where(['parent' => $name])->count() > 0;
    }


    public static function isPermissionUsed($name)
    {
        return AuthItemChild::find()->where(['child' => $name])->count() > 0;
    }


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


    public static function getAllPermissionExcluding($exclusions)
    {
        return AuthItem::find()
            ->where(['type' => 2])
            ->andWhere(['not', ['name' => $exclusions]])
            ->all();
    }


    public static function getChildPermissionsOfRole($role)
    {
        return AuthItemChild::find()
            ->innerJoin('auth_item', '`auth_item_child`.`child` = `auth_item`.`name`')
            ->where(['auth_item_child.parent' => $role, 'auth_item.type' => 2])
            ->all();
    }


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


    public static function getAllRoleAssignments()
    {
        return AuthAssignment::find()->all();
    }


    public static function getParentChildAssociation($parent, $child)
    {
        return AuthItemChild::find()
            ->where(['parent' => $parent, 'child' => $child])
            ->one();
    }


    public static function getUserRoleAssignment($item_name, $user_id)
    {
        return AuthAssignment::find()
            ->where(['item_name' => $item_name, 'user_id' => $user_id])
            ->one();
    }


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

            $parent_type =
                AuthItem::find()
                ->where(['name' => $role_association->parent])
                ->one()
                ->type;

            $child_type =
                AuthItem::find()
                ->where(['name' => $role_association->child])
                ->one()
                ->type;

            if ($parent_type == 1 && $child_type == 2) {
                $data['parent'] =  $role_association->parent;
                $data['child'] = $role_association->child;
                $container[] = $data;
            }
        }

        return $container;
    }

    public static function assignRoleToUser($user, $role_name)
    {
        if ($user == true && $role_name == true) {
            $currentUserRoleAssignment = self::getUserCurrentRole($user->id);

            if ($currentUserRoleAssignment == true) {
                $currentUserRoleAssignment->item_name = $role_name;
                return $currentUserRoleAssignment->save();
            } else {
                $newUserRoleAssignment = new AuthAssignment();
                $newUserRoleAssignment->user_id = $user->id;
                $newUserRoleAssignment->item_name = $role_name;
                $newUserRoleAssignment->created_at = time();
                return $newUserRoleAssignment->save();
            }
        }
        return false;
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

        if (
            $permission_name == true && $permission != null
            && $permission->type === 2
        ) {
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

    public static function generateUserRolesListing()
    {
        return ArrayHelper::map(
            AuthorizationModel::getAllRoles(),
            'name',
            'name'
        );
    }


    public static function loginSessionExpired($user)
    {
        if ($user->isGuest == true) {
            return true;
        }
        return false;
    }

    public static function AccountSuspended($user)
    {
        if ($user == true && ($user->isactive == 0 || $user->isdeleted == 1)) {
            return true;
        }
        return false;
    }

    public static function failsAuthorizationCheck($user, $authorization)
    {
        if ($user == true && Yii::$app->user->can($authorization) == false) {
            return true;
        }
        return false;
    }

    public static function generateRoleBasedUserLayout($user, $authorization)
    {
        if ($user == false) {
            throw new LoginSessionExpiredException();
        } else {
            if (self::userLoginSessionExpired($user) == true) {
                throw new LoginSessionExpiredException();
            } elseif (self::userAccountSuspended($user) == true) {
                throw new AccountSuspendedException();
            } elseif (self::failsAuthorizationCheck(
                $user,
                $authorization
            ) == true) {
                throw new UnauthorizedAccessException();
            } else {
                $roleName = self::getUserRoleName($user->personid);
                if ($roleName != null) {
                    $trimmed = trim($roleName);
                    $lowerCased = strtolower($trimmed);

                    $replaceSpacesWithUnderscores =
                        str_replace(" ", "_", $lowerCased);

                    $replaceDashesWithUnderscores =
                        str_replace("-", "_", $replaceSpacesWithUnderscores);

                    return $replaceDashesWithUnderscores;
                }
            }
        }

        return null;
    }

    /**
     * Return true when user argument provided is suspended
     * 
     * Example usage:
     * $user = new User()
     * $user->isactive = 0;
     * $user->isdeleted = 0;
     * AuthorizationModel::userAccountIsActive($user) == true
     * 
     * Test Command:
     * php vendor/bin/codecept run tests/unit/AuthorizationModel/UserAccountIsSuspendedTest.php
     *
     * @param [type] $user
     * @return boolean|ErrorObject
     */
    public static function userAccountIsSuspended($user)
    {
        if (
            $user == null
            || ($user instanceof User) == false
        ) {
            return new ErrorObject("Argument is not a valid User model");
        } elseif (
            $user != null
            && $user instanceof User
            && $user->isactive == 0
            && $user->isdeleted == 0
        ) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Hard to write Unit test as it requires session variables to be set.
     * This can only be easily achieved in Functional tests
     */
    public static function screenUserRequest($authorization, $user)
    {
        if ($user == false) {
            throw new UserSessionInvalidException();
        } elseif (self::userAccountSuspended($user) == true) {
            throw new AccountSuspendedException();
        } elseif (Yii::$app->user->can($authorization) == false) {
            throw new UnauthorizedAccessException();
        }
    }

    /**
     * Return true when user argument provided is suspended
     * 
     * Example usage:
     * AuthorizationModel::getLayout($user)
     * -> "system_administrator"
     * 
     * Test Command:
     * php vendor/bin/codecept run tests/unit/AuthorizationModel/GetLayoutTest.php
     *
     * @param String
     * @return User|ErrorObject
     */
    public static function getLayout($user)
    {
        if ($user == null) {
            return new ErrorObject("Invalid User model argument provided");
        } elseif ($user == true) {
            $userRole = self::getUserRoleName($user->personid);
            if ($userRole == false) {
                return new ErrorObject("User has not been assigned a role");
            } else {
                $trimmed = trim($userRole);
                $lowerCased = strtolower($trimmed);
                $replaceSpacesWithUnderscores = str_replace(" ", "_", $lowerCased);
                return str_replace("-", "_", $replaceSpacesWithUnderscores);
            }
        }
        return null;
    }
}
