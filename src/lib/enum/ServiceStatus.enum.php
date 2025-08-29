<?php

enum ServiceStatus: int {
    case OPERATIONAL = 0;
    case HIGH_RESPONSE_TIME = 100;
    case INTERNAL_ERROR = 200;
    case NOT_RESPONDING = 300;
}
