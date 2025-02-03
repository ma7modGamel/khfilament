<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>kholood co</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Calibri:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap" rel="stylesheet">


    <style>
        body {
            font-family: Arial, sans-serif;
        }



        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        h1,
        h2,
        h3 {
            margin: 0;
        }

        hr {
            border: none;
            border-top: 1px solid #000;
            margin: 20px 0;
        }

        p {
            margin: 0;
        }

        .center {
            text-align: center;
        }

        body {}

        .header,
        .header-space,
        .footer,
        .footer-space {
            height: 120px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            padding: 0 !important;
            left: 35%;
            width: 100%;
            font-family: 'Montserrat', sans-serif;
            padding: 20px;
            text-align: center;
        }

        /* header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #ffffff;

        } */



        .table {
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-right: 16px;
        }

        /* .logo {
            height: 80px;
            padding: 0;
        } */

        main {
            z-index: 9999999;
            font-family: "Calibri", sans-serif;

            page-break-inside: avoid;
            /* Avoid page break inside the main section */
        }

        /* Montserrat font */
        @import url('https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap');


        .footer-text {
            font-size: 13px;
            font-family: 'Montserrat', sans-serif;
            text-align: left;
            /* Set the text alignment to left */
        }





        .letterhead {
            margin: 36px 50px 50px 40px;
        }

        table {

            margin: 16px;
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            text-align: right !important;
            padding: 8px;
            text-align: center;

        }
    </style>
</head>

