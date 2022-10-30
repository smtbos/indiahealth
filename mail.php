<?php

/*

mail_header($title, $url)


*/

function mail_header($title, $url)
{
    $ht = ' <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

                <html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
                
                <head>
                    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
                    <meta content="width=device-width" name="viewport" />
                    <meta content="IE=edge" http-equiv="X-UA-Compatible" />
                    <title></title>
                    <style type="text/css">
                        body {
                            margin: 0;
                            padding: 0;
                        }
                
                        table,
                        td,
                        tr {
                            vertical-align: top;
                            border-collapse: collapse;
                        }
                
                        * {
                            line-height: inherit;
                        }
                
                        a[x-apple-data-detectors=true] {
                            color: inherit !important;
                            text-decoration: none !important;
                        }
                    </style>
                    <style id="media-query" type="text/css">
                        @media (max-width: 520px) {
                
                            .block-grid,
                            .col {
                                min-width: 320px !important;
                                max-width: 100% !important;
                                display: block !important;
                            }
                
                            .block-grid {
                                width: 100% !important;
                            }
                
                            .col {
                                width: 100% !important;
                            }
                
                            .col>div {
                                margin: 0 auto;
                            }
                
                            img.fullwidth,
                            img.fullwidthOnMobile {
                                max-width: 100% !important;
                            }
                
                            .no-stack .col {
                                min-width: 0 !important;
                                display: table-cell !important;
                            }
                
                            .no-stack.two-up .col {
                                width: 50% !important;
                            }
                
                            .no-stack .col.num4 {
                                width: 33% !important;
                            }
                
                            .no-stack .col.num8 {
                                width: 66% !important;
                            }
                
                            .no-stack .col.num4 {
                                width: 33% !important;
                            }
                
                            .no-stack .col.num3 {
                                width: 25% !important;
                            }
                
                            .no-stack .col.num6 {
                                width: 50% !important;
                            }
                
                            .no-stack .col.num9 {
                                width: 75% !important;
                            }
                
                            .video-block {
                                max-width: none !important;
                            }
                
                            .mobile_hide {
                                min-height: 0px;
                                max-height: 0px;
                                max-width: 0px;
                                display: none;
                                overflow: hidden;
                                font-size: 0px;
                            }
                
                            .desktop_hide {
                                display: block !important;
                                max-height: none !important;
                            }
                        }
                    </style>
                </head>
                <body class="clean-body" style="margin: 0; padding: 0; -webkit-text-size-adjust: 100%; background-color: #FFFFFF;">
                    <table bgcolor="#FFFFFF" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="table-layout: fixed; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #FFFFFF; width: 100%;" valign="top" width="100%">
                        <tbody>
                            <tr style="vertical-align: top;" valign="top">
                                <td style="word-break: break-word; vertical-align: top;" valign="top">
                                    <div style="background-color:transparent;">
                                        <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 500px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                                            <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                                <div class="col num12" style="min-width: 320px; max-width: 500px; display: table-cell; vertical-align: top; width: 500px;">
                                                    <div style="width:100% !important;">
                                                        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                            <div style="color:#000000;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                                                <div style="line-height: 1.2; font-size: 12px; color: #000000; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 14px;">
                                                                    <p style="font-size: 42px; line-height: 1.2; word-break: break-word; text-align: center; mso-line-height-alt: 50px; margin: 0;"><span style="font-size: 42px;"><strong><a href="' . $url . '" rel="noopener" style="text-decoration: none; color: #0068A5;" target="_blank">' . $title . '</a></strong></span></p>
                                                                </div>
                                                            </div>
                                                            <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;"
                                                                valign="top" width="100%">
                                                                <tbody>
                                                                    <tr style="vertical-align: top;" valign="top">
                                                                        <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 1px solid #0068A5; width: 100%;"
                                                                                valign="top" width="100%">
                                                                                <tbody>
                                                                                    <tr style="vertical-align: top;" valign="top">
                                                                                        <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
    return $ht;
}

function mail_text_sentences($sentences)
{

    $str = '';
    foreach ($sentences as $sentence) {
        $str .= '<p style="line-height: 1.8; word-break: break-word; font-size: 15px; mso-line-height-alt: 27px; margin: 0;"><span style="font-size: 15px;">' . $sentence . '</span></p>';
    }

    $ht = ' <div style="background-color:transparent;">
                <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 500px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                        <div class="col num12" style="min-width: 320px; max-width: 500px; display: table-cell; vertical-align: top; width: 500px;">
                            <div style="width:100% !important;">
                                <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                    <div style="color:#555555;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.8;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                        <div style="line-height: 1.8; font-size: 12px; color: #555555; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 22px;">
                                            ' . $str . '
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
    return $ht;
}

