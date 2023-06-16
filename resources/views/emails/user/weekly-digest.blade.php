<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Weekly Digest</title>
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
        <h1>Your weekly digest</h1>

        <p>Here is your weekly report for the period from {{ $weekly_info['startDate'] }} to {{ $weekly_info['endDate'] }}:</p>

        <h2>Summary</h2>
        <table>
            <thead>
                <tr>
                    <th>Metrics</th>
                    <th>This Week</th>
                    <th>Last Week</th>
                    <th>Change</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>New Orders</td>
                    <td>{{ $weekly_info['newOrdersThisWeek'] }}</td>
                    <td>{{ $weekly_info['newOrdersLastWeek'] }}</td>
                    <td>{{ $weekly_info['newOrdersChange'] }}%</td>
                </tr>
                <tr>
                    <td>New Customers</td>
                    <td>{{ $weekly_info['newCustomersThisWeek'] }}</td>
                    <td>{{ $weekly_info['newCustomersLastWeek'] }}</td>
                    <td>{{ $weekly_info['newCustomersChange'] }}%</td>
                </tr>
            </tbody>
        </table>


    <h2>Orders Due This Week</h2>
    <table>
        <thead>
            <tr>
                <th>Customer</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($weekly_info['orders'] as $order)
                <tr>
                    <td>{{ $order->customer->name }}</td>
                    <td>{{ $order->total_amount }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p>See more by visiting your dashboard <a href="https://app.handyseam.com/login">here</a> </p>
    <p class="signature">Best regards,<br>The HandySeam Team</p>
</div>
