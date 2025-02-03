<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A4 Page Layout with Watermark</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        direction: rtl;
        text-align: right;
        margin: 20px;
        background-color: #ffffff00;
    }

    .container {
        background-color: #ffffff00;
        padding: 20px;
        max-width: 600px;
        margin: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 16px 0;
    }

    th,
    td {
        padding: 8px;
        text-align: center;
    }

    .header,
    .footer {
        position: fixed;
        width: 100%;
        left: 0;
        background-color: #ffffff;
    }

    .header {
        top: 0;
        height: 120px;
    }

    .footer {
        bottom: 0;
        height: 130px;
        text-align: center;
        font-family: 'Montserrat', sans-serif;
        padding: 20px;
    }

    .watermark {
        position: fixed;
        top: 180px;
        left: 16px;
        width: auto;
        height: 80%;
        transform-origin: bottom left;
        z-index: -1;
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

    .footer p {
        margin: 0.5;
        font-size: 13px;
        line-height: 13px;
    }

    .footer i {
        color: #e55326;
    }
    </style>
</head>

<body>
    <header class="header">
        <div class="logo"> <img src="https://kholood.com/wp-content/uploads/2024/02/for-white-background.png"
                alt="Logo"></div>

        <div class="TextHeader">
            <p style="font-size: 17px;">الرقم المرجعى لأذن الصرف:
                {{$order->disbursementordernumber  }} </p>
            <p style="font-size: 17px;">الرقم المرجعى لطلب الشراء:
                {{$order->purchase_code}} </p>

            <p style="font-size: 17px;">تاريخ اصدار امر الصرف:
                {{Carbon\Carbon::now()->translatedFormat('l Y-m-d H:i') }}</p>

        </div>
        <div class="clear"></div>
        <h2 style=" text-align: center;">طلب صرف</h2>
        <hr>
    </header>

    <div class="container" style="padding-top:110px ">
        <table>
            <tbody>
                <tr>
                    <td>تاريخ طلب الشراء:</td>
                    <td>{{\Carbon\Carbon::parse($order->purchase_date)->translatedFormat('l Y-m-d H:i')}}</td>
                </tr>
                <tr>
                    <td>المشـــــروع:</td>
                    <td>{{$order->project_name}}</td>
                </tr>

                <tr>
                    <td>الموظف:</td>
                    <td>{{$order->purchase_order['sender']['name']}}</td>
                </tr>
                <tr>
                    <td>يصرف لامر:</td>
                    <td>{{ $order->purchase_order['quotations'][0]['supplier']['name'] ?? 'غير متوفر' }}</td>

                </tr>
                <tr>
                    <td>القيمة الاجمالية:</td>
                    <td> {{$order->residual_value}} ريال سعودى فقط <span style=" border-bottom: 1px solid black;">
                            {{convertNumberToArabicWords($order->residual_value) }}
                            ريال سعودى</span></td>

                <tr>
                    <td>مبلغ وقدرة:</td>

                    <td>{{$order->total_value}} ريال سعودى فقط <span
                            style="border-bottom: 1px solid black;">{{convertNumberToArabicWords($order->total_value) }}
                            ريال سعودى</span></td>

                    {{-- <td>فقط <span style="border-bottom: 1px solid black;">{{$order->total_value}}</td> --}}
                </tr>

                <tr>
                    <td>بموجب:</td>
                    @php
                    $optionmethod=[
                    'cash' => 'عهده',
                    'cheque' => 'شيك',
                    'bank_transfer' => 'تحويل بنكي',
                    ]

                    @endphp
                    <td>
                        <label><input type="radio" name="payment_method" style="margin-right: 5px;">
                            {{ $optionmethod[$order->payment]??'غير محدد'}}</label>
                    </td>
                </tr>
            </tbody>
        </table>

        <table dir="rtl" border="1">
            <thead>
                <tr>
                    <th>مسلسل</th>
                    <th>اسم المحاسب</th>
                    <th>التوقيع</th>
                    <th>ملاحظات الحسابات</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>{{$one }}
                    </td>
                    <td>
                        @if($sigCheck)
                        <img src="{{ asset($sig) }}" alt="توقيع المحاسب" style="height: 40px; width: auto;">
                        @else
                        "غير محدد"
                        @endif
                    </td>
                    <td>{{ $order->notes }}</td>
                </tr>
            </tbody>
        </table>

    </div>

    <footer class="footer">
        <div style="text-align: left;">
            <p><i class="fa-solid fa-location-dot"></i> C.R:1010020305 - C.C.NO : 1455 PO. BOX 2976 Riyadh 11461</p>
            <p><i class="fas fa-phone"></i> 966 114773676/114761627/11 4765745</p>
            <p><i class="fas fa-envelope"></i> contact@kholood.com</p>
            <p><i class="fas fa-globe"></i> www.kholood.com</p>
            <p>
                <i class="fab fa-facebook-f"></i>
                <i class="fab fa-twitter"></i>
                <i class="fab fa-instagram"></i>
                <i class="fab fa-linkedin"></i>
                <span>KTCC</span>
            </p>
        </div>
    </footer>

    <img src="https://kholood.com/wp-content/uploads/2024/05/watermark.png" alt="Watermark" class="watermark"
        style="opacity: 0.1; width: 100%; height: 100%; position: fixed; top: 0; left: 0; z-index: -1;">
</body>

</html>