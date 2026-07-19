@extends('emails.layouts.app')

@section('content')


<h2 style="
    margin:0 0 20px;
    font-size:23px;
    color:#0F172A;">
    Verification Code
</h2>


<p style="
    margin:0 0 24px;
    font-size:16px;
    line-height:28px;
    color:#475569;">

    Please use the code below to verify your account.

</p>



<table width="100%"
    cellpadding="0"
    cellspacing="0"
    border="0"
    style="
        background:#F8FAFC;
        border:1px solid #E2E8F0;
        border-radius:10px;">

    <tr>

        <td align="center" style="padding:25px;">


            <p style="
    margin:0;
    font-size:34px;
    font-weight:700;
    letter-spacing:8px;
    color:#064E9B;">

                {{ $otp }}

            </p>


        </td>

    </tr>

</table>



<p style="
    margin:24px 0 0;
    font-size:14px;
    color:darkred;">

    This code expires in 5 minutes.

</p>


@endsection