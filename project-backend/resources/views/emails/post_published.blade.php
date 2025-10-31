<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Post Published</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f7f7f7; padding:20px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width:600px; margin:auto; background:white; border-radius:8px; overflow:hidden;">
    <tr>
        <td style="background:#1a73e8; padding:18px; color:white; font-size:22px;">
            📢 New Post Alert
        </td>
    </tr>

    <tr>
        <td style="padding:18px; font-size:16px; color:#333;">
            <p>Hello Subscriber 👋,</p>

            <p>A new post has just been published on <strong>{{ $website->name }}</strong>.</p>

            <h2 style="color:#1a73e8; margin-bottom:10px;">{{ $post->title }}</h2>
            <p style="line-height:1.6; color:#555;">
                {{ $post->description }}
            </p>

            <p style="margin-top:30px;">
                <a href="{{ $website->url ?? '#' }}" style="display:inline-block; background:#1a73e8; padding:12px 25px; color:#fff; text-decoration:none; border-radius:6px;">
                    View Website
                </a>
            </p>

            <p style="margin-top:30px; font-size:14px; color:#777;">
                You are receiving this email because you subscribed to this website.
            </p>

            <hr style="border:none; border-top:1px solid #eee; margin:25px 0;">

            <p style="font-size:13px; color:#aaa; text-align:center;">
                &copy; {{ date('Y') }} Subscription Platform. All rights reserved.
            </p>
        </td>
    </tr>
</table>
</body>
</html>
