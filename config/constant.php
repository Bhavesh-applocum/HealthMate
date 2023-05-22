<?php

return [

    'job_status' => [
        0 => 'Published', // published
        1 => 'Application', // application
        2 => 'Booking', // booking
        3 => 'Worked', // worked
    ],

    'parking' => [
        0 => 'Not Available',
        1 => 'Available',
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
        16 => 'Nurse',
        17 => 'Other',
    ],

    'job_Category for 2' => [
        'Audiologist',
        'Cardiologists',
        'Cardiothoracic Surgeon',
        'Dentist',
        'Endocrinologist',
        'Gynecologists',
        'Neurologists',
        'Ophthalmologists',
        'Orthopedic Surgeon',
        'Pediatrician',
        'Physician',
        'Psychiatrist',
        'Radiologist',
        'Surgeon',
        'Urologist',
        'Nurse',
        'Other',
    ],

    'job_Skills' => [
        [
            'Communication skills',
            'Diagnostic skills',
            'Technical skills',
            'Empathy and patience',
            'Attention to detail',
        ],

        [
            'Analytical skills',
            'Communication skills',
            'Technical skills',
            'Decision-making skills',
            'Empathy and compassion'
        ],

        [
            'Surgical skills',
            'Critical thinking skills'
        ],

        [
            'Manual dexterity',
            'Communication skills',
            'Problem-solving skills'
        ],

        [
            'Analytical skills',
            'Communication skills',
            'Critical thinking skills',
            'Empathy and compassion',
            'Collaboration skills'
        ],

        [
            'Diagnostic skills',
            'Surgical skills',
            'Multidisciplinary collaboration skills'
        ],

        [
            'Analytical skills',
            'Communication skills',
            'Attention to detail',
            'Empathy and compassion',
            'Continuous learning'
        ],

        [
            'Diagnostic skills',
            'Surgical skills',
            'Communication skills'
        ],

        [
            'Surgical skills',
            'Analytical skills',
            'Communication skills',
            'Physical dexterity',
            'Empathy and compassion'
        ],

        [
            'Diagnostic skills',
            'Patience and flexibility',
            'Continuous learning'
        ],

        [
            'Diagnostic skills',
            'Communication skills',
            'Analytical skills'
        ],

        [
            'Multitasking skills',
            'Listening skills',
            'Diagnostic skills'
        ],

        [
            'Technical skills',
            'Analytical skills',
            'Attention to detail'
        ],

        [
            'Manual dexterity',
            'Attention to detail',
            'Critical thinking',
            'Problem-solving skills',
            'Communication skills',
            'Leadership skills',
            'Empathy and compassion',
            'Continuous learning'
        ],

        [
            'Compassion',
            'Attention to detail',
            'Analytical skills'
        ],

        [
            'Leadership skills',
            'Medical equipment and technology',
            'technical skills',
            'Communication skills',
            'Compassion'
        ],

        [
            'Analytical skills',
            'Communication skills',
            'Attention to detail',
            'Compassion'
        ]
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

    'Timesheet_status_client' => [
        0 => 'Assigned', //client side  (After booking candidate default status is booked )
        1 => 'Pending', //client side (After booking candidate default status is booked and if candidate click on sign off button then after status is changed to pending)
        2 => 'Approved', //client side (After booking candidate default status is booked and if candidate click on sign off button then after status is changed to pending)
        3 => 'Dispute', //client side (After booking candidate default status is booked and if candidate click on sign off button then after status is changed to pending)
    ],

    'Working_status_candidate' => [
        1 => 'Worked', //candidate side (After worked candidate default status is Pendidg )
        2 => 'Payment Due', //client check timesheet and approve
        3 => 'Paid', //client click on mark as paid button
        4 => 'Dispute', //client click on reject timesheet button
    ],

    'Invoice_status' => [
        1 => 'Processing', //client side (After approve timesheet default status in invoice block is Processing )        
        2 => 'Paid', //client click on mark as paid button
    ],

    'LatLong' => [
        '23.00759022425588, 72.50543517287196',
        '22.99027432718577, 72.48662990590957',
        '23.017432925366727, 72.55591963917125',
        '23.049116421505037, 72.5313066545136',
        '38.89777647849469, -77.03661563337388',
        '53.57787504118864, -2.424147200236007',
        '23.055164932108397, 72.59666476800747',
        '23.006126255515984, 72.60100495663993',
    ]
];
