<?php

namespace App\Models;

enum Provider: string {
    case DISCORD = 'discord';
    case TELEGRAM = 'telegram';
}
