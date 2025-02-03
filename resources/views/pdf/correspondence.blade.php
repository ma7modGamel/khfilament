<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KHOLOOD L.L.C</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Calibri:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap" rel="stylesheet">
    <!-- googleFonts -->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bokor&family=Cairo:wght@200..1000&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Oswald:wght@200..700&display=swap"
        rel="stylesheet">


    <style>
    * {
        font-family: "Cairo", sans-serif !important;

    }

    body {
        font-family: "Cairo", sans-serif !important;
        padding-top: 140px;
        padding-bottom: 140px;

    }

    th,
    td {
        border: 1px solid #000;
        padding: 8px;
        text-align: right !important;
        font-family: "Cairo", sans-serif;

    }

    table {
        page-break-inside: auto;
    }

    thead {
        background-color: #f1f1f1;
    }

    td,
    th {
        word-wrap: break-word;
    }

    th {
        font-size: 1rem;
        font-weight: 500;
    }


    hr {
        border: none;
        border-top: 1px solid #000;
        margin: 20px 0;
    }

    p {
        margin: 0;
        font-size: 20px;
        text-align: left;
    }

    header,
    footer {
        height: 160px;
        margin-bottom: 20px;
    }

    footer {
        position: fixed;
        bottom: 0;
        /*padding: 0 !important;*/
        left: 35%;
        width: 100%;
        padding: 20px;
        text-align: center;
    }

    header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background-color: #ffffff;
        display: flex;
        align-items: center;
        padding: 20px;
        box-sizing: border-box;
        flex-direction: row;

    }


    .logo {
        height: 80px;
        padding-top: 10px;
        width: 150px;
        float: left;
    }

    .TextHeader {
        font-size: 12px;
        padding-bottom: 10px;
        float: right;
    }

    .clear {
        clear: both;
    }

    .headerInfo h4 {

        margin: 5px 0;
        font-weight: 400;
    }

    .headerInfo {
        width: 50%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: right;
        direction: rtl;
        font-weight: 500;
    }

    .projectInfo {
        margin: 10px;
        text-align: center;
    }

    main {
        page-break-inside: avoid;
    }

    .watermark {
        position: fixed;
        top: 180px;
        left: 16px;
        width: auto;
        height: 80%;
        transform-origin: bottom left;
        opacity: 0.1;
    }
    </style>
</head>

<img src="https://kholood.com/wp-content/uploads/2024/05/watermark.png" alt="Watermark" class="watermark" />

<body>
    <table style="margin: 16px; width: 100%; border-collapse: collapse;">
        <thead style="text-align: right !important; padding: 8px; text-align: center;">
            <tr>
                <td style="text-align: right !important; padding: 8px; text-align: center;">
                    <div style="height: 120px;">&nbsp;</div>
                </td>
            </tr>
        </thead>

        <tfoot>
            <tr>
                <td>
                    <div style="height: 120px;">&nbsp;</div>
                </td>
            </tr>
        </tfoot>
    </table>
    <hr>
    <header class="header">
        <div class="logo"> <img src="https://kholood.com/wp-content/uploads/2024/02/for-white-background.png"
                alt="Logo"></div>
        <div class="headerInfo">
            <h4 dir="rtl">رقم المستند:{{ $correspondence->number }}</h4>
            <h4 dir="rtl">المشروع : {{ $correspondence->project?->name }}
            </h4>
            <h4 dir="rtl">تاريخ المستند: {{ $correspondence->date}}</h4>
            <h4 dir="rtl">الجهه:
                {{ $correspondence->correspondent?->modelable?->name }}</h4>
            <h4 dir="rtl">نوع المستند:
                {{ $correspondence->correspondence_document?->type }}</h4>
            <h4 dir="rtl">حالة المستند:
                {{ $correspondence->trackings->isEmpty()?'جديد':$correspondence->trackings->last()->toUser->name }}</h4>
        </div>
        <div class="clear"></div>
    </header>
    <hr>
    <hr>
    <div class="projectInfo">
        @php
        $filepath = url('storage/' . $correspondence->file);
        $projectDescription = $correspondence->description;
        $projectType= $correspondence->correspondence_document?->type ;
        @endphp
        <b> وصف المستند :</b>
        {{$projectDescription}}
        <br>
        <b>النوع:</b> {{$projectType}}
        <div>
            <br>
            <br>
            <a href="{{$filepath}}" target="_blank" style="text-decoration: none;"> افتح
                الملف
            </a>
        </div>

    </div>
    <hr>

    <table dir="rtl" border="1" style="margin: 16px; width: 100%; border-collapse: collapse;">
        <thead style="text-align: right !important; padding: 8px; text-align: center;">
            <tr style="text-align: right !important; padding: 8px; text-align: center;">
                <th>مسلسل</th>
                <th>الراسل</th>
                <th>الى</th>
                <th>تاريخ الاحاله</th>
                <th>وقت الاحاله</th>
                <th>التوقيع</th>
                <th>ملف الملاحظة</th>
            </tr>
        </thead>
        <tbody>
            @if ($correspondence->trackings->isNotEmpty())
            @foreach ($correspondence->trackings as $track)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $track->fromUser?->name }}</td>
                <td>{{ $track->toUser?->name }}</td>
                <td>{{ $track->request_date->format('d/m/Y') }}</td>
                <td>{{ $track->request_date->format('H:i ') }}</td>
                <td>
                    @php
                    $path=asset(\Storage::url($track->signature))
                    @endphp
                    <img src="{{$path}}" alt="Signature" style="height:40px; width:auto;">
                </td>
                <td>
                    @if($track->file)
                    <a href="{{ asset(\Storage::url($track->file)) }}" style="text-align: center" target="_blank">افتح
                        الملف</a>
                    @else
                    <p>لا يوجد</p>
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="7">
                    <strong>ملاحظات:</strong> {{ $track->notes }}
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>



    <footer style="text-align: justify;">
        <div>
            <div>
                <h5>
                    C.R:1010020305 - C.C.NO : 1455 PO. BOX 2976 Riyadh 11461
                </h5>
                <h5>
                    <span>966 114773676/114761627/11 4765745</span>
                </h5>
                <h5>
                    <span>contact@kholood.com</span>
                </h5>
                <h5>
                    <span>www.kholood.com</span>
                </h5>
                <h5>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="10" height="24">
                        <path
                            d="M512 256C512 114.6 397.4 0 256 0S0 114.6 0 256C0 376 82.7 476.8 194.2 504.5V334.2H141.4V256h52.8V222.3c0-87.1 39.4-127.5 125-127.5c16.2 0 44.2 3.2 55.7 6.4V172c-6-.6-16.5-1-29.6-1c-42 0-58.2 15.9-58.2 57.2V256h83.6l-14.4 78.2H287V510.1C413.8 494.8 512 386.9 512 256h0z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="10" height="24">
                        <path
                            d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="10" height="10">
                        <path
                            d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="10px">
                        <path
                            d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z" />
                    </svg>
                    <div>KTCC</div>
                </h5>
            </div>
        </div>
    </footer>
</body>

</html>