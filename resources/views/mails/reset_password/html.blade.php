@include('mails.layouts.header')


<!--[if mso | IE]></td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:600px;" width="600" bgcolor="#ffffff" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->


<div style="background:#ffffff;background-color:#ffffff;margin:0px auto;max-width:600px;">

    <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation"
        style="background:#ffffff;background-color:#ffffff;width:100%;">
        <tbody>
            <tr>
                <td
                    style="direction:ltr;font-size:0px;padding:0px 0px 0px 0px;padding-bottom:0px;padding-left:0px;padding-right:0px;padding-top:0px;text-align:center;">
                    <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:600px;" ><![endif]-->

                    <div class="mj-column-per-100 mj-outlook-group-fix"
                        style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">

                        <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                            style="vertical-align:top;" width="100%">
                            <tbody>

                                <tr>
                                    <td align="left"
                                        style="font-size:0px;padding:45px 35px 0px 35px;padding-top:45px;padding-right:35px;padding-bottom:0px;padding-left:35px;word-break:break-word;">

                                        <div
                                            style="font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:20px;letter-spacing:normal;line-height:22px;text-align:left;color:#55575d;">
                                            <p class="text-build-content" data-testid="EnP3QUeyx-dh59LcX_q2g"
                                                style="margin: 10px 0; margin-top: 10px; margin-bottom: 10px;"><span
                                                    style="font-family:Roboto, Helvetica, Arial, sans-serif;font-size:20px;">{{
                                                    __('Password recovery') }}</span></p>
                                        </div>

                                    </td>
                                </tr>

                                <tr>
                                    <td align="left"
                                        style="font-size:0px;padding:0px 35px 0px 35px;padding-top:0px;padding-right:35px;padding-bottom:0px;padding-left:35px;word-break:break-word;">

                                        <div
                                            style="font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:16px;letter-spacing:normal;line-height:22px;text-align:left;color:#55575d;">
                                            <p class="text-build-content" data-testid="cTwS3fUh7ghtjeyri9ujO"
                                                style="margin: 10px 0; margin-top: 10px; margin-bottom: 10px;">
                                                {{ __('To restore your profile login password follow')}}</span><a
                                                    class="link-build-content" href="{{ $data->link }}"><span
                                                        style="color:#3173F6;font-family:Roboto, Helvetica, Arial, sans-serif;font-size:16px;"><u>{{
                                                            __('this link') }}</u></span></a><span
                                                    style="color:#6E6E73;font-family:Roboto, Helvetica, Arial, sans-serif;font-size:16px;">.</span>
                                            </p>
                                        </div>

                                    </td>
                                </tr>

                                <tr>
                                    <td align="center" vertical-align="middle"
                                        style="font-size:0px;padding:75px 45px 205px 45px;padding-top:75px;padding-right:45px;padding-bottom:205px;padding-left:45px;word-break:break-word;">

                                        <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                                            style="border-collapse:separate;line-height:100%;">
                                            <tbody>
                                                <tr>
                                                    <td align="center" bgcolor="#01b0ac" role="presentation"
                                                        style="border:none;border-radius:100px;cursor:auto;mso-padding-alt:18px 25px 18px 25px;background:#01b0ac;"
                                                        valign="middle">
                                                        <a href="{{ $data->link }}"
                                                            style="display:inline-block;background:#01b0ac;color:#ffffff;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;font-weight:normal;line-height:120%;margin:0;text-decoration:none;text-transform:none;padding:18px 25px 18px 25px;mso-padding-alt:0px;border-radius:100px;"
                                                            target="_blank">
                                                            <span style="color:#ffffff;font-size:14px;">{{ __('Restore
                                                                password') }}</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </td>
                                </tr>

                            </tbody>
                        </table>

                    </div>

                    <!--[if mso | IE]></td></tr></table><![endif]-->
                </td>
            </tr>
        </tbody>
    </table>

</div>

@include('mails.layouts.footer')
