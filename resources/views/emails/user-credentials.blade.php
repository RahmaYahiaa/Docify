@extends('emails.layouts.app')

@section('content')

<h2 style="margin:0 0 20px;font-size:26px;color:#0F172A;">
    Welcome, {{ $user->first_name }}
</h2>

<p style="margin:0 0 24px;font-size:16px;line-height:26px;color:#475569;">
    Your account has been created successfully by the administrator.
    You can now sign in using the credentials below.
</p>

<table role="presentation"
    width="100%"
    cellpadding="0"
    cellspacing="0"
    border="0"
    style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:10px;margin:24px 0;">

    <tr>
        <td style="padding:20px;">

            <p style="margin:0 0 16px;">
                <strong style="color:#0F172A;">Email</strong><br>
                <span style="color:#475569;">{{ $user->email }}</span>
            </p>

            <p style="margin:0;">
                <strong style="color:#0F172A;">Temporary Password</strong><br>
                <span style="color:#475569;">{{ $password }}</span>
            </p>

        </td>
    </tr>

</table>

<p style="margin:0 0 28px;font-size:15px;color:darkred;">
    For your security, please change your password after your first login.
</p>

<!-- <table role="presentation" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td bgcolor="#0d2455" style="border-radius:8px;">
            <a href="{{ url('/login') }}"
               style="
                    display:inline-block;
                    padding:14px 28px;
                    color:#ffffff;
                    text-decoration:none;
                    font-size:15px;
                    font-weight:bold;">
                Login to Your Account
            </a>
        </td>
    </tr>
</table> -->

@endsection