<body>
    <table style="margin: 16px; width: 100%; border-collapse: collapse;">
        <thead style="text-align: right !important; padding: 8px; text-align: center;">
            <tr>
                <td style="text-align: right !important; padding: 8px; text-align: center;">
                    <div style="height: 120px;">&nbsp;</div>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: right !important; padding: 8px; text-align: center;">
                    <main dir="rtl">
                        <div style="z-index: 9999999; font-family:'Calibri', sans-serif; page-break-inside: avoid;">
                        </div>
                    </main>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td>
                    <div style="height: 120px;">&nbsp;</div>
                </td>
            </tr>
        </tfoot>
    </table>

    <header
        style="position: fixed; top: 0; left: 0; width: 100%; height: 120px; background-color: #ffffff; display: flex; align-items: center; padding: 0 20px;">
        <img src="https://kholood.com/wp-content/uploads/2024/02/for-white-background.png" alt="Logo"
            style="height: 80px; margin-right: 20px;">
        {{-- <div style="flex: 1;">
            <h1 style="font-size: 40px; margin: 0;">KTCC</h1>
            <h3 style="font-size: 20px; margin: 5px 0 0 0;">KHLOOD L.L.C</h3>
        </div>
        <div style="text-align: right;">
            <p style="font-size: 16px; margin: 0;">#7844541</p>
            <p style="font-size: 16px; margin: 5px 0 0 0;">Trading & Contracting</p>
        </div> --}}

        @php
            $project = $order->project;
            $projectEmployee = $project->projectEmployees->where('done', 0)->first();

            $user = $projectEmployee->nonEmployees;

        @endphp
        <h5 dir="rtl" style="text-align: right; margin-top: 20px;">مقدم الطلب: {{ $order->sender->name }}</h5>
        <h5 dir="rtl" style="text-align: right; margin-top: 20px;">الرقم المرجعي : {{ $order->ref_num }}</h5>
        <h5 dir="rtl" style="text-align: right; margin-top: 20px;">في انتظار الرد من   :
            {{ $user->name}}</h5>
        <h5 dir="rtl" style="text-align: right; margin-top: 20px;">تاريخ الإنشاء:
            {{ $order->created_at->format('Y-m-d H:i:s') }}</h5>
        <h5 dir="rtl" style="text-align: right; margin-top: 20px;">الحالة:
            {{ optional($order->statuses->sortByDesc('created_at')->first())->status->name }}</h5>



    </header>


    <hr>
    <div>
        <table dir="rtl" border="1" style="width: 100%; margin-bottom: 20px;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>رقم البند</th>
                    <th>وصف</th>
                    <th>الوحدة</th>
                    <th>الكمية</th>
                    <th>السعر الوحدة</th>
                    <th>الإجمالي</th>
                    <th>سعر المورد</th>
                    <th>الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $orderTotal = $order->total_price;
                    $t = 0;
                @endphp
                <tr>
                    <td>1</td>
                    <td>{{ $order->productItem?->number }}</td>
                    <td>{{ $order->description }}</td>
                    <td>{{ $order->productItem?->unit }}</td>
                    <td>{{ $order->qty }}</td>
                    <td>{{ $order->productItem?->unit_price }}</td>
                    <td>{{ $orderTotal }}</td>
                    @php
                        $quotation = $approvedQuotations->firstWhere('purchase_order_id', $order->id);
                    @endphp
                    <td>{{ $quotation ? $quotation->price : 'N/A' }}</td>
                    <td>{{ $quotation ? $quotation->price * $order->qty : 'N/A' }}</td>
                </tr>
                @if (count($order->additions) > 0)
                    @php
                        $totalAdditions = 0;
                    @endphp
                    @foreach ($order->additions as $addition)
                        @php
                            $total = $addition->price * $addition->qty;
                            $totalAdditions += $total;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration + 1 }}</td>
                            <td>{{ $addition->id }}</td>
                            <td>{{ $addition->name }}</td>
                            <td>{{ $order->productItem?->unit }}</td>
                            <td>{{ $addition->qty }}</td>
                            <td>{{ $addition->price }}</td>
                            <td>{{ $total }}</td>
                            <td>{{ $addition->price }}</td>
                            <td>{{ $total }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="6">المجموع:</td>
                        <td>{{ $totalAdditions + $orderTotal }}</td>
                        <td></td>
                        <td>{{ $totalAdditions + ($quotation ? $quotation->price * $order->qty : 0) }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

        {{-- <h4>الحالات</h4> --}}
        <table dir="rtl" border="1" style="width: 100%;">
            <thead>
                <tr>
                    <th>الراسل</th>
                    <th>الحاله</th>
                    <th>date</th>
                    <th>التوقيع</th>

                </tr>
            </thead>
            <tbody>
                @if (count($order->Statuses) > 0)

                    @foreach ($order->Statuses as $status)
                        <tr>
                            <td>{{ $status->sender?->name }}</td>
                            <td>{{ $status->status?->name }}</td>
                            <td>{{ $status->created_at }}</td>
                            {{-- <td>{{  public_path() .$status->sender?->signature }}</td> --}}
                            <td>@php
                                    $path = public_path() .'/storage/'.$status->sender?->signature;
                                @endphp
                                @if (is_file($path))
                                    <img src="{{ $path }}" alt="Signature" style="width: 100px; height: auto;">
                                @else
                                    <p>لا يوجد توقيع</p>
                                @endif</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
{{--    @if($approvedQuotations->isNotEmpty())--}}
{{--        <div>--}}
{{--            <h3 class="center">سعر المورد</h3>--}}
{{--            <table dir="rtl" border="1" style="width: 100%;">--}}
{{--                <thead>--}}
{{--                <tr>--}}
{{--                    <th>اسم المورد</th>--}}
{{--                    <th>السعر</th>--}}
{{--                    <!-- Add more headers as needed -->--}}
{{--                </tr>--}}
{{--                </thead>--}}
{{--                <tbody>--}}
{{--                @foreach($approvedQuotations as $quotation)--}}
{{--                    <tr>--}}
{{--                        <td>{{ $quotation->importer_name }}</td>--}}
{{--                        <td>{{ $quotation->price }}</td>--}}
{{--                        <!-- Add more fields as needed -->--}}
{{--                    </tr>--}}
{{--                @endforeach--}}
{{--                </tbody>--}}
{{--            </table>--}}
{{--        </div>--}}
{{--    @endif--}}




    <footer
        style="height: 120px; position: fixed; bottom: 0; padding: 0 !important; left: 35%; width: 100%; font-family: 'Montserrat', sans-serif; padding: 20px; text-align: center;">
        <div>
            <div>
                <p style="font-size: 13px; font-family: 'Montserrat', sans-serif; text-align: left;">
                    <i class="fa-solid fa-location-dot" style="color: #e55326;"></i>
                    C.R:1010020305 - C.C.NO : 1455 PO. BOX 2976 Riyadh 11461
                </p>
                <p style="font-size: 13px; font-family: 'Montserrat', sans-serif; text-align: left;">
                    <i class="fas fa-phone" style="color: #e55326;"></i>
                    <span>966 114773676/114761627/11 4765745</span>
                </p>
                <p style="font-size: 13px; font-family: 'Montserrat', sans-serif; text-align: left;">
                    <i class="fas fa-envelope" style="color: #e55326;"></i>
                    <span>contact@kholood.com</span>
                </p>
                <p style="font-size: 13px; font-family: 'Montserrat', sans-serif; text-align: left;">
                    <i class="fas fa-globe" style="color: #e55326;"></i>
                    <span>www.kholood.com</span>
                </p>
                <p style="font-size: 13px; font-family: 'Montserrat', sans-serif; text-align: left;">
                    <i class="fab fa-facebook-f" style="color: #e55326;"></i>
                    <i class="fab fa-twitter" style="color: #e55326;"></i>
                    <i class="fab fa-instagram" style="color: #e55326;"></i>
                    <i class="fab fa-linkedin" style="color: #e55326;"></i>
                    <span>KTCC</span>
                </p>
            </div>
        </div>
    </footer>

    <img src="https://kholood.com/wp-content/uploads/2024/05/watermark.png" alt="Watermark"
        style="position: fixed; top: 180px; left: 16px; width: auto; height: 80%; transform-origin: bottom left; z-index: -1;" />
</body>

</html>
make the cmpany details just next to image i mena <div style="margin: 36px 50px 50px 40px;">
    <table style="width: 100%;">
        <tr>
            <td style="text-align: left;">
                <h1 style="font-size: 40px; margin-bottom: 0;">KTCC</h1>
            </td>
            <td style="text-align: right;">
                <p style="font-size: 16px;">#7844541</p>
            </td>
        </tr>
        <tr>
            <td style="text-align: left;">
                <h3 style="font-size: 20px; margin-top: 0;">KHLOOD L.L.C</h3>
            </td>
            <td style="text-align: right;">
                <p style="font-size: 16px;">Trading & Contracting</p>
            </td>
        </tr>
    </table>
</div>
