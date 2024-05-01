<?php

enum ServiceStatus: int {
    case UNKNOWN = -1;
    case OPERATIONAL = 0;
    case PARTIAL_OUTAGE = 1;
    case FULL_OUTAGE = 2;
}
