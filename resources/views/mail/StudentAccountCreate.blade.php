<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Use the latest (edge) version of IE rendering engine -->
    <meta name="x-apple-disable-message-reformatting" />
    <!-- Disable auto-scale in iOS 10 Mail entirely -->
    <title>Email Template</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,500" rel="stylesheet" />
    <script src="https://code.iconify.design/iconify-icon/1.0.2/iconify-icon.min.js"></script>
    <style>
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
        }

        /* What it does: Stops email clients resizing small text. */
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        /* What it does: Centers email on Android 4.4 */
        div[style*="margin: 16px 0"] {
            margin: 0 !important;
        }

        /* What it does: Stops Outlook from adding extra spacing to tables. */
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }

        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }

        table table table {
            table-layout: auto;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        *[x-apple-data-detectors],
        /* iOS */
        .x-gmail-data-detectors,
        /* Gmail */
        .x-gmail-data-detectors *,
        .aBn {
            border-bottom: 0 !important;
            cursor: default !important;
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        .a6S {
            display: none !important;
            opacity: 0.01 !important;
        }

        img.g-img+div {
            display: none !important;
        }

        /* What it does: Prevents underlining the button text in Windows 10 */
        .button-link {
            text-decoration: none !important;
        }

        @media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
            .email-container {
                min-width: 375px !important;
            }

            .photoandwelcome {
                display: flex !important;
                flex-direction: column-reverse !important;

            }
        }
    </style>
    <style>
        .button-td,
        .button-a {
            transition: all 100ms ease-in;
        }

        .button-td:hover .button-link,
        .button-a:hover .button-link {
            color: #ffffff !important;
        }

        .button-td:hover,
        .button-a:hover {
            background: #A31621 !important;
            border-color: #A31621 !important;
        }

        /* Media Queries */
        @media screen and (max-width: 480px) {
            .fluid {
                width: 100% !important;
                max-width: 100% !important;
                height: auto !important;
                margin-left: auto !important;
                margin-right: auto !important;
            }

            .photoandwelcome {
                display: flex !important;
                flex-direction: column-reverse !important;

            }

            .stack-column,
            .stack-column-center {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                direction: ltr !important;
            }

            /* And center justify these ones. */
            .stack-column-center {
                text-align: center !important;
            }

            .center-on-narrow {
                text-align: center !important;
                display: block !important;
                margin-left: auto !important;
                margin-right: auto !important;
                float: none !important;
            }

            table.center-on-narrow {
                display: inline-block !important;
            }

            .email-container p {
                font-size: 17px !important;
                line-height: 22px !important;
            }
        }
    </style>
</head>

<body width="100%" bgcolor="#F1F1F1" style="margin: 0; mso-line-height-rule: exactly">
    <center style="width: 100%; background: #f1f1f1; text-align: left">
        <div style="max-width: 680px; margin: auto" class="email-container">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%"
                style="max-width: 680px" class="email-container">
                <tr>
                    <td bgcolor="#2535A1" align="center" valign=""
                        style="
                text-align: center;
                background-position: center center !important;
                background-size: cover !important;
              ">
                        <div>
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" align="center"
                                width="100%" style="max-width: 500px; margin: auto">
                                <tr>
                                    <td align="center" valign="middle">
                                        <table>
                                            <tr>
                                                <td valign="top"
                                                    style="
                              text-align: center;
                              padding: 20px 0 10px 0px;
                            ">
                                                    <img style="
                                margin: 0;
                                width: 30%;
                              "
                                                        src="{{ $logo }}">

                                                    </img>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign=""
                                                    style="
                              text-align: center;
                              padding: 10px 0 10px 20px;
                            ">
                                                    <h1
                                                        style="
                                margin: 0;
                                font-family: 'Montserrat', sans-serif;
                                font-size: 25px;
                                line-height: 36px;
                                color: #ffffff;
                                font-weight: bold;
                              ">
                                                        WELCOME FROM
                                                    </h1>
                                                    <h1
                                                        style="
                                margin: 0;
                                font-family: 'Eczar', eczar;
                                font-size: 20px;
                                line-height: 36px;
                                color: #ffffff;
                                font-weight: bold;
                              ">
                                                        {{ $sitename }}
                                                    </h1>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="20" style="font-size: 20px; line-height: 20px">
                                        &nbsp;
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#ffffff">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr class="photoandwelcome">
                                <td style="padding: 40px 40px 20px 40px; text-align: left">
                                    <h1
                                        style="
                        margin: 0;
                        font-family: 'Montserrat', sans-serif;
                        font-size: 20px;
                        line-height: 26px;
                        color: #333333;
                        font-weight: bold;
                      ">
                                        YOUR ACCOUNT IS NOW ACTIVE
                                    </h1>
                                </td>
                                <td rowspan="3"><img
                                        src="https://myschoolstorage.sgp1.digitaloceanspaces.com/emailLogo/EmailHelloPhoto.png"
                                        alt="" srcset="" style="width: 100%;"></td>
                            </tr>
                            <tr>
                                <td
                                    style="
                      padding: 0px 40px 20px 40px;
                      font-family: sans-serif;
                      font-size: 15px;
                      line-height: 20px;
                      color: #555555;
                      text-align: left;
                      font-weight: bold;
                    ">
                                    <p style="margin: 0">
                                        Congrulation for being {{ $sitename }} Student.
                                    </p>
                                </td>

                            </tr>
                            <tr>
                                <td
                                    style="
                      padding: 0px 40px 20px 40px;
                      font-family: sans-serif;
                      font-size: 15px;
                      line-height: 20px;
                      color: #555555;
                      text-align: left;
                      font-weight: normal;
                    ">
                                    <p style="margin: 0">
                                        Please use this email and password to login. <br />
                                        Email : {{ $email }} <br />
                                        Password :<span style="font-weight: bold;"> {{ $password }}</span>
                                    </p>
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>
                <!-- INTRO : END -->
                <!-- CTA : BEGIN -->
                <tr>
                    <td bgcolor="#2535A1">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td style="padding: 40px 40px 5px 40px; text-align: center">
                                    <h1
                                        style="
                        margin: 0;
                        font-family: 'Montserrat', sans-serif;
                        font-size: 20px;
                        line-height: 24px;
                        color: #ffffff;
                        font-weight: bold;
                      ">
                                        SIGN IN HERE !
                                    </h1>
                                </td>
                            </tr>
                            <tr>
                                <td
                                    style="
                      padding: 0px 40px 20px 40px;
                      font-family: sans-serif;
                      font-size: 17px;
                      line-height: 23px;
                      color: #ffffff;
                      text-align: center;
                      font-weight: normal;
                      opacity: 0.5;
                    ">
                                    <p style="margin: 0">
                                        Let's find out what you can access !
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td valign="middle" align="center"
                                    style="text-align: center; padding: 0px 20px 40px 20px">
                                    <!-- Button : BEGIN -->
                                    <table role="presentation" align="center" cellspacing="0" cellpadding="0"
                                        border="0" class="center-on-narrow">
                                        <tr>
                                            <td style="
                            border-radius: 50px;
                            background: #ffffff;
                            text-align: center;
                          "
                                                class="button-td">
                                                <a href={{ env('WEBSITE_LINK_STUDENT') }}
                                                    style="
                              background: #ffffff;
                              border: 15px solid #ffffff;
                              font-family: 'Montserrat', sans-serif;
                              font-size: 14px;
                              line-height: 1.1;
                              text-align: center;
                              text-decoration: none;
                              display: block;
                              border-radius: 50px;
                              font-weight: bold;
                            "
                                                    class="button-a">
                                                    <span style="color: #2535A1"
                                                        class="button-link">&nbsp;&nbsp;&nbsp;&nbsp; Go to
                                                        {{ $sitename }}
                                                        Website&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#ffffff">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <br />
                            <tr style="font: Roboto; height: 50px;font-weight: bold;text-align: center">
                                <td style="font-size: 1em;padding-left: 10px;">{{ $sitename }}</td>
                                <td style="font-size: 1em">Address</td>
                                <td style="font-size: 1em">Social</td>
                            </tr>

                            <tr style="font: Roboto; font-size: 0.8em;">
                                <td
                                    style="display: flex;
                      align-items: center;
                      text-align: center;
                      padding-left: 30px;">
                                    <img src="https://myschoolstorage.sgp1.digitaloceanspaces.com/emailLogo/zpfooter/ringingphoneoutlineicon.png"
                                        style="margin-right: 5px;width: 19px;height: 17px;" alt=""
                                        srcset=""><span style="opacity: 0.6;">+959-403-559-701</span>
                                </td>
                                <td style="text-align: center;opacity: 0.6;">No.(1,200), Room 6B</td>
                                <td
                                    style="
                      display: flex;
                      align-items: center;
                      padding-left: 10px;
                    ">
                                    <img src="https://myschoolstorage.sgp1.digitaloceanspaces.com/emailLogo/zpfooter/facebookroundcoloricon.png"
                                        style="width: 30px;height: 30px;" style="margin-right: 5px" alt=""
                                        srcset=""><a href="{{ $facebook_link }}"
                                        style="opacity: 0.6;margin-left: 10px;">{{ $sitename }}</a>
                                </td>
                            </tr>
                            <tr style="font: Roboto; font-size: 0.8em;">
                                <td
                                    style="display: flex;
                      align-items: center;
                      text-align: center;
                      padding-left: 30px;">
                                    <img src="https://myschoolstorage.sgp1.digitaloceanspaces.com/emailLogo/zpfooter/emailicon.png"
                                        style="margin-right: 5px;width: 19px;height: 13px;" alt=""
                                        srcset=""><span style="opacity: 0.6;">info@exbrainedu.com</span>

                                </td>
                                <td style="text-align: center;opacity: 0.6;">Yadanar Road, South okkalapa</td>
                                <td
                                    style="
                      display: flex;
                      align-items: center;
                      padding-left: 10px;
                    ">
                                    <img src="https://myschoolstorage.sgp1.digitaloceanspaces.com/emailLogo/zpfooter/youtubecoloricon.png"
                                        style="width: 35px;height: 30px;" style="margin-right: 5px" alt=""
                                        srcset=""><a href="{{ $youtube_link1 }}"
                                        style="opacity: 0.6;margin-left: 5px;">Software Development</a>
                                </td>
                            </tr>
                            <tr style="font: Roboto; font-size: 0.8em;">
                                <td
                                    style="display: flex;
                      align-items: center;
                      text-align: center;
                      padding-left: 30px;">
                                    <img src="https://myschoolstorage.sgp1.digitaloceanspaces.com/emailLogo/zpfooter/worldglobelineicon.png"
                                        style="margin-right: 5px;width: 18px;height: 18px;" alt=""
                                        srcset=""></iconify-icon><a href=""
                                        style="opacity: 0.6;">www.exbraingroup.com</a>
                                </td>
                                <td style="text-align: center;opacity: 0.6;">Yangon, Myanmar</td>
                                <td
                                    style="
                      display: flex;
                      align-items: center;
                      padding-left: 10px;
                    ">
                                    <img src="https://myschoolstorage.sgp1.digitaloceanspaces.com/emailLogo/zpfooter/youtubecoloricon.png"
                                        style="width: 35px;height: 30px;" alt="" srcset="">
                                    </iconify-icon><a href="{{ $youtube_link2 }}"
                                        style="opacity: 0.6;margin-left: 5px;">Japan</a>
                                </td>
                            </tr>
                            <tr>
                                <td height="20" style="font-size:20px; line-height:20px;">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#ffffff">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <br>
                            <tr>
                                <td
                                    style="padding: 0px 40px 10px 40px; font-family: sans-serif; font-size: 12px; line-height: 18px; color: #666666; text-align: center; font-weight:normal;">
                                    <p style="margin: 0;">This email was sent to you from info@exbrainedu.com</p>
                                </td>
                            </tr>
                            <tr>
                                <td
                                    style="padding: 0px 40px 40px 40px; font-family: sans-serif; font-size: 12px; line-height: 18px; color: #666666; text-align: center; font-weight:normal;">
                                    <p style="margin: 0;">Copyright &copy; 2018-2019 <b>{{ $sitename }}</b>, All
                                        Rights
                                        Reserved.</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </center>
</body>

</html>
