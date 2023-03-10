<?php

return [

    'job_status' => [
        0 => 'Published',// published
        1 => 'Application', // application
        2 => 'Booking',// booking
        3 => 'Worked',// worked
    ],

    'job_Category' => [
        1 => 'Audiologist',
        2 => 'Cardiologists',
        3 => 'Cardiothoracic Surgeon',
        4 => 'Dentist',
        5 => 'Endocrinologist',
        6 => 'Gynecologists',
        7 => 'Neurologists',
        8 => 'Ophthalmologists',
        9 => 'Orthopedic Surgeon',
        10 => 'Pediatrician',
        11 => 'Physician',
        12 => 'Psychiatrist',
        13 => 'Radiologist',
        14 => 'Surgeon',
        15 => 'Urologist',
        16 => 'Other',
        17 => 'Nurse',

    ],

    'job_type' => [
        1 => 'Full Time',
        2 => 'Part Time',
        3 => 'Contract',
        4 => 'Temporary',
        5 => 'Internship',
        6 => 'Volunteer',
    ],

    'Application_status' => [
        0 => 'Published', // published
        1 => 'Applied', // applied
        2 => 'Booked', // booked
        3 => 'Worked', // worked
    ],

    'After_Booking_status' => [
        1 => "Booked", // default status
        2 => 'Pending', //client side (After booking candidate default status is booked and if candidate click on sign off button then after status is changed to pending)
    ],

    'After_Worked_candidate_status' => [
        1 => 'Pending', //candidate side (After worked candidate default status is Pendidg )
        2 => 'Processing', //client check timesheet and approve
        3 => 'Paid', //client click on mark as paid button
        4 => 'Rejected', //client click on reject timesheet button
    ],

    'After_Worked_client_status' => [
        1 => 'Processing', //client side (After approve timesheet default status in invoice block is Processing )        
        2 => 'Paid', //client click on mark as paid button
    ]
];