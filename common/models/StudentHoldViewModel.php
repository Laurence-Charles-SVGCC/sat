<?php

namespace common\models;

class StudentHoldViewModel
{
    public $id;
    public $studentRegistrationID;
    public $studentHoldID;
    public $appliedBy;
    public $dateApplied;
    public $appliedDetails;
    public $resolvedBy;
    public $dateresolved;
    public $resolvedDetails;
    public $notificationStatus;
    public $typeID;
    public $name;
    public $status;
    public $details;
    public $registrationDetails;

    public function __construct($hold)
    {
        $this->id = $hold->studentholdid;
        $this->studentRegistrationID = $hold->studentregistrationid;

        $this->appliedBy = EmployeeModel::getEmployeeFullName($hold->appliedby);
        $this->dateApplied = date_format(new \DateTime($hold->dateapplied), "F j, Y");
        $this->appliedDetails = "{$this->appliedBy} - {$this->dateApplied}";

        $this->resolvedBy = EmployeeModel::getEmployeeFullName($hold->resolvedby);
        if ($hold->dateresolved == true) {
            $this->dateresolved = date_format(new \DateTime($hold->dateresolved), "F j, Y");
        } else {
            $this->dateresolved = "";
        }
        $this->resolvedDetails = "";
        if ($this->resolvedBy == true) {
            $this->resolvedDetails .= $this->resolvedBy;
            if ($hold->dateresolved == true) {
                $this->resolvedDetails .=
                    " - "
                    . date_format(new \DateTime($hold->dateresolved), "F j, Y");
            }
        }

        if ($hold->wasnotified == 1) {
            $this->notificationStatus = "Sent";
        } else {
            $this->notificationStatus = "Not Sent";
        }

        $this->typeID = $hold->holdtypeid;
        $this->name =
            HoldTypeModel::getHoldTypeNameByID($hold->holdtypeid);

        if ($hold->holdstatus == 1) {
            $this->status = "Active";
        } else {
            $this->status = "Resolved";
        }

        $this->details = $hold->details;

        $this->registrationDetails =
            StudentRegistrationModel::generateRegistrationDescription(
                $hold->studentregistrationid
            );
    }
}