function mail_person_name($person_name)
{
    $ht = ' <div style="background-color:transparent;">
                <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 500px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                        <div class="col num12" style="min-width: 320px; max-width: 500px; display: table-cell; vertical-align: top; width: 500px;">
                            <div style="width:100% !important;">
                                <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                    <div style="color:#555555;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                        <div style="line-height: 1.2; font-size: 12px; color: #555555; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 14px;">
                                            <p style="font-size: 20px; line-height: 1.2; word-break: break-word; text-align: center; mso-line-height-alt: 24px; margin: 0;"><span style="font-size: 20px;"><strong>' . $person_name . '</strong></span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
    return $ht;
}
function mail_data_key_value($key, $value)
{
    $ht = ' <div style="background-color:transparent;">
                <div class="block-grid two-up" style="Margin: 0 auto; min-width: 320px; max-width: 500px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                        <div class="col num6" style="max-width: 320px; min-width: 250px; display: table-cell; vertical-align: top; width: 250px;">
                            <div style="width:100% !important;">
                                <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                    <div style="color:#555555;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:0px;padding-right:10px;padding-bottom:0px;padding-left:10px;">
                                        <div style="line-height: 1.2; font-size: 12px; color: #555555; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 14px;">
                                            <p style="line-height: 1.2; word-break: break-word; mso-line-height-alt: 14px; margin: 0;"><strong><span style="font-size: 15px;">' . $key . '</span></strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col num6" style="max-width: 320px; min-width: 250px; display: table-cell; vertical-align: top; width: 250px;">
                            <div style="width:100% !important;">
                                <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                    <div style="color:#555555;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:0px;padding-right:10px;padding-bottom:0px;padding-left:10px;">
                                        <div style="line-height: 1.2; font-size: 12px; color: #555555; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 14px;">
                                            <p style="font-size: 15px; line-height: 1.2; word-break: break-word; mso-line-height-alt: 18px; margin: 0;"><span style="font-size: 15px;"><strong>' . $value . '</strong></span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
    return $ht;
}

function mail_empty_row()
{
    $ht = ' <div style="background-color:transparent;">
                <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 500px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                        <div class="col num12" style="min-width: 320px; max-width: 500px; display: table-cell; vertical-align: top; width: 500px;">
                            <div style="width:100% !important;">
                                <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                    <div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
    return $ht;
}

function mail_button($text, $url)
{
    $ht = ' <div style="background-color:transparent;">
                <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 500px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                        <div class="col num12" style="min-width: 320px; max-width: 500px; display: table-cell; vertical-align: top; width: 500px;">
                            <div style="width:100% !important;">
                                <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                    <div align="center" class="button-container" style="padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                        <a href="' . $url . '" style="-webkit-text-size-adjust: none; text-decoration: none; display: inline-block; color: #ffffff; background-color: #3AAEE0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; width: auto; width: auto; border-top: 1px solid #3AAEE0; border-right: 1px solid #3AAEE0; border-bottom: 1px solid #3AAEE0; border-left: 1px solid #3AAEE0; padding-top: 5px; padding-bottom: 5px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; text-align: center; mso-border-alt: none; word-break: keep-all;" target="_blank"><span style="padding-left:20px;padding-right:20px;font-size:15px;display:inline-block;"><span style="line-height: 24px; word-break: break-word;">' . $text . '</span></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
    return $ht;
}

function mail_footer($title, $url)
{
    $ht = '<div style="background-color:transparent;">
                <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 500px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                        <div class="col num12" style="min-width: 320px; max-width: 500px; display: table-cell; vertical-align: top; width: 500px;">
                            <div style="width:100% !important;">
                                <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                    <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                        <tbody>
                                            <tr style="vertical-align: top;" valign="top">
                                                <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 1px solid #0068A5; width: 100%;" valign="top" width="100%">
                                                        <tbody>
                                                            <tr style="vertical-align: top;" valign="top">
                                                                <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div style="color:#555555;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                        <div style="line-height: 1.2; font-size: 12px; color: #555555; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 14px;">
                                            <p style="font-size: 14px; line-height: 1.2; word-break: break-word; mso-line-height-alt: 17px; margin: 0;">© <a href="' . $url . '" rel="noopener" style="text-decoration: none; color: #0068A5;" target="_blank">' . $title . '</a>, All Rights Reserved.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </td>
            </tr>
            </tbody>
            </table>
            </body>

            </html>';
    return $ht;
}

