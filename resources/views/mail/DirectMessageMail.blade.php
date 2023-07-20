<!DOCTYPE html>
<html>

<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <style type="text/css">
        @media screen {
            @font-face {
                font-family: "Lato";
                font-style: normal;
                font-weight: 400;
                src: local("Lato Regular"), local("Lato-Regular"),
                    url(https://fonts.gstatic.com/s/lato/v11/qIIYRU-oROkIk8vfvxw6QvesZW2xOQ-xsNqO47m55DA.woff) format("woff");
            }

            @font-face {
                font-family: "Lato";
                font-style: normal;
                font-weight: 700;
                src: local("Lato Bold"), local("Lato-Bold"),
                    url(https://fonts.gstatic.com/s/lato/v11/qdgUG4U09HnJwhYI-uK18wLUuEpTyoUstqEm5AMlJo4.woff) format("woff");
            }

            @font-face {
                font-family: "Lato";
                font-style: italic;
                font-weight: 400;
                src: local("Lato Italic"), local("Lato-Italic"),
                    url(https://fonts.gstatic.com/s/lato/v11/RYyZNoeFgb0l7W3Vu1aSWOvvDin1pK8aKteLpeZ5c0A.woff) format("woff");
            }

            @font-face {
                font-family: "Lato";
                font-style: italic;
                font-weight: 700;
                src: local("Lato Bold Italic"), local("Lato-BoldItalic"),
                    url(https://fonts.gstatic.com/s/lato/v11/HkF_qI1x_noxlxhrhMQYELO3LdcAZYWl9Si6vvxL-qU.woff) format("woff");
            }
        }

        /* CLIENT-SPECIFIC STYLES */
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        /* RESET STYLES */
        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        table {
            border-collapse: collapse !important;
        }

        body {
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            font-family: "Poppins", sans-serif;
        }

        /* iOS BLUE LINKS */
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* MOBILE STYLES */
        @media screen and (max-width: 600px) {
            h1 {
                font-size: 32px !important;
                line-height: 32px !important;
            }
        }

        /* ANDROID CENTER FIX */
        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }
    </style>
</head>

<body style="
      background-color: #f4f4f4;
      margin: 0 !important;
      padding: 0 !important;
    ">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <!-- LOGO -->

        <tr>
            <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px">
                    <tr>
                        <td
                            style="
                  font-size: 48px;
                  font-weight: 400;
                  letter-spacing: 4px;
                  line-height: 48px;
                ">
                    <tr align="center" bgcolor="#FFC652">
                        <td style="padding: 20px 0">
                            <h1
                                style="
                        font-size: 38px;
                        font-weight: 400;
                        text-transform: uppercase;
                        color: #fff;
                      ">
                                Direct Message
                            </h1>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 0 30px">
                            <img src="{{ $logo }}" alt="" width="70px" />
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff"
                            style="
                      padding: 0px 30px 0 30px;
                      font-size: 24px;
                      font-weight: 400;
                    ">
                            <p>{{ $title }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff" style="padding: 0px 30px; font-size: 14px">
                            <p>Dear {{ $name }} ,</p>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff"
                            style="
                      padding: 0px 30px 0 40px;
                      font-size: 14px;
                      line-height: 25px;
                    ">
                            <p style="min-height: 60vh;">
                                {{ $description }}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff" style="padding: 0 0 30px 30px; font-size: 14px; font-weight: bold;">
                            <p>{{ $sitename }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff" align="left" style="border-top: 1px solid #000;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td bgcolor="#ffffff"
                                        style="
                                    padding: 20px 30px 20px 0;
                                    display: flex;
                                    font-size: 12px;
                                    ">
                                        <ul style="list-style: none">
                                            <li
                                                style="
                                        white-space: nowrap;
                                        font-weight: bold;
                                        margin-bottom: 10px;
                                        ">
                                                {{ $sitename }}
                                            </li>
                                            <li style="margin-bottom: 10px; display: flex; align-items: center;">
                                                <img src="https://myschoolstorage.sgp1.digitaloceanspaces.com/emailLogo/phone-call.png"
                                                    alt="" width="15px" /><span
                                                    style="margin-left: 5px">{{ $phone }}</span>
                                            </li>
                                            <li style="white-space: nowrap; display: flex; align-items: center;">
                                                <img src="https://myschoolstorage.sgp1.digitaloceanspaces.com/emailLogo/mail.png"
                                                    alt="" width="15px" /><span
                                                    style="margin-left: 5px">{{ $email }}</span>
                                            </li>
                                        </ul>
                                        <ul style="list-style: none;">
                                            <li style="font-weight: bold; margin-bottom: 10px; text-align: center;">
                                                Address
                                            </li>
                                            <li style="white-space: nowrap">
                                                {{ $address }}
                                            </li>
                                        </ul>
                                        <ul style="list-style: none">
                                            <li style="font-weight: bold; margin-bottom: 10px; text-align: center;">
                                                Social
                                            </li>
                                            <li style="white-space: nowrap; margin-bottom: 15px; display: flex; align-items: center;">
                                                <img src="https://myschoolstorage.sgp1.digitaloceanspaces.com/emailLogo/facebook.png"
                                                    alt="" width="15px" />
                                                <span style="margin-left: 5px">
                                                    <a href="{{ $facebook }}" target="_blank">Ex;braiN Education</a>
                                                </span>
                                            </li>
                                            <li style="white-space: nowrap; margin-bottom: 10px; display: flex; align-items: center;">
                                                <img src="https://myschoolstorage.sgp1.digitaloceanspaces.com/emailLogo/youtube.png"
                                                    alt="" width="15px" />
                                                <span style="margin-left: 5px">
                                                    <a href="{{ $youtube1 }}" target="_blank">Software
                                                        Development</a>
                                                </span>
                                            </li>
                                            <li style=" display: flex; align-items: center;">
                                                <img src="https://myschoolstorage.sgp1.digitaloceanspaces.com/emailLogo/youtube.png"
                                                    alt="" width="15px" />
                                                <span style="margin-left: 5px">
                                                    <a href="{{ $youtube2 }}" target="_blank">Japanese</a>
                                                </span>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr style="padding: 0">
                                    <td bgcolor="#ffffff" align="center" style="font-size: 12px;">
                                        <p>{{ $copyright }}</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
            </td>
        </tr>
    </table>
    </td>
    </tr>
    </table>
</body>

</html>
