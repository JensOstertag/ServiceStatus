<?php

namespace app\users;

enum PermissionLevel: int implements \struktal\Auth\PermissionLevel {
    case USER = 0;

    public function value(): int {
        return $this->value;
    }
}
