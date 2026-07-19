@extends('emails.layouts.app')

@section('content')

<h2 style="
    margin:0 0 20px;
    font-size:26px;
    color:#0F172A;">
    {{ $title }}
</h2>


<p style="
    margin:0 0 24px;
    font-size:16px;
    line-height:28px;
    color:#475569;">
    {{ $body }}
</p>



@if(!empty($data))

<table width="100%"
    cellpadding="0"
    cellspacing="0"
    border="0"
    style="
            background:#F8FAFC;
            border:1px solid #E2E8F0;
            border-radius:8px;">

    <tr>
        <td style="padding:18px;">

            @foreach($data as $key => $value)

            <p style="margin:0 0 10px;">

                <strong style="color:#0F172A;">
                    {{ ucfirst($key) }}:
                </strong>

                <span style="color:#475569;">
                    {{ $value }}
                </span>

            </p>

            @endforeach


        </td>
    </tr>

</table>

@endif


@endsection