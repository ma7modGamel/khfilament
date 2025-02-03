
{{-- <!DOCTYPE html>
<html>

<head>
    <title>KTCC Letterhead</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
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
            padding: 0;
        }

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

        .watermark {
            position: fixed;
            top: 180px;
            left: 16px;
            width: auto;
            height: 80%;
            transform-origin: bottom left;

            z-index: -1;
        }

        footer p {
            margin: 2px;
            padding: 2px;
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


        body {
            background: rgb(204, 204, 204);
        }

        page {
            background: white;
            display: block;
            margin: 0 auto;
            margin-bottom: 0.5cm;
            box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.5);
        }

        page[size="A4"] {
            width: 21cm;
            height: 29.7cm;
        }

        page[size="A4"][layout="landscape"] {
            width: 29.7cm;
            height: 21cm;
        }

        page[size="A3"] {
            width: 29.7cm;
            height: 42cm;
        }

        page[size="A3"][layout="landscape"] {
            width: 42cm;
            height: 29.7cm;
        }

        page[size="A5"] {
            width: 14.8cm;
            height: 21cm;
        }

        page[size="A5"][layout="landscape"] {
            width: 21cm;
            height: 14.8cm;
        }
    </style>
</head>

<body>
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
    <p dir="rtl" style="text-align: right; margin-top: 20px;">مقدم الطلب : {{ $order->sender->name }}</p>
    <hr>
    <div class="center">
        <table dir="rtl">
            <tr>
                <th>#</th>
                <th>رقم العقد</th>
                <th>وصف السلعة</th>
                <th>الوحدة</th>
                <th>الكمية</th>
                <th>السعر الوحدة</th>
                <th>الإجمالي</th>
            </tr>
            <tr>
                <td>1</td>
                <td>{{ $order->ref_num }}</td>
                <td>{{ $order->description }}</td>
                <td>{{ $order->qty }}</td>
                <td>{{ $order->single_price }}</td>
                <td>{{ $order->total_price }}</td>
                <td>17.00</td>
            </tr>
            @if (count($order->additions) > 0)
                @foreach ($order->additions as $addition)
                    <tr>
                        <td>1</td>
                        <td>{{ $addition->name }}</td>
                        <td>{{ $addition->qty }}</td>
                        <td>{{ $addition->price }}</td>
                    </tr>
                @endforeach
            @endif
        </table>
    </div>
    
    <hr>
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-info">
                <p class="footer-text">
                    <i class="fa-solid fa-location-dot" style="color: #e55326;"></i>
                    C.R:1010020305 - C.C.NO : 1455 PO. BOX 2976 Riyadh 11461
                </p>
                <p class="footer-text">
                    <i class="fas fa-phone" style="color: #e55326;"></i>
                    <span>966 114773676/114761627/11 4765745</span>
                </p>
                <p class="footer-text">
                    <i class="fas fa-envelope" style="color: #e55326;"></i>
                    <span>contact@kholood.com</span>
                </p>
                <p class="footer-text">
                    <i class="fas fa-globe" style="color: #e55326;"></i>
                    <span>www.kholood.com</span>
                </p>
                <p class="footer-text">
                    <i class="fab fa-facebook-f" style="color: #e55326;"></i>
                    <i class="fab fa-twitter" style="color: #e55326;"></i>
                    <i class="fab fa-instagram" style="color: #e55326;"></i>
                    <i class="fab fa-linkedin" style="color: #e55326;"></i>
                    <span>KTCC</span>
                </p>
            </div>
        </div>
    </footer>
</body>

</html> --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A4 Page Layout with Watermark</title>
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> --}}
    {{-- <link rel="preconnect" href="https://fonts.gstatic.com"> --}}
    {{-- <link href="https://fonts.googleapis.com/css2?family=Calibri:wght@400;700&display=swap" rel="stylesheet"> --}}
    {{-- <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap" rel="stylesheet"> --}}
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

    <header style="position: fixed; top: 0; left: 0; width: 100%; height: 120px; background-color: #ffffff;">
        <img src="https://kholood.com/wp-content/uploads/2024/02/for-white-background.png" alt="Logo"
            style="height: 80px; padding: 0;">
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
    </header>

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
