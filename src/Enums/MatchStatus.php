<?php

namespace Kczereczon\Scoreboard\Enums;

enum MatchStatus: string
{
    case NOT_STARTED = 'not_started';
    case DURING = 'during';
    case FINISHED = 'finished';
    case CANCELLED = 'cancelled';
    case DISTURBED = 'disturbed';
    case RESCHEDULED = 'rescheduled';
}