function mail_join($eles)
{
    return implode("", $eles);
}

function mail_template_new_register($web_name, $web_url, $user_id, $username, $name, $dob)
{
    return mail_join(array(
        mail_header($web_name, $web_url),
        mail_person_name($name),
        mail_text_sentences(array(
            "We are very happy to have you join our organization.",
            "Your Account Details is as Follows"
        )),
        mail_data_key_value("Unic id :", $username),
        mail_empty_row(),
        mail_data_key_value("Date of Birth :", $dob),
        mail_empty_row(),
        mail_footer($web_name, $web_url),
    ));
}

function mail_template_new_register_with_password($web_name, $web_url, $user_id, $username, $name, $dob, $password, $created_by)
{
    return mail_join(array(
        mail_header($web_name, $web_url),
        mail_person_name($name),
        mail_text_sentences(array(
            "We are very happy to have you join our organization.",
            "Your Account is Created By, " . $created_by,
            "Your Account Details is as Follows"
        )),
        mail_data_key_value("Unic id :", $username),
        mail_empty_row(),
        mail_data_key_value("Date of Birth :", $dob),
        mail_empty_row(),
        mail_data_key_value("Password :", $password),
        mail_empty_row(),
        mail_footer($web_name, $web_url),
    ));
}

function mail_template_report_added($web_name, $web_url, $report_id, $report_date, $report_url, $name, $addded_by)
{
    return mail_join(array(
        mail_header($web_name, $web_url),
        mail_person_name($name),
        mail_text_sentences(array(
            "New Report Added to You Account by,",
            $addded_by,
            "Report Details is as Follows."
        )),
        mail_data_key_value("Report id :", $report_id),
        mail_empty_row(),
        mail_data_key_value("Report Date :", $report_date),
        mail_button("View Report", $report_url),
        mail_text_sentences(array(
            "if It's Not Done With You Permission then,",
            "Login With Your Username and Password, Make it as Spam",
        )),
        mail_empty_row(),
        mail_footer($web_name, $web_url),
    ));
}

function mail_template_report_view($web_name, $web_url, $name, $report_id, $viewed_by)
{
    return mail_join(array(
        mail_header($web_name, $web_url),
        mail_person_name($name),
        mail_text_sentences(array(
            "Your Report id No-" . $report_id . " is",
            "Viewed By " . $viewed_by,
        )),
        mail_empty_row(),
        mail_text_sentences(array(
            "if It's Not Done With You Permission then,",
            "Login With Your Username, Password and",
            "Report This Action From Contact Page",
        )),
        mail_footer($web_name, $web_url),
    ));
}

function mail_template_password_reseted($web_name, $web_url, $name, $username, $password, $reseted_by)
{
    return mail_join(array(
        mail_header($web_name, $web_url),
        mail_person_name($name),
        mail_text_sentences(array(
            "Your Password is Reseted By, " . $reseted_by,
        )),
        mail_empty_row(),
        mail_data_key_value("Your Username : ", $username),
        mail_empty_row(),
        mail_data_key_value("New Password : ", $password),
        mail_empty_row(),
        mail_text_sentences(array(
            "if It's Not Done With You Permission then,",
            "Login With Your Username, Password and",
            "Report This Action From Contact Page",
        )),
        mail_footer($web_name, $web_url),
    ));
}


function mail_template_password_reset($web_name, $web_url, $name, $username, $reset_url)
{
    return mail_join(array(
        mail_header($web_name, $web_url),
        mail_person_name($name),
        mail_text_sentences(array(
            "Your Password Reset Link is Here",
            "Click Below Bttton and Set Your New Password For Username : " . $username,
        )),
        mail_button("Reset Now", $reset_url),
        mail_footer($web_name, $web_url),
    ));
}

function mail_html($to, $subject, $html, $from = "India Health <indiahealth2022@gmail.com>")
{
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: ' . $from . "\r\n";
    mail($to, $subject, $html, $headers);
}
