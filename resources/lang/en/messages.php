<?php

return [
    "documents" => [
        "title" => "Documents",
        "group" => "Content",
        "single" => "Document",
        "form" => [
            "ref" => "Reference",
            "id" => "ID",
            "model" => "Connected To",
            "document_template_id" => "Template",
            "document" =>  "Document",
            "body" =>  "Body",
            "is_send" =>  "Is Send",
            "template" => "Template",
            "values" => "Values",
            "var-value" => "Value",
            "var-label" => "Label",
        ],
        "actions" => [
            "print" => "Print",
            "document" => [
                "title" => "Create Document",
                "notification" => [
                    "title" => "Document Created",
                    "body" => "Document has been created",
                    "action" => "View Document",
                ]
            ]
        ]
    ],
    "document-templates" => [
        "title" => "Document Templates",
        "group" => "Content",
        "single" => "Template",
        "form" => [
            "name" => "Name",
            "vars" => "Variables",
            "vars-key" => "Key",
            "vars-label" => "Value",
            "is_active" => "Is Active",
            "body" => "Body",
            "icon" => "Icon",
            "color" => "Color",
        ]
    ],
    "vars" => [
        "day" => "Day",
        "date" => "Date",
        "time" => "Time",
        "random" => "Random",
        "uuid" => "UUID",
    ],
];
