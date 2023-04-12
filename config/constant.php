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
            'role_id' => 1,
            'role_skills' => [
                'Medical diagnosis',
                'Clinical reasoning',
                'Patient assessment',
                'Treatment planning',
                'Medical record keeping',
            ]
        ], [
            'role_id' => 2,
            'role_skills' => [
                'Medical terminology',
                'Pharmacology',
                'Medical coding',
            ]
        ], [
            'role_id' => 3,
            'role_skills' => [
                'Medical billing',
                'Medical research',
            ]
        ], [
            'role_id' => 4,
            'role_skills' => [
                'Clinical trials',
                'Medical ethics',
                'Patient communication',
            ]
        ],
        [
            'role_id' => 5,
            'role_skills' => [
                'Medical counseling',
                'Medical documentation',
                'Medical history taking',
            ]
        ], [
            'role_id' => 6,
            'role_skills' => [
                'Medical examination',
            ],
        ], [
            'role_id' => 7,
            'role_skills' => [
                'Patient education',
                'Medical decision making',
            ]
        ], [
            'role_id' => 8,
            'role_skills' => [
                'Emergency medicine',
                'Surgery',
                'Anesthesiology',
                'Radiology'
            ]
        ], [
            'role_id' => 9,
            'role_skills' => [
                'Cardiology',
                'Neurology',
                'Oncology',
                'Gastroenterology',
                'Dermatology'
            ]
        ], [
            'role_id' => 10,
            'role_skills' => [
                'Endocrinology',
                'Hematology',
            ]
        ], [
            'role_id' => 11,
            'role_skills' => [
                'Infectious disease management',
            ]
        ], [
            'role_id' => 12,
            'role_skills' => [
                'Medical imaging',
                'Medical laboratory',
                'Medical transcription',
            ]
        ], [
            'role_id' => 13,
            'role_skills' => [
                'Medical device design',
                'Medical device manufacturing',
                'Medical device testing',
            ]
        ], [
            'role_id' => 14,
            'role_skills' => [
                'Medical device sales',
                'Medical device marketing',
                'Medical device distribution',
            ]
        ], [
            'role_id' => 15,
            'role_skills' => [
                'Nephrology',
                'Obstetrics and gynecology',
                'Pediatrics',
                'Psychiatry',
            ]
        ], [
            'role_id' => 16,
            'role_skills' => [
                'Health policy',
                'Leadership',
                'Teamwork',
                'Communication',
                'Critical thinking',
                'Problem solving',
                'Decision making',
            ]
        ], [
            'role_id' => 17,
            'role_skills' => [
                'Time management',
                'Interpersonal skills',
                'Decision making under pressure.',
            ]
        ],
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
        1 => 'Payment Due', //candidate side (After worked candidate default status is Pendidg )
        2 => 'Processing', //client check timesheet and approve
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
