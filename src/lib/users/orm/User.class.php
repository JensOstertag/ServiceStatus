<?php

namespace app\users;

class User extends \struktal\ORM\GenericUser {
    #[\struktal\ORM\InheritedType(\app\users\PermissionLevel::class)]
    public ?\struktal\Auth\PermissionLevel $permissionLevel = null;
}
