<?php

namespace App\Helper;

class Constants
{
    const CountDurationForDashboard = [
        [
          'title' => 'Today',
          'time' => 0,
          'text' => 'Today'
        ],
        [
          'title' => 'Weekly',
          'time' => 1,
          'text' => 'This Week'
        ],
        [
          'title' => 'Monthly',
          'time' => 2,
          'text' => 'This Month'
        ],
        [
          'title' => 'Quarterly',
          'time' => 3,
          'text' => 'This Quarter'
        ],
        [
          'title' => 'Yearly',
          'time' => 4,
          'text' => 'This Year'
        ],
        [
          'title' => 'Total',
          'time' => 5,
          'text' => 'Total'
        ],
      ];
}