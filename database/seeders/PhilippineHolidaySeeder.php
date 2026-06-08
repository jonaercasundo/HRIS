<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhilippineHolidaySeeder extends Seeder
{
    public function run(): void
    {
        $year = 2026;

    DB::table('holidays')->upsert([
        [
            'holiday_date' => '2026-01-01',
            'name' => "New Year's Day",
        ],
        [
            'holiday_date' => '2026-04-09',
            'name' => "Araw ng Kagitingan",
        ],
        [
            'holiday_date' => '2026-05-01',
            'name' => "Labor Day",
        ],
        [
            'holiday_date' => '2026-06-12',
            'name' => "Independence Day",
        ],
        [
            'holiday_date' => '2026-08-25',
            'name' => "National Heroes Day (Last Monday of August)",
        ],
        [
            'holiday_date' => '2026-11-30',
            'name' => "Bonifacio Day",
        ],
        [
            'holiday_date' => '2026-12-25',
            'name' => "Christmas Day",
        ],
        [
            'holiday_date' => '2026-12-30',
            'name' => "Rizal Day",
        ],
        [
            'holiday_date' => '2026-02-25',
            'name' => "EDSA People Power Revolution",
        ],
        [
            'holiday_date' => '2026-04-17',
            'name' => "Maundy Thursday",
        ],
        [
            'holiday_date' => '2026-04-18',
            'name' => "Good Friday",
        ],
        [
            'holiday_date' => '2026-11-01',
            'name' => "All Saints' Day",
        ],
        [
            'holiday_date' => '2026-11-02',
            'name' => "All Souls' Day",
        ],
        [
            'holiday_date' => '2026-12-08',
            'name' => "Feast of the Immaculate Conception",
        ],
        [
            'holiday_date' => '2026-12-24',
            'name' => "Christmas Eve",
        ],
        [
            'holiday_date' => '2026-12-31',
            'name' => "New Year's Eve",
        ],
    ],
    ['holiday_date'], // unique key
    ['name'] // fields to update if exists
    );
    }
}