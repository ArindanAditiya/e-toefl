<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sertifikat {{ $grade->category->name }} - {{ $grade->user->name }}</title>
</head>
<style>
    html,
    body {
        width: 100vw;
        height: 100vh;
    }

    body {
        padding: 0;
        margin: 0;
        overflow: hidden;
    }

    @font-face {
        font-family: "Arial";
        src: url("{{ storage_path('fonts/lobster.ttf')}}") format("truetype");
    }

    .inbackground {
        top:25%;
        border:1px solid yellow;
        height:700px;
    }

    .certificate_name {
        color: #332B28;
        font-family: "Arial";
        font-size: 3rem;
        margin-top:21%;
    }

    .test {
        position: relative;
    }
    td{
        font-size: 20px;
    }
</style>

<body>
    <div style="position:fixed;left: 0px; right: 0px; bottom: 0px; text-align: center;z-index: -1000;">
        <img src="{{ env('APP_URL') }}/assets/certificate/toefl.jpg" style="width: 100%;height: 100%;">
    </div>
    <div class="inbackground">
        <h2><br />
            <p class="certificate_name" align="center">
                {{ $grade->user->name }}
            </p>
        </h2>
        <h3 align="center" align="center" style="margin-top:-40px;">Achieved the following scores on the</h3>
        <h2 align="center" align="center" style="margin-top:-10px"><i>{{ $grade->exam->title}}</i></h2>
        <table align="center">
            @foreach($grade->gradeDetail as $gradeDetail)
                <tr>
                    <td>{{ $gradeDetail->valueCategory->name }}</td>
                    <td width="20px" align="center">:</td>
                    <td>{{ $gradeDetail->grade }}</td>
                </tr>
            @endforeach
            <tr>
                <td>Total Score</td>
                <td align="center">:</td>
                <th><bold>{{ $grade->grade }}</bold></th>
            </tr>
        </table>
        <p style="margin-left: 120px; margin-top: 45px">Date : {{ $grade->end_time }}
            <br />At : {{ env('APP_URL') }}
        </p>

    </div>
</body>

</html>
