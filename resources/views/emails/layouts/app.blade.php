<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Docify</title>
</head>

<body style="margin:0;padding:0;background-color:#F8FAFC;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
        style="background:#F8FAFC;padding:32px 16px;">

        <tr>
            <td align="center">

                <table role="presentation"
                    cellpadding="0"
                    cellspacing="0"
                    border="0"
                    width="600"
                    style="
                        width:600px;
                        max-width:600px;
                        background:#FFFFFF;
                        border:1px solid #E2E8F0;
                        border-radius:12px;
                        overflow:hidden;">

                    <!-- Header -->
                    <tr>
                        <td
                            style="
                            padding:24px 32px;
                            background:#EEF6FF;
                            border-bottom:1px solid #E2E8F0;
                        ">

                            <table role="presentation"
                                cellpadding="0"
                                cellspacing="0"
                                border="0">

                                <tr>

                                    <!-- Logo -->
                                    <td valign="middle">
                                        <img
                                            src="{{ asset('images/logo.png') }}"
                                            alt="Docify"
                                            width="70"
                                            height="70"
                                            style="
                                            display:block;
                                            border:0;
                                        ">
                                    </td>

                                    <td style="width:18px;"></td>

                                    <!-- Name + Subtitle -->
                                    <td valign="middle">

                                        <div
                                            style="
                                            font-family:Arial,Helvetica,sans-serif;
                                            font-size:30px;
                                            line-height:36px;
                                            font-weight:700;
                                            color:#064E9B;
                                            letter-spacing:0.5px;">
                                            Docify
                                        </div>

                                        <div
                                            style="
                                            margin-top:4px;
                                            font-family:Arial,Helvetica,sans-serif;
                                            font-size:10px;
                                            line-height:20px;
                                            letter-spacing:2px;
                                            color:#64748B;">
                                            HEALTH MANAGEMENT
                                        </div>

                                    </td>

                                </tr>

                            </table>

                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="
                                padding:30px 32px 24px;
                                font-family:Arial,Helvetica,sans-serif;
                                font-size:16px;
                                line-height:28px;
                                color:#334155;">

                            @yield('content')

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="
                                padding:18px 32px;
                                border-top:1px solid #E2E8F0;
                                font-family:Arial,Helvetica,sans-serif;
                                font-size:13px;
                                line-height:22px;
                                text-align:center;
                                color:#64748B;">

                            If you didn't request this email, you can safely ignore it.

                            <br><br>

                            <span style="color:#94A3B8;">
                                © {{ date('Y') }} Docify. All rights reserved.
                            </span>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>

    </table>

</body>

</html>