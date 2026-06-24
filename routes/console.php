<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('blog:generate')->dailyAt('02:00');
