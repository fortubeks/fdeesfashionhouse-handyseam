<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome To The HandySeam</title>
    <style>
        body {
            background-color: #f5f5f5;
            color: #444444;
            font-family: Arial, sans-serif;
        }
        .container {
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0px 0px 5px #aaaaaa;
            margin: 20px auto;
            padding: 20px;
            width: 80%;
        }
        h1 {
            color: #222222;
            font-size: 28px;
            margin-bottom: 20px;
            text-align: center;
        }
        h2 {
            color: #222222;
            font-size: 20px;
            margin-bottom: 10px;
            margin-top: 30px;
            text-align: left;
            text-transform: uppercase;
        }
        table {
            border-collapse: collapse;
            margin-bottom: 30px;
            width: 100%;
        }
        table th {
            background-color: #eeeeee;
            border: 1px solid #dddddd;
            font-size: 16px;
            font-weight: bold;
            padding: 10px;
            text-align: center;
            text-transform: uppercase;
        }
        table td {
            border: 1px solid #dddddd;
            font-size: 14px;
            padding: 10px;
            text-align: center;
        }
        .signature {
            color: #888888;
            font-size: 14px;
            font-style: italic;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Dear {{$user->name}},</h1>

        <p>Welcome To The HandySeam. Your digital portal to manage and grow your fashion and tailoring business</p>
        <p>My name is Fortune & I will be your guide to make sure you get the most out of this software. </br>
            Handyseam was built to allow you access your portal from any internet enabled device: Laptop, Tablet or Mobile Phone </br>
            Below are vital informations you should be in sync with
        </p>

        <h2>Demo & Overview</h2>
        <p><a href="https://www.youtube.com/watch?v=OlwkXZ5_qYI&t=32s">Watch Software Demo Overview</a></p>

        <h2>Functions of the software</h2>
        <ul>
            <li>Save & access all your customer information including their measurements </li>
            <li>Upload images of the styles they want while creating their job/order</li>
            <li>Create & send them invoices </li>
            <li>Assign jobs/orders to specific tailors </li>
            <li>Manage your stock & inventory, including fabrics, appliques and ready to wear (RTW)</li>
            <li>Marketing: keep your customers coming back by bulksms & whatsapp messages</li>
            <li>Financial Report: weekly report of how your your business is doing</li>
        </ul>
        <h2>Plans</h2>
        <ul>
            <li>Free: This plan is free and has all the functions except maketing and financial reporting</li>
            <li>Premium: This plan costs NGN2,500 or $3 monthly and includes all the features of handyseam and future updates and features</li>
        </ul>

        <p>Hit me up on +2348090839412 phone or whatsapp if you require any assistance using the software. Our email support is available 24/7 </p>
        <p class="signature">Best regards,<br>Fortune Bekee </br>From The HandySeam Team</p>
</div>
