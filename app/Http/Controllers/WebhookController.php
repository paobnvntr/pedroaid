<?php

namespace App\Http\Controllers;

use App\Mail\AppointmentMail;
use App\Mail\InquiryMail;
use App\Models\Appointment;
use App\Models\DocumentRequest;
use App\Models\Inquiry;
use App\Models\Ordinances;
use App\Models\User;
use App\Notifications\NewAppointment;
use App\Notifications\NewInquiry;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class WebhookController extends Controller
{
    public function webhookAvailableDates() {

        $currentDate = Carbon::now();
        $month = $currentDate->month;
        $year = $currentDate->year;
        $daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth;

        $tuesdaysAndThursdays = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($year, $month, $day);
            if ($date->isTuesday() || $date->isThursday()) {
                if ($date->gte($currentDate)) {
                    $tuesdaysAndThursdays[] = $date->toDateString();
                }
            }
        }

        $timeslots = ['14:00', '14:10', '14:20', '14:30', '14:40', '14:50', '15:00', '15:10', '15:20', '15:30', '15:40', '15:50'];

        $availableDates = [];

        foreach ($tuesdaysAndThursdays as $date) {
            $dateFormatted = Carbon::parse($date)->format('F j, Y');
            foreach ($timeslots as $timeslot) {
                if (!Appointment::where('appointment_date', $date)
                                ->where('appointment_time', $timeslot)
                                ->whereIn('appointment_status', ['Pending', 'Booked', 'Rescheduled'])
                                ->exists()) {
                    $availableTimeslots[] = date("h:i A", strtotime($timeslot));
                }
            }

            if (!empty($availableTimeslots)) {
                $availableDates[] = [
                    'date' => $dateFormatted,
                ];
            }
        }

        $response = [
            'fulfillmentResponse' => [
                'messages' => [
                    [
                        'text' => [
                            'text' => ["Available Dates: \n" . implode("\n", array_column($availableDates, 'date'))]
                        ]
                    ]
                ]
            ]
        ];

        return response()->json($response);

    }
       

    public function webhookCheckDateAvailability(Request $request) {
        $tag = $request->input('fulfillmentInfo.tag');
        $message = $tag ?: $request->input('message', 'Hello World!');
        $dateArray = $request->input('sessionInfo.parameters.date');

        if ($dateArray === "Reset" || $dateArray === "reset" || $dateArray === "RESET") {
            $response = [
                'sessionInfo' => [
                    'parameters' => [
                        'date' => null,
                        'date-availability' => 'reset'
                    ],
                    'tag' => $tag
                ] 
            ];

            // Return the response as JSON
            return response()->json($response);
        }

        if ($dateArray === "Go Back" || $dateArray === "go back" || $dateArray === "GO BACK" || $dateArray === "Back" || $dateArray === "back" || $dateArray === "Go back") {
            $response = [
                'sessionInfo' => [
                    'parameters' => [
                        'date' => null,
                        'date-availability' => 'go-back'
                    ],
                    'tag' => $tag
                ] 
            ];

            // Return the response as JSON
            return response()->json($response);
        }

        $monthNames = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];

        if ($dateArray) {
            $year = $dateArray['year'];
            $monthNumber = $dateArray['month'];
            $monthNumberPadded = sprintf("%02d", $monthNumber);
            $month = $monthNames[$monthNumber] ?? 'Unknown';
            $day = $dateArray['day'];

            if ($year !== null && $month !== null && $day !== null) {

                $dateTime = new DateTime("$year-$monthNumberPadded-$day");

                function militaryToAMPM($time) {
                    return date("h:i A", strtotime($time));
                }

                if ($dateTime->format('N') == 2 || $dateTime->format('N') == 4) {
                    $dateFormatted = "$year-$monthNumberPadded-$day";
                    $timeslots = ['14:00', '14:10', '14:20', '14:30', '14:40', '14:50', '15:00', '15:10', '15:20', '15:30', '15:40', '15:50'];            
                    $availableTimeslots = [];

                    // Check each timeslot to see if it's available
                    foreach ($timeslots as $timeslot) {
                        // Check if there's no appointment for the provided date and timeslot
                        if (!Appointment::where('appointment_date', $dateFormatted)
                                        ->where('appointment_time', $timeslot)
                                        ->whereIn('appointment_status', ['Pending', 'Booked', 'Rescheduled'])
                                        ->exists()) {
                            // Add the available timeslot to the list
                            $availableTimeslots[] = militaryToAMPM($timeslot);
                        }
                    }

                    if (!empty($availableTimeslots)) {    
                        $message = "Appointment Date: " . $dateTime->format('l, F j, Y') . "\n\nAvailable Timeslots: " . implode(", ", $availableTimeslots);

                        $response = [
                            'fulfillmentResponse' => [
                                'messages' => [
                                    [
                                        'text' => [
                                            'text' => [$message]
                                        ]
                                    ]
                                ]
                            ],
                            'sessionInfo' => [
                                'parameters' => [
                                    'date' => [
                                        'year' => $year,
                                        'month' => $monthNumberPadded,
                                        'day' => $day
                                    ],
                                    'date-formatted' => $dateFormatted,
                                    'date-availability' => 'available'
                                ],
                                'tag' => $tag
                            ] 
                        ];

                        return response()->json($response);
                    } else {
                        $message = "Appointments are fully booked for " . $dateTime->format('l, F j, Y') . ".";

                        $response = [
                            'fulfillmentResponse' => [
                                'messages' => [
                                    [
                                        'text' => [
                                            'text' => [$message]
                                        ]
                                    ]
                                ]
                            ],
                            'sessionInfo' => [
                                'parameters' => [
                                    'date' => null,
                                    'date-availability' => 'fully-booked'
                                ],
                                'tag' => $tag
                            ] 
                        ];
                    }

                } else {

                    $message = "Appointments are only available on Tuesdays and Thursdays.";

                    $response = [
                        'fulfillmentResponse' => [
                            'messages' => [
                                [
                                    'text' => [
                                        'text' => [$message]
                                    ]
                                ]
                            ]
                        ],
                        'sessionInfo' => [
                            'parameters' => [
                                'date' => null,
                                'date-availability' => 'not-tues-thurs'
                            ],
                            'tag' => $tag
                        ] 
                    ];
        
                    // Return the response as JSON
                    return response()->json($response);
                }

            } else {
                $message = "Incomplete date information provided.";

                $response = [
                    'fulfillmentResponse' => [
                        'messages' => [
                            [
                                'text' => [
                                    'text' => [$message]
                                ]
                            ]
                        ]
                    ],
                    'sessionInfo' => [
                        'parameters' => [
                            'date' => null,
                            'date-availability' => 'incomplete-date'
                        ],
                        'tag' => $tag
                    ] 
                ];
    
                // Return the response as JSON
                return response()->json($response);
            }
        
        } else {
            $response = [
                'fulfillmentResponse' => [
                    'messages' => [
                        [
                            'text' => [
                                'text' => ["The date you provided is not valid."]
                            ],
                        ],
                    ],
                    
                ],
                'sessionInfo' => [
                    'parameters' => [
                        'date' => null,
                        'date-availability' => 'invalid-date'
                    ],
                    'tag' => $tag
                ]
            ];

            // Return the response as JSON
            return response()->json($response);
        }

    }

    public function webhookCheckTimeAvailability(Request $request) {
        $tag = $request->input('fulfillmentInfo.tag');
        $message = $tag ?: $request->input('message', 'Hello World!');
        $dateArray = $request->input('sessionInfo.parameters.date');
        $timeArray = $request->input('sessionInfo.parameters.time');

        $year = $dateArray['year'];
        $monthNumber = $dateArray['month'];
        $monthNumberPadded = sprintf("%02d", $monthNumber);
        $day = $dateArray['day'];

        $hour = $timeArray['hours'];
        $minute = $timeArray['minutes'];
    
        if ($dateArray && $timeArray) {
            $dateTime = new DateTime("$year-$monthNumberPadded-$day");

            // Convert hours and minutes to a string representation of the time slot
            $timeslot = $hour . ':' . str_pad($minute, 2, '0', STR_PAD_RIGHT);

            $timeslots = ['14:00', '14:10', '14:20', '14:30', '14:40', '14:50', '15:00', '15:10', '15:20', '15:30', '15:40', '15:50'];
    
            function militaryToAMPM($time) {
                return date("h:i A", strtotime($time));
            }

            if ($dateTime->format('N') == 2 || $dateTime->format('N') == 4) {
                $dateFormatted = "$year-$monthNumberPadded-$day";
                $availableTimeslots = [];

                // Check if the provided time is in the predefined timeslots
                if (!in_array($timeslot, $timeslots)) {
                    $message = $timeslot . " is an invalid timeslot. Please select a valid timeslot.";
        
                    $response = [
                        'fulfillmentResponse' => [
                            'messages' => [
                                [
                                    'text' => [
                                        'text' => [$message]
                                    ]
                                ]
                            ]
                        ],
                        'sessionInfo' => [
                            'parameters' => [
                                'date' => [
                                    'year' => $year,
                                    'month' => $monthNumberPadded,
                                    'day' => $day
                                ],
                                'time' => null,
                                'time-availability' => 'invalid-timeslot'
                            ],
                            'tag' => $tag
                        ] 
                    ];
        
                    return response()->json($response);

                } else {
                
                    if (Appointment::where('appointment_date', $dateFormatted)
                                ->where('appointment_time', $timeslot)
                                ->whereIn('appointment_status', ['Pending', 'Booked', 'Rescheduled'])
                                ->exists()) {

                        // Check each timeslot to see if it's available
                        foreach ($timeslots as $timeslot) {
                            // Check if there's no appointment for the provided date and timeslot
                            if (!Appointment::where('appointment_date', $dateFormatted)
                                            ->where('appointment_time', $timeslot)
                                            ->whereIn('appointment_status', ['Pending', 'Booked', 'Rescheduled'])
                                            ->exists()) {
                                // Add the available timeslot to the list
                                $availableTimeslots[] = militaryToAMPM($timeslot);
                            }
                        }

                        $message = "The timeslot you selected is no longer available. Please select another timeslot. \n\nAvailable Timeslots: " . implode(", ", $availableTimeslots);
            
                        $response = [
                            'fulfillmentResponse' => [
                                'messages' => [
                                    [
                                        'text' => [
                                            'text' => [$message]
                                        ]
                                    ]
                                ]
                            ],
                            'sessionInfo' => [
                                'parameters' => [
                                    'date' => [
                                        'year' => $year,
                                        'month' => $monthNumberPadded,
                                        'day' => $day
                                    ],
                                    'time' => null,
                                    'time-availability' => 'not-available'
                                ],
                                'tag' => $tag
                            ] 
                        ];

                        return response()->json($response);

                    } else {
                        $dateTime = new DateTime("$year-$monthNumberPadded-$day");
                        $message = "The timeslot you selected is available. \n\nAppointment Date: " . $dateTime->format('l, F j, Y') . "\nAppointment Time: " . date("h:i A", strtotime($timeslot));
            
                        $response = [
                            'fulfillmentResponse' => [
                                'messages' => [
                                    [
                                        'text' => [
                                            'text' => [$message]
                                        ]
                                    ]
                                ]
                            ],
                            'sessionInfo' => [
                                'parameters' => [
                                    'date' => [
                                        'year' => $year,
                                        'month' => $monthNumberPadded,
                                        'day' => $day
                                    ],
                                    'time' => [
                                        'hours' => $hour,
                                        'minutes' => $minute
                                    ],
                                    'time-formatted' => date("h:i A", strtotime($timeslot)),
                                    'time-availability' => 'available'
                                ],
                                'tag' => $tag
                            ] 
                        ];

                        return response()->json($response);
                    }
                }
            }
    
        } else if (!$dateArray && $timeArray) {
            $message = "No date information provided.";
    
            $response = [
                'fulfillmentResponse' => [
                    'messages' => [
                        [
                            'text' => [
                                'text' => [$message]
                            ]
                        ]
                    ]
                ],
                'sessionInfo' => [
                    'parameters' => [
                        'date' => null,
                        'time' => null,
                        'date-availability' => null,
                        'time-availability' => 'no-date'
                    ],
                    'tag' => $tag
                ]
            ];

            return response()->json($response);
        
        } else if ($dateArray && !$timeArray) {
            $message = "No time information provided.";
    
            $response = [
                'fulfillmentResponse' => [
                    'messages' => [
                        [
                            'text' => [
                                'text' => [$message]
                            ]
                        ]
                    ]
                ],
                'sessionInfo' => [
                    'parameters' => [
                        'date' => [
                            'year' => $year,
                            'month' => $monthNumberPadded,
                            'day' => $day
                        ],
                        'time' => null,
                        'time-availability' => 'no-time'
                    ],
                    'tag' => $tag
                ]
            ];

            return response()->json($response);
        
        } else {
            $message = "Incomplete date and time information provided.";
    
            $response = [
                'fulfillmentResponse' => [
                    'messages' => [
                        [
                            'text' => [
                                'text' => [$message]
                            ]
                        ]
                    ]
                ],
                'sessionInfo' => [
                    'parameters' => [
                        'date' => null,
                        'time' => null,
                        'date-availability' => null,
                        'time-availability' => 'no-date-time'
                    ],
                    'tag' => $tag
                ]
            ];

            return response()->json($response);
        }
    }    

    public function webhookCityChecker(Request $request) {
        $tag = $request->input('fulfillmentInfo.tag');
        $city = strtolower($request->input('sessionInfo.parameters.city'));
    
        // Define variants of San Pedro
        $sanPedroVariants = [
            'san pedro',
            'san pedro laguna',
            'san pedro city',
            'spl',
            'sp city',
        ];
    
        if ($city && in_array($city, $sanPedroVariants)) {
            // City is San Pedro or its variants
            $response = [
                'sessionInfo' => [
                    'parameters' => [
                        'city' => "San Pedro City"
                    ],
                    'tag' => $tag
                ] 
            ];
        } else {
            // City is not San Pedro or its variants
            if ($city) {
                // Check if "City" already exists, if not, append it
                $formattedCity = ucwords($city);
                if (strpos(strtolower($city), 'city') === false) {
                    $formattedCity .= ' City';
                }
            } else {
                $formattedCity = null;
            }
            $response = [
                'sessionInfo' => [
                    'parameters' => [
                        'city' => $formattedCity,
                    ],
                    'tag' => $tag
                ]
            ];
        }
    
        return response()->json($response);
    }   
    
    public function webhookCellphoneNumberChecker(Request $request) {
        $tag = $request->input('fulfillmentInfo.tag');
        $cellphoneNumber = $request->input('sessionInfo.parameters.cellphone_number');
    
        if ($cellphoneNumber && (preg_match('/^09\d{9}$/', $cellphoneNumber) || preg_match('/^\+639\d{9}$/', $cellphoneNumber))) {
            // Cellphone number is provided and in correct Philippine format
            $response = [
                'sessionInfo' => [
                    'parameters' => [
                        'cellphone_number' => $cellphoneNumber,
                        'number-checker' => 'valid'
                    ],
                    'tag' => $tag
                ] 
            ];
        } else {
            // Cellphone number is not provided or not in correct format
            $message = "The cellphone number you provided is not valid. Please provide a valid mobile number.";

            $response = [
                'fulfillmentResponse' => [
                    'messages' => [
                        [
                            'text' => [
                                'text' => [$message]
                            ]
                        ]
                    ]
                ],
                'sessionInfo' => [
                    'parameters' => [
                        'cellphone_number' => null,
                        'number-checker' => 'invalid'
                    ],
                    'tag' => $tag
                ] 
            ];
        }
    
        return response()->json($response);
    }

    private function sendAppointmentEmail(string $email, array $data, string $subject) {
        Mail::to($email)->send(new AppointmentMail($data, $subject));
    }

    public function webhookCreateAppoinment(Request $request) {
        $tag = $request->input('fulfillmentInfo.tag');
        $message = $tag ?: $request->input('message', 'Hello World!');

        $dateArray = $request->input('sessionInfo.parameters.date');
        $timeArray = $request->input('sessionInfo.parameters.time');
        $nameArray = $request->input('sessionInfo.parameters.name');
        $city = $request->input('sessionInfo.parameters.city');
        $barangay = $request->input('sessionInfo.parameters.barangay');
        $street = $request->input('sessionInfo.parameters.street');
        $cellphoneNumber = $request->input('sessionInfo.parameters.cellphone_number');
        $email = $request->input('sessionInfo.parameters.email');
    
        $year = $dateArray['year'];
        $monthNumber = $dateArray['month'];
        $monthNumberPadded = sprintf("%02d", $monthNumber);
        $day = $dateArray['day'];
    
        $hour = $timeArray['hours'];
        $minute = $timeArray['minutes'];
    
        $date = "$year-$monthNumberPadded-$day";
        $timeslot = $hour . ':' . str_pad($minute, 2, '0', STR_PAD_RIGHT);

        $name = $nameArray['name'];

        $address = $street . ', Brgy. ' . $barangay . ', ' . $city;
    
        $appointmentID = Appointment::generateUniqueAppointmentID();

        $appointmentData = [
            'appointment_id' => $appointmentID,
            'name' => $name,
            'address' => $address,
            'cellphone_number' => $cellphoneNumber,
            'email' => $email,
            'appointment_date' => $date,
            'appointment_time' => $timeslot,
            'appointment_status' => 'Pending',
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ];

        $createAppointment = Appointment::create($appointmentData);

        if ($createAppointment->save()) {
            $mailData = [
                'title' => 'Mail from PedroAID',
                'name' => $name,
                'message' => 'Appointment Request Received! Our team will review your request shortly.',
                'tracking_id' => $appointmentID,
                'link' => route('appointmentDetails', ['appointment_id' => $appointmentID, 'email' => $email]),
            ];
    
            $mailSubject = "[#$appointmentID] Requested Appointment: Appointment from $name";
    
            $this->sendAppointmentEmail($email, $mailData, $mailSubject);

            $staff = User::where('transaction_level', 'Appointment')->get();
            $admins = User::where('level', 'Admin')->get();
            $superAdmins = User::where('level', 'Super Admin')->get();
            
            $notificationData = [
                'appointment_id' => $appointmentID,
                'name' => $name,
            ];

            foreach ($staff as $user) {
                $user->notify(new NewAppointment($notificationData));
            }

            foreach ($admins as $admin) {
                $admin->notify(new NewAppointment($notificationData));
            }

            foreach ($superAdmins as $superAdmin) {
                $superAdmin->notify(new NewAppointment($notificationData));
            }
    
            $message = "Your appointment has been successfully scheduled.";
    
            $response = [
                'fulfillmentResponse' => [
                    'messages' => [
                        [
                            'text' => [
                                'text' => [$message]
                            ]
                        ]
                    ]
                ],
                'sessionInfo' => [
                    'parameters' => [
                        'date' => null,
                        'time' => null,
                        'name' => null,
                        'city' => null,
                        'barangay' => null,
                        'street' => null,
                        'cellphone_number' => null,
                        'email' => null,
                        'appointment-status' => 'success'
                    ],
                    'tag' => $tag
                ] 
            ];
        
            return response()->json($response);

        } else {
            $message = "An error occurred while scheduling your appointment. Please try again later.";

            $response = [
                'fulfillmentResponse' => [
                    'messages' => [
                        [
                            'text' => [
                                'text' => [$message]
                            ]
                        ]
                    ]
                ],
                'sessionInfo' => [
                    'parameters' => [
                        'date' => null,
                        'time' => null,
                        'name' => null,
                        'city' => null,
                        'barangay' => null,
                        'street' => null,
                        'cellphone_number' => null,
                        'email' => null,
                        'appointment-status' => 'error'
                    ],
                    'tag' => $tag
                ] 
            ];

            return response()->json($response);
        }
    }

    public function webhookSearchOrdinanceByNumber(Request $request) {
        $tag = $request->input('fulfillmentInfo.tag');
        $message = $tag ?: $request->input('message', 'Hello World!');
        $ordinance_number = $request->input('sessionInfo.parameters.ord_number');

        if ($ordinance_number) {
            $ordinance = Ordinances::where('ordinance_number', $ordinance_number)->first();

            if ($ordinance) {
                $response = [
                    'fulfillmentResponse' => [
                        'messages' => [
                            [
                                'text' => [
                                    'text' => [
                                        "Here is the information about Ordinance No. " . $ordinance_number . 
                                        ".\n\nCommittee: " . $ordinance->committee . 
                                        "\n\nDescription: " . $ordinance->description . 
                                        "\n\nDate Approved: " . $ordinance->date_approved . 
                                        "\n\nFile: <a href=\"" . asset($ordinance->ordinance_file) . "\" target=\"_blank\">Click here to download</a>"
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'sessionInfo' => [
                        'parameters' => [
                            'ord_number' => $ordinance_number,
                            'ord-availability' => 'available'
                        ],
                        'tag' => $tag
                    ] 
                ];

                return response()->json($response);
            } else {
                $response = [
                    'fulfillmentResponse' => [
                        'messages' => [
                            [
                                'text' => [
                                    'text' => ["The ordinance number you provided doesn't exist."]
                                ]
                            ]
                        ]
                    ],
                    'sessionInfo' => [
                        'parameters' => [
                            'ord_number' => null,
                            'ord-availability' => 'not-exist'
                        ],
                        'tag' => $tag
                    ] 
                ];

                return response()->json($response);
            }
        } else {
            $response = [
                'fulfillmentResponse' => [
                    'messages' => [
                        [
                            'text' => [
                                'text' => ["The ordinance number you provided is not valid."]
                            ]
                        ]
                    ]
                ],
                'sessionInfo' => [
                    'parameters' => [
                        'ord_number' => null,

                    ],
                    'tag' => $tag
                ] 
            ];

            return response()->json($response);
        }
    }

    public function webhookSearchOrdinanceByTopic(Request $request) {
        $tag = $request->input('fulfillmentInfo.tag');
        $topic = $request->input('sessionInfo.parameters.ordinance_topic');
        $committee = $request->input('sessionInfo.parameters.ordinance_committee');
        $entity = $request->input('sessionInfo.parameters.ordinance_entity');
        $dateArray = $request->input('sessionInfo.parameters.ordinance_date');

        if ($dateArray) {
            $date = $dateArray['startDate']['year'];
        } else {
            $date = null;
        }
    
        if ($topic || $committee || $entity || $date) {
            $query = Ordinances::query();
    
            if ($topic && !$committee && !$entity && !$date) {
                $query->where('description', 'like', '%' . $topic . '%');
            } else if (!$topic && $committee && !$entity && !$date) {
                $query->where('committee', $committee);
            } else if (!$topic && !$committee && $entity && !$date) {
                $query->where('description', 'like', '%' . $entity . '%');
            } else if (!$topic && !$committee && !$entity && $date) {
                $query->where('date_approved', 'like', '%' . $date . '%');

            } else if ($topic && $committee && !$entity && !$date) {
                $query->where('description', 'like', '%' . $topic . '%')->where('committee', $committee);
            } else if ($topic && !$committee && $entity && !$date) {
                $query->where('description', 'like', '%' . $topic . '%')->where('description', 'like', '%' . $entity . '%');
            } else if ($topic && !$committee && !$entity && $date) {
                $query->where('description', 'like', '%' . $topic . '%')->where('date_approved', 'like', '%' . $date . '%');

            } else if ($committee && $entity && !$topic && !$date) {
                $query->where('committee', $committee)->where('description', 'like', '%' . $entity . '%');
            } else if ($committee && !$topic && !$entity && $date) {
                $query->where('committee', $committee)->where('date_approved', 'like', '%' . $date . '%');

            } else if ($entity && $date && !$topic && !$committee) {
                $query->where('description', 'like', '%' . $entity . '%')->where('date_approved', 'like', '%' . $date . '%');

            } else if ($topic && $committee && $entity && !$date) {
                $query->where('description', 'like', '%' . $topic . '%')->where('committee', $committee)->where('description', 'like', '%' . $entity . '%');
            } else if ($topic && $committee && !$entity && $date) {
                $query->where('description', 'like', '%' . $topic . '%')->where('committee', $committee)->where('date_approved', 'like', '%' . $date . '%');
            } else if ($topic && !$committee && $entity && $date) {
                $query->where('description', 'like', '%' . $topic . '%')->where('description', 'like', '%' . $entity . '%')->where('date_approved', 'like', '%' . $date . '%');
            } else if (!$topic && $committee && $entity && $date) {
                $query->where('committee', $committee)->where('description', 'like', '%' . $entity . '%')->where('date_approved', 'like', '%' . $date . '%');
            
            } else if ($topic && $committee && $entity && $date) {
                $query->where('description', 'like', '%' . $topic . '%')->where('committee', $committee)->where('description', 'like', '%' . $entity . '%')->where('date_approved', 'like', '%' . $date . '%');
            }
    
            $ordinances = $query->get();
    
            if ($ordinances->count() > 0) {
                $response = [
                    'fulfillmentResponse' => [
                        'messages' => [
                            [
                                'text' => [
                                    'text' => [
                                        "Here are the ordinances" . 
                                        ($topic && !$committee && !$entity && !$date ? " related to " . $topic : 
                                        (!$topic && $committee && !$entity && !$date ? " under " . $committee : 
                                        (!$topic && !$committee && $entity && !$date ? " related to " . $entity : 
                                        (!$topic && !$committee && !$entity && $date ? " approved in " . $date :

                                        ($topic && $committee && !$entity && !$date ? " related to " . $topic . " under " . $committee : 
                                        ($topic && $entity && !$committee && !$date ? " related to " . $topic . " and " . $entity : 
                                        ($topic && $date && !$committee && !$entity ? " related to " . $topic . " and approved in " . $date : 

                                        ($committee && $entity && !$topic && !$date ? " related to " . $entity . " under " . $committee: 
                                        ($committee && $date && !$topic && !$entity ? " under " . $committee . " and approved in " . $date : 

                                        ($entity && $date && !$topic && !$committee ? " related to " . $entity . " and approved in " . $date : 

                                        ($topic && $committee && $entity && !$date ? " related to " . $topic . " under " . $committee . " and related to " . $entity : 
                                        ($topic && $committee && $date && !$entity ? " related to " . $topic . " under " . $committee . " and approved in " . $date : 
                                        ($topic && $entity && $date && !$committee ? " related to " . $topic . " and " . $entity . " and approved in " . $date : 
                                        ($committee && $entity && $date && !$topic ? " related to " . $entity . " under " . $committee . " and approved in " . $date : 

                                        ($topic && $committee && $entity && $date ? " related to " . $topic . " and " . $entity . " under " . $committee . " and approved in " . $date : ""))))))))))))))) . "\n\n" .
                                        "Ordinance No. " . $ordinances->implode('ordinance_number', "\nOrdinance No. ")
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'sessionInfo' => [
                        'parameters' => [
                            'ord-availability' => 'available'
                        ],
                        'tag' => $tag
                    ] 
                ];
    
                return response()->json($response);
            } else {
                $response = [
                    'fulfillmentResponse' => [
                        'messages' => [
                            [
                                'text' => [
                                    'text' => [
                                        "There are no ordinances related to " . 
                                        ($topic && !$committee && !$entity && !$date ? $topic : 
                                        (!$topic && $committee && !$entity && !$date ? $committee : 
                                        (!$topic && !$committee && $entity && !$date ? $entity : 
                                        (!$topic && !$committee && !$entity && $date ? $date : 

                                        ($topic && $committee && !$entity && !$date ? $topic . " under " . $committee : 
                                        ($topic && $entity && !$committee && !$date ? $topic . " and " . $entity : 
                                        ($topic && $date && !$committee && !$entity ? $topic . " and approved in " . $date : 

                                        ($committee && $entity && !$topic && !$date ? $committee . " and " . $entity : 
                                        ($committee && $date && !$topic && !$entity ? $committee . " and approved in " . $date : 

                                        ($entity && $date && !$topic && !$committee ? $entity . " and approved in " . $date : 

                                        ($topic && $committee && $entity && !$date ? $topic . " under " . $committee . " and " . $entity : 
                                        ($topic && $committee && $date && !$entity ? $topic . " under " . $committee . " and approved in " . $date : 
                                        ($topic && $entity && $date && !$committee ? $topic . " and " . $entity . " and approved in " . $date : 
                                        ($committee && $entity && $date && !$topic ? $committee . " and " . $entity . " and approved in " . $date : 

                                        ($topic && $committee && $entity && $date ? $topic . " and " . $entity . " under " . $committee .  " and approved in " . $date : ""))))))))))))))) .
                                        "."
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'sessionInfo' => [
                        'parameters' => [
                            'topic' => null,
                            'ord-availability' => 'not-exist'
                        ],
                        'tag' => $tag
                    ] 
                ];
    
                return response()->json($response);
            }
        } else {
            $response = [
                'fulfillmentResponse' => [
                    'messages' => [
                        [
                            'text' => [
                                'text' => ["Please provide a valid topic or committee."]
                            ]
                        ]
                    ]
                ],
                'sessionInfo' => [
                    'parameters' => [
                        'topic' => null,
                        // 'ord-availability' => 'not-valid'
                    ],
                    'tag' => $tag
                ] 
            ];
    
            return response()->json($response);
        }
    }  
    
    public function sendInquiryEmail(string $email, array $data, string $subject) {
        Mail::to($email)->send(new InquiryMail($data, $subject));
    }

    public function webhookCreateInquiry(Request $request) {
        $tag = $request->input('fulfillmentInfo.tag');
        $message = $tag ?: $request->input('message', 'Hello World!');

        $inquiry = $request->input('sessionInfo.parameters.inquiry');
        $nameArray = $request->input('sessionInfo.parameters.name');
        $email = $request->input('sessionInfo.parameters.email');

        $name = $nameArray['name'];

        $inquiryID = Inquiry::generateUniqueInquiryID();

        $inquiryData = [
            'inquiry_id' => $inquiryID,
            'name' => $name,
            'email' => $email,
            'inquiry' => $inquiry,
            'status' => 'Unanswered',
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ];

        $createInquiry = Inquiry::create($inquiryData);

        if ($createInquiry->save()) {

            $mailData = [
                'title' => 'Mail from PedroAID',
                'name' => $name,
                'message' => 'Inquiry Received! We will get back to you as soon as possible.',
                'tracking_id' => $inquiryID,
                'link' => route('inquiryDetails', ['inquiry_id' => $inquiryID, 'email' => $email]),
            ];

            $mailSubject = "[#$inquiryID] Inquiry: Inquiry from $name";

            $this->sendInquiryEmail($email, $mailData, $mailSubject);

            $staff = User::where('transaction_level', 'Inquiry')->get();
            $admins = User::where('level', 'Admin')->get();
            $superAdmins = User::where('level', 'Super Admin')->get();
            
            $notificationData = [
                'inquiry_id' => $inquiryID,
                'name' => $name,
            ];

            foreach ($staff as $user) {
                $user->notify(new NewInquiry($notificationData));
            }

            foreach ($admins as $admin) {
                $admin->notify(new NewInquiry($notificationData));
            }

            foreach ($superAdmins as $superAdmin) {
                $superAdmin->notify(new NewInquiry($notificationData));
            }
    
            $message = "Your inquiry has been successfully submitted.";
    
            $response = [
                'fulfillmentResponse' => [
                    'messages' => [
                        [
                            'text' => [
                                'text' => [$message]
                            ]
                        ]
                    ]
                ],
                'sessionInfo' => [
                    'parameters' => [
                        'name' => null,
                        'email' => null,
                        'inquiry' => null,
                        'confirm' => null,
                    ],
                    'tag' => $tag
                ] 
            ];
        
            return response()->json($response);

        } else {
            $message = "An error occurred while submitting your inquiry. Please try again later.";

            $response = [
                'fulfillmentResponse' => [
                    'messages' => [
                        [
                            'text' => [
                                'text' => [$message]
                            ]
                        ]
                    ]
                ],
                'sessionInfo' => [
                    'parameters' => [
                        'name'=> null,
                        'email' => null,
                        'inquiry' => null,
                        'confirm' => null,
                    ],
                    'tag' => $tag
                ]
            ];

            return response()->json($response);
        }
    }

    public function webhookTrackingIdChecker(Request $request) {
        $tag = $request->input('fulfillmentInfo.tag');
        $trackingID = $request->input('sessionInfo.parameters.tracking_id');
        
        // Check if the tracking ID is valid
        $isValidTrackingID = $this->isTrackingIdValid($trackingID);
        
        // Define the response message based on the validity of the tracking ID
        if ($isValidTrackingID) {
            $response = [
                'sessionInfo' => [
                    'parameters' => [
                        'tracking_id' => $trackingID,
                        'tracking_id_checker' => "valid"
                    ],
                    'tag' => $tag
                ] 
            ];
        
            // Return the response
            return response()->json($response);

        } else {
            $response = [
                'sessionInfo' => [
                    'parameters' => [
                        'tracking_id' => null,
                        'tracking_id_checker' => "invalid"
                    ],
                    'tag' => $tag
                ] 
            ];

            // Return the response
            return response()->json($response);
        }

    }
    
    private function isTrackingIdValid($trackingID) {
        // Define the regular expression pattern for tracking IDs
        $pattern = '/^[A-Z0-9]{3}-[A-Z0-9]{3}-[A-Z0-9]{3}$/'; // Assuming tracking ID consists of 10 characters, uppercase letters, digits, and hyphens only
        
        // Use preg_match to check if the trackingID matches the pattern
        return preg_match($pattern, $trackingID);
    }    

    public function webhookCheckStatus(Request $request) {
        $tag = $request->input('fulfillmentInfo.tag');
        $trackingID = $request->input('sessionInfo.parameters.tracking_id');
        $email = $request->input('sessionInfo.parameters.email');

        $appointment = Appointment::where('appointment_id', $trackingID)->where('email', $email)->exists();
        $inquiry = Inquiry::where('inquiry_id', $trackingID)->where('email', $email)->exists();
        $documentRequest = DocumentRequest::where('documentRequest_id', $trackingID)->where('email', $email)->exists();

        if ($appointment) {
            $appointment = Appointment::where('appointment_id', $trackingID)->where('email', $email)->first();

            $response = [
                'fulfillmentResponse' => [
                    'messages' => [
                        [
                            'text' => [
                                'text' => [
                                    "Here is the status of your appointment.\n
                                    Appointment ID: " . $appointment->appointment_id . 
                                    "\nAppointment Date: " . $appointment->appointment_date . 
                                    "\nAppointment Time: " . $appointment->appointment_time .
                                    "\nAppointment Status: " . $appointment->appointment_status .
                                    "\nDate Finished: " . $appointment->date_finished .
                                    "\n\nName: " . $appointment->name .
                                    "\nAddress: " . $appointment->address .
                                    "\nCellphone Number: " . $appointment->cellphone_number .
                                    "\nEmail: " . $appointment->email
                                ]
                            ]
                        ]
                    ]
                ],
                'sessionInfo' => [
                    'parameters' => [
                        'tracking_id' => $trackingID,
                        'tracking_id_checker' => "valid"
                    ],
                    'tag' => $tag
                ] 
            ];

            return response()->json($response);
        } else if ($inquiry) {
            $inquiry = Inquiry::where('inquiry_id', $trackingID)->where('email', $email)->first();

            $response = [
                'fulfillmentResponse' => [
                    'messages' => [
                        [
                            'text' => [
                                'text' => [
                                    "Here is the status of your inquiry.\n
                                    Inquiry ID: " . $inquiry->inquiry_id . 
                                    "\nName: " . $inquiry->name . 
                                    "\nEmail: " . $inquiry->email .
                                    "\nStatus: " . $inquiry->status .
                                    "\n\nInquiry: " . $inquiry->inquiry
                                ]
                            ]
                        ]
                    ]
                ],
                'sessionInfo' => [
                    'parameters' => [
                        'tracking_id' => $trackingID,
                        'tracking_id_checker' => "valid"
                    ],
                    'tag' => $tag
                ] 
            ];

            return response()->json($response);

        } else if ($documentRequest) {
            $documentRequest = DocumentRequest::where('documentRequest_id', $trackingID)->where('email', $email)->first();

            $response = [
                'fulfillmentResponse' => [
                    'messages' => [
                        [
                            'text' => [
                                'text' => [
                                    "Here is the status of your document request.\n
                                    Document Request ID: " . $documentRequest->documentRequest_id . 
                                    "\nDocument Type: " . $documentRequest->document_type .
                                    "\nStatus: " . $documentRequest->status .
                                    "\n\nName: " . $documentRequest->name . 
                                    "\nAddress: " . $documentRequest->address .
                                    "\nCellphone Number: " . $documentRequest->cellphone_number .
                                    "\nEmail: " . $documentRequest->email
                                ]
                            ]
                        ]
                    ]
                ],
                'sessionInfo' => [
                    'parameters' => [
                        'tracking_id' => $trackingID,
                        'tracking_id_checker' => "valid"
                    ],
                    'tag' => $tag
                ] 
            ];

            return response()->json($response);
        } else {
            $response = [
                'fulfillmentResponse' => [
                    'messages' => [
                        [
                            'text' => [
                                'text' => ["The tracking ID with email you provided doesn't exist."]
                            ]
                        ]
                    ]
                ],
                'sessionInfo' => [
                    'parameters' => [
                        'tracking_id' => null,
                        'tracking_id_checker' => "invalid"
                    ],
                    'tag' => $tag
                ]
            ];
            return response()->json($response);
        }
    }
}