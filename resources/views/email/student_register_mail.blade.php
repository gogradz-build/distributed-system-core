<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Email Template</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background: linear-gradient(270deg, rgba(33, 15, 18, 1) 5%, rgba(76, 13, 23, 1) 35%, rgba(178, 30, 53, 1) 100%);
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }

        .email-header img {
            max-width: 150px;
        }

        .email-body {
            padding: 20px;
        }

        .email-body h1 {
            font-size: 24px;
            margin-bottom: 15px;
        }

        .email-body p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .email-footer {
            background-color: #f4f4f4;
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #666;
        }

        .button {
            display: inline-block;
          
            color: #ffffff;
            text-decoration: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            margin: 10px 5px;
        }

        .button:hover {
            background-color: #45a049;
        }

        @media screen and (max-width: 600px) {
            .email-body h1 {
                font-size: 20px;
            }

            .email-body p {
                font-size: 14px;
            }

            .button {
                font-size: 14px;
                padding: 8px 15px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <img src="{{ url('images/logo.png') }}" alt="Company Logo">
            <h2>NATIONS HR ACADEMY</h2>
        </div>
        <div class="email-body">
            <h1>Welcome to Nations HR Academy!</h1>
            <p>{{ $mailData['message'] }}</p>
            <p>
                Hello {{ $mailData['name'] }}, <br>
                Congratulations on successfully registering with Nations HR Academy! We're excited to have you join our
                learning community. Below are your login credentials to access our platform:
            </p>
            <p><strong>Username:</strong> {{ $mailData['user_name'] }}</p>
            <p><strong>Password:</strong> {{ $mailData['password'] }}</p>
            <p>
              Use these credentials to log in and get started with your courses and resources. 
            </p>
            <p>
              If you have any questions or need assistance, feel free to contact us at any time.
            </p>
        </div>
        <div class="email-footer">
            <p>&copy; 2024 GoGradz. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
