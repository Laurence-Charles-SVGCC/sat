<?php


namespace src\Persistence\ActiveRecord\Repository;

use src\Domain\Repository\RepositoryInterface;

abstract class AbstractActiveRecordRepository implements RepositoryInterface
{
    protected $activeRecordClass;

    public function getById($id)
    {
        return $this->activeRecordClass::findOne($id);
    }

    public function getAllActive()
    {
        if (
            $this->activeRecordClass->hasAttribute("isactive") == true
            && $this->activeRecordClass->hasAttribute("isdeleted") == true
        ) {
            return $this->activeRecordClass::find()
                ->where(["isactive" => 1, "isdeleted" => 0])
                ->all();
        } elseif (
            $this->activeRecordClass->hasAttribute("is_active") == true
            && $this->activeRecordClass->hasAttribute("is_deleted") == true
        ) {
            return $this->activeRecordClass::find()
                ->where(["is_active" => 1, "is_deleted" => 0])
                ->all();
        } else {
            return null;
        }
    }

    public function getAll()
    {
        return $this->activeRecordClass::find()->all();
    }

    public function persist($entity)
    {
        if ($entity->save() == true) {
            return $this;
        } else {
            return false;
        }
    }
}
