<!DOCTYPE html>
<html>
  <head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <style type="text/css">
      @import url("https://fonts.googleapis.com/css2?family=Average+Sans&display=swap");
      @import url("https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap");
      @import url("https://fonts.googleapis.com/css2?family=Poppins&display=swap");

      @media screen {
        @font-face {
          font-family: "Lato";
          font-style: normal;
          font-weight: 400;
          src: local("Lato Regular"), local("Lato-Regular"),
            url(https://fonts.gstatic.com/s/lato/v11/qIIYRU-oROkIk8vfvxw6QvesZW2xOQ-xsNqO47m55DA.woff)
              format("woff");
        }

        @font-face {
          font-family: "Lato";
          font-style: normal;
          font-weight: 700;
          src: local("Lato Bold"), local("Lato-Bold"),
            url(https://fonts.gstatic.com/s/lato/v11/qdgUG4U09HnJwhYI-uK18wLUuEpTyoUstqEm5AMlJo4.woff)
              format("woff");
        }

        @font-face {
          font-family: "Lato";
          font-style: italic;
          font-weight: 400;
          src: local("Lato Italic"), local("Lato-Italic"),
            url(https://fonts.gstatic.com/s/lato/v11/RYyZNoeFgb0l7W3Vu1aSWOvvDin1pK8aKteLpeZ5c0A.woff)
              format("woff");
        }

        @font-face {
          font-family: "Lato";
          font-style: italic;
          font-weight: 700;
          src: local("Lato Bold Italic"), local("Lato-BoldItalic"),
            url(https://fonts.gstatic.com/s/lato/v11/HkF_qI1x_noxlxhrhMQYELO3LdcAZYWl9Si6vvxL-qU.woff)
              format("woff");
        }
      }

      /* CLIENT-SPECIFIC STYLES */
      body,
      table,
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
        /* height: auto; */
        line-height: 100%;
        outline: none;
        text-decoration: none;
      }

      table {
        border-collapse: collapse !important;
      }

      body {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        font-family: "Poppins", sans-serif, "Average Sans", "Roboto", "Poppins";
      }

      .mainheader{
        display: flex;
         margin-left: 75px;
      }

      .infomation{
        border-bottom: 1px dotted #c03;
        width: 100%;
        display: block;
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

        .header,
        .sub-header {
          text-align: center !important;
          margin-left: 0 !important;
          font-size: 18px !important;
          padding-left: 35px !important;
        }

        .sub-header {
          font-size: 12px !important;
          padding-left: 35px !important;

        }

        .logo {
          width: 45px;
          padding-top: 14px;
        }

        .data-name {
          font-size: 30px !important;
        }

        .percentage,
        .data {
          font-size: 30px !important;
        }
        .data {
          font-size: 12px !important;
        }

        .information {
          font-size: 14px;
          width: 350px !important;
        }
      }

      /* ANDROID CENTER FIX */
      div[style*="margin: 16px 0;"] {
        margin: 0 !important;
      }
    </style>
  </head>



  <body
    style="background-color: #fff; margin: 0 !important; padding: 0 !important"
  >

    {!! html_entity_decode($maintable, ENT_QUOTES, 'UTF-8') !!}



  </body>
</html>
