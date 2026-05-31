<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate</title>
    <style>
        body { font-family: 'Georgia', serif; text-align: center; padding: 60px; background: #fff; }
        .border { border: 3px solid #4F46E5; padding: 60px; margin: 20px; }
        .title { font-size: 36px; color: #4F46E5; margin-bottom: 10px; }
        .subtitle { font-size: 18px; color: #666; margin-bottom: 40px; }
        .name { font-size: 32px; font-weight: bold; color: #1F2937; border-bottom: 2px solid #4F46E5; display: inline-block; padding-bottom: 5px; margin-bottom: 20px; }
        .course { font-size: 24px; color: #374151; margin: 20px 0; }
        .date { font-size: 14px; color: #9CA3AF; margin-top: 40px; }
        .code { font-size: 12px; color: #D1D5DB; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="border">
        <div class="title">Certificate of Completion</div>
        <div class="subtitle">Learn Everything Platform</div>
        <p>This certifies that</p>
        <div class="name">{{ $certificate->user->name }}</div>
        <p>has successfully completed the course</p>
        <div class="course">{{ $certificate->enrollment->course->title }}</div>
        <div class="date">Issued on {{ $certificate->issued_at->format('d F Y') }}</div>
        <div class="code">Certificate ID: {{ $certificate->certificate_number }}</div>
    </div>
</body>
</html>