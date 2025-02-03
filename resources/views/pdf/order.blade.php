<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>kholood co</title>
    <linktr rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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


        hr {
            border: none;
            border-top: 1px solid #000;
            margin: 20px 0;
        }


        .center {
            text-align: center;
        }



        .header,
        .footer {
            position: fixed;
            width: 100%;
            height: 200px;
            background-color: #ffffff;
        }

        .header {
            top: 0;
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

        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #ffffff;

        }


        .table {
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-right: 16px;
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

        main {
            z-index: 9999999;
            font-family: "Calibri", sans-serif;

            page-break-inside: avoid;
            /* Avoid page break inside the main section */
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

    <header class="header">
        <div class="logo"> <img src="https://kholood.com/wp-content/uploads/2024/02/for-white-background.png"
                alt="Logo"></div>



        @php
        use App\Models\ProjectUser;use App\Models\User;$project = $order->project;

        $user = null;
        $last_user=$order->statuses->sortByDesc('created_at')->first();
        $distribution_order=$order->finishDisbursementOrder();

        $project_users =ProjectUser::query()->where('project_id',$order->project->id)
        ->where('management_type', 'purchase_order')
        ->orderBy('order')->get();
        if ($last_user->status_id==1){
        $next=$project_users->first();
        }
        else {
        if ($project_users->contains('user_id',$last_user->sender_id))
        {
        $project_user = $project_users->where('user_id', $last_user->sender_id)->first();
        $next = $project_users->skipWhile(function ($item) use ($project_user) {
        return $item->id != $project_user->id;
        })->skip(1)->first();
        }
        }
        if ($next){
        $user= User::query()->find($next->user_id);
        }

        @endphp

        <div class="TextHeader">
            <p dir="rtl">مقدم الطلب:
                {{ $order->sender->name }}
            </p>
            <p dir="rtl">الرقم المرجعي :
                {{ $order->ref_num }}
            </p>
            <p dir="rtl">المشروع: {{ $project->name }}
            </p>
            <p dir="rtl">في انتظار الرد من :
                {{ $user ?$user->name:($distribution_order?'منتهى وتم اصدار اذن صرف':'منتهى وفى انتظار اذن صرف')}}

            </p>
            <p dir="rtl" style="text-align: right;">تاريخ الإنشاء:
                {{ $order->created_at->format('Y-m-d H:i:s') }}</p>
            <p dir="rtl" style="text-align: right;">الحالة:
                {{ optional($order->statuses->sortByDesc('created_at')->first())->status->name }}</p>

        </div>

        <div class="clear"></div>
    </header>


    <hr>
    <h3 style="text-align:center;"> طلب الشراء</h3>
    <div>
        <table dir="rtl" border="1" style="width: 100%; margin-bottom: 20px;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>رقم البند</th>
                    <th>الاسم</th>
                    <th>الكمية</th>
                    <th>الوحدة</th>
                    <th>السعر الوحدة جدول الكميات</th>
                    <th>إجمالي البند من المنافسه</th>
                    <th>سعر الافرادى للمورد</th>
                    <th> إجمالي المورد</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($order->items as $order_item)
                <tr>
                    <td>1</td>
                    <td>{{ $order_item->item->number }}</td>
                    <td>{{ $order_item->name }}</td>

                    <td>{{ $order_item->qty }}</td>
                    <td>{{ $order_item->item->unit }}</td>
                    <td>{{ $order_item->item->unit_price }}
                        <br>
                        {{convertNumberToArabicWords($order_item->item->unit_price) }}
                        <span>ريال</span>
                    </td>

                    <td>{{ $order_item->item->unit_price * $order_item->qty }}
                        <br>
                        {{convertNumberToArabicWords( $order_item->item->unit_price * $order_item->qty  ) }}
                        <span>ريال</span>
                    </td>
                    @php
                    $quotation = $order->quotations->firstWhere('approved',1);
                    @endphp
                    <td>
                        {{ $quotation ? $quotation->price : 'لم يتم تحديد عرض  سعر بعد' }}
                        {{convertNumberToArabicWords( $quotation ? $quotation->price : 'لم يتم تحديد عرض  سعر بعد') }}

                    </td>
                    <td>{{ $quotation ? $quotation->price * $order_item->qty : 'لم يتم تحديد عرض  سعر بعد' }}

                        {{convertNumberToArabicWords($quotation ? $quotation->price * $order_item->qty : 'لم يتم تحديد عرض  سعر بعد' ) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="9" style="text-align: right;">
                        <strong>وصف:</strong>
                        {{ $order_item->description }}
                    </td>
                </tr>

                @endforeach
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
                    <td>
                        @php
                        $path = public_path() .'/storage/'.$status->sender?->signature;
                        @endphp
                        @if (is_file($path))
                        <img src="{{ $path }}" alt="Signature" style="width: auto; height: 40px;">
                        @else
                        <p>لا يوجد توقيع</p>
                        @endif
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
        @if($quotation && $quotation->supplier->files )
        <h3 class="center">ملفات المورد</h3>
        <table dir="rtl" border="1" style="width: 100%;">
            <thead>
                <tr>
                    <th>اوراق ثبوتية المورد</th>
                    <!-- Add more headers as needed -->
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td>

                        @if($quotation->file)
                        <a href="{{ asset(\Storage::url($quotation->file)) }}" style="text-align: center"
                            target="_blank">تحميل عرض السعر الموافق عليه</a>
                        @endif

                    </td>

                    <!-- Add more fields as needed -->
                </tr>

                @foreach($quotation->supplier->files as $file)
                <tr>
                    <td>
                        @if($file)
                        <a href="{{ asset(\Storage::url($file)) }}" style="text-align: center" target="_blank">افتح
                            الملف </a>
                        @endif
                    </td>

                    <!-- Add more fields as needed -->
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>



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
make the cmpany details just next to image i mena
<div style="margin: 36px 50px 50px 40px;">